<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function getUpdatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function blog()
    {
        return $this->belongsTo(Blog::class,'blog_id')->select('id','title');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->select('id','name');
    }
}
