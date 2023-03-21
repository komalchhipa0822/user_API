<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('designations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $designations = [
            ['department_id' => '1', 'name' => 'Trainee Admin'],
            ['department_id' => '1', 'name' => 'Junior Admin'],
            ['department_id' => '1', 'name' => 'Senior Admin'],
            ['department_id' => '2', 'name' => 'Trainee HR'],
            ['department_id' => '2', 'name' => 'Junior HR'],
            ['department_id' => '2', 'name' => 'Senior HR'],
            ['department_id' => '3', 'name' => 'Trainee PHP Developer'],
            ['department_id' => '3', 'name' => 'Junior PHP Developer'],
            ['department_id' => '3', 'name' => 'Senior PHP Developer'],
            ['department_id' => '3', 'name' => 'Team Leader PHP'],
            ['department_id' => '3', 'name' => 'Project Manager PHP'],
            ['department_id' => '4', 'name' => 'Trainee JS Developer'],
            ['department_id' => '4', 'name' => 'Junior JS Developer'],
            ['department_id' => '4', 'name' => 'Senior JS Developer'],
            ['department_id' => '4', 'name' => 'Team Leader JS'],
            ['department_id' => '4', 'name' => 'Project Manager JS'],
            ['department_id' => '5', 'name' => 'Trainee ASP.NET Developer'],
            ['department_id' => '5', 'name' => 'Junior ASP.NET Developer'],
            ['department_id' => '5', 'name' => 'Senior ASP.NET Developer'],
            ['department_id' => '5', 'name' => 'Team Leader ASP.NET'],
            ['department_id' => '5', 'name' => 'Project Manager ASP.NET'],
            ['department_id' => '6', 'name' => 'Trainee Marketing'],
            ['department_id' => '6', 'name' => 'Junior Marketing'],
            ['department_id' => '6', 'name' => 'Senior Marketing'],
        ];

        Designation::insert($designations);
    }
}
