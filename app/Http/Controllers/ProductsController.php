<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse with hardcoded products
     */
    public function index()
    {
        return response()->json([
            'products' => [
                ['id' => 1, 'name' => 'Product 1', 'price' => 100],
                ['id' => 2, 'name' => 'Product 2', 'price' => 200],
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse with hardcoded product
     */
    public function store(Request $request)
    {
        return response()->json([
            'product' => [
                'id' => 1,
                'name' => 'Product 1',
                'price' => 100
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            return response()->json([
            'product' => [
                'id' => $id,
                'name' => 'Product 1',
                'price' => 100
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json([
            'product' => [
                'id' => $id,
                'name' => 'Product 1',
                'price' => 100
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
