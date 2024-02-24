<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boards = array(
            ['id' => 1, 'name'=> 'Test boards', 'description' => 'Initial board', 'project_id' => 1,
                'created_at' => new DateTime, 'updated_at' => new DateTime]
        );

        DB::table('boards')->insert($boards);
    }
}
