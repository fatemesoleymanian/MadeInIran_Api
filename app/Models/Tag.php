<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'type'];
    public  function blog()
    {
        return $this->hasMany(BlogTag::class, 'tag_id');
    }
    public  function product()
    {
        return $this->hasMany(ProductTag::class, 'tag_id');
    }

    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function getUpdatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    use HasFactory;
}
