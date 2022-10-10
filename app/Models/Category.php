<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function getUpdatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
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
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function product()
    {
        return $this->hasMany(Product::class, 'category_id')->select([
            'pageTitle',
            'name',
            'image',
            'id',
            'category_id',
            'discount',
            'slug'
        ]);
    }
    public function department_linked()
    {
        return $this->belongsTo(Department::class, 'department_id')->select(['id','name']);
    }

    use HasFactory;
}
