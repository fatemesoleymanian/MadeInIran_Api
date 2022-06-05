<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request_for_representation extends Model
{
    use HasFactory;
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

}
