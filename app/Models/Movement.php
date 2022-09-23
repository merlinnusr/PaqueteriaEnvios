<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['message', 'user_id', 'admin_id','created_at'];
}
