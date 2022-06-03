<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFRDelsey extends Model
{
    protected $fillable = [
        'full_name',
        'phone_number',
        'city',
        'age',
        'education',
        'course',
        'work_experience',
        'job',
        'selected_package',
        'reasons',
        'experts'
    ];
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
