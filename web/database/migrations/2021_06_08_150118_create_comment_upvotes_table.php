<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentUpvotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_upvotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_user_id');
            $table->unsignedBigInteger('comment_id');
            $table->timestamps();

            $table->foreign('course_user_id')->references('id')->on('course_users');
            $table->foreign('comment_id')->references('id')->on('comments');
            $table->index(['course_user_id']);
            $table->index(['comment_id']);
            $table->unique(['course_user_id', 'comment_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_upvotes');
    }
}
