<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'prefix' => 'Mr',
            'first_name'=>'Henish',
            'last_name'=>'Patel',
            'role' => 1,
            'email' =>'admin@gmail.com',
            'password' => Hash::make('Henish_12'),
        ]);

    }
}
