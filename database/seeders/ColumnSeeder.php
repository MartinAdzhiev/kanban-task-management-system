<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $columns = array(
            ['id' => 1, 'name'=> 'Test columns', 'board_id' => 1,
                'created_at' => new DateTime, 'updated_at' => new DateTime]
        );

        DB::table('columns')->insert($columns);
    }
}
