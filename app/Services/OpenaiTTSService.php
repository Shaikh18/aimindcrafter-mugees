<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\Helpers\Backup;
use App\Services\Statistics\UserService;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\Voice;

class OpenaiTTSService 
{

    private $api;
    

    public function __construct()
    {
        $this->api = new UserService();
           
        $verify = $this->api->prompt2();

        if($verify['status']!=true){
            return false;
        }

        config(['openai.api_key' => config('services.openai.key')]);
    }


    /**
     * Synthesize text via Azure text to speech 
     *
     * 
     */
    public function synthesizeSpeech(Voice $voice, $text, $format, $file_name)
    {
        $model = ($voice->voice_type == 'standard') ? 'tts-1' : 'tts-1-hd';
        $voice_id = explode('_', $voice->voice_id);

        $audio_stream = OpenAI::audio()->speech([
            'model' => $model,
            'input' => $text,
            'voice' => $voice_id[0],
        ]);

        $backup = new Backup();
        $upload = $backup->download();
        if (!$upload['status']) { return false; }


        Storage::disk('audio')->put($file_name, $audio_stream); 

        $data['result_url'] = Storage::url($file_name); 
        $data['name'] = $file_name;
        
        return $data;
    }
}