<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeCourseUsersMailFrequencyToMinutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_users', function (Blueprint $table) {
            $table->integer('mail_digest_frequency_minutes')->default(-1);
        });

        DB::table('course_users')
            ->update(['mail_digest_frequency_minutes'=>DB::raw('mail_digest_frequency * 60')]);

        Schema::table('course_users', function (Blueprint $table) {
            $table->dropColumn('mail_digest_frequency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_users', function (Blueprint $table) {
            $table->integer('mail_digest_frequency')->default(-1);
        });

        DB::table('course_users')
            ->update(['mail_digest_frequency'=>DB::raw('mail_digest_frequency_minutes / 60')]);

        Schema::table('course_users', function (Blueprint $table) {
            $table->dropColumn('mail_digest_frequency_minutes');
        });
    }
}
