<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('user_application', function (Blueprint $table) {
            $table->unique(['user_id', 'job_id'], 'user_job_unique');
        });
    }

    public function down()
    {
        Schema::table('user_application', function (Blueprint $table) {
            $table->dropUnique('user_job_unique');
        });
    }

};
