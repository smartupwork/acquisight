<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeToUserIdForeignInDealFilesTable extends Migration
{
    public function up()
    {
        // Drop the existing foreign key constraint
        Schema::table('deal_files', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Add the new foreign key with 'on delete cascade'
        Schema::table('deal_files', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {

        Schema::table('deal_files', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Optionally, you can remove the cascade rule if rolling back
        Schema::table('deal_files', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users');
        });
    }
}
