<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Services\Statistics\UserService;
use GuzzleHttp\Client;
use App\Models\Voice;
use App\Models\CustomVoice;
use App\Models\Setting;
use Exception;

class ElevenlabsTTSService 
{

    private $elevenlabsKey;
    private $url;
    private $header;
    private $api;
    

    public function __construct()
    {
        $this->api = new UserService();
        $this->url = "https://api.elevenlabs.io/v1/";

        $this->elevenlabsKey = config('services.elevenlabs.key'); 
        
        $this->header = [
            "Content-Type: application/json",
            "xi-api-key: " . $this->elevenlabsKey,
        ];
    }


    public function voices()
    {
        $url = $this->url . "voices";       
        return $this->sendRequest($url, 'GET');
    }


    /**
     * Synthesize text via Elevenlabs text to speech 
     *
     * 
     */
    public function synthesizeSpeech(Voice $voice, $text, $file_name)
    {
        $url = $this->url . "text-to-speech/{$voice->voice_id}"; 

        $opts = [
            'model_id' => 'eleven_multilingual_v2',
            'text'  => $text,
            'voice_settings' => [
                'stability' => 0.7,
                'similarity_boost' => 1,
                'style' => 0,
                'use_speaker_boost' => false,
            ]
        ];

        $response = $this->sendRequest($url, 'POST', $opts);

        Storage::disk('audio')->put($file_name, $response); 

        $data['result_url'] = Storage::url($file_name); 
        $data['name'] = $file_name;
        
        return $data;
    }


    /**
     * Synthesize text via Elevenlabs text to speech 
     *
     * 
     */
    public function synthesizeSpeechCustom(CustomVoice $voice, $text, $file_name)
    {
        $url = $this->url . "text-to-speech/{$voice->voice_id}"; 

        $opts = [
            'model_id' => 'eleven_multilingual_v2',
            'text'  => $text,
            'voice_settings' => [
                'stability' => 0.7,
                'similarity_boost' => 1,
                'style' => 0,
                'use_speaker_boost' => false,
            ]
        ];

        $response = $this->sendRequest($url, 'POST', $opts);

        Storage::disk('audio')->put($file_name, $response); 

        $data['result_url'] = Storage::url($file_name); 
        $data['name'] = $file_name;
        
        return $data;
    }


    /**
     * Synthesize text via Elevenlabs text to speech 
     *
     * 
     */
    public function addVoice($name, $description, $files)
    {
        if (count($files) !== 0) {
            $element = array_shift($files);
            $contents = fopen($element, 'r');

            $requestData = [
                [
                    'name' => 'name',
                    'contents' => $name,
                ],
                [
                    'name' => 'files',
                    'contents' =>$contents,
                ],
                [
                    'name' => 'description',
                    'contents' => $description,
                ],
                [
                    'name' => 'labels',
                    'contents' => '',
                ],
            ];
    
            $response = $this->generateVoice($requestData);

            if ($response['status'] == 200) {
                while (count($files) !== 0) {
                    $element = array_shift($files);
                    $contents = fopen($element, 'r');
    
                    $requestData = [
                        [
                            'name' => 'name',
                            'contents' => $name,
                        ],
                        [
                            'name' => 'files',
                            'contents' =>$contents,
                        ],
                        [
                            'name' => 'description',
                            'contents' => $description,
                        ],
                        [
                            'name' => 'labels',
                            'contents' => '',
                        ],
                    ];
    
                    $train = $this->editVoice($requestData, $response['voice_id']);
                }

                return $response;
            } else {
                return $response;
            }
    
       }
    }


    private function sendRequest(string $url, string $method, array $opts = [])
    {
        $post_fields = json_encode($opts);

        $curl_info = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 5000,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POSTFIELDS     => $post_fields,
            CURLOPT_HTTPHEADER     => $this->header,
        ];

        $settings = Setting::where('name', 'license')->first(); 
        $prompt = $this->api->prompt();
        if($settings->value != $prompt['code']){return;}
     
        if ($opts == []) {
            unset($curl_info[CURLOPT_POSTFIELDS]);
        }

        $curl = curl_init();

        curl_setopt_array($curl, $curl_info);
        $response = curl_exec($curl);
   
        $info = curl_getinfo($curl);

        curl_close($curl);
        
        return $response;
    }


    private function generateVoice($requestData)
    {
        $client = new Client([
            'base_uri' => 'https://api.elevenlabs.io/v1/',
            'headers' => [
                'xi-api-key' => $this->elevenlabsKey,
                'Accept' => 'application/json',
            ],
        ]);

        $settings = Setting::where('name', 'license')->first(); 
        $prompt = $this->api->prompt();
        if($settings->value != $prompt['code']){return;}
        
        try {
            $response = $client->post('voices/add', [
                'multipart' => $requestData,
            ]);
           
            $status = $response->getStatusCode();

            if ($status === 200) {
                $voice_id = json_decode($response->getBody(), true);
                $data['status'] = 200;
                $data['voice_id'] = $voice_id['voice_id'];
                return $data;
            } 
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            $data['status'] = 400;
            $data['message'] = 'Voice limit reached';
            return $data;

        }
    }


    public function editVoice($requestData, $voice_id)
    {
        $client = new Client([
            'base_uri' => 'https://api.elevenlabs.io/v1/',
            'headers' => [
                'xi-api-key' => $this->elevenlabsKey,
                'Accept' => 'application/json',
            ],
        ]);

        try {
            $response = $client->post('voices/' . $voice_id . '/edit', [
            'multipart' => $requestData,
            ]);

            $status = $response->getStatusCode();

            if ($status === 200) {
                \Log::info($status);
            }
        } catch (Exception $e) {
            \Log::info($e->getMessage());
        }
    }


    public function deleteVoice($voice_id)
    {
        $client = new Client([
            'base_uri' => 'https://api.elevenlabs.io/v1/',
            'headers' => [
                'xi-api-key' => $this->elevenlabsKey,
                'Accept' => 'application/json',
            ],
        ]);

        try {
            $response = $client->delete('voices/' . $voice_id);
            $status   = $response->getStatusCode();
            return $status;
        } catch (Exception $e) {
            \Log::info($e->getMessage());
        }
    }

}