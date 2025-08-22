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
        Schema::create('approval_workflows', function (Blueprint $table) {
            $table->id();
            $table->string('workflowable_type'); // Tipo do modelo (Materia, Edicao)
            $table->unsignedBigInteger('workflowable_id'); // ID do registro
            $table->enum('current_step', ['draft', 'review', 'approval', 'published', 'rejected'])->default('draft');
            $table->json('steps')->nullable(); // Histórico de etapas
            $table->unsignedBigInteger('current_user_id')->nullable(); // Usuário responsável atual
            $table->unsignedBigInteger('created_by')->nullable();
            $table->text('comments')->nullable(); // Comentários da etapa atual
            $table->timestamp('step_deadline')->nullable(); // Prazo para ação
            $table->json('metadata')->nullable(); // Dados adicionais
            $table->timestamps();
            
            $table->index(['workflowable_type', 'workflowable_id']);
            $table->index(['current_step']);
            $table->index(['current_user_id']);
            $table->index(['step_deadline']);
            
            $table->foreign('current_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_workflows');
    }
};
