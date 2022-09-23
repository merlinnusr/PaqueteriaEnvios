<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentDeleted extends Model
{
    use HasFactory;
    protected $table = 'paquetes_cancelados';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'reason'
    ];
}
