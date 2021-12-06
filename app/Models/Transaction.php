<?php

namespace App\Models;

//use App\Processors\CoinbaseCommerce\Processor as CoinbaseCommerceProcessor;
//use App\Processors\Stripe\Processor as StripeProcessor;
//use App\Support\Nano\NanoPaymentProcessor;
use App\Http\Resources\TransactionResource;
use App\Jobs\GetPriceForNano;
use App\Mail\TransactionComplete;
use App\Models\Casts\Json;
use App\Models\User;
use App\Services\Nano\Nano;
use App\Support\Services\CoinbaseCommerce\CoinbaseCommerce;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use BinaryCabin\NanoUnits\Facades\NanoUnits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Transaction extends Model
{

    use HasUUID;
    use HasFactory;

    protected $fillable = [
        'uuid',
        'token',
        'recipient_user_id',
        'recipient_team_id',
        'transactionable_id',
        'transactionable_type',
        'ip',
        'payment_method_key',
        'payment_method_type',
        'currency',
        'amount',
        'status', //Completed,Pending,Failed
        'data',
    ];

    protected $appends = [
        'status_display'
    ];

    protected $casts = [
        'data' => Json::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->token)){
                $model->token = static::randomToken();
            }
        });
    }

    public static function randomToken(){
        return 'coinembed_'.Str::random(40);
    }

    public function transactionable(){
        return $this->morphTo('transactionable');
    }

    public function recipientUser(){
        return $this->belongsTo(User::class,'recipient_user_id');
    }

    public function recipientTeam(){
        return $this->belongsTo(Team::class,'recipient_team_id');
    }

    public function blocks(){
        return $this->hasMany(Block::class);
    }

    public function getNanoBlocksAttribute(){
        return $this->blocks()->where('payment_method_key','nano')->get();
    }

    public function getNanoAmountConfirmedAttribute(){
        $totalRaw = 0;
        foreach($this->nanoBlocks as $block){
            if($block->data->confirmed == "true"){
                $totalRaw += $block->data->amount;
            }
        }
        return $totalRaw;
    }

    public function getNanoAmountPendingAttribute(){
        $totalRaw = 0;
        foreach($this->nanoBlocks as $block){
            if($block->data->confirmed == "false"){
                $totalRaw += $block->data->amount;
            }
        }
        return $totalRaw;
    }

    public function getStatusDisplayAttribute(){
        if(empty($this->status)){
            return 'Abandoned';
        }
        return $this->status;
    }

    public function getAmountUsdAttribute(){
        return $this->amount;
    }

    public function getExpectedAmountAttribute(){
        if($this->payment_method_key == 'nano'){
            return $this->data->charge->amount_nano ?? 0;
        }
        if($this->payment_method_key == 'coinbase-commerce'){
            $coinbaseCommerceTypeKey = $this->getCoinbaseCommerceTypeKey($this->payment_method_direct_type);
            return $this->data->charge->data->pricing->{$coinbaseCommerceTypeKey}->amount ?? 0;
        }
        if($this->payment_method_key == 'stripe'){
            return $this->amount_usd;
        }
        return null;
    }

    public function getExpectedCurrencyAttribute(){
        if($this->payment_method_key == 'nano'){
            return 'nano';
        }
        if($this->payment_method_key == 'coinbase-commerce'){
            $coinbaseCommerceTypeKey = $this->getCoinbaseCommerceTypeKey($this->payment_method_direct_type);
            return strtolower($this->data->charge->data->pricing->{$coinbaseCommerceTypeKey}->currency ?? null);
        }
        if($this->payment_method_key == 'stripe'){
            return 'usd';
        }
        return null;
    }

    private function getCoinbaseCommerceTypeKey($type){
        return $type;
        /*
        if(!isset($this->data->charge->data->pricing)){
            return null;
        }
        foreach($this->data->charge->data->pricing as $coinbaseCommerceTypeKey => $pricingItem){
            if(strtolower($pricingItem->currency) == strtolower($type)){
                return $coinbaseCommerceTypeKey;
            }
        }
        return null;*/
    }

    public function getPaidCurrencyAttribute(){
        if($this->payment_method_key == 'nano'){
            return 'nano';
        }
        if($this->payment_method_key == 'coinbase-commerce'){
            $coinbaseCommerceTypeKey = $this->getCoinbaseCommerceTypeKey($this->payment_method_direct_type);
            return $this->data->charge->data->pricing->{$coinbaseCommerceTypeKey}->currency ?? null;
        }
        if($this->payment_method_key == 'stripe'){
            return 'usd';
        }
        return null;
    }

    public function getPaidAmountAttribute(){
        if($this->payment_method_key == 'nano'){
            $amountRaw = 0;
            foreach($this->nano_blocks as $block){
                $amountRaw += $block->data->amount ?? 0;
            }
            return sprintf('%.8f',\BinaryCabin\NanoUnits\NanoUnits::convert('raw', 'ticker', $amountRaw));
        }
        if($this->payment_method_key == 'coinbase-commerce'){
            if(!isset($this->data->charge->data->payments)){
                return 0;
            }
            foreach($this->data->charge->data->payments as $payment){
                $totalAmount = 0;
                // TODO - Do we only want to show confirmed? Other statuses?
                if($payment->status == 'PENDING'){
                    $totalAmount += $payment->value->crypto->amount ?? 0;
                    //$pendingPaymentAmount+=$payment->value->crypto->amount ?? 0;
                }else if($payment->status == 'CONFIRMED'){
                    $totalAmount += $payment->value->crypto->amount ?? 0;
                    //$confirmedPaymentAmount+=$payment->value->crypto->amount ?? 0;
                }else{
                    //$otherPaymentAmount+=$payment->value->crypto->amount ?? 0;
                }
                return $totalAmount;
            }
        }
        if($this->payment_method_key == 'stripe'){
            $amountCents = $this->data->charge->amount ?? 0;
            if(empty($amountCents)){
                return 0;
            }
            return number_format($amountCents/100,2,'.','');
        }
        return 0;
    }

    public function getPaidUsdAmountAttribute(){
        if($this->paidCurrency == 'usd'){
            return $this->paidAmount;
        }
        if($this->payment_method_key == 'nano'){
            $priceUSD = $this->getNanoUSDPrice();
            return $this->paidAmount * $priceUSD;
        }
        //TODO coinbase commerce
        return 0;
    }

    private function getNanoUSDPrice(){
        return Cache::remember('price_nano', 60, function(){
            $price = Price::findByKey('nano');
            if(!$price){
                GetPriceForNano::dispatch();
                $price = Price::findByKey('nano');
            }
            return $price->price_usd;
        });
    }

    public function getPaymentMethodDirectTypeAttribute(){
        $paymentMethodKey = $this->payment_method_key ?? null;
        $paymentMethodType = $this->payment_method_type ?? null;
        if(!empty($paymentMethodType)){
            $paymentMethodType = str_replace($paymentMethodKey.'.', '', $paymentMethodType);
        }
        return $paymentMethodType;
    }

    public function updateFromService()
    {
        if($this->payment_method_key == 'coinbase-commerce'){
            $this->updateFromServiceCoinbaseCommerce();
        }
        if($this->payment_method_key == 'nano'){
            $this->updateFromServiceNano();
        }
    }

    private function updateFromServiceCoinbaseCommerce()
    {
        $transactionData = $this->data;
        if(!isset($transactionData->charge->data->id)){
            return null;
        }
        $chargeId = $transactionData->charge->data->id;
        $coinbaseCommerceService = new CoinbaseCommerce();
        $paymentMethod = $this->recipientTeam->getPaymentMethod('coinbase-commerce');
        $privateKeyEncryptedEntry = $paymentMethod->getEncryptedEntry('apiKeyPrivate');
        $coinbaseCommerceService->setCredentials(['apiKeyPrivate'=>$privateKeyEncryptedEntry->getValueDecrypted()]);
        $chargeResponse = $coinbaseCommerceService->getCharge($chargeId);
        if(isset($chargeResponse->data->id)){
            $transactionData->charge = $chargeResponse;
        }
        if(isset($transactionData->charge->data->timeline)){
            $lastTimeline = end($transactionData->charge->data->timeline);
            $newStatus = CoinbaseCommerce::getStatusFromChargeStatus($lastTimeline->status);
            if($newStatus == 'Completed'){
                $this->setStatusCompleted();
            }else{
                $this->status = $newStatus;
            }
            $this->data = $transactionData;
            $this->save();
        }
    }

    private function updateFromServiceNano()
    {
        $transactionData = $this->data;
        if(isset($transactionData->charge->address->nano_address)){
            $address = $transactionData->charge->address->nano_address;
            $nano = new Nano();

            // Add pending blocks
            $pendingBlocksResponse = $nano->call('pending',['account'=>$address,'count'=>'-1']);
            if(isset($pendingBlocksResponse->blocks) && !empty($pendingBlocksResponse->blocks)){
                foreach($pendingBlocksResponse->blocks as $blockId){
                    $block = Block::where('block_id',$blockId)->where('payment_method_key','nano')->first();
                    if($block){
                        //Check if update needed
                        if($block->data->confirmed == "false"){
                            $pendingBlockInfoResponse = $nano->call('block_info',[
                                'json_block' => true,
                                'hash' => $blockId,
                            ]);
                            if(isset($pendingBlockInfoResponse->confirmed) && $pendingBlockInfoResponse->confirmed == "true"){
                                $block->data = $pendingBlockInfoResponse;
                                $block->save();
                            }
                        }
                    }
                    if(!$block){
                        $pendingBlockInfoResponse = $nano->call('block_info',[
                            'json_block' => true,
                            'hash' => $blockId,
                        ]);
                        $block = Block::create([
                            'block_id' => $blockId,
                            'payment_method_key' => 'nano',
                            'transaction_id' => $this->id,
                            'data' => $pendingBlockInfoResponse,
                        ]);
                    }
                }
            }

            // Update status
            $blocks = Block::where('transaction_id', $this->id)->where('payment_method_key','nano')->get();
            if($blocks->count() > 0){
                $totalRawNano = $this->nanoAmountConfirmed;
                $expectedRawNano = NanoUnits::convert('ticker','raw',$this->data->charge->amount_nano);
                if($totalRawNano >= $expectedRawNano){
                    $this->setStatusCompleted();
                }else{
                    $this->status = 'Pending';
                }
                $this->save();
            }
        }
    }

    public function setStatusCompleted(){
        if($this->status != 'Completed'){
            $this->status = 'Completed';
            $this->save();
            try {
                // Send Mail
                Mail::to($this->recipientTeam->owner->email)->queue(new TransactionComplete($this));
                // Send Webhook
                if(isset($this->transactionable->settings->webhook_url) && !empty($this->transactionable->settings->webhook_url)){
                    $transactionResource = new TransactionResource($this);
                    $transactionResourceJsonString = (string) $transactionResource->toJson(true);
                    Http::post($this->transactionable->settings->webhook_url, [
                        'action' => 'transaction-completed',
                        'transaction' => json_decode($transactionResourceJsonString),
                    ]);
                }
            }catch (\Exception $exception){
                Log::error($exception->getMessage());
            }
        }
    }

    public function nanoCalculatePrice($amount=null){
        if(empty($amount)){
            $amount = $this->amount;
        }
        $price = Price::findByKey('nano');
        if(!$price){
            GetPriceForNano::dispatch();
            $price = Price::findByKey('nano');
        }
        $priceUSD = $price->price_usd;
        $priceUSDMultiplier = 1/$priceUSD;
        return round($amount * $priceUSDMultiplier, 5);
    }

    public function isOverpayment(){
        if($this->payment_method_key == 'coinbase-commerce'){
            if($this->paid_amount > $this->expected_amount){
                return true;
            }
        }
        if($this->payment_method_key == 'nano'){
            if($this->paid_amount > $this->expected_amount){
                return true;
            }
        }
        return false;
    }

    public function isUnderpayment(){
        if($this->paid_amount == 0){
            return false;
        }
        if($this->payment_method_key == 'coinbase-commerce'){
            if($this->paid_amount < $this->expected_amount){
                return true;
            }
        }
        if($this->payment_method_key == 'nano'){
            if($this->paid_amount < $this->expected_amount){
                return true;
            }
        }
        return false;
    }

    public function scopeWithPayments($query)
    {
        return $query->where(function($query){
            $query->has('blocks'); //Nano
            $query->orWhere('data','like','%"paid":true%'); //Stripe
            $query->orWhere('data','like','%"transaction_id%'); //Coinbase Commerce
        });
    }

}
