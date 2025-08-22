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
        Schema::create('legislacao', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('conteudo');
            $table->enum('tipo', ['lei', 'decreto', 'portaria', 'resolucao', 'instrucao_normativa']);
            $table->string('numero');
            $table->date('data_publicacao');
            $table->string('ementa');
            $table->text('texto_completo');
            $table->string('arquivo_pdf')->nullable();
            $table->enum('status', ['vigente', 'revogada', 'suspensa'])->default('vigente');
            $table->foreignId('orgao_id')->constrained('orgaos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legislacao');
    }
};
