<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    protected $fillable = ['tag_id','blog_id'];
    public function blog()
    {
        return $this->hasMany(Blog::class,'tag_id');
    }
    public function tag()
    {
        return $this->belongsToMany(Tag::class,'blog_tags');
    }
    use HasFactory;
}
