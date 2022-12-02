<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductModelController extends Controller
{
    public function showAll()
    {
        return ProductModel::query()->get(['name']);
    }
}
