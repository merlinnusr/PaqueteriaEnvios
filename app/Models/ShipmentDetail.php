<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentDetail extends Model
{
    use HasFactory;
    protected $table ="pedidos_envio";
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'buyer_name',
        'buyer_email',
        'paid_amount',
        'paid_amount_currency',
        'txn_id',
        'payment_status',
        'id_paquete',
        'paquetes_cancelados_id',
    ];
}
