<?php

namespace App\Console\Commands;

use App\Notifications\remindReseller;
use App\User;
use Illuminate\Console\Command;

class AwaitingResellers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reseller:remind {days=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind Awaiting Resellers who has not subscribed for certain days';

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
        $resellers = User::where('is_reseller', 1)->get();
        $resellers->each(function ($user) use (&$count) {
            if (!$user->lastSub() && $user->created_at->diffInDays(now()) >= $this->argument('days')) {

                $count++;
                $user->notify(new remindReseller());

            }
        });

        $this->info("{$count} reseller(s) reminded.");

    }
}
