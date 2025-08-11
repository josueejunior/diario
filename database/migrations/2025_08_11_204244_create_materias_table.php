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
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_id')->constrained();
            $table->foreignId('orgao_id')->constrained();
            $table->string('numero');
            $table->date('data');
            $table->integer('ano')->storedAs('YEAR(data)');
            $table->string('titulo');
            $table->text('texto');
            $table->string('arquivo')->nullable(); // Para possíveis anexos
            $table->enum('status', ['rascunho', 'revisao', 'aprovado', 'publicado'])->default('rascunho');
            $table->text('notas_revisao')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            // Garante numeração única por tipo/ano usando a coluna computada
            $table->unique(['tipo_id', 'numero', 'ano']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
