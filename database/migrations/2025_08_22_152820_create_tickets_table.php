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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'critica'])->default('media');
            $table->enum('status', ['aberto', 'em_andamento', 'aguardando', 'resolvido', 'fechado'])->default('aberto');
            $table->enum('categoria', ['bug', 'melhoria', 'suporte', 'duvida'])->default('suporte');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('atribuido_para')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('data_abertura');
            $table->timestamp('data_fechamento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
