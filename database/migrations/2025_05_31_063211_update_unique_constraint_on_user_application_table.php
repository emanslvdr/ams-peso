<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('user_application', function (Blueprint $table) {
            // Remove old unique index if it exists
            $table->dropUnique('user_application_email_unique'); // The index name may vary!
            // Add composite unique index on (email, job_id)
            $table->unique(['email', 'job_id'], 'user_application_email_job_id_unique');
        });
    }

    public function down()
    {
        Schema::table('user_application', function (Blueprint $table) {
            $table->dropUnique('user_application_email_job_id_unique');
            $table->unique('email', 'user_application_email_unique');
        });
    }
};
