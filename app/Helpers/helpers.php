<?php

if (!function_exists('getRolNombre')) {
    /**
     * Obtiene el nombre legible de un rol
     */
    function getRolNombre(string $rol): string
    {
        return match($rol) {
            'coordinador' => 'Coordinador',
            'instructor_lider' => 'Instructor Líder',
            'instructor' => 'Instructor',
            'aprendiz' => 'Aprendiz',
            default => ucfirst($rol),
        };
    }
}

if (!function_exists('getEstadoNombre')) {
    /**
     * Obtiene el nombre legible de un estado
     */
    function getEstadoNombre(string $estado): string
    {
        return match($estado) {
            'activo' => 'Activo',
            'inactivo' => 'Inactivo',
            'suspendido' => 'Suspendido',
            'cancelado' => 'Cancelado',
            'activa' => 'Activa',
            'finalizada' => 'Finalizada',
            'cancelada' => 'Cancelada',
            default => ucfirst($estado),
        };
    }
}

if (!function_exists('getJuicioNombre')) {
    /**
     * Obtiene el nombre legible de un juicio
     */
    function getJuicioNombre(string $juicio): string
    {
        return match($juicio) {
            'A' => 'Aprobado',
            'D' => 'Deficiente',
            default => $juicio,
        };
    }
}

if (!function_exists('getEstadoAlertaNombre')) {
    /**
     * Obtiene el nombre legible de un estado de alerta
     */
    function getEstadoAlertaNombre(string $estado): string
    {
        return match($estado) {
            'sin_alerta' => 'Sin Alerta',
            'preventiva' => 'Alerta Preventiva',
            'critica' => 'Alerta Crítica',
            'causal_sancion' => 'Causal de Sanción',
            'cancelacion_automatica' => 'Cancelación Automática',
            default => ucfirst(str_replace('_', ' ', $estado)),
        };
    }
}

if (!function_exists('formatearFecha')) {
    /**
     * Formatea una fecha en formato legible
     */
    function formatearFecha($fecha, string $formato = 'd/m/Y'): string
    {
        if (!$fecha) {
            return '-';
        }

        if (is_string($fecha)) {
            $fecha = new \DateTime($fecha);
        }

        return $fecha->format($formato);
    }
}

if (!function_exists('formatearFechaHora')) {
    /**
     * Formatea una fecha y hora en formato legible
     */
    function formatearFechaHora($fecha, string $formato = 'd/m/Y H:i'): string
    {
        if (!$fecha) {
            return '-';
        }

        if (is_string($fecha)) {
            $fecha = new \DateTime($fecha);
        }

        return $fecha->format($formato);
    }
}

if (!function_exists('diasRestantes')) {
    /**
     * Calcula los días restantes hasta una fecha
     */
    function diasRestantes($fecha): ?int
    {
        if (!$fecha) {
            return null;
        }

        if (is_string($fecha)) {
            $fecha = new \DateTime($fecha);
        }

        $hoy = new \DateTime();
        $diferencia = $hoy->diff($fecha);

        return $diferencia->invert ? -$diferencia->days : $diferencia->days;
    }
}

if (!function_exists('obtenerIniciales')) {
    /**
     * Obtiene las iniciales de un nombre
     */
    function obtenerIniciales(string $nombre): string
    {
        $palabras = explode(' ', trim($nombre));
        $iniciales = '';

        foreach ($palabras as $palabra) {
            if (!empty($palabra)) {
                $iniciales .= strtoupper(substr($palabra, 0, 1));
            }
        }

        return substr($iniciales, 0, 2);
    }
}

