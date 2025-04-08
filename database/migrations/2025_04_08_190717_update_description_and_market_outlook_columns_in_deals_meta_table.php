<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('deals_meta', function (Blueprint $table) {
            DB::statement("ALTER TABLE deals_meta MODIFY market_outlook LONGTEXT NULL");
        });
    }

    public function down(): void
    {
        Schema::table('deals_meta', function (Blueprint $table) {
            $table->string('market_outlook', 255)->nullable()->change();
        });
    }
};
