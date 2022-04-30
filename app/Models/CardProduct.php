<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardProduct extends Model
{
    protected $fillable = ['card_id','product_id','count'];

    public function card()
    {
        return $this->hasOne(Card::class,'card_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function state()
    {
        return $this->belongsTo(ProductState::class,'state_id');
    }
    use HasFactory;
}
