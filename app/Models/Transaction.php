<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'date_time',
        'terminal_id',
        'responseTransaction_id',
        'invoice_id',
        'product_id',
        'amount',
        'account_id',
        'response_code',
        'carrierControlNo',
        'responseMessage',
        'productName',
        'fee',
        'responseCode'
    ];
}
