<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'iconImage',
        'metaDescription',
        'metaKeyword',
        'pageTitle'
    ];
    public function category()
    {
        return $this->hasMany(Category::class);
    }
    use HasFactory;
}
