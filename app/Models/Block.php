<?php

namespace App\Models;

use App\Models\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_id',
        'payment_method_key',
        'transaction_id',
        'status',
        'data',
        'completed_at',
    ];

    protected $dates = [
        'completed_at',
    ];

    protected $casts = [
        'data' => Json::class,
    ];


}
