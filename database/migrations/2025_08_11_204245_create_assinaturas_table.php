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
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edicao_id')->constrained('edicoes')->onDelete('cascade');
            $table->string('signatario');
            $table->string('ac'); // Autoridade Certificadora
            $table->string('algoritmo');
            $table->string('hash');
            $table->timestamp('carimbo_tempo');
            $table->text('cadeia_certificados'); // JSON da cadeia de certificados
            $table->foreignId('signed_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
};
