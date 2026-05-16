<x-panel-layout title="Alta en portal cliente" subtitle="Creá credenciales para que tu cliente acceda">
    <form method="POST" action="{{ route('admin.usuarios-cliente.store') }}" class="max-w-xl space-y-6 rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200/60">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700">Cliente</label>
            <select name="cliente_id" required class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach ($clientes as $c)
                    <option value="{{ $c->id }}" @selected(old('cliente_id') == $c->id)>{{ $c->nombre }} ({{ $c->user->name }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Nombre visible</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Email (login)</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Contraseña</label>
            <input type="password" name="password" required class="mt-1 w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" required class="mt-1 w-full rounded-xl border-slate-300 text-sm">
        </div>
        <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-500">Crear acceso</button>
    </form>
</x-panel-layout>
