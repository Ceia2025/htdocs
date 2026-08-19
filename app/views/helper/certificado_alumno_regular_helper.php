<?php
// helpers/cert_helper.php
// Utilidades compartidas para los certificados de alumno regular (SAAT).

if (!function_exists('certImagenes')) {
    /** Rutas de imágenes de los certificados, centralizadas en un solo lugar. */
    function certImagenes(): array
    {
        $base = 'http://localhost:8080/app/public/img/';
        return [
            'logo1'  => $base . 'certificado_1.png',
            'logo2'  => $base . 'certificado_3.png', // orden visual: 1, 3, 2
            'logo3'  => $base . 'certificado_2.png',
            'timbre' => $base . 'TIMBRE_JJACH.png',
            'pie'    => $base . 'pie_pagina_certificado.png',
        ];
    }
}

if (!function_exists('certMeses')) {
    function certMeses(): array
    {
        return ['enero','febrero','marzo','abril','mayo','junio',
                'julio','agosto','septiembre','octubre','noviembre','diciembre'];
    }
}

if (!function_exists('certDias')) {
    function certDias(): array
    {
        return ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
    }
}

if (!function_exists('certFechaLarga')) {
    /**
     * Fecha en formato "lunes, 11 de agosto de 2026".
     *  - $fechaISO válido (YYYY-MM-DD)  => usa esa fecha (manual, la del index).
     *  - $fechaISO vacío o inválido     => usa la fecha de hoy (automático).
     */
    function certFechaLarga(?string $fechaISO = null): string
    {
        $fechaISO = trim((string) $fechaISO);
        $dt = null;

        if ($fechaISO !== '') {
            $dt  = DateTime::createFromFormat('Y-m-d', $fechaISO);
            $err = DateTime::getLastErrors();
            if (!$dt || ($err && ($err['warning_count'] || $err['error_count']))) {
                $dt = null; // formato inválido => caemos a hoy
            }
        }

        if (!$dt) {
            $dt = new DateTime(); // hoy
        }

        $dias  = certDias();
        $meses = certMeses();

        return $dias[(int) $dt->format('w')]
            . ', ' . (int) $dt->format('j')
            . ' de ' . $meses[(int) $dt->format('n') - 1]
            . ' de ' . $dt->format('Y');
    }
}

if (!function_exists('certFechaNacimiento')) {
    /** Devuelve "11 agosto 2010" o '' si no hay fecha. */
    function certFechaNacimiento(?string $fechaISO = null): string
    {
        $fechaISO = trim((string) $fechaISO);
        if ($fechaISO === '') {
            return '';
        }
        try {
            $fn = new DateTime($fechaISO);
        } catch (Exception $e) {
            return '';
        }
        $meses = certMeses();
        return $fn->format('d') . ' ' . $meses[(int) $fn->format('n') - 1] . ' ' . $fn->format('Y');
    }
}

if (!function_exists('certDatosAlumno')) {
    /** Normaliza nombre, run y etiquetas de género para las plantillas. */
    function certDatosAlumno(array $d): array
    {
        $nombre = $d['nombre'] ?? '';
        $apepat = $d['apepat'] ?? '';
        $apemat = $d['apemat'] ?? '';
        $run    = $d['run'] ?? '';
        $codver = $d['codver'] ?? '';
        $esF    = ($d['sexo'] ?? '') === 'F';

        return [
            'nombreCompleto' => strtoupper(trim("$nombre $apepat $apemat")),
            'runCompleto'    => $run . ($codver !== '' ? '-' . $codver : ''),
            'esF'            => $esF,
            'alumnaO'        => $esF ? 'alumna' : 'alumno',
            'laEl'           => $esF ? 'la' : 'el',
            'sraSr'          => $esF ? 'Srta.' : 'Sr.',
            'tratamiento'    => 'Don (ña):',
        ];
    }
}