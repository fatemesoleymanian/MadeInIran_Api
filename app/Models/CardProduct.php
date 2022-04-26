<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardProduct extends Model
{
    protected $fillable = ['card_id','product_id','state_id','count'];

    use HasFactory;
}
