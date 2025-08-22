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
        Schema::create('notification_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('phone')->nullable()->index();
            $table->string('name')->nullable();
            $table->enum('type', ['email', 'whatsapp', 'both'])->default('email');
            $table->json('preferences')->nullable(); // Filtros: tipos, órgãos, palavras-chave
            $table->boolean('active')->default(true);
            $table->string('token')->unique(); // Para unsubscribe
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('last_notification_at')->nullable();
            $table->timestamps();
            
            $table->unique(['email', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_subscriptions');
    }
};
