<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'post',
        'post_excerpt',
        'slug',
        'featuredImage',
        'metaDescription',
        'metaKeyword',
        'pageTitle',
        'views',
        'category_id',

    ];
    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function getUpdatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    public  function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
    public  function tag()
    {
        return $this->belongsToMany(Tag::class, BlogTag::class);
    }
    public function comment()
    {
        return $this->hasMany(BlogComment::class);
    }
    use HasFactory;
}
