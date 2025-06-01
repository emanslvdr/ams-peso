<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('user_application', function (Illuminate\Database\Schema\Blueprint $table) {
            // Drop the old foreign key (if it exists)
            $table->dropForeign(['job_id']);
            // Add the correct one
            $table->foreign('job_id')->references('id')->on('job_listing')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('user_application', function (Illuminate\Database\Schema\Blueprint $table) {
            // Drop the correct foreign key
            $table->dropForeign(['job_id']);
            // (Optional) Restore the old one, but you probably don't need to
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

};
