<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\YookassaService;
use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;

class YookassaSubscriptionTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yookassa:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process yookassa subscription new charges';

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
     * Check subscription status, block the ones that missed payments.
     *
     * @return int
     */
    public function handle()
    {
        # Get all active subscriptions
        $subscriptions = Subscriber::where('gateway', 'Yookassa')->where('status', 'Active')->get();
        
        foreach($subscriptions as $row) {

            $date = Carbon::createFromFormat('Y-m-d H:i:s', $row->active_until);
            $result = Carbon::createFromFormat('Y-m-d H:i:s', $date)->isPast();

            if ($result) {            
                
                $yookassa = new YookassaService();
                $yookassa->processNewCharge($row->id);                            
            }
        }
    }
}
