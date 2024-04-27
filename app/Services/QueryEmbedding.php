<?php

namespace App\Services;

use Exception;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\SubscriptionPlan;
use App\Models\ApiKey;

class QueryEmbedding
{
    public function getQueryEmbedding($question): array
    {
        if (config('settings.personal_openai_api') == 'allow') {
            config(['openai.api_key' => auth()->user()->personal_openai_key]);         
        } elseif (!is_null(auth()->user()->plan_id)) {
            $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($check_api->personal_openai_api) {
                config(['openai.api_key' => auth()->user()->personal_openai_key]);                
            } else {
                if (config('settings.openai_key_usage') !== 'main') {
                    $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                    array_push($api_keys, config('services.openai.key'));
                    $key = array_rand($api_keys, 1);
                    config(['openai.api_key' => $api_keys[$key]]);
                } else {
                    config(['openai.api_key' => config('services.openai.key')]);
                }
            }
        } else {
            if (config('settings.openai_key_usage') !== 'main') {
                $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                array_push($api_keys, config('services.openai.key'));
                $key = array_rand($api_keys, 1);
                config(['openai.api_key' => $api_keys[$key]]);
            } else {
                config(['openai.api_key' => config('services.openai.key')]);
            }
        }

        $result = OpenAI::embeddings()->create([
            'model' => config('settings.default_embedding_model'),
            'input' => $question,
        ]);

        if (count($result['data']) == 0) {
            throw new Exception("Failed to generated query embedding!");
        }

        return $result['data'][0]['embedding'];
    }

    public function askQuestionStreamed($context, $question)
    {
        if (config('settings.personal_openai_api') == 'allow') {
            config(['openai.api_key' => auth()->user()->personal_openai_key]);         
        } elseif (!is_null(auth()->user()->plan_id)) {
            $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($check_api->personal_openai_api) {
                config(['openai.api_key' => auth()->user()->personal_openai_key]);                
            } else {
                if (config('settings.openai_key_usage') !== 'main') {
                    $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                    array_push($api_keys, config('services.openai.key'));
                    $key = array_rand($api_keys, 1);
                    config(['openai.api_key' => $api_keys[$key]]);
                } else {
                    config(['openai.api_key' => config('services.openai.key')]);
                }
            }
        } else {
            if (config('settings.openai_key_usage') !== 'main') {
                $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                array_push($api_keys, config('services.openai.key'));
                $key = array_rand($api_keys, 1);
                config(['openai.api_key' => $api_keys[$key]]);
            } else {
                config(['openai.api_key' => config('services.openai.key')]);
            }
        }

        $system_template = "
        Use the following pieces of context to answer the users question. 
        If you don't know the answer, just say that you don't know, don't try to make up an answer.
        ----------------
        {context}
        ";

        $system_prompt = str_replace("{context}", $context, $system_template);

        # Apply proper model based on role and subsciption
        if (auth()->user()->group == 'user') {
            $model = config('settings.default_model_user');
        } elseif (auth()->user()->group == 'admin') {
            $model = config('settings.default_model_admin');
        } else {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            $model = $plan->model_chat;
        }

        return OpenAI::chat()->createStreamed([
            'model' => $model,
            'temperature' => 0.8,
            'messages' => [
                ['role' => 'system', 'content' => $system_prompt],
                ['role' => 'user', 'content' => $question],
            ],
        ]);
    }
}
