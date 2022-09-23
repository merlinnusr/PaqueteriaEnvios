<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuponUsed extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = 'cupones_usados';
    protected $fillable =[
        'cupon_id',	
        'usuario_id',	
        'pedido_id',	
        'fecha',
        'usado_en',	
        'telefono',

    ];
}
