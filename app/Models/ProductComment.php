<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
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
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id')->select('id','name');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    use HasFactory;
}
