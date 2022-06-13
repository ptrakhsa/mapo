<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
}
