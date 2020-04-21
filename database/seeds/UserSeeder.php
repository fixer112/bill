<?php

use App\Subscription;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'balance' => 1200,
            'login' => 'user',
            'first_name' => 'Abubakar',
            'last_name' => 'Lawal',
            'email' => 'abula3003@gmail.com',
            'password' => 'abula112',
            'is_reseller' => 1,
            'api_token' => Str::random(60),
        ]);

        $tran = Transaction::create([
            'amount' => 1200,
            'balance' => 1200,
            'type' => 'credit',
            'desc' => "Subscription basic bonus",
            'ref' => generateRef($user),
            'user_id' => $user->id,

            // 'reason' => 'subscription',
        ]);

        Subscription::create([
            'amount' => 5000,
            'bonus' => 1200,
            'user_id' => $user->id,
            'name' => 'basic',
            'transaction_id' => $tran->id,
        ]);

        User::create([
            'login' => 'individual',
            'first_name' => 'Abubakar',
            'last_name' => 'Lawal',
            'email' => 'individual@gmail.com',
            'password' => 'abula112',
            'is_reseller' => 0,
            'api_token' => Str::random(60),
        ]);

    }
}