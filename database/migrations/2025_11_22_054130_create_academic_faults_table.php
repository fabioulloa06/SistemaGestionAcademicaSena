<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_faults', function (Blueprint $table) {
            $table->id();
            $table->string('codigo'); // Ej: "Razón 1", "Razón 2"
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_faults');
    }
};
