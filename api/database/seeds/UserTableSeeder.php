<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
        	'name'=>'Admin',
        	'email'=>'admin@steed.vn',
        	'password'=>Hash::make('123456')
        ]);
    }
}
