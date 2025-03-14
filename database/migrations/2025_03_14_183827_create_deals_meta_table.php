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
        Schema::create('deals_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained('deals')->onDelete('cascade');
            $table->string('asking_price')->nullable();
            $table->string('gross_revenue')->nullable();
            $table->string('cash_flow')->nullable();
            $table->string('ebitda')->nullable();
            $table->string('inventory')->nullable();
            $table->string('ffe')->nullable();
            $table->string('business_desc')->nullable();
            $table->string('location')->nullable();
            $table->string('no_employee')->nullable();
            $table->string('real_estate')->nullable();
            $table->string('rent')->nullable();
            $table->string('lease_expiration')->nullable();
            $table->string('facilities')->nullable();
            $table->string('market_outlook')->nullable();
            $table->string('selling_reason')->nullable();
            $table->string('train_support')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals_meta');
    }
};
