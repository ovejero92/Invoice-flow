# ProjInvoiceFlow

Sistema de **proyectos, horas y facturación** para freelancers y equipos pequeños: panel **admin**, **freelancer** y **portal cliente**, con **PDF** (Dompdf) y flujo de **pagos** registrados sobre facturas.

## Funcionalidad

| Área | Contenido |
|------|-----------|
| **Admin** | Clientes, proyectos, tareas (incl. facturable), generación de facturas por rango, listado de pagos, alta de usuarios de portal cliente. |
| **Freelancer** | Registro de horas, proyectos asignados, reporte de horas. |
| **Cliente** | Proyectos en solo lectura, facturas, descarga PDF, registro de pagos sobre facturas. |
| **Auth** | Laravel Breeze; email verificado requerido para el panel; registro público crea siempre cuenta **freelancer**. |
| **Roles** | `admin`, `freelancer`, `client` (enum `UserRole`). |

## Requisitos

- PHP **8.3+**
- Composer 2.x
- Node.js + npm (build/front)
- Extensión **mbstring** recomendada en el servidor (en Windows local ver guías de PHP portable si aplica).

## Instalación local

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed   # solo en entornos no productivos; ver abajo
npm install
npm run dev           # desarrollo (junto con php artisan serve)
# o: npm run build    # sin servidor Vite
```

**Datos demo (desarrollo):** el seeder crea usuarios `admin@invoiceflow.demo`, `freelancer@invoiceflow.demo`, `cliente@invoiceflow.demo` con contraseña `password` **solo si no estás en producción** (o si pones `DEMO_SEED=true` en `.env`).

**Primer administrador en servidor real:**

```bash
php artisan invoiceflow:create-admin tu@correo.com
```

Seguí las indicaciones interactivas (nombre/contraseña si es usuario nuevo; promoción a admin si el email ya existe).

## Despliegue en Fly.io

Guía paso a paso: [DEPLOY_FLY.md](DEPLOY_FLY.md) (Dockerfile generado con `fly-apps/dockerfile-laravel`, `fly.toml`, Postgres recomendado).

## Producción (resumen)

- `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` con HTTPS.
- `composer install --no-dev --optimize-autoloader`
- `npm ci && npm run build`
- Migraciones: `php artisan migrate --force`
- **No** hagas `db:seed` en producción salvo que quieras datos demo (`DEMO_SEED=true`, no recomendado).
- Creá el admin con `invoiceflow:create-admin`.
- Cachés: `php artisan config:cache`, `route:cache`, `view:cache`
- Base de datos: en hosting suele usarse **MySQL** o **PostgreSQL**; configurá `DB_*` en `.env`.

## Tests

```bash
php artisan test
```

## Seguridad y Git

- No commitees **`.env`**, archivos **`*.sqlite`** con datos, ni **`public/hot`** (entrada de desarrollo Vite).
- El repositorio incluye código de negocio y seed de demo con contraseña conocida solo para desarrollo; mantené producción sin esos usuarios o con contraseñas cambiadas.

## Licencia del esqueleto

El proyecto se basa en el [skeleton de Laravel](https://github.com/laravel/laravel) (MIT). El dominio InvoiceFlow y la implementación específica son parte de este repositorio.
