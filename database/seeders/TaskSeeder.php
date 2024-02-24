<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tasks = array(
            ['id' => 1, 'name'=> 'Test task1', 'description' => 'desc task1', 'assigned_to' => 1,
               'column_id' => 1, 'deadline' => new DateTime, 'priority' => 1, 'created_at' => new DateTime, 'updated_at' => new DateTime]
        );

        DB::table('tasks')->insert($tasks);
    }
}
