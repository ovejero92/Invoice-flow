<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Política de privacidad — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <main class="mx-auto max-w-3xl px-4 py-12 prose prose-slate">
        <h1>Política de privacidad</h1>
        <p class="text-sm text-slate-500">Última actualización: {{ now()->format('d/m/Y') }}</p>
        <p>InvoiceFlow trata datos de cuenta (nombre, email), datos de clientes y proyectos que vos cargás, y registros de horas/facturación para prestar el servicio.</p>
        <h2>Responsable</h2>
        <p>El responsable del tratamiento es quien opera esta instancia de InvoiceFlow (configurá tu razón social en producción).</p>
        <h2>Finalidad</h2>
        <ul>
            <li>Gestión de cuenta y autenticación.</li>
            <li>Facturación y portal de clientes.</li>
            <li>Comunicaciones transaccionales (verificación de email, recuperación de contraseña) vía nuestro servicio de correo.</li>
        </ul>
        <h2>Conservación</h2>
        <p>Los datos se conservan mientras mantengas la cuenta activa. Podés solicitar eliminación contactando al operador del servicio.</p>
        <h2>Derechos (RGPD / LOPD-GDD)</h2>
        <p>Acceso, rectificación, supresión, limitación, portabilidad y oposición escribiendo al email de contacto del operador.</p>
        <p><a href="{{ url('/') }}">Volver al inicio</a></p>
    </main>
</body>
</html>
