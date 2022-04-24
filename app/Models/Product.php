<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'metaDescription',
        'metaKeyword',
        'pageTitle',
        'name',
        'image',
        'description_excerpt',
        'description',
        'category_id'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function tag()
    {
        return $this->belongsToMany(Tag::class,'product_tags');
    }
    public function state()
    {
        return $this->hasMany(ProductState::class,'product_id');
    }
    use HasFactory;
}
