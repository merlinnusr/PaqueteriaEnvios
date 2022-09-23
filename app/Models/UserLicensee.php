<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLicensee extends Model
{
    protected $table = 'clientes_licenciatarios';
    public $timestamps = FALSE;
    use HasFactory;
    protected $fillable = [
        'user_id_licenciatario',
        'user_id_cliente'
    ];
}
