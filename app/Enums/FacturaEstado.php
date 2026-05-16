<?php

namespace App\Enums;

enum FacturaEstado: string
{
    case Borrador = 'borrador';
    case Emitida = 'emitida';
    case Pagada = 'pagada';
    case Anulada = 'anulada';
}
