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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 120)->nullable()->index();
            $table->string('ip_hash', 64)->index();
            $table->string('path', 2048);
            $table->string('referrer', 2048)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('device', 30)->nullable();
            $table->string('browser', 80)->nullable();
            $table->string('platform', 80)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_bot')->default(false);
            $table->timestamps();
            $table->index(['created_at', 'is_bot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
