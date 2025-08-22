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
        Schema::create('tipos_arquivos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('extensao');
            $table->string('mime_type');
            $table->integer('tamanho_maximo')->default(10485760); // 10MB
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_arquivos');
    }
};
