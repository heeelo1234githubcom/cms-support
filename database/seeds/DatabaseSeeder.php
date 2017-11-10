<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Trần Việt Đức',
            'email' => 'tranvietduchn@gmail.com',
            'password' => bcrypt('123@123'),
            'level' => 'admin',
            'status' => 'enable'
        ]);
    }
}
