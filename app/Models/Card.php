<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $guarded = [];

    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function getUpdatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function product()
    {
        return $this->hasMany(Product::class, 'product_id',);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'card_products');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    use HasFactory;
}
