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
            ['id' => 1, 'name'=> 'Test project', 'description' => 'Initial project', 'owner_id' => 1,
                'created_at' => new DateTime, 'updated_at' => new DateTime]
        );

        DB::table('projects')->insert($projects);
    }
}
