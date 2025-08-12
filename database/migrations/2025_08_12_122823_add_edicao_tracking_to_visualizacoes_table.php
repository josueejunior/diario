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
        Schema::table('visualizacoes', function (Blueprint $table) {
            $table->unsignedBigInteger('edicao_id')->nullable()->after('materia_id');
            $table->string('ip_address')->nullable()->after('edicao_id');
            $table->text('user_agent')->nullable()->after('ip_address');
            
            $table->foreign('edicao_id')->references('id')->on('edicoes')->onDelete('cascade');
            $table->index(['edicao_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visualizacoes', function (Blueprint $table) {
            $table->dropForeign(['edicao_id']);
            $table->dropColumn(['edicao_id', 'ip_address', 'user_agent']);
        });
    }
};
