<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create(['role_id'=>'1', 'name'=>'Admin', 'email'=>'admin@gmail.com', 'password' => Hash::make('123456'), 'api_token' => Hash::make('123456'), 'created_at' => now(),'updated_at' => now()]);
        User::create(['role_id'=>'2', 'name'=>'User', 'email'=>'user@gmail.com', 'password' => Hash::make('123456'), 'api_token' => Hash::make('123456'), 'created_at' => now(),'updated_at' => now()]);
        Role::create([
            'name'=>'admin',
            'created_at' => now(),
            'updated_at' => now()
            ]);
        Role::create([
            'name'=>'user',
            'created_at' => now(),
            'updated_at' => now()
            ]);
    }
}
