<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class under_construction extends Model
{
    protected $fillable = ['full_name','phone_number','description'];
    use HasFactory;
}
