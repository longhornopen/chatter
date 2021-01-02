<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('lti_context_pk')->nullable();
            $table->timestamps();

            $table->index(['lti_context_pk']);
        });

        Schema::create('course_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('role');
            $table->string('lti_user_id')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->index(['course_id', 'lti_user_id']);
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('author_user_id');
            $table->string('author_user_name');
            $table->string('author_user_role');
            $table->boolean('author_anonymous')->default(false);
            $table->string('title');
            $table->string('body');
            $table->boolean('pinned')->default(false);
            $table->boolean('locked')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('author_user_id')->references('id')->on('course_users');
            $table->index('course_id');
        });

        Schema::create('course_user_post_last_read_flags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('course_user_id');
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('course_user_id')->references('id')->on('course_users');
            $table->index(['post_id','course_user_id']);
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('author_user_id');
            $table->string('author_user_name');
            $table->string('author_user_role');
            $table->boolean('author_anonymous')->default(false);
            $table->unsignedBigInteger('parent_comment_id')->nullable();
            $table->boolean('endorsed')->default(false);
            $table->unsignedBigInteger('muted_by_user_id')->nullable();
            $table->text('body');
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('author_user_id')->references('id')->on('course_users');
            $table->foreign('muted_by_user_id')->references('id')->on('course_users');
            $table->foreign('parent_comment_id')->references('id')->on('comments');
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_endorsed_users');
        Schema::dropIfExists('course_user_post_last_read_flags');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('course_users');
        Schema::dropIfExists('courses');
    }
}
