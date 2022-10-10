<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = ['name', 'pageTitle', 'metaKeyword', 'metaDescription'];

    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function getUpdatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }

    public  function blog()
    {
        return $this->hasMany(Blog::class, 'category_id')->select(['id','title',
            'slug',
            'featuredImage',
            'metaDescription',
            'metaKeyword',
            'category_id',]);
    }

    use HasFactory;
}
