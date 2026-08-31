<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryApiController extends Controller
{
    public function index()
    {
        $categories = Category::select('id', 'name')->get();

        return response()->json([
            'data' => $categories
        ]);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response()->json($category);
    }
}
