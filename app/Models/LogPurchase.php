<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon;
class LogPurchase extends Model
{
    public $timestamps = false;
    protected $table ="movements";

    use HasFactory;

    protected $fillable = [
        'message',
        'user_id'
    ];
    public function getCreatedAtAttribute($value)
    {
        return date('d/m/y  h:i A', strtotime($value));
    }
}