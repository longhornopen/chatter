<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPostTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->text('post_tags')->nullable();
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->string('tag')->nullable();
        });
        DB::table('courses')->update(['post_tags' => "[" .
                                                 "{\"name\":\"announcement\",\"bgcolor\":\"#9A2617\",\"teacher_only\":true}," .
                                                 "{\"name\":\"question\",\"bgcolor\":\"#093145\",\"teacher_only\":false}," .
                                                 "{\"name\":\"discussion\",\"bgcolor\":\"#C2571A\",\"teacher_only\":false}" .
                                                 "]" ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('post_tags');
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('tag');
        });
    }
}
