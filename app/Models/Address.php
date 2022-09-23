<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = 'saved_addresses';
    public $timestamps = false;
    protected $fillable = [
        'address_info',
        'user_id',
        'rute'
    ];
}
