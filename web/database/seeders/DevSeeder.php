<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/** Seed a few dummy classes/users for local development */
class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->insert([
            'name'=>'Chatter test course',
            'post_tags'=>json_encode(Course::get_default_post_tags()),
        ]);
        DB::table('course_users')->insert([
            'course_id'=>1,
            'name'=>'Tammy Teacher',
            'email'=>'tammy@example.edu',
            'role'=>'teacher',
        ]);
        DB::table('course_users')->insert([
            'course_id'=>1,
            'name'=>'Sally Student',
            'email'=>'sally@example.edu',
            'role'=>'student',
        ]);
    }
}
