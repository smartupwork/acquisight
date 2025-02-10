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
        Schema::table('deal_folders', function (Blueprint $table) {
            $table->renameColumn('drive_folder_id', 'gcs_folder_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deal_folders', function (Blueprint $table) {
            $table->renameColumn('gcs_folder_id', 'drive_folder_id');
        });
    }
};
