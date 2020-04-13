<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'login' => 'user',
            'first_name' => 'Abubakar',
            'last_name' => 'Lawal',
            'email' => 'abula3003@gmail.com',
            'password' => 'abula112',
            'is_reseller' => 1,
        ]);

        User::create([
            'login' => 'individual',
            'first_name' => 'Abubakar',
            'last_name' => 'Lawal',
            'email' => 'individual@gmail.com',
            'password' => 'abula112',
            'is_reseller' => 0,
        ]);

    }
}