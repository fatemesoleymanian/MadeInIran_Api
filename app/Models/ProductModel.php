<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function state()
    {
        return $this->hasMany(ProductState::class, 'model_id');
    }
}
