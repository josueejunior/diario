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
        Schema::create('certificados_nuvem', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('arquivo_p12');
            $table->string('senha_encrypted');
            $table->string('titular');
            $table->string('cpf_cnpj');
            $table->date('data_inicio');
            $table->date('data_vencimento');
            $table->enum('tipo', ['a1', 'a3'])->default('a1');
            $table->enum('status', ['ativo', 'inativo', 'vencido'])->default('ativo');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificados_nuvem');
    }
};
