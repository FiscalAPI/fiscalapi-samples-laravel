<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Fiscalapi\Services\FiscalApiClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{

    private FiscalApiClient $fiscalApi;
    private string $base64Cert;
    private string $base64Key;
    private string $password;
    private string $base64Logo;

    public function __construct(FiscalApiClient $fiscalApi)
    {
        $this->fiscalApi = $fiscalApi;


        // Sellos SAT de prueba (KARLA FUENTE NOLASCO FUNK671228PH6)
        // Visita https://docs.fiscalapi.com/tax-files-info#codificacion-de-fiel-o-csd-en-base64 para leer como convertir tus sellos CSD en base 64
        $this->base64Cert = "MIIFgDCCA2igAwIBAgIUMzAwMDEwMDAwMDA1MDAwMDM0NDYwDQYJKoZIhvcNAQELBQAwggErMQ8wDQYDVQQDDAZBQyBVQVQxLjAsBgNVBAoMJVNFUlZJQ0lPIERFIEFETUlOSVNUUkFDSU9OIFRSSUJVVEFSSUExGjAYBgNVBAsMEVNBVC1JRVMgQXV0aG9yaXR5MSgwJgYJKoZIhvcNAQkBFhlvc2Nhci5tYXJ0aW5lekBzYXQuZ29iLm14MR0wGwYDVQQJDBQzcmEgY2VycmFkYSBkZSBjYWxpejEOMAwGA1UEEQwFMDYzNzAxCzAJBgNVBAYTAk1YMRkwFwYDVQQIDBBDSVVEQUQgREUgTUVYSUNPMREwDwYDVQQHDAhDT1lPQUNBTjERMA8GA1UELRMIMi41LjQuNDUxJTAjBgkqhkiG9w0BCQITFnJlc3BvbnNhYmxlOiBBQ0RNQS1TQVQwHhcNMjMwNTE4MTQzNTM3WhcNMjcwNTE4MTQzNTM3WjCBpzEdMBsGA1UEAxMUS0FSTEEgRlVFTlRFIE5PTEFTQ08xHTAbBgNVBCkTFEtBUkxBIEZVRU5URSBOT0xBU0NPMR0wGwYDVQQKExRLQVJMQSBGVUVOVEUgTk9MQVNDTzEWMBQGA1UELRMNRlVOSzY3MTIyOFBINjEbMBkGA1UEBRMSRlVOSzY3MTIyOE1DTE5MUjA1MRMwEQYDVQQLEwpTdWN1cnNhbCAxMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhNXbTSqGX6+/3Urpemyy5vVG2IdP2v7v001+c4BoMxEDFDQ32cOFdDiRxy0Fq9aR+Ojrofq8VeftvN586iyA1A6a0QnA68i7JnQKI4uJy+u0qiixuHu6u3b3BhSpoaVHcUtqFWLLlzr0yBxfVLOqVna/1/tHbQJg9hx57mp97P0JmXO1WeIqi+Zqob/mVZh2lsPGdJ8iqgjYFaFn9QVOQ1Pq74o1PTqwfzqgJSfV0zOOlESDPWggaDAYE4VNyTBisOUjlNd0x7ppcTxSi3yenrJHqkq/pqJsRLKf6VJ/s9p6bsd2bj07hSDpjlDC2lB25eEfkEkeMkXoE7ErXQ5QCwIDAQABox0wGzAMBgNVHRMBAf8EAjAAMAsGA1UdDwQEAwIGwDANBgkqhkiG9w0BAQsFAAOCAgEAHwYpgbClHULXYhK4GNTgonvXh81oqfXwCSWAyDPiTYFDWVfWM9C4ApxMLyc0XvJte75Rla+bPC08oYN3OlhbbvP3twBL/w9SsfxvkbpFn2ZfGSTXZhyiq4vjmQHW1pnFvGelwgU4v3eeRE/MjoCnE7M/Q5thpuog6WGf7CbKERnWZn8QsUaJsZSEkg6Bv2jm69ye57ab5rrOUaeMlstTfdlaHAEkUgLX/NXq7RbGwv82hkHY5b2vYcXeh34tUMBL6os3OdRlooN9ZQGkVIISvxVZpSHkYC20DFNh1Bb0ovjfujlTcka81GnbUhFGZtRuoVQ1RVpMO8xtx3YKBLp4do3hPmnRCV5hCm43OIjYx9Ov2dqICV3AaNXSLV1dW39Bak/RBiIDGHzOIW2+VMPjvvypBjmPv/tmbqNHWPSAWOxTyMx6E1gFCZvi+5F+BgkdC3Lm7U0BU0NfvsXajZd8sXnIllvEMrikCLoI/yurvexNDcF1RW/FhMsoua0eerwczcNm66pGjHm05p9DR6lFeJZrtqeqZuojdxBWy4vH6ghyJaupergoX+nmdG3JYeRttCFF/ITI68TeCES5V3Y0C3psYAg1XxcGRLGd4chPo/4xwiLkijWtgt0/to5ljGBwfK7r62PHZfL1Dp+i7V3w7hmOlhbXzP+zhMZn1GCk7KY="; // Reemplaza con tu certificado en base64

        $this->base64Key = "MIIFDjBABgkqhkiG9w0BBQ0wMzAbBgkqhkiG9w0BBQwwDgQIAgEAAoIBAQACAggAMBQGCCqGSIb3DQMHBAgwggS9AgEAMASCBMh4EHl7aNSCaMDA1VlRoXCZ5UUmqErAbucRBAKNQXH8t8gVCl/ItHMI2hMJ76QOECOqEi1Y89cDpegDvh/INXyMsXbzi87tfFzgq1O+9ID6aPWGg+bNGADXyXxDVdy7Nq/SCdoXvo66MTYwq8jyJeUHDHEGMVBcmZpD44VJCvLBxDcvByuevP4Wo2NKqJCwK+ecAdZc/8Rvd947SjbMHuS8BppfQWARVUqA5BLOkTAHNv6tEk/hncC7O2YOGSShart8fM8dokgGSyewHVFe08POuQ+WDHeVpvApH/SP29rwktSoiHRoL6dK+F2YeEB5SuFW9LQgYCutjapmUP/9TC3Byro9Li6UrvQHxNmgMFGQJSYjFdqlGjLibfuguLp7pueutbROoZaSxU8HqlfYxLkpJUxUwNI1ja/1t3wcivtWknVXBd13R06iVfU1HGe8Kb4u5il4a4yP4p7VT4RE3b1SBLJeG+BxHiE8gFaaKcX/Cl6JV14RPTvk/6VnAtEQ66qHJex21KKuiJo2JoOmDXVHmvGQlWXNjYgoPx28Xd5WsofL+n7HDR2Ku8XgwJw6IXBJGuoday9qWN9v/k7DGlNGB6Sm4gdVUmycMP6EGhB1vFTiDfOGQO42ywmcpKoMETPVQ5InYKE0xAOckgcminDgxWjtUHjBDPEKifEjYudPwKmR6Cf4ZdGvUWwY/zq9pPAC9bu423KeBCnSL8AQ4r5SVsW6XG0njamwfNjpegwh/YG7sS7sDtZ8gi7r6tZYjsOqZlCYU0j7QTBpuQn81Yof2nQRCFxhRJCeydmIA8+z0nXrcElk7NDPk4kYQS0VitJ2qeQYNENzGBglROkCl2y6GlxAG80IBtReCUp/xOSdlwDR0eim+SNkdStvmQM5IcWBuDKwGZc1A4v/UoLl7niV9fpl4X6bUX8lZzY4gidJOafoJ30VoY/lYGkrkEuz3GpbbT5v8fF3iXVRlEqhlpe8JSGu7Rd2cPcJSkQ1Cuj/QRhHPhFMF2KhTEf95c9ZBKI8H7SvBi7eLXfSW2Y0ve6vXBZKyjK9whgCU9iVOsJjqRXpAccaWOKi420CjmS0+uwj/Xr2wLZhPEjBA/G6Od30+eG9mICmbp/5wAGhK/ZxCT17ZETyFmOMo49jl9pxdKocJNuzMrLpSz7/g5Jwp8+y8Ck5YP7AX0R/dVA0t37DO7nAbQT5XVSYpMVh/yvpYJ9WR+tb8Yg1h2lERLR2fbuhQRcwmisZR2W3Sr2b7hX9MCMkMQw8y2fDJrzLrqKqkHcjvnI/TdzZW2MzeQDoBBb3fmgvjYg07l4kThS73wGX992w2Y+a1A2iirSmrYEm9dSh16JmXa8boGQAONQzQkHh7vpw0IBs9cnvqO1QLB1GtbBztUBXonA4TxMKLYZkVrrd2RhrYWMsDp7MpC4M0p/DA3E/qscYwq1OpwriewNdx6XXqMZbdUNqMP2viBY2VSGmNdHtVfbN/rnaeJetFGX7XgTVYD7wDq8TW9yseCK944jcT+y/o0YiT9j3OLQ2Ts0LDTQskpJSxRmXEQGy3NBDOYFTvRkcGJEQJItuol8NivJN1H9LoLIUAlAHBZxfHpUYx66YnP4PdTdMIWH+nxyekKPFfAT7olQ="; // Reemplaza con tu clave privada en base64

        $this->password = "12345678a"; // Reemplaza con la contraseña de tu clave privada de los sellos del SAT.

        $this->base64Logo = "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";

    }

    /**
     * @OA\Get(
     *     path="/api/facturas",
     *     summary="Listar facturas",
     *     tags={"Facturas"},
     *     @OA\Response(response=200, description="Lista de facturas")
     * )
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // pagina 1, 5 facturas por pagina.
        $response = $this->fiscalApi->getInvoiceService()->list(1,5);
        $data = $response->getJson();
        return response()->json($data, $response->getStatusCode());
    }


    /**
     * @OA\Get(
     *     path="/api/facturas/{id}",
     *     summary="Obtener factura por ID",
     *     tags={"Facturas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la factura",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Detalles de la factura")
     * )
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $response = $this->fiscalApi->getInvoiceService()->get($id, true);
        $data = $response->getJson();
        return response()->json($data, $response->getStatusCode());
    }

        /**
     * @OA\Post(
     *     path="/api/facturas/factura-global-por-valores",
     *     summary="Crear factura global por valores",
     *     tags={"Facturas"},
     *     @OA\Response(response=200, description="Factura global creada")
     * )
     * @return JsonResponse
     */
    public function facturaGlobalPorValores(): JsonResponse
    {
        $currentDate = $this->getCurrentDate();

        $invoice = [
            'versionCode' => "4.0",
            'series' => "F",
            'date' => $currentDate, // Fecha específica de la factura global
            'paymentFormCode' => "01",
            'paymentMethodCode' => "PUE",
            'currencyCode' => "MXN",
            'typeCode' => "I",
            'expeditionZipCode' => "01160",
            'exchangeRate' => 1,
            'exportCode' => "01",
            'globalInformation' => [
                'periodicityCode' => "01", // Periodicidad 01 = Diario
                'monthCode' => "01",       // Mes  01 = Enero
                'year' => 2025             // Año fiscal
            ],
            'issuer' => [
                'tin' => "FUNK671228PH6",
                'legalName' => "KARLA FUENTE NOLASCO",
                'taxRegimeCode' => "621",
                'taxCredentials' => [
                    [
                        'base64File' => $this->base64Cert,
                        'fileType' => 0,
                        'password' => $this->password
                    ],
                    [
                        'base64File' => $this->base64Key,
                        'fileType' => 1,
                        'password' => $this->password
                    ]
                ]
            ],
            'recipient' => [
                'tin' => "XAXX010101000",        // RFC genérico para público en general
                'legalName' => "PUBLICO EN GENERAL",
                'zipCode' => "01160",
                'taxRegimeCode' => "616",        // Régimen Sin obligaciones fiscales
                'cfdiUseCode' => "S01",          // Sin efectos fiscales
                'email' => "someone@somewhere.com"
            ],
            'items' => [
                [
                    'itemCode' => "01010101",    // Código de producto/servicio SAT
                    'quantity' => 1,
                    'unitOfMeasurementCode' => "ACT", // Unidad de medida de la SAT (ACT=Actividad)
                    'description' => "Venta", // Fijo
                    'unitPrice' => 1230.00, // Total de la venta sin impuestos
                    'taxObjectCode' => "02",     // 02 = Sí objeto de impuesto
                    'itemSku' => "1",            // Indentificador interno de la venta
                    'itemTaxes' => [
                        [
                            'taxCode' => "002",           // 002 = IVA | 001 = ISR | 003 = IEPS
                            'taxTypeCode' => "Tasa",      // Tipo de impuesto (Tasa, Cuota o Exento)
                            'taxRate' => "0.160000",        // 16%
                            'taxFlagCode' => "T"          // T = Traslado o R = Retención
                        ]
                    ]
                ]
            ]
        ];

        $apiResponse = $this->fiscalApi->getInvoiceService()->create($invoice);
        $data = $apiResponse->getJson();
        return response()->json($data, $apiResponse->getStatusCode());
    }

        /**
     * @OA\Post(
     *     path="/api/facturas/factura-global-por-referencias",
     *     summary="Crear factura global por referencias",
     *     tags={"Facturas"},
     *     @OA\Response(response=200, description="Factura global creada por referencias")
     * )
     * @return JsonResponse
     */
    public function facturaGlobalPorReferencias(): JsonResponse
    {
        $currentDate = $this->getCurrentDate();

        $invoice = [
            'versionCode' => "4.0",
            'series' => "F",
            'date' => $currentDate, // Fecha de emisión de la factura global
            'paymentFormCode' => "01",
            'paymentMethodCode' => "PUE", // PUE=Pago en una sola exhibición, PPD=Pago en parcialidades o diferido
            'currencyCode' => "MXN", // Moneda de la factura
            'typeCode' => "I", // I=Ingreso, E=Egreso
            'expeditionZipCode' => "01160", // Código postal del emisor
            'exchangeRate' => 1, // Tipo de cambio
            'exportCode' => "01",
            'globalInformation' => [
                'periodicityCode' => "02", // Periodicidad 02 = Semanal
                'monthCode' => "02",       // Mes 02 = Febrero
                'year' => 2025             // Año fiscal
            ],
            'issuer' => [
                'id' => "78d380fd-1b69-4e3c-8bc0-4f57737f7d5f" // ID del emisor
            ],
            'recipient' => [
                'id' => "bef56254-0892-4558-95c3-f9c8729e4b0e" // ID del receptor
            ],
            'items' => [
                [
                    'itemCode' => "01010101",    // Fijo
                    'quantity' => 1,
                    'unitOfMeasurementCode' => "ACT", // Unidad de medida de la SAT (ACT=Actividad)
                    'description' => "Venta", // Fijo
                    'unitPrice' => 1230.00, // Total de la venta sin impuestos
                    'taxObjectCode' => "02", // 02 = Sí objeto de impuesto
                    'itemSku' => "1", // Identificador interno de la venta
                    'itemTaxes' => [
                        [
                            'taxCode' => "002",           // 002 = IVA
                            'taxTypeCode' => "Tasa",      // Tipo de factor
                            'taxRate' => "0.160000",      // Tasa 16%
                            'taxFlagCode' => "T"          // T = Traslado
                        ]
                    ]
                ]
            ]
        ];

        $apiResponse = $this->fiscalApi->getInvoiceService()->create($invoice);
        $data = $apiResponse->getJson();
        return response()->json($data, $apiResponse->getStatusCode());
    }

    /**
    * @OA\Post(
    *     path="/api/facturas/factura-con-iva-16",
    *     summary="Crear factura de ingreso con IVA 16%",
    *     tags={"Facturas"},
    *     @OA\Response(response=200, description="Factura de ingreso creada con IVA 16%")
    * )
    * @return JsonResponse
    */
    public function facturaConIva16(): JsonResponse
    {
    $currentDate = $this->getCurrentDate();

    $invoice = [
        'versionCode' => "4.0",
        'series' => "F",
        'date' => $currentDate, // Formato de fecha equivalente a DateTime.now()
        'paymentFormCode' => "01",
        'paymentMethodCode' => "PUE",
        'currencyCode' => "MXN",
        'typeCode' => "I",
        'expeditionZipCode' => "42501",
        'exchangeRate' => 1,
        'exportCode' => "01",
        'issuer' => [
            'tin' => "FUNK671228PH6",
            'legalName' => "KARLA FUENTE NOLASCO",
            'taxRegimeCode' => "621",
            'taxCredentials' => [
                [
                    'base64File' => $this->base64Cert,
                    'fileType' => 0,
                    'password' => $this->password
                ],
                [
                    'base64File' => $this->base64Key,
                    'fileType' => 1,
                    'password' => $this->password
                ]
            ]
        ],
        'recipient' => [
            'tin' => "EKU9003173C9",
            'legalName' => "ESCUELA KEMPER URGATE",
            'zipCode' => "42501",
            'taxRegimeCode' => "601",
            'cfdiUseCode' => "G01",
            'email' => "someone@somewhere.com"
        ],
        'items' => [
            [
                'itemCode' => "01010101",
                'quantity' => 9.5,
                'unitOfMeasurementCode' => "E48",
                'description' => "Invoicing software as a service",
                'unitPrice' => 3587.75,
                'taxObjectCode' => "02",
                'itemSku' => "7506022301697",
                'discount' => 255.85,
                'itemTaxes' => [
                    [
                        'taxCode' => "002",
                        'taxTypeCode' => "Tasa",
                        'taxRate' => "0.160000",
                        'taxFlagCode' => "T"
                    ]
                ]
            ]
        ]
    ];

    $apiResponse = $this->fiscalApi->getInvoiceService()->create($invoice);
    $data = $apiResponse->getJson();
    return response()->json($data, $apiResponse->getStatusCode());
    }

    /**
     * @OA\Post(
     *     path="/api/facturas/factura-iva-exento",
     *     summary="Crear factura con IVA exento",
     *     tags={"Facturas"},
     *     @OA\Response(response=200, description="Factura con IVA exento creada")
     * )
     * @return JsonResponse
     */
    public function facturaIvaExento(): JsonResponse
    {
        $currentDate = $this->getCurrentDate();

        $invoiceExento = [
            'versionCode' => "4.0",
            'series' => "F",
            'date' => $currentDate,
            'paymentFormCode' => "01",
            'paymentMethodCode' => "PUE",
            'currencyCode' => "MXN",
            'typeCode' => "I",
            'expeditionZipCode' => "42501",
            'exchangeRate' => 1,
            'exportCode' => "01",
            'issuer' => [
                'tin' => "FUNK671228PH6",
                'legalName' => "KARLA FUENTE NOLASCO",
                'taxRegimeCode' => "621",
                'taxCredentials' => [
                    [
                        'base64File' => $this->base64Cert,
                        'fileType' => 0,
                        'password' => $this->password
                    ],
                    [
                        'base64File' => $this->base64Key,
                        'fileType' => 1,
                        'password' => $this->password
                    ]
                ]
            ],
            'recipient' => [
                'tin' => "EKU9003173C9",
                'legalName' => "ESCUELA KEMPER URGATE",
                'zipCode' => "42501",
                'taxRegimeCode' => "601",
                'cfdiUseCode' => "G01",
                'email' => "someone@somewhere.com"
            ],
            'items' => [
                [
                    'itemCode' => "01010101",
                    'quantity' => 9.5,
                    'unitOfMeasurementCode' => "E48", // Unidad de servicio
                    'description' => "Invoicing software as a service",
                    'unitPrice' => 3587.75,
                    'taxObjectCode' => "02",
                    'itemSku' => "7506022301697",
                    'discount' => 255.85,
                    'itemTaxes' => [
                        [
                            'taxCode' => "002", // 001=ISR, 002=IVA, 003=IEPS
                            'taxTypeCode' => "Exento", // Tipo "Exento" para indicar que está exento de impuestos
                            'taxFlagCode' => "T" // T=Traslado o R=Retención
                        ]
                    ]
                ]
            ]
        ];

        $apiResponse = $this->fiscalApi->getInvoiceService()->create($invoiceExento);
        $data = $apiResponse->getJson();
        return response()->json($data, $apiResponse->getStatusCode());
    }

    /**
    * @OA\Post(
    *     path="/api/facturas/factura-iva-tasa-cero",
    *     summary="Crear factura con IVA tasa cero",
    *     tags={"Facturas"},
    *     @OA\Response(response=200, description="Factura con IVA tasa cero creada")
    * )
    * @return JsonResponse
    */
    public function facturaIvaTasaCero(): JsonResponse
    {
    $currentDate = $this->getCurrentDate();

    $invoiceTasaCero = [
        'versionCode' => "4.0",
        'series' => "F",
        'date' => $currentDate,
        'paymentFormCode' => "01", // 01=Efectivo, 02=Cheque, 03=Transferencia electrónica de fondos, 04=Tarjeta de débito, 05=Tarjeta de crédito, 06=Otro
        'paymentMethodCode' => "PUE", // PUE=Pago en una sola exhibición, PPD=Pago en parcialidades o diferido
        'currencyCode' => "MXN",
        'typeCode' => "I", // I=Ingreso, E=Egreso
        'expeditionZipCode' => "42501",
        'exchangeRate' => 1,
        'exportCode' => "01", // 01=Exportación, 02=No exportación
        'issuer' => [
            'tin' => "FUNK671228PH6", // RFC del emisor
            'legalName' => "KARLA FUENTE NOLASCO", // Razón social del emisor sin regimen de capital
            'taxRegimeCode' => "621", // Código del régimen fiscal del emisor. Catálogo del SAT c_RegimenFiscal
            'taxCredentials' => [
                [
                    'base64File' => $this->base64Cert,
                    'fileType' => 0,
                    'password' => $this->password
                ],
                [
                    'base64File' => $this->base64Key,
                    'fileType' => 1,
                    'password' => $this->password
                ]
            ]
        ],
        'recipient' => [
            'tin' => "EKU9003173C9", // RFC del receptor
            'legalName' => "ESCUELA KEMPER URGATE", // Razón social del receptor sin regimen de capital
            'zipCode' => "42501", // Código postal del receptor
            'taxRegimeCode' => "601", // Código del régimen fiscal del receptor. Catálogo del SAT c_RegimenFiscal
            'cfdiUseCode' => "G01", // Código del uso CFDI. Catálogo del SAT c_UsoCFDI
            'email' => "someone@somewhere.com" // Correo electrónico del receptor. Para enviar la factura desde el dasborard
        ],
        'items' => [
            [
                'itemCode' => "01010101",
                'quantity' => 9.5,
                'unitOfMeasurementCode' => "E48", // Código de la unidad de medida. Catálogo c_ClaveUnidad
                'description' => "Invoicing software as a service", // Descripción del producto o servicio
                'unitPrice' => 3587.75, // Precio unitario del producto o servicio. (Sin impuestos)
                'taxObjectCode' => "02", // Código de obligaciones de impuesto aplicables al producto o servicio. Catálogo c_ObjetoImp
                'itemSku' => "7506022301697", // SKU o clave del sistema externo que identifica al producto o servicio
                'discount' => 255.85, // Cantidad monetaria del descuento aplicado al producto o servicio
                'itemTaxes' => [
                    [
                        'taxCode' => "002", // Código del impuesto. Catálogo del SAT c_Impuesto
                        'taxTypeCode' => "Tasa", // Tipo de factor. Catálogo del SAT c_TipoFactor
                        'taxRate' => "0.000000", // Tasa cero (0%)
                        'taxFlagCode' => "T" // T=Traslado o R=Retención
                    ]
                ]
            ]
        ]
    ];

    $apiResponse = $this->fiscalApi->getInvoiceService()->create($invoiceTasaCero);
    $data = $apiResponse->getJson();
    return response()->json($data, $apiResponse->getStatusCode());
    }

    /**
    * @OA\Post(
    *     path="/api/facturas/factura-por-referencias",
    *     summary="Crear factura de ingreso por referencias (solo IDs)",
    *     tags={"Facturas"},
    *     @OA\Response(response=200, description="Factura de ingreso por referencias creada")
    * )
    * @return JsonResponse
    */
    public function facturaPorReferencias(): JsonResponse
    {
    $currentDate = $this->getCurrentDate();

    $invoiceByReferences = [
        'versionCode' => "4.0",
        'series' => "F",
        'date' => $currentDate,
        'paymentFormCode' => "01",
        'currencyCode' => "MXN",
        'typeCode' => "I",
        'expeditionZipCode' => "42501",
        'paymentMethodCode' => "PUE",
        'exchangeRate' => 1,
        'exportCode' => "01",
        'issuer' => [
            'id' => "78d380fd-1b69-4e3c-8bc0-4f57737f7d5f" // ID del emisor en fiscalapi
        ],
        'recipient' => [
            'id' => "bef56254-0892-4558-95c3-f9c8729e4b0e" // ID del receptor en fiscalapi
        ],
        'items' => [
            [
                'id' => "310301b3-1ae9-441b-b463-51a8f9ca8ba2", // ID del producto/servicio en fiscalapi
                'quantity' => 2, // Solo es necesario especificar la cantidad
                'discount' => 25.85 // Y opcionalmente el descuento
            ]
        ]
    ];

    $apiResponse = $this->fiscalApi->getInvoiceService()->create($invoiceByReferences);
    $data = $apiResponse->getJson();
    return response()->json($data, $apiResponse->getStatusCode());
    }

    /**
    * @OA\Post(
    *     path="/api/facturas/nota-credito",
    *     summary="Crear nota de crédito por valores",
    *     tags={"Facturas"},
    *     @OA\Response(response=200, description="Nota de crédito creada")
    * )
    * @return JsonResponse
    */
    public function notaCredito(): JsonResponse
    {
    $currentDate = $this->getCurrentDate();

    $creditNote = [
        'versionCode' => "4.0",
        'series' => "CN", // Serie CN para notas de crédito
        'date' => $currentDate,
        'paymentFormCode' => "03", // 03 - Transferencia electrónica de fondos
        'currencyCode' => "MXN",
        'typeCode' => "E", // Tipo E para notas de crédito (Egreso)
        'expeditionZipCode' => "01160",
        'paymentMethodCode' => "PUE",
        'exchangeRate' => 1,
        'exportCode' => "01",
        'issuer' => [
            'tin' => "FUNK671228PH6",
            'legalName' => "KARLA FUENTE NOLASCO",
            'taxRegimeCode' => "621",
            'taxCredentials' => [
                [
                    'base64File' => $this->base64Cert,
                    'fileType' => 0,
                    'password' => $this->password
                ],
                [
                    'base64File' => $this->base64Key,
                    'fileType' => 1,
                    'password' => $this->password
                ]
            ]
        ],
        'recipient' => [
            'tin' => "EKU9003173C9",
            'legalName' => "ESCUELA KEMPER URGATE",
            'zipCode' => "42501",
            'taxRegimeCode' => "601",
            'cfdiUseCode' => "G01",
            'email' => "someone@somewhere.com"
        ],
        // Facturas relacionadas - necesarias para notas de crédito
        'relatedInvoices' => [
            [
                'uuid' => "5FB2822E-396D-4725-8521-CDC4BDD20CCF", // UUID de la factura original
                'relationshipTypeCode' => "01" // 01 - Nota de crédito de los documentos relacionados
            ]
        ],
        'items' => [
            [
                'itemCode' => "01010101",
                'quantity' => 0.5,
                'unitOfMeasurementCode' => "E48",
                'description' => "Invoicing software as a service",
                'unitPrice' => 3587.75,
                'taxObjectCode' => "02", // Código de obligaciones de impuesto aplicables al producto o servicio. Catálogo c_ObjetoImp
                'itemSku' => "7506022301697",
                'itemTaxes' => [
                    [
                        'taxCode' => "002", // 001=ISR, 002=IVA, 003=IEPS
                        'taxTypeCode' => "Tasa", // Tipo de factor. Catálogo del SAT c_TipoFactor
                        'taxRate' => "0.160000", // Tasa 16%
                        'taxFlagCode' => "T"  // T=Traslado o R=Retención
                    ]
                ]
            ]
        ]
    ];

    $apiResponse = $this->fiscalApi->getInvoiceService()->create($creditNote);
    $data = $apiResponse->getJson();
    return response()->json($data, $apiResponse->getStatusCode());
    }


    /**
    * @OA\Post(
    *     path="/api/facturas/nota-credito-por-referencias",
    *     summary="Crear nota de crédito por referencias",
    *     tags={"Facturas"},
    *     @OA\Response(response=200, description="Nota de crédito creada por referencias")
    * )
    * @return JsonResponse
    */
    public function notaCreditoPorReferencias(Request $request): JsonResponse
    {
        $currentDate = $this->getCurrentDate();

        $creditNoteByReferences = [
            'versionCode' => "4.0",
            'series' => "CN",
            'date' => $currentDate,
            'paymentFormCode' => "03",
            'currencyCode' => "MXN",
            'typeCode' => "E", // Tipo E para notas de crédito (Egreso)
            'expeditionZipCode' => "01160",
            'paymentMethodCode' => "PUE",
            'exchangeRate' => 1,
            'exportCode' => "01",
            'issuer' => [
                'id' => "78d380fd-1b69-4e3c-8bc0-4f57737f7d5f" // ID del emisor en fiscalapi
            ],
            'recipient' => [
                'id' => "bef56254-0892-4558-95c3-f9c8729e4b0e" // ID del receptor en fiscalapi
            ],
            // Importante: Las facturas relacionadas siempre son necesarias para notas de crédito
            'relatedInvoices' => [
                [
                    'uuid' => "5FB2822E-396D-4725-8521-CDC4BDD20CCF",
                    'relationshipTypeCode' => "01" // 01 - Nota de crédito de los documentos relacionados
                ]
            ],
            'items' => [
                [
                    'id' => "310301b3-1ae9-441b-b463-51a8f9ca8ba2", // ID del producto/servicio en fiscalapi
                    'quantity' => 0.5 // La cantidad que se está acreditando/devolviendo
                ]
            ]
        ];

        $apiResponse = $this->fiscalApi->getInvoiceService()->create($creditNoteByReferences);
        $data = $apiResponse->getJson();
        return response()->json($data, $apiResponse->getStatusCode());
    }


    /**
    * @OA\Post(
    *     path="/api/facturas/complemento-pago",
    *     summary="Crear un complemento de pago por valores",
    *     tags={"Facturas"},
    *     @OA\Response(response=200, description="Complemento de pago creado")
    * )
    * @return JsonResponse
    */
    public function complementoPago(Request $request): JsonResponse
    {
        $currentDate = $this->getCurrentDate();

        $invoice = [
            'versionCode' => "4.0",
            'series' => "CP", // Serie CP para complementos de pago
            'date' => $currentDate,
            'currencyCode' => "XXX", // Para complementos de pago se usa XXX
            'typeCode' => "P", // Tipo P para complementos de pago
            'expeditionZipCode' => "01160",
            'exchangeRate' => 1,
            'exportCode' => "01",
            'issuer' => [
                'tin' => "FUNK671228PH6",
                'legalName' => "KARLA FUENTE NOLASCO",
                'taxRegimeCode' => "621",
                'taxCredentials' => [
                    [
                        'base64File' => $this->base64Cert,
                        'fileType' => 0,
                        'password' => $this->password
                    ],
                    [
                        'base64File' => $this->base64Key,
                        'fileType' => 1,
                        'password' => $this->password
                    ]
                ]
            ],
            'recipient' => [
                'tin' => "EKU9003173C9",
                'legalName' => "ESCUELA KEMPER URGATE",
                'zipCode' => "42501",
                'taxRegimeCode' => "601",
                'cfdiUseCode' => "CP01", // Uso específico para pagos
                'email' => "someone@somewhere.com"
            ],
            // El concepto es fijo para complementos de pago
            'items' => [
                [
                    'itemCode' => "84111506", // Código específico para pagos
                    'quantity' => 1,
                    'unitOfMeasurementCode' => "ACT",
                    'description' => "Pago",
                    'unitPrice' => 0,
                    'taxObjectCode' => "01"
                ]
            ],
            // Sección de pagos - específica para complementos de pago
            'payments' => [
                [
                    'paymentDate' => "2025-03-31T14:44:56", // Fecha del pago
                    'paymentFormCode' => "28", // 28 - Tarjeta de débito
                    'currencyCode' => "MXN",
                    'exchangeRate' => 1,
                    'amount' => 11600.00, // Monto total del pago
                    'sourceBankTin' => "BSM970519DU8", // RFC del banco emisor
                    'sourceBankAccount' => "1234567891012131", // Cuenta del banco emisor
                    'targetBankTin' => "BBA830831LJ2", // RFC del banco receptor
                    'targetBankAccount' => "1234567890", // Cuenta del banco receptor
                    'paidInvoices' => [
                        [
                            'uuid' => "5C7B0622-01B4-4EB8-96D0-E0DEBD89FF0F", // UUID de la factura que se está pagando
                            'series' => "F",
                            'number' => "1501",
                            'currencyCode' => "MXN",
                            'partialityNumber' => 1, // Número de parcialidad (1 si es pago único)
                            'subTotal' => 10000,
                            'previousBalance' => 11600.00, // Saldo anterior
                            'paymentAmount' => 11600.00, // Cantidad pagada
                            'remainingBalance' => 0, // Saldo restante
                            'taxObjectCode' => "02",
                            'paidInvoiceTaxes' => [
                                [
                                    'taxCode' => "002", // 001=ISR, 002=IVA, 003=IEPS
                                    'taxTypeCode' => "Tasa", // Tasa Cuota Excento
                                    'taxRate' => "0.160000", // Tasa 16%
                                    'taxFlagCode' => "T" // T=Traslado o R=Retención
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $apiResponse = $this->fiscalApi->getInvoiceService()->create($invoice);
        $data = $apiResponse->getJson();
        return response()->json($data, $apiResponse->getStatusCode());
    }






























     /**
     * Definir la fecha actual
     */
    private function getCurrentDate(): string
    {
        // México Central se mantiene en UTC-6 permanentemente desde la eliminación del horario de verano
        date_default_timezone_set('Etc/GMT+6');  // Nota: GMT+6 es UTC-6

        // Formatear la fecha y hora en formato SAT: AAAA-MM-DDThh:mm:ss
        return date('Y-m-d\TH:i:s');
    }
}
