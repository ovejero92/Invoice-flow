# Desplegar InvoiceFlow en [Fly.io](https://fly.io/docs/laravel/)

## 1. Herramientas

1. Instalá **Flyctl** (CLI): [Instalación de flyctl](https://fly.io/docs/flyctl/install/) (Windows: `powershell -Command "iwr https://fly.io/install.ps1 -useb | iex"` o el instalador que indique la doc).
2. Iniciá sesión: `fly auth signup` o `fly auth login`.
3. **Docker Desktop** (u otro Docker) suele hacer falta en local para que `fly deploy` construya la imagen en tu máquina.

## 2. Código listo en GitHub

En tu PC, en la carpeta del proyecto (`ProjInvoiceFlow`), commit y push de lo nuevo (Dockerfile, `fly.toml`, `.fly/`, `.dockerignore`, etc.):

```bash
git add Dockerfile fly.toml .dockerignore .fly/ bootstrap/app.php config/database.php composer.json composer.lock
git commit -m "Add Fly.io deployment configuration"
git push
```

## 3. Base PostgreSQL en Fly (recomendado)

La app en `fly.toml` usa `DB_CONNECTION=pgsql`. Creá una BD **antes** o justo después de crear la app:

```bash
fly postgres create --name projinvoiceflow-db --region mad
```

(Region `mad` o la misma que pongas en `primary_region` de `fly.toml`.)

## 4. Crear la app y desplegar

Desde la raíz del proyecto:

```bash
cd /ruta/a/ProjInvoiceFlow
```

- Si **no** tenés `fly.toml` todavía: `fly launch` (Fly puede proponer nombre, región y generar/ajustar archivos; revisá que no sobrescriba tu Dockerfile sin confirmar).
- Si **ya** commiteaste este repo con `fly.toml`, podés:

```bash
fly apps create projinvoiceflow
```

(Si el nombre `projinvoiceflow` está ocupado, cambiá `app` en `fly.toml` y el comando anterior.)

Vinculá la Postgres a la app (ajustá nombres si los cambiaste):

```bash
fly postgres attach projinvoiceflow-db --app projinvoiceflow
```

Fly inyecta `DATABASE_URL` en la aplicación. Este proyecto lee `DATABASE_URL` en `config/database.php` para `pgsql`.

## 5. Secretos obligatorios

Generá una clave en tu máquina (una sola vez) y copiá el valor:

```bash
php artisan key:generate --show
```

Subila a Fly:

```bash
fly secrets set APP_KEY="base64:..." --app projinvoiceflow
```

Definí la URL pública (sustituí por tu dominio `*.fly.dev` real cuando Fly te lo muestre):

```bash
fly secrets set APP_URL="https://projinvoiceflow.fly.dev" --app projinvoiceflow
```

Opcional: `MAIL_*` si querés correo real (reset de contraseña, etc.).

## 6. Primer deploy y administrador

```bash
fly deploy --app projinvoiceflow
```

`release_command` en `fly.toml` ejecuta migraciones tras cada deploy. Si falla por BD, asegurate de haber hecho **`fly postgres attach`** y de que exista `DATABASE_URL`.

Creá el primer admin **en el servidor**:

```bash
fly ssh console --app projinvoiceflow
cd /var/www/html
php artisan invoiceflow:create-admin tu@email.com
exit
```

Abrí la app: `fly apps open --app projinvoiceflow` o la URL `https://<app>.fly.dev`.

## 7. Sobre el botón “Deploy” de Laravel

Eso lleva a Laravel Cloud, no configura Fly. Para Fly usás **`fly deploy`** y esta configuración.

## 8. Ajustes útiles

- **Región:** editá `primary_region` en `fly.toml` ([regiones Fly](https://fly.io/docs/reference/regions/)).
- **Logs:** `fly logs --app projinvoiceflow`
- **Escalar RAM:** editá `[[vm]]` en `fly.toml` y volvé a `fly deploy`.
- **Sesiones:** en `fly.toml` está `SESSION_DRIVER=cookie` para no depender de tabla `sessions` en BD; si preferís `database`, creá la tabla y cambiá el env.

## 9. Coste

Fly tiene cupo gratuito limitado; Postgres y máquinas pueden generar coste según uso. Revisá el panel de facturación de Fly.
