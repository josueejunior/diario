<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('edicoes', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->date('data');
            $table->enum('tipo', ['normal', 'extra']);
            $table->string('arquivo_pdf');
            $table->string('hash');
            $table->timestamp('carimbo_tempo');
            $table->string('signatario');
            $table->string('ac');
            $table->string('algoritmo');
            $table->integer('tamanho');
            $table->integer('visualizacoes')->default(0);
            $table->integer('downloads')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edicoes');
    }
};
