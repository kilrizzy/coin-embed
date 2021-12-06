<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    use HasFactory;
    use HasUUID;

    protected $fillable = [
        'uuid',
        'user_id',
        'team_id',
        'transaction_uuid',
        'months',
    ];

    public function transaction()
    {
       return $this->belongsTo(Transaction::class, 'transaction_uuid', 'uuid');
    }

}
