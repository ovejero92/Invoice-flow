<x-panel-layout title="Panel freelancer" subtitle="Registrá tu tiempo trabajado">
    {{-- Guía principal: visible y legible aunque falle parte del CSS --}}
    <section class="mb-8 rounded-2xl border-2 border-teal-200 bg-gradient-to-b from-teal-50 to-white p-6 shadow-sm">
        <h2 class="text-base font-bold text-teal-950">¿Qué hace esta pantalla?</h2>
        <p class="mt-2 text-sm text-slate-700 leading-relaxed max-w-3xl">
            Acá <strong>registrás las horas</strong> que trabajaste en cada <strong>tarea</strong> de un <strong>proyecto</strong>.
            Esas horas las usa el <strong>panel de administración</strong> para armar <strong>facturas</strong> por periodo.
            El <strong>cliente</strong> ve sus proyectos y facturas en su propio portal (no es esta sesión).
        </p>
        <ol class="mt-5 grid gap-3 sm:grid-cols-3 text-sm text-slate-800">
            <li class="flex gap-3 rounded-xl bg-white/80 p-4 ring-1 ring-teal-100">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-teal-600 text-xs font-bold text-white">1</span>
                <span><strong>Elegí la tarea</strong> en la que trabajaste (de proyectos activos o pausados).</span>
            </li>
            <li class="flex gap-3 rounded-xl bg-white/80 p-4 ring-1 ring-teal-100">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-teal-600 text-xs font-bold text-white">2</span>
                <span><strong>Cronómetro opcional:</strong> Iniciá → Detener copia las horas al campo; o escribí las horas a mano.</span>
            </li>
            <li class="flex gap-3 rounded-xl bg-white/80 p-4 ring-1 ring-teal-100">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-teal-600 text-xs font-bold text-white">3</span>
                <span><strong>Guardá el registro.</strong> Después podés ver todo en <a href="{{ route('freelancer.reporte-horas') }}" class="font-semibold text-teal-800 underline">Reporte de horas</a>.</span>
            </li>
        </ol>
        <p class="mt-4 text-xs text-slate-500">
            ¿No aparece ninguna tarea abajo? Pedile a <strong>administración</strong> que cree el proyecto y las tareas, o que el proyecto no esté archivado / completado sin nuevas tareas activas.
        </p>
    </section>

    <div class="grid gap-6 lg:grid-cols-3 mb-8">
        <div class="rounded-2xl bg-white p-6 shadow-md ring-1 ring-slate-200/80">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Proyectos en curso</p>
            <p class="mt-2 text-3xl font-bold text-teal-700">{{ $proyectosActivos }}</p>
            <p class="mt-1 text-xs text-slate-500">Activos o pausados a tu nombre.</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-md ring-1 ring-slate-200/80">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Horas esta semana</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format((float) $horasSemana, 1, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500">Lunes a domingo de la semana actual.</p>
        </div>
        <a href="{{ route('freelancer.proyectos.index') }}" class="group rounded-2xl bg-gradient-to-br from-teal-600 to-emerald-700 p-6 text-white shadow-lg ring-1 ring-teal-900/20 transition hover:shadow-xl hover:brightness-105">
            <p class="text-xs font-semibold uppercase tracking-wider text-teal-100">Acción rápida</p>
            <p class="mt-2 text-lg font-bold">Ver mis proyectos</p>
            <p class="mt-2 text-sm text-teal-100 leading-relaxed">Listado de proyectos, cliente y estado (solo lectura de tareas).</p>
            <span class="mt-3 inline-flex items-center text-sm font-semibold text-white group-hover:underline">Abrir listado →</span>
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-8 shadow-md ring-1 ring-slate-200/80" x-data="hourTimer()">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Nuevo registro de horas</h2>
                    <p class="mt-1 text-sm text-slate-600">Completá los datos y pulsá <strong class="text-teal-800">Guardar registro</strong>.</p>
                </div>
            </div>

            @if ($tareasTimer->isEmpty())
                <div class="mt-6 rounded-xl border-2 border-dashed border-amber-300 bg-amber-50 p-5 text-sm text-amber-950">
                    <p class="font-semibold">No hay tareas disponibles para cargar horas.</p>
                    <p class="mt-2 text-amber-900/90">Solo se listan tareas de proyectos <strong>activos</strong> o <strong>pausados</strong>. Si tu cuenta es nueva, hace falta que alguien con rol <strong>admin</strong> cree un proyecto y tareas, o que exista al menos un proyecto en curso.</p>
                </div>
            @endif

            <form action="{{ route('freelancer.registro-horas.store') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-800">1. Tarea en la que trabajaste</label>
                    <select name="tarea_id" x-model="tareaId" @if($tareasTimer->isEmpty()) disabled @endif required class="mt-1 block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500 disabled:bg-slate-100 disabled:text-slate-500">
                        <option value="">— Elegí una tarea —</option>
                        @foreach ($tareasTimer as $t)
                            <option value="{{ $t->id }}">{{ $t->proyecto->nombre }}: {{ $t->titulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-800">2. Fecha del trabajo</label>
                    <input type="date" name="fecha" value="{{ now()->toDateString() }}" required class="mt-1 block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500">
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6 text-center text-white shadow-inner">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-teal-300">Cronómetro (opcional)</p>
                    <p class="mt-1 text-xs text-slate-400">Si lo usás, pulsá <strong class="text-white">Detener</strong> y las horas se copian al paso 3.</p>
                    <p class="mt-4 font-mono text-5xl font-bold tabular-nums tracking-tight text-white" x-text="display()">00:00:00</p>
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        <button type="button" @click="start()" class="inline-flex items-center gap-2 rounded-xl bg-teal-500 px-4 py-2.5 text-sm font-bold text-slate-900 shadow hover:bg-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 4.3A1 1 0 004 5v10a1 1 0 001.6.8l8-5a1 1 0 000-1.6l-8-5z"/></svg>
                            Iniciar
                        </button>
                        <button type="button" @click="stop()" class="inline-flex items-center gap-2 rounded-xl bg-slate-600 px-4 py-2.5 text-sm font-bold text-white shadow hover:bg-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-400">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4h3v12H5V4zm7 0h3v12h-3V4z"/></svg>
                            Detener y pasar horas
                        </button>
                        <button type="button" @click="reset()" class="inline-flex items-center gap-2 rounded-xl border-2 border-slate-500 bg-transparent px-4 py-2.5 text-sm font-bold text-slate-200 hover:bg-slate-800">
                            Reiniciar
                        </button>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-800">3. Cantidad de horas</label>
                    <p class="text-xs text-slate-500 mb-1">Ej.: 1,5 = una hora y media. Podés escribir sin usar el cronómetro.</p>
                    <input type="number" step="0.01" min="0.01" max="24" name="horas" x-model="horasField" @if($tareasTimer->isEmpty()) disabled @endif required placeholder="0,00" class="mt-1 block w-full max-w-xs rounded-xl border-slate-300 text-base font-mono shadow-sm focus:border-teal-500 focus:ring-teal-500 disabled:bg-slate-100">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-800">Notas (opcional)</label>
                    <textarea name="notas" rows="2" class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500" placeholder="Ej.: llamada con cliente, revisión de diseño…"></textarea>
                </div>
                <button type="submit" @if($tareasTimer->isEmpty()) disabled @endif class="w-full sm:w-auto rounded-xl bg-teal-600 px-8 py-3 text-sm font-bold text-white shadow-lg hover:bg-teal-500 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none">
                    Guardar registro de horas
                </button>
            </form>
        </div>

        <div class="rounded-2xl bg-white p-8 shadow-md ring-1 ring-slate-200/80">
            <h2 class="text-lg font-bold text-slate-900">Últimos registros</h2>
            <p class="mt-1 text-sm text-slate-600">Lo más reciente que cargaste en esta cuenta.</p>
            <ul class="mt-5 divide-y divide-slate-100">
                @forelse ($ultimosRegistros as $r)
                    <li class="flex justify-between gap-4 py-3 text-sm">
                        <span class="text-slate-700"><span class="font-mono text-slate-500">{{ $r->fecha->format('d/m') }}</span> · {{ $r->tarea->titulo }}</span>
                        <span class="shrink-0 font-mono font-semibold text-slate-900">{{ number_format((float) $r->horas, 2, ',', '.') }} h</span>
                    </li>
                @empty
                    <li class="py-10 text-center text-slate-500 text-sm">Todavía no hay registros. Cuando guardes el primero, aparecerá acá.</li>
                @endforelse
            </ul>
            <a href="{{ route('freelancer.reporte-horas') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-teal-700 hover:text-teal-900 hover:underline">
                Ver reporte filtrable por fechas
                <span aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</x-panel-layout>
