<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    use HasFactory;
    protected $table ="cupones";
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'cantidad',
        'fecha_caducidad',
        'descuento',
        'picking',
        'paqueteria',
        'tipo_cupon',
        'activo',
    ];
}
