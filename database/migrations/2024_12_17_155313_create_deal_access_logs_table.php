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
        Schema::create('deal_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('deal_files')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_access_logs');
    }
};
