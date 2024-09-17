<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->text('welcome_page')->nullable();
        });

        $welcome_page = <<<MARKDOWN
## Welcome to Chatter!

Chatter is a discussion-board tool where students and instructors can communicate outside the classroom.

Click a post to the left to read it and comment, or the 'Write a Post' button to start a new one!

Check out the 'Help' icon, in the upper right, for more.
MARKDOWN;

        DB::table('courses')->update(['welcome_page' => $welcome_page]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('welcome_page');
        });
    }
};
