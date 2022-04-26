<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductState extends Model
{
    protected $fillable = ['type','price','product_id','discounted_price'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    use HasFactory;
}
