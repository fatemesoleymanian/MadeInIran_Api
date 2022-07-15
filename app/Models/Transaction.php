<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const STATUS = ['ACCEPTED', 'PENDING', 'REJECTED'];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function getUpdatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    use HasFactory;
}
