<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = ['name'];
    public  function blog()
    {
        return $this->hasOne(Blog::class,'category_id');
    }
    use HasFactory;
}
