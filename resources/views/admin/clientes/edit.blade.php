<x-panel-layout title="Editar cliente" subtitle="{{ $cliente->nombre }}">
    <form method="POST" action="{{ route('admin.clientes.update', $cliente) }}" class="max-w-2xl space-y-6 rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200/60">
        @csrf
        @method('PATCH')
        <div>
            <label class="block text-sm font-medium text-slate-700">Freelancer responsable</label>
            <select name="user_id" required class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach ($freelancers as $f)
                    <option value="{{ $f->id }}" @selected(old('user_id', $cliente->user_id) == $f->id)>{{ $f->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Nombre / Razón social</label>
            <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $cliente->email) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Dirección</label>
            <textarea name="direccion" rows="2" class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('direccion', $cliente->direccion) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Notas internas</label>
            <textarea name="notas" rows="3" class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notas', $cliente->notas) }}</textarea>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-500">Actualizar</button>
            <a href="{{ route('admin.clientes.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Volver</a>
        </div>
    </form>
</x-panel-layout>
