<x-panel-layout title="Estudios" subtitle="Cuentas registradas y planes">
    @if (session('ok'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('ok') }}</div>
    @endif

    <p class="mb-6 text-sm text-slate-600">
        Cada freelancer que se registra crea un estudio (organización). Activá Pro manualmente hasta integrar pagos.
    </p>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3">Estudio</th>
                    <th class="px-6 py-3">Plan</th>
                    <th class="px-6 py-3">Usuarios</th>
                    <th class="px-6 py-3">Clientes</th>
                    <th class="px-6 py-3">Proyectos</th>
                    <th class="px-6 py-3">Facturas</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($organizations as $org)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-900">{{ $org->name }}</p>
                            <p class="text-xs text-slate-500">{{ $org->slug }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $org->isPro() ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $org->isPro() ? 'Pro' : 'Gratis' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $org->users_count }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $org->clientes_count }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $org->proyectos_count }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $org->facturas_count }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            @if ($org->isPro())
                                <form action="{{ route('admin.organizations.downgrade', $org) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-slate-600 hover:underline">Bajar a Gratis</button>
                                </form>
                            @else
                                <form action="{{ route('admin.organizations.upgrade', $org) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-indigo-600 hover:underline font-medium">Activar Pro</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-slate-500">No hay estudios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $organizations->links() }}</div>
</x-panel-layout>
