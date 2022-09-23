<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $table ="paquetes_envio";
    public $timestamps = false;
    protected $fillable = [
        'purchase_number',
        'user',
        'object_id_quote',
        'name',
        'correo',
        'codven',
        'empaque',
        'codigo',
        'costo_extra',
        'name_from',
        'street_from',
        'numero_from',
        'numero_int_from',
        'street2_from',
        'zipcode_from',
        'email_from',
        'phone_from',
        'ciudad_from',
        'estado_from',
        'municipio_from',
        'colonia_from',
        'referencia_from',
        'name_to',
        'street_to',
        'numero_to',
        'numero_int_to',
        'street2_to',
        'zipcode_to',
        'email_to',
        'phone_to',
        'ciudad_to',
        'estado_to',
        'municipio_to',
        'colonia_to',
        'referencia_to',
        'width',
        'length',
        'height',
        'weight',
        'description',
        'amount',
        'id_zipcode_from',
        'id_zipcode_to',
        'id_rate',
        'rate_servicelevel',
        'rate_duration_terms',
        'rate_provider',
        'rate_provider_img',
        'tracking_number',
        'tracking_url',
        'tracking_label_url',
        'recepcionable',
        'activo',
    ];
    public function details()
    {
        return $this->hasOne(ShipmentDetail::class, 'id_paquete');
    }

    public function getPriceAttribute($value)
    {
        $valor = ceil(($this->costo_extra * 1.16) + $this->amount);
        return number_format($valor, 2, '.', '');

    }
}
