<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Términos de uso — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <main class="mx-auto max-w-3xl px-4 py-12 prose prose-slate">
        <h1>Términos de uso</h1>
        <p class="text-sm text-slate-500">Última actualización: {{ now()->format('d/m/Y') }}</p>
        <p>Al usar InvoiceFlow aceptás estos términos. El servicio se ofrece «tal cual»; no sustituye asesoramiento fiscal o legal.</p>
        <h2>Cuentas</h2>
        <p>El registro público crea cuentas de tipo freelancer con plan gratuito sujeto a límites. Las cuentas de administrador de plataforma y clientes se gestionan según el flujo de la aplicación.</p>
        <h2>Planes</h2>
        <p>El plan gratuito tiene límites de uso (clientes, proyectos, facturas/mes). El plan Pro amplía límites y funciones; la activación de pago puede ser manual hasta integrar pasarela.</p>
        <h2>Responsabilidad</h2>
        <p>Sos responsable de la exactitud de datos fiscales en facturas PDF/CSV. InvoiceFlow no garantiza cumplimiento normativo en tu jurisdicción.</p>
        <p><a href="{{ url('/') }}">Volver al inicio</a></p>
    </main>
</body>
</html>
