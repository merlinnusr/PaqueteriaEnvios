<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reception extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table ="recepciones";

    protected $fillable = [
        'paquete_id'
    ];
}
