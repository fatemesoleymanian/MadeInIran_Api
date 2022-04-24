<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'iconImage',
       'department_id',
        'metaDescription',
        'metaKeyword',
        'pageTitle'
    ];
    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }
    public function product()
    {
        return $this->hasMany(Product::class,'category_id');
    }
    use HasFactory;
}
