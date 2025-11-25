<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    echo "Sending email to fabio.ulloa06@gmail.com...\n";
    Mail::raw('Este es un correo de prueba del Sistema de Control de Asistencias SENA. Si lo recibes, la configuración SMTP es correcta.', function($msg) { 
        $msg->to('fabio.ulloa06@gmail.com')->subject('Prueba de Configuración SMTP - SENA'); 
    });
    echo "Email sent successfully.\n";
} catch (\Exception $e) {
    echo "Error sending email: " . $e->getMessage() . "\n";
}
