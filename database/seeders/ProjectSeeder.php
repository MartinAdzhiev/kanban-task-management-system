<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\table;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $projects = array(
            ['id' => 1, 'name'=> 'Test project1', 'description' => 'Initial project1', 'owner_id' => 1,
                'created_at' => new DateTime, 'updated_at' => new DateTime],

            ['id' => 2, 'name'=> 'Test project2', 'description' => 'Initial project2', 'owner_id' => 1,
            'created_at' => new DateTime, 'updated_at' => new DateTime],

            ['id' => 3, 'name'=> 'Test project3', 'description' => 'Initial project3', 'owner_id' => 1,
                'created_at' => new DateTime, 'updated_at' => new DateTime]
        );

        DB::table('projects')->insert($projects);
    }
}
