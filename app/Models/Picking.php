<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picking extends Model
{
    use HasFactory;
    protected $table = "picking";
    protected $fillable = [

        	'cliente_id',
            'nombre',	
            'celular',	
            'correo',	
            'contenido',	
            'foto_paquete',	
            'dimensiones',	
            'costo',	
            'sucursal_id',	
            'recepcionado_por',	
            'fecha_creacion',	
            'fecha_recepcion',	
            'fecha_entrega',	
            'entregado_por',
            'codigo',	
            'clave',
            'fecha_clave'
    ];
    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
        'fecha_creacion'
    ];

    public function branchOffice()
    {
        return $this->hasOne(BranchOffice::class, 'id', 'sucursal_id' );
    }
    public function FunctionName(Type $var = null)
    {
        # code...
    }
    public function getFechaCreacionAttribute($value)
    {
        $carbonDate = new Carbon($value);
        $carbonDate->timezone = 'America/Mexico_City';
        return $carbonDate->format('Y-m-d h:i:s A');
    }
    public function getCostoAttribute($value)
    {
        return floatval($value);
    }
}
