<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        App\User::create([
            'name'=>'Admin',
            'email'=>'admin@steed.vn',
            'password'=>Hash::make('123456'),
            'phone'=>'044545645645',
            'active'=>1,
            'create_by'=>1,
            'modify_by'=>1
        ]);

        Model::reguard();
    }
}
