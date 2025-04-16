<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Fiscalapi\Services\FiscalApiClient;



/**
 * @OA\Info(
 *     title="Fiscalapi con Laravel",
 *     version="1.0.0",
 *     description="Ejemplo básico de integración de Fiscalapi con Laravel"
 * )
 */
class ProductsController extends Controller
{
    private FiscalApiClient $fiscalApi;

    public function __construct(FiscalApiClient $fiscalApi)
    {
        $this->fiscalApi = $fiscalApi;
    }



    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Mostrar lista de productos",
     *     tags={"Products"},
     *     @OA\Response(response=200, description="Lista de productos")
     * )
     * @return JsonResponse with hardcoded products
     */
    public function index(): JsonResponse
    {
        $response = $this->fiscalApi->getProductService()->list(1,5);
        $data = $response->getJson();
        return response()->json($data, $response->getStatusCode());
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
         //Crear producto con IVA trasladado del 16% por defecto.
         //Para crear un producto con otros impuestos, vea el ejemplo de update.
        $data = [
            'description' => 'Libro de PHP Con Laravel', //Descripción del producto
            'unitPrice' => 100.00, //Precio unitario del producto sin impuestos
        ];

        //Crear producto
        $apiResponse = $this->fiscalApi->getProductService()->create($data);
        $data = $apiResponse->getJson();
        return response()->json($data, $apiResponse->getStatusCode());
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
        //Recuperar el producto con los detalles relacionados (details=true)
        $response = $this->fiscalApi->getProductService()->get($id,true);
        $data = $response->getJson();
        return response()->json($data, $response->getStatusCode());
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
        // Actualizar producto por id
        $data = [
            'id' => $id,
            'description' => 'Libro de PHP Con Laravel Avanzado',
            'unitPrice' => 250.85,
            'satUnitMeasurementId' => 'D63', // Unidad de medida de la SAT (D63=Unidad)
            'satTaxObjectId' => '02', // Objeto de impuesto de la SAT (02= Si, objeto de impuesto)
            'satProductCodeId' => '14111804', // Código de producto de la SAT (14111804=Libros)
            'productTaxes' => [
                [
                    'rate' => 0.16, // Tasa del impuesto. El valor debe estar entre 0.00000 y 1.000000 p. ej. `0.160000` para un 16% de impuesto
                    'taxId' => '002', // 001=ISR, 002=IVA, 003=IEPS
                    'taxFlagId' => 'T', // T=Traslado o R=Retención
                    'taxTypeId' => 'Tasa', // Tasa, Cuota o Exento
                ],
                [
                    'rate' => 0.08, // Tasa del impuesto
                    'taxId' => '003', // 001=ISR, 002=IVA, 003=IEPS
                    'taxFlagId' => 'T', // T=Traslado o R=Retención
                    'taxTypeId' => 'Tasa', // Tasa, Cuota o Exento
                ]
            ]
        ];

        //Actualizar producto
        $apiResponse = $this->fiscalApi->getProductService()->update($data);
        $data = $apiResponse->getJson();
        return response()->json($data, $apiResponse->getStatusCode());
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
        //Eliminar producto por id
        $apiResponse = $this->fiscalApi->getProductService()->delete($id);
        $data = $apiResponse->getJson();
        return response()->json($data, $apiResponse->getStatusCode());
    }
}