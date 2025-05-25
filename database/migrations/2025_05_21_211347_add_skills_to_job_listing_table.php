<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('job_listing', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('skills')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('job_listing', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('skills');
        });
    }

};
