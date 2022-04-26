<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['total','provider','status','order_id'];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    use HasFactory;
}
