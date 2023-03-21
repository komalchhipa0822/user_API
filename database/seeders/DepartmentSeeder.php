<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('departments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $demartments = [
            ['name' => 'Admin'],
            ['name' => 'HR'],
            ['name' => 'PHP'],
            ['name' => 'JS'],
            ['name' => 'ASP.NET'],
            ['name' => 'Marketing'],
        ];

        Department::insert($demartments);
    }
}
