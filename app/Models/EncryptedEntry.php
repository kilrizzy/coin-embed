<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class EncryptedEntry extends Model
{

    use HasUUID;

    protected $fillable = [
        'uuid',
        'encryptable_id',
        'encryptable_type',
        'key',
        'value',
    ];

    public function getValueDecrypted(){
        return decrypt($this->value);
    }

}
