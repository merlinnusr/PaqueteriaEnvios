<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'folio',
        'amount',
        'service_category_id',
        'date',
        'authorized',
        'authorize_user',
        'receipt_img',
        'created_at',
        'authorized_at',
        'payment_places_id',
    ];
}
