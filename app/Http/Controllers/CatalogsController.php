<?php

/**
 * ┌───────────────────────────────────────────────────────────────────────────────┐
 * │                          IMPORTANTE - AVISO                                   │
 * │                                                                               │
 * │ Este controlador ha sido creado con fines exclusivamente demostrativos.       │
 * │                                                                               │
 * │ La mayoría de los datos están hardcodeados directamente en las acciones       │
 * │ del controlador para facilitar las pruebas inmediatas sin necesidad de        │
 * │ configurar cuerpos de peticiones. Esto permite probar la funcionalidad        │
 * │ simplemente haciendo clic en el botón "Probar" de Swagger.                    │
 * │                                                                               │
 * │ ADVERTENCIA: Las rutas y cURL paths generados por Swagger en esta aplicación  │
 * │ NO CORRESPONDEN CON LAS rutas y recursos de FiscalAPI, son generados          │
 * │ localmente para la aplicación. Para ver las rutas y todo el API reference     │
 * │ visite: https://docs.fiscalapi.com                                            │
 * │                                                                               │
 * │ En esta aplicación de ejemplo solo se han creado dos controladores:           │
 * │ el de productos y el de factura, para demostrar el funcionamiento             │
 * │ básico de la API. Si desea ver ejemplos de todos los recursos                 │
 * │ disponibles en la API, visite los ejemplos completos de PHP en:               │
 * │ https://github.com/FiscalAPI/fiscalapi-php/blob/main/examples.php             │
 * │                                                                               │
 * │ Este código NO representa una arquitectura limpia ni sigue las mejores        │
 * │ prácticas para aplicaciones en producción. Aunque el SDK de FiscalAPI         │
 * │ implementa las mejores prácticas internamente, esta aplicación de             │
 * │ ejemplo en Laravel está diseñada priorizando la simplicidad y facilidad       │
 * │ de prueba.                                                                    │
 * │                                                                               │
 * │ En un entorno de producción, se recomienda utilizar una arquitectura          │
 * │ apropiada con separación de responsabilidades, validación adecuada,           │
 * │ manejo de errores, autenticación, autorización, etc.                          │
 * └───────────────────────────────────────────────────────────────────────────────┘
 */

 declare(strict_types=1);


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Fiscalapi\Services\FiscalApiClient;
use Illuminate\Http\JsonResponse;

class CatalogsController extends Controller
{
    private FiscalApiClient $fiscalApi;

    public function __construct(FiscalApiClient $fiscalApi)
    {
        $this->fiscalApi = $fiscalApi;
    }


    /**
     * @OA\Get(
     *     path="/api/catalogos/listar",
     *     summary="Listar catálogos",
     *     tags={"Catalogos"},
     *     @OA\Parameter(
     *         name="pagina",
     *         in="query",
     *         required=false,
     *         description="Número de página",
     *         @OA\Schema(type="integer", default=1, example=1)
     *     ),
     *     @OA\Parameter(
     *         name="limite",
     *         in="query",
     *         required=false,
     *         description="Límite de registros por página",
     *         @OA\Schema(type="integer", default=100, example=100)
     *     ),
     *     @OA\Response(response=200, description="Lista de catálogos")
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function listar(Request $request): JsonResponse
    {
        $pagina = $request->query('pagina', 1);
        $limite = $request->query('limite', 100);

        $apiResponse = $this->fiscalApi->getCatalogService()->list($pagina, $limite);
        $data = $apiResponse->getJson();
        return response()->json($data, $apiResponse->getStatusCode());
    }




    /**
     * @OA\Get(
     *     path="/api/catalogos/{nombre}/{id}",
     *     summary="Obtener registro de catálogo por ID",
     *     description="Obtiene un registro específico de un catálogo por su ID. Ejemplo: Obtiene el registro '03' (Transferencia electrónica de fondos) del catálogo de formas de pago (SatPaymentForms)",
     *     tags={"Catalogos"},
     *     @OA\Parameter(
     *         name="nombre",
     *         in="path",
     *         required=true,
     *         description="Nombre del catálogo (ej: SatPaymentForms para formas de pago)",
     *         @OA\Schema(type="string", example="SatPaymentForms")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del registro en el catálogo (ej: 03 para Transferencia electrónica de fondos)",
     *         @OA\Schema(type="string", example="03")
     *     ),
     *     @OA\Response(response=200, description="Registro del catálogo")
     * )
     * @param string $nombre
     * @param string $id
     * @return JsonResponse
     */
    public function obtenerPorId(string $nombre, string $id): JsonResponse
    {
        $apiResponse = $this->fiscalApi->getCatalogService()->getById($nombre, $id);
        $data = $apiResponse->getJson();
        return response()->json($data, $apiResponse->getStatusCode());
    }


   /**
 * @OA\Get(
 *     path="/api/catalogos/buscar",
 *     summary="Buscar en un catálogo",
 *     description="Busca registros en un catálogo según un término. Ejemplo: Busca en el catálogo de formas de pago (SatPaymentForms) todos los registros que contengan la palabra 'Tarjeta'",
 *     tags={"Catalogos"},
 *     @OA\Parameter(
 *         name="catalogo",
 *         in="query",
 *         required=true,
 *         description="Nombre del catálogo (ej: SatPaymentForms)",
 *         @OA\Schema(type="string", example="SatPaymentForms")
 *     ),
 *     @OA\Parameter(
 *         name="termino",
 *         in="query",
 *         required=true,
 *         description="Término a buscar (ej: Tarjeta)",
 *         @OA\Schema(type="string", example="Tarjeta")
 *     ),
 *     @OA\Parameter(
 *         name="pagina",
 *         in="query",
 *         required=false,
 *         description="Número de página",
 *         @OA\Schema(type="integer", default=1, example=1)
 *     ),
 *     @OA\Parameter(
 *         name="limite",
 *         in="query",
 *         required=false,
 *         description="Límite de registros por página",
 *         @OA\Schema(type="integer", default=100, example=100)
 *     ),
 *     @OA\Response(response=200, description="Resultados de la búsqueda en el catálogo")
 * )
 * @param Request $request
 * @return JsonResponse
 */
public function buscar(Request $request): JsonResponse
{
    $catalogo = $request->query('catalogo');
    $termino = $request->query('termino');
    $pagina = $request->query('pagina', 1);
    $limite = $request->query('limite', 100);

    $apiResponse = $this->fiscalApi->getCatalogService()->search($catalogo, $termino, $pagina, $limite);
    $data = $apiResponse->getJson();
    return response()->json($data, $apiResponse->getStatusCode());
}
}
