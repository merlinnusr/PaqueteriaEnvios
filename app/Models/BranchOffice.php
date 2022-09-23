<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    use HasFactory;
    protected $table =  'sucursales';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 
        'domicilio', 
        'colonia', 
        'ciudad', 
        'estado',
        'telefono', 
        'activo'
    ];
}
