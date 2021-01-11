<?php

namespace App\Console\Commands;

use App\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class VerifyPendingTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify all pending transactions';

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
        $trans = Transaction::where('status', 'pending')->get();
        $trans->each(function ($tran) {
            try {
                Http::get(url("/verify/ussd?refid=$tran->ref"))->throw();
            } catch (\Throwable$th) {
                //throw $th;
            }
        });
    }
}