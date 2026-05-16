<?php

namespace App\Enums;

enum ProyectoEstado: string
{
    case Activo = 'activo';
    case Pausado = 'pausado';
    case Completado = 'completado';
    case Archivado = 'archivado';
}
