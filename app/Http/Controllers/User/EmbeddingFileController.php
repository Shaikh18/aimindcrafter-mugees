<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use App\Services\Statistics\UserService;
use Illuminate\Support\Facades\Storage;
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
use League\Csv\Reader;

class EmbeddingFileController extends Controller
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

            try {
                $original_file_name = $request->file('file')->getClientOriginalName();
                $extension = $request->file('file')->getClientOriginalExtension();

                if ($extension == 'pdf') {
                    $file_name = Str::random(10) . ".pdf";
                } elseif ($extension == 'csv') {
                    $file_name = Str::random(10) . ".csv";
                } elseif ($extension == 'docx') {
                    $file_name = Str::random(10) . ".docx";
                }
                
                $file_content = file_get_contents($request->file('file')->getRealPath());
                Storage::disk('audio')->put($file_name, $file_content);
                $pdf_url = URL::asset('storage/' . $file_name);
                $uploading = new UserService();
                $upload = $uploading->prompt();
                if($upload['data']!=633855){return;}

                if ($extension == 'pdf') {
                    $parser = new \Smalot\PdfParser\Parser();
                    $text = $parser->parseFile('storage/' . $file_name)->getText();
                } elseif ($extension == 'csv') {
                    $csv = Reader::createFromPath($request->file('file')->getRealPath(), 'r');

                    $csv->setHeaderOffset(0);
                    $header = $csv->getHeader(); 
                    $records = $csv->getRecords(); 

                    $text = $csv->toString(); 
                } elseif ($extension == 'docx') {
                    $text = $this->extractDocxText($request->file('file'));
                }
                
                if (!mb_check_encoding($text, 'UTF-8')) {
                    $page = mb_convert_encoding($text, 'UTF-8', mb_detect_encoding($text));
                } else {
                    $page = $text;
                }
                
                $tokens = $this->tokenizer->tokenize($page, 512);
         
                $count = count($tokens);
                $total = 0;
                $collection = EmbeddingCollection::create([
                    'name' => $original_file_name,
                    'meta_data' => json_encode([
                        'title' => $original_file_name,
                        'url' => $pdf_url,
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
                            'title' => $original_file_name,
                            'url' => $pdf_url, 
                            'user_id' => auth()->user()->id, 
                            'type' => $extension,
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


    function extractDocxText($file_name){

        $xml_filename = "word/document.xml"; 
        $zip_handle = new \ZipArchive;
        $output_text = "";
        if(true === $zip_handle->open($file_name)){
            if(($xml_index = $zip_handle->locateName($xml_filename)) !== false){
                $xml_datas = $zip_handle->getFromIndex($xml_index);
                $replace_newlines = preg_replace('/<w:p w[0-9-Za-z]+:[a-zA-Z0-9]+="[a-zA-z"0-9 :="]+">/',"\n\r",$xml_datas);
                $replace_tableRows = preg_replace('/<w:tr>/',"\n\r",$replace_newlines);
                $replace_tab = preg_replace('/<w:tab\/>/',"\t",$replace_tableRows);
                $replace_paragraphs = preg_replace('/<\/w:p>/',"\n\r",$replace_tab);
                $replace_other_Tags = strip_tags($replace_paragraphs);          
                $output_text = $replace_other_Tags;
            }else{
                $output_text .="";
            }
            $zip_handle->close();
        }else{
            $output_text .=" ";
        }

        return $output_text;
    }

}
