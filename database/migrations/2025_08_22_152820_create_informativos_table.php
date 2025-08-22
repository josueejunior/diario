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
        Schema::create('informativos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('conteudo');
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->enum('tipo', ['noticia', 'comunicado', 'aviso'])->default('noticia');
            $table->enum('status', ['ativo', 'inativo', 'agendado'])->default('ativo');
            $table->boolean('destaque')->default(false);
            $table->string('imagem')->nullable();
            $table->foreignId('autor_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informativos');
    }
};
