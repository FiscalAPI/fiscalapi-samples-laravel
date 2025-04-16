# 🚀 Laravel-FiscalAPI 

Esta aplicación es un ejemplo de integración entre Laravel y FiscalAPI, que te permite utilizar los servicios de facturación electrónica en México de manera sencilla a través de una API RESTful.

## 📋 Descripción

Este proyecto implementa una API web con Laravel que se integra con FiscalAPI para generar documentos fiscales (CFDI). FiscalAPI simplifica la integración con los servicios de facturación electrónica, eliminando las complejidades del SAT y facilitando la generación de facturas, notas de crédito, complementos de pago, nómina, carta porte, entre otros.

## ✨ Características

- API RESTful completa para emisión de documentos fiscales
- Documentación con Swagger/OpenAPI
- Base de datos SQLite para desarrollo local
- Integración con FiscalAPI SDK

## 📦 Requisitos

- PHP >= 8.1
- Composer
- SQLite
- Extensiones PHP requeridas (ver sección de configuración)

## ⚙️ Configuración del entorno

### 1. Configuración de PHP

⚠️ **ADVERTENCIA**: Es necesario configurar correctamente tu archivo php.ini. Asegúrate de tener habilitadas las siguientes extensiones:

```ini
extension_dir = "C:\php-8.4.6\ext"  # Ajusta esta ruta a tu instalación de PHP
extension=fileinfo
extension=openssl
extension=pdo_sqlite
extension=sqlite3
extension=zip
```


### 2. Variables de entorno

Crea un archivo `.env` en la raíz del proyecto con las siguientes variables:

```env
# FiscalAPI Configuration
FISCALAPI_URL=https://test.fiscalapi.com
FISCALAPI_KEY=sk_test_391b8980_42d0_4341_8e37_50475128d086
FISCALAPI_TENANT=102e5f13-e114-41dd-bea7-507fce177281
FISCALAPI_DEBUG=false
FISCALAPI_VERIFY_SSL=false
FISCALAPI_API_VERSION=v4
FISCALAPI_TIMEZONE=America/Mexico_City
```

## 🔧 Instalación

1. Clona este repositorio:
```bash
git clone https://github.com/FiscalAPI/fiscalapi-samples-laravel.git
cd fiscalapi-samples-laravel
```

2. Instala las dependencias:
```bash
composer install
```

3. Genera la clave de la aplicación:
```bash
php artisan key:generate
```

4. Crea el archivo de base de datos SQLite:
```bash
touch database/database.sqlite
```

5. Ejecuta las migraciones:
```bash
php artisan migrate
```

6. Opcionalmente, carga datos de ejemplo:
```bash
php artisan db:seed
```

## 🚀 Uso

### Iniciar servidor de desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: http://127.0.0.1:8000

### Documentación de la API

La documentación de la API con Swagger está disponible en:
http://127.0.0.1:8000/api/documentation

## 📝 Endpoints principales

- `GET /api/clientes` - Obtener lista de clientes
- `POST /api/facturas` - Generar una nueva factura
- `GET /api/facturas/{id}` - Obtener detalles de una factura
- `GET /api/facturas/{id}/pdf` - Descargar PDF de una factura
- `GET /api/facturas/{id}/xml` - Descargar XML de una factura

## 🧰 Acerca de FiscalAPI

FiscalAPI es un servicio que simplifica la emisión de comprobantes fiscales en México. Con FiscalAPI puedes:

- Emitir facturas (CFDI)
- Generar notas de crédito
- Crear complementos de pago
- Emitir nóminas
- Generar documentos de carta porte
- Y más servicios fiscales electrónicos

Para más información, visita [FiscalAPI](https://fiscalapi.com).

### 💡 Este ejemplo depende del sdk de fiscalapi para php.
Para más informacion, visita [fiscalapi-php](https://github.com/FiscalAPI/fiscalapi-php)


## 👥 Contribuir

Las contribuciones son bienvenidas. Para contribuir:

1. Haz fork del repositorio
2. Crea una rama para tu característica (`git checkout -b feature/nueva-caracteristica`)
3. Haz commit de tus cambios (`git commit -m 'Agregada nueva característica'`)
4. Sube tu rama (`git push origin feature/nueva-caracteristica`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.