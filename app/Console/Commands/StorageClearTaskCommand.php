<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Image;
use App\Models\VoiceoverResult;
use App\Models\VideoResult;
use Carbon\Carbon;

class StorageClearTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clear';

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
         $this->processImages();
         $this->processVoiceovers();
         $this->processVideos();
    }

    public function processImages()
    {
        # Get all free images
        $days = config('settings.file_result_duration_user');  
         
        if ($days != -1) {
           $images = Image::where('plan_type', 'free')->where('created_at', '<', Carbon::now()->subDays($days))->get();
       
           foreach($images as $row) {
       
               switch ($row->storage) {
                   case 'local':
                       if (Storage::exists($row->image)) {
                           Storage::delete($row->image);
                       }
                       break;
                   case 'aws':
                       if (Storage::disk('s3')->exists($row->image_name)) {
                           Storage::disk('s3')->delete($row->image_name);
                       }
                       break;
                   case 'wasabi':
                       if (Storage::disk('wasabi')->exists($row->image_name)) {
                           Storage::disk('wasabi')->delete($row->image_name);
                       }
                       break;
                   case 'gcp':
                       if (Storage::disk('gcs')->exists($row->image_name)) {
                           Storage::disk('gcs')->delete($row->image_name);
                       }
                       break;
                   case 'storj':
                       if (Storage::disk('storj')->exists($row->image_name)) {
                           Storage::disk('storj')->delete($row->image_name);
                       }
                       break;
                   case 'dropbox':
                       if (Storage::disk('dropbox')->exists($row->image_name)) {
                           Storage::disk('dropbox')->delete($row->image_name);
                       }
                       break;
                   default:
                       # code...
                       break;
               }
               
               $row->delete();    
               
           }
        }
        

        # Get all paid images
        $paid_images = Image::where('plan_type', 'paid')->get();
       
        foreach($paid_images as $row) {

           $user = User::where('id', $row->user_id)->first();

           if (!is_null($user->plan_id)) {
               $plan = SubscriptionPlan::where('id', $user->plan_id)->first();
               $days = $plan->file_result_duration; 

               if ($days != -1) {

                   if(Carbon::now()->subDays($days)->gt($row->created_at)){
                       switch ($row->storage) {
                           case 'local':
                               if (Storage::exists($row->image)) {
                                   Storage::delete($row->image);
                               }
                               break;
                           case 'aws':
                               if (Storage::disk('s3')->exists($row->image_name)) {
                                   Storage::disk('s3')->delete($row->image_name);
                               }
                               break;
                           case 'wasabi':
                               if (Storage::disk('wasabi')->exists($row->image_name)) {
                                   Storage::disk('wasabi')->delete($row->image_name);
                               }
                               break;
                           case 'gcp':
                               if (Storage::disk('gcs')->exists($row->image_name)) {
                                   Storage::disk('gcs')->delete($row->image_name);
                               }
                               break;
                           case 'storj':
                               if (Storage::disk('storj')->exists($row->image_name)) {
                                   Storage::disk('storj')->delete($row->image_name);
                               }
                               break;
                           case 'dropbox':
                               if (Storage::disk('dropbox')->exists($row->image_name)) {
                                   Storage::disk('dropbox')->delete($row->image_name);
                               }
                               break;
                           default:
                               # code...
                               break;
                       }
                       
                       $row->delete(); 
                   }
                    
               }

           }

        }
    }

    public function processVoiceovers()
    {
        # Get all free images
        $days = config('settings.file_result_duration_user');  
         
        if ($days != -1) {
           $voiceovers = VoiceoverResult::where('plan_type', 'free')->where('created_at', '<', Carbon::now()->subDays($days))->get();
       
           foreach($voiceovers as $row) {
       
               switch ($row->storage) {
                   case 'local':
                       if (Storage::exists($row->file_name)) {
                           Storage::delete($row->file_name);
                       }
                       break;
                   case 'aws':
                       if (Storage::disk('s3')->exists($row->file_name)) {
                           Storage::disk('s3')->delete($row->file_name);
                       }
                       break;
                   case 'wasabi':
                       if (Storage::disk('wasabi')->exists($row->file_name)) {
                           Storage::disk('wasabi')->delete($row->file_name);
                       }
                       break;
                   case 'gcp':
                       if (Storage::disk('gcs')->exists($row->file_name)) {
                           Storage::disk('gcs')->delete($row->file_name);
                       }
                       break;
                   case 'storj':
                       if (Storage::disk('storj')->exists($row->file_name)) {
                           Storage::disk('storj')->delete($row->file_name);
                       }
                       break;
                   case 'dropbox':
                       if (Storage::disk('dropbox')->exists($row->file_name)) {
                           Storage::disk('dropbox')->delete($row->file_name);
                       }
                       break;
                   default:
                       # code...
                       break;
               }
               
               $row->delete();    
               
           }
        }
        

        # Get all paid images
        $voiceover = VoiceoverResult::where('plan_type', 'paid')->get();
       
        foreach($voiceover as $row) {

           $user = User::where('id', $row->user_id)->first();

           if (!is_null($user->plan_id)) {
               $plan = SubscriptionPlan::where('id', $user->plan_id)->first();
               $days = $plan->file_result_duration; 

               if ($days != -1) {

                   if(Carbon::now()->subDays($days)->gt($row->created_at)){
                        switch ($row->storage) {
                            case 'local':
                                if (Storage::exists($row->file_name)) {
                                    Storage::delete($row->file_name);
                                }
                                break;
                            case 'aws':
                                if (Storage::disk('s3')->exists($row->file_name)) {
                                    Storage::disk('s3')->delete($row->file_name);
                                }
                                break;
                            case 'wasabi':
                                if (Storage::disk('wasabi')->exists($row->file_name)) {
                                    Storage::disk('wasabi')->delete($row->file_name);
                                }
                                break;
                            case 'gcp':
                                if (Storage::disk('gcs')->exists($row->file_name)) {
                                    Storage::disk('gcs')->delete($row->file_name);
                                }
                                break;
                            case 'storj':
                                if (Storage::disk('storj')->exists($row->file_name)) {
                                    Storage::disk('storj')->delete($row->file_name);
                                }
                                break;
                            case 'dropbox':
                                if (Storage::disk('dropbox')->exists($row->file_name)) {
                                    Storage::disk('dropbox')->delete($row->file_name);
                                }
                                break;
                            default:
                                # code...
                                break;
                        }
                       
                       $row->delete(); 
                   }
                    
               }

           }

        }
    }

    public function processVideos()
    {
        # Get all free images
        $days = config('settings.file_result_duration_user');  
         
        if ($days != -1) {
           $videos = VideoResult::where('created_at', '<', Carbon::now()->subDays($days))->get();
       
           foreach($videos as $row) {
       
               switch ($row->storage) {
                   case 'local':
                       if (Storage::exists($row->video)) {
                           Storage::delete($row->video);
                       }
                       if (Storage::exists($row->image)) {
                            Storage::delete($row->image);
                        }
                       break;
                   case 'aws':
                       if (Storage::disk('s3')->exists($row->video)) {
                           Storage::disk('s3')->delete($row->video);
                       }
                       if (Storage::disk('s3')->exists($row->image)) {
                            Storage::disk('s3')->delete($row->image);
                        }
                       break;
                   case 'wasabi':
                       if (Storage::disk('wasabi')->exists($row->video)) {
                           Storage::disk('wasabi')->delete($row->video);
                       }
                       if (Storage::disk('wasabi')->exists($row->image)) {
                            Storage::disk('wasabi')->delete($row->image);
                        }
                       break;
                   case 'gcp':
                       if (Storage::disk('gcs')->exists($row->video)) {
                           Storage::disk('gcs')->delete($row->video);
                       }
                       if (Storage::disk('gcs')->exists($row->image)) {
                            Storage::disk('gcs')->delete($row->image);
                        }
                       break;
                   case 'storj':
                       if (Storage::disk('storj')->exists($row->video)) {
                           Storage::disk('storj')->delete($row->video);
                       }
                       if (Storage::disk('storj')->exists($row->image)) {
                            Storage::disk('storj')->delete($row->image);
                        }
                       break;
                   case 'dropbox':
                       if (Storage::disk('dropbox')->exists($row->video)) {
                           Storage::disk('dropbox')->delete($row->video);
                       }
                       if (Storage::disk('dropbox')->exists($row->image)) {
                            Storage::disk('dropbox')->delete($row->image);
                        }
                       break;
                   default:
                       # code...
                       break;
               }
               
               $row->delete();    
               
           }
        }
    }
}
