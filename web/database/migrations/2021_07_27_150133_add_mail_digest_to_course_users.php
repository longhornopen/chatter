<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMailDigestToCourseUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_users', function (Blueprint $table) {
            $table->integer('mail_digest_frequency')->default(-1);
            $table->timestamp('previous_mail_digest_at')->nullable();
            $table->timestamp('last_launch_at')->nullable();
            $table->index(['last_launch_at']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->timestamp('last_comment_at')->nullable();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->timestamp('edited_at')->nullable();
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
            $table->dropColumn('mail_digest_frequency');
            $table->dropColumn('previous_mail_digest_at');
            $table->dropColumn('last_launch_at');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('last_comment_at');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('edited_at');
        });
    }
}
