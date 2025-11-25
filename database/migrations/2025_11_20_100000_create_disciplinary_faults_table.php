<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplinary_faults', function (Blueprint $table) {
            $table->id();
            $table->string('codigo'); // Literal 1, Literal 2...
            $table->text('description'); // Texto completo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplinary_faults');
    }
};
