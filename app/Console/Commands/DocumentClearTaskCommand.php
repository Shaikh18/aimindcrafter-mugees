<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Content;
use Carbon\Carbon;

class DocumentClearTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'document:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Control data storage';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         $this->processDocuments();
    }

    public function processDocuments()
    {
        # Get all free documents
        $days = config('settings.document_result_duration_user');  
         
        if ($days != -1) {
           $documents = Content::where('plan_type', 'free')->where('created_at', '<', Carbon::now()->subDays($days))->get();
       
           foreach($documents as $row) {
               $row->delete();                  
           }
        }
        

        # Get all paid documents
        $paid_documents = Content::where('plan_type', 'paid')->get();
       
        foreach($paid_documents as $row) {

           $user = User::where('id', $row->user_id)->first();

           if (!is_null($user->plan_id)) {
               $plan = SubscriptionPlan::where('id', $user->plan_id)->first();
               $days = $plan->document_result_duration; 

               if ($days != -1) {

                   if(Carbon::now()->subDays($days)->gt($row->created_at)){
                       $row->delete(); 
                   }
                    
               }
           }
        }
    }
}
