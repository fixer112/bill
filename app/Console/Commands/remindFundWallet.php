<?php

namespace App\Console\Commands;

use App\Notifications\remindUserFund;
use App\User;
use Illuminate\Console\Command;

class remindFundWallet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:remindFund {days=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind users who have not funded their wallet for certain period od days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = 0;
        $users = User::where('is_admin', 0)->chunk(100, function ($users) use (&$count) {
            foreach ($users as $user) {
                $tran = $user->transactions->where('reason', 'top-up')->sortByDesc('created_at')->first();
                if (!$tran || ($tran && $tran->created_at->diffInDays(now()) >= $this->argument('days') && $user->balance < 500)) {

                    if (!$user->is_reseller || ($user->lastSub() && $user->is_reseller)) {

                        $count++;
                        $user->notify((new remindUserFund())->delay(now()->addSeconds(60)));

                    }
                }

            }
        });

        $this->info("{$count} user(s) reminded.");

    }
}