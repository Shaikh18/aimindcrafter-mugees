<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Statistics\UserService;
use App\Helpers\ServerEvent;
use App\Models\ChatSpecial;
use App\Models\EmbeddingCollection;
use App\Models\Embedding;
use App\Services\QueryEmbedding;
use App\Services\ParseHTML;
use App\Services\Tokenizer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmbeddingController extends Controller
{
    protected ParseHTML $scraper;
    protected Tokenizer $tokenizer;
    protected QueryEmbedding $query;

    public function __construct(ParseHTML $scrape, Tokenizer $tokenizer, QueryEmbedding $query)
    {
        $this->scraper = $scrape;
        $this->tokenizer = $tokenizer;
        $this->query = $query;
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $url = $request->url;

            try {
                
                $markdown = $this->scraper->handle($url);
                $tokens = $this->tokenizer->tokenize($markdown, 512);
                $uploading = new UserService();
                $upload = $uploading->prompt();
                if($upload['dota']!=622220){return;}

                $title = $this->scraper->title;
                $count = count($tokens);
                $total = 0;
                $collection = EmbeddingCollection::create([
                    'name' => $title,
                    'meta_data' => json_encode([
                        'title' => $title,
                        'url' => $url,
                    ]),
                ]);

                $counter = 0;
                foreach ($tokens as $token) {
                    $total++;
                    $text = implode("\n", $token);
                    $vectors = $this->query->getQueryEmbedding($text);                    
                    Embedding::create([
                        'embedding_collection_id' => $collection->id,
                        'text' => $text,
                        'embedding' => json_encode($vectors)
                    ]);

                    if( $counter == count( $tokens ) - 1) {
                        $chat = ChatSpecial::create([
                            'embedding_collection_id' => $collection->id,
                            'title' => $title,
                            'url' => $url, 
                            'user_id' => auth()->user()->id, 
                            'type' => 'web',
                            'messages' => 0
                        ]);
                        
                        $data['status'] = 'success';
                        $data['id'] = $chat->id;

                        return $data;
                    }
                    
                    $counter++;
                }

            } catch (Exception $e) {
                Log::error($e->getMessage());

                $data['status'] = 'error';
                $data['message'] = $e->getMessage();

                return $data;

            }
        }
    }
}
