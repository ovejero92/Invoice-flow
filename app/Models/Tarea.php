<?php

namespace App\Models;

use App\Enums\TareaEstado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarea extends Model
{
    protected $fillable = [
        'proyecto_id',
        'titulo',
        'descripcion',
        'estado',
        'orden',
        'facturable',
    ];

    protected function casts(): array
    {
        return [
            'estado' => TareaEstado::class,
            'facturable' => 'boolean',
        ];
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function registrosHoras(): HasMany
    {
        return $this->hasMany(RegistroHoras::class, 'tarea_id');
    }
}
