<x-panel-layout title="Datos fiscales" subtitle="Aparecen en tus facturas PDF">
    <form method="POST" action="{{ route('freelancer.organization.settings.update') }}" class="max-w-xl space-y-4 rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200/60">
        @csrf
        @method('PATCH')
        @if (session('ok'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('ok') }}</div>
        @endif
        <div>
            <label class="block text-sm font-medium text-slate-700">Nombre del estudio</label>
            <input type="text" name="name" value="{{ old('name', $organization->name) }}" required class="mt-1 w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Razon social / nombre fiscal</label>
            <input type="text" name="legal_name" value="{{ old('legal_name', $organization->legal_name) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">NIF / CIF / Tax ID</label>
            <input type="text" name="tax_id" value="{{ old('tax_id', $organization->tax_id) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Direccion fiscal</label>
            <textarea name="legal_address" rows="2" class="mt-1 w-full rounded-xl border-slate-300 text-sm">{{ old('legal_address', $organization->legal_address) }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Prefijo serie factura</label>
                <input type="text" name="invoice_series_prefix" value="{{ old('invoice_series_prefix', $organization->invoice_series_prefix) }}" placeholder="FAC" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">IVA % por defecto</label>
                <input type="number" name="default_tax_rate" value="{{ old('default_tax_rate', $organization->default_tax_rate) }}" min="0" max="100" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Pais (ISO)</label>
            <input type="text" name="country_code" value="{{ old('country_code', $organization->country_code) }}" maxlength="2" class="mt-1 w-24 rounded-xl border-slate-300 text-sm uppercase">
        </div>
        <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Guardar</button>
    </form>
</x-panel-layout>

