<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FQ extends Model
{
    protected $fillable = ['question','answer'];
    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function getUpdatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }

    public function product()
    {
        return $this->hasMany(FQProduct::class,'product_id');
    }
    public function fq()
    {
        return $this->hasMany(FQProduct::class,'fq_id');
    }
    use HasFactory;
}
