<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('project_member')->insert([
            'id' => 1,
            'project_id' => 1,
            'user_id' => 2,
            'role' => 'Backend Developer',
        ]);

        DB::table('project_member')->insert([
            'id' => 2,
            'project_id' => 1,
            'user_id' => 3,
            'role' => 'Frontend Developer',
        ]);

        DB::table('project_member')->insert([
            'id' => 3,
            'project_id' => 1,
            'user_id' => 4,
            'role' => 'DevOps',
        ]);

        DB::table('project_member')->insert([
            'id' => 4,
            'project_id' => 2,
            'user_id' => 2,
            'role' => 'Backend Developer',
        ]);

        DB::table('project_member')->insert([
            'id' => 5,
            'project_id' => 2,
            'user_id' => 3,
            'role' => 'Frontend Developer',
        ]);

        DB::table('project_member')->insert([
            'id' => 6,
            'project_id' => 3,
            'user_id' => 4,
            'role' => 'DevOps',
        ]);
    }
}
