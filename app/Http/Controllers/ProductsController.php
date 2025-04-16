<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Fiscalapi con Laravel",
 *     version="1.0.0",
 *     description="Ejemplo básico de integración de Fiscalapi con Laravel"
 * )
 */
class ProductsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Mostrar lista de productos",
     *     tags={"Products"},
     *     @OA\Response(response=200, description="Lista de productos")
     * )
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
     * @OA\Post(
     *     path="/api/products",
     *     summary="Crear un nuevo producto",
     *     tags={"Products"},
     *     @OA\Response(response=200, description="Producto creado")
     * )
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
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Mostrar un producto específico",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalles del producto")
     * )
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
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Actualizar un producto existente",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Producto actualizado")
     * )
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
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Eliminar un producto",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Producto eliminado")
     * )
     */
    public function destroy(string $id)
    {
        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}