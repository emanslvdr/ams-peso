<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_application', function (Blueprint $table) {
            $table->unsignedBigInteger('job_id')->nullable()->after('user_id');

            // Optional: Add foreign key constraint for integrity
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_application', function (Blueprint $table) {
            // Drop the foreign key first, then the column
            $table->dropForeign(['job_id']);
            $table->dropColumn('job_id');
        });
    }
};
