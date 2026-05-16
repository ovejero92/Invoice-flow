<x-panel-layout title="Administración" subtitle="Visión general del negocio">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60">
            <p class="text-sm font-medium text-slate-500">Clientes</p>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ $clientes }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60">
            <p class="text-sm font-medium text-slate-500">Proyectos activos</p>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-indigo-700">{{ $proyectosActivos }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60">
            <p class="text-sm font-medium text-slate-500">Facturas pendientes / borrador</p>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-amber-700">{{ $facturasPendientes }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60">
            <p class="text-sm font-medium text-slate-500">Horas registradas este mes (todos)</p>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ number_format((float) $horasMes, 1, ',', '.') }}</p>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-700 p-8 text-white shadow-lg">
            <h2 class="text-lg font-semibold">Flujo de facturación</h2>
            <p class="mt-2 text-sm text-indigo-100 leading-relaxed">
                Desde <strong>Proyectos</strong> marcá tareas como facturables, registrá horas con el panel del freelancer, y generá facturas por rango de fechas en <strong>Facturación</strong>. Los clientes pueden registrar pagos desde su portal.
            </p>
        </div>
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200/60">
            <h2 class="text-lg font-semibold text-slate-900">Accesos rápidos</h2>
            <ul class="mt-4 space-y-2 text-sm text-indigo-700 font-medium">
                <li><a class="hover:underline" href="{{ route('admin.clientes.create') }}">Nuevo cliente</a></li>
                <li><a class="hover:underline" href="{{ route('admin.proyectos.create') }}">Nuevo proyecto</a></li>
                <li><a class="hover:underline" href="{{ route('admin.facturas.create') }}">Generar factura</a></li>
                <li><a class="hover:underline" href="{{ route('admin.usuarios-cliente.create') }}">Dar acceso a un cliente</a></li>
            </ul>
        </div>
    </div>
</x-panel-layout>
