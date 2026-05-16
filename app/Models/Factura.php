<?php

namespace App\Models;

use App\Enums\FacturaEstado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Factura extends Model
{
    protected $fillable = [
        'user_id',
        'cliente_id',
        'numero',
        'fecha_emision',
        'periodo_desde',
        'periodo_hasta',
        'subtotal',
        'impuesto_pct',
        'impuesto_importe',
        'total',
        'estado',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision' => 'date',
            'periodo_desde' => 'date',
            'periodo_hasta' => 'date',
            'subtotal' => 'decimal:2',
            'impuesto_pct' => 'decimal:2',
            'impuesto_importe' => 'decimal:2',
            'total' => 'decimal:2',
            'estado' => FacturaEstado::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function registrosHoras(): HasMany
    {
        return $this->hasMany(RegistroHoras::class, 'factura_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(FacturaPago::class);
    }

    public function montoPagado(): float
    {
        return (float) $this->pagos()->sum('monto');
    }

    public function saldoPendiente(): float
    {
        return max(0, (float) $this->total - $this->montoPagado());
    }
}
