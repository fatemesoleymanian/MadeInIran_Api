<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['card_id', 'status', 'total', 'change_state'];

    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function getUpdatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }
    public function transactin()
    {
        return $this->hasOne(Transaction::class, 'order_id');
    }

    use HasFactory;
}
