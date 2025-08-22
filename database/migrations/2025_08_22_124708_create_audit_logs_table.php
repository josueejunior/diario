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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('auditable_type'); // Modelo auditado (Edicao, Materia, etc.)
            $table->unsignedBigInteger('auditable_id'); // ID do registro auditado
            $table->string('event'); // created, updated, deleted, published, signed
            $table->json('old_values')->nullable(); // Valores anteriores
            $table->json('new_values')->nullable(); // Novos valores
            $table->unsignedBigInteger('user_id')->nullable(); // Usuário que fez a ação
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('url')->nullable();
            $table->json('tags')->nullable(); // Tags para categorização
            $table->timestamps();
            
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['user_id']);
            $table->index(['event']);
            $table->index(['created_at']);
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
