<x-panel-layout title="Clientes" subtitle="Cuentas de clientes y contactos">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-slate-600">Cada cliente pertenece a un freelancer responsable.</p>
        <a href="{{ route('admin.clientes.create') }}" class="inline-flex justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">Nuevo cliente</a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Freelancer</th>
                    <th class="px-6 py-3">Proyectos</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($clientes as $c)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $c->nombre }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $c->user->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $c->proyectos_count }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.clientes.edit', $c) }}" class="text-indigo-600 hover:underline">Editar</a>
                            <form action="{{ route('admin.clientes.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar cliente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500">No hay clientes todavía.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $clientes->links() }}</div>
</x-panel-layout>
