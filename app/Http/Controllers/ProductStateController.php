<?php

namespace App\Http\Controllers;

use App\Models\ProductState;
use Illuminate\Http\Request;

class ProductStateController extends Controller
{
    public function filterOnState($type)
    {
        return ProductState::with(['product'])->where('type','=',$type)->get();
    }

}
