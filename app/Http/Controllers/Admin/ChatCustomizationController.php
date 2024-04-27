<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\ChatCategory;
use App\Models\ChatPrompt;
use App\Models\Chat;
use DataTables;

class ChatCustomizationController extends Controller
{
    /**
     * List all ai chats
     */
    public function chats(Request $request)
    {
        if ($request->ajax()) {
            $data = Chat::orderBy('category', 'asc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>        
                                        <a class="editButton" href="'. route("admin.davinci.chat.edit", $row["id"] ). '"><i class="fa fa-edit table-action-buttons view-action-button" title="'. __('Edit Chat Bot') .'"></i></a>      
                                        <a class="activateButton" id="' . $row["id"] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="'. __('Activate Chat Bot') .'"></i></a>
                                        <a class="deactivateButton" id="' . $row["id"] . '" href="#"><i class="fa fa-close table-action-buttons delete-action-button" title="'. __('Deactivate Chat Bot') .'"></i></a>  
                                    </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["updated_at"], 'd M Y').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-status', function($row){
                        $status = ($row['status']) ? 'active' : 'deactive'; 
                        $custom_voice = '<span class="cell-box status-'. $status.'">'.ucfirst($status).'</span>';
                        return $custom_voice;
                    })
                    ->addColumn('custom-group', function($row){
                        $group = '<span>'.ucfirst($row['group']).'</span>';
                        return $group;
                    })
                    ->addColumn('custom-avatar', function($row){
                        if ($row['logo']) {
                            $path = URL::asset($row['logo']);
                        } else {
                            $path = URL::asset('img/users/avatar.jpg');
                        }

                        $avatar = '<div class="widget-user-image-sm overflow-hidden"><img alt="Voice Avatar" class="rounded-circle" src="' . $path . '"></div>';
                        return $avatar;
                    })
                    ->addColumn('custom-package', function($row){
                        switch ($row['category']) {
                            case 'all':
                                $package = '<span class="cell-box plan-regular">' . __('All') .'</span>';
                                break;
                            case 'free':
                                $package = '<span class="cell-box plan-free">' . __('Free') .'</span>';
                                break;
                            case 'professional':
                                $package = '<span class="cell-box plan-professional">' . __('Professional') .'</span>';
                                break;
                            case 'premium':
                                $package = '<span class="cell-box plan-premium">' . __('Premium') .'</span>';
                                break;
                            default:
                                $package = '<span class="cell-box plan-regular">' . __('Standard') .'</span>';
                                break;
                        }                      
                        return $package;
                    })
                    ->rawColumns(['actions', 'created-on', 'custom-status', 'custom-avatar', 'custom-package', 'custom-group'])
                    ->make(true);
                    
        }

        return view('admin.davinci.chats.index');
    }


    /**
     * Edit the specified chat
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $chat = Chat::where('id', $id)->first();

        $categories = ChatCategory::all();

        return view('admin.davinci.chats.edit', compact('chat', 'categories'));     
    }


    /**
     * Create new chat chat
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $categories = ChatCategory::all();

        return view('admin.davinci.chats.create', compact('categories'));     
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $code = strtoupper(Str::random(5));
        $status = (request('activate') == 'on') ? true : false;

        $chat = new Chat([
            'status' => $status,
            'name' => request('name'),
            'sub_name' => request('character'),
            'description' => request('introduction'),
            'prompt' => request('prompt'),
            'category' => request('category'),
            'chat_code' => $code,
            'type' => 'custom',
            'group' => request('group')
        ]); 

        $chat->save();

        if (request()->has('logo')) {
        
            try {
                request()->validate([
                    'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:10048'
                ]);
                
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'PHP FileInfo is disabled: ' . $e->getMessage());
            }
            
            $image = request()->file('logo');

            $name = Str::random(5);

          
            $filePath = '/chats/' . $name . '.' . $image->getClientOriginalExtension();
            
            $this->uploadImage($image, 'chats/', 'public', $name);
            
            $chat->logo = $filePath;
            $chat->save();
        }

        toastr()->success(__('Chat Bot has been successfully created'));
        return redirect()->route('admin.davinci.chats');     
    }


    
    /**
     * Update the specified chat
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {   
        $chat = Chat::where('id', $id)->first();

        $status = (request('activate') == 'on') ? true : false;

        $chat->update([
            'status' => $status,
            'name' => request('name'),
            'sub_name' => request('character'),
            'description' => request('introduction'),
            'prompt' => request('prompt'),
            'category' => request('category'),
            'group' => request('group')
        ]);

        if (request()->has('logo')) {
        
            try {
                request()->validate([
                    'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:10048'
                ]);
                
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'PHP FileInfo is disabled: ' . $e->getMessage());
            }
            
            $image = request()->file('logo');

            $name = Str::random(5);

          
            $filePath = '/chats/' . $name . '.' . $image->getClientOriginalExtension();
            
            $this->uploadImage($image, 'chats/', 'public', $name);
            
            $chat->logo = $filePath;
            $chat->save();
        }

        toastr()->success(__('Chat Bot has been successfully updated'));
        return redirect()->route('admin.davinci.chats');     
    }


    /**
     * Enable the specified chat.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function chatActivate(Request $request)
    {
        if ($request->ajax()) {

            $voice = Chat::where('id', request('id'))->firstOrFail();  

            if ($voice->status == true) {
                return  response()->json('active');
            }

            $voice->update(['status' => true]);

            return  response()->json('success');
        }
    }


    /**
     * Disable the specified chat.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function chatDeactivate(Request $request)
    {
        if ($request->ajax()) {

            $voice = Chat::where('id', request('id'))->firstOrFail();  

            if ($voice->status == false) {
                return  response()->json('deactive');
            }

            $voice->update(['status' => false]);

            return  response()->json('success');
        }    
    }


    /**
     * Upload voice avatar image
     */
    public function uploadImage(UploadedFile $file, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $image = $file->storeAs($folder, $name .'.'. $file->getClientOriginalExtension(), $disk);

        return $image;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function category(Request $request)
    {
        if ($request->ajax()) {
            $data = ChatCategory::orderBy('name', 'asc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    $actionBtn = '<div>      
                                    <a class="editButton" id="' . $row["id"] . '" href="#"><i class="fa fa-edit table-action-buttons view-action-button" title="'. __('Change Category Name') .'"></i></a>                    
                                    <a class="deleteButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="'. __('Delete Category') .'"></i></a> 
                                </div>';     
                    return $actionBtn;
                })
                ->addColumn('updated-on', function($row){
                    $created_on = '<span class="font-weight-bold">'.date_format($row["updated_at"], 'd/m/Y').'</span><br><span>'.date_format($row["updated_at"], 'H:i A').'</span>';
                    return $created_on;
                })
                ->addColumn('custom-name', function($row){
                    $user = '<span class="font-weight-bold">'. ucfirst(__($row['name'])) .'</span>';
                    return $user;
                })  
                ->addColumn('custom-type', function($row){
                    $color = ($row['type'] == 'original') ? 'category-blog' : 'category-main';
                    $user = '<span class="cell-box '.$color.'">'. ucfirst($row['type']) .'</span>';
                    return $user;
                })
                ->rawColumns(['actions', 'updated-on', 'custom-name', 'custom-type'])
                ->make(true);
                    
        }

        return view('admin.davinci.chats.category');
    }


    /**
     * Update the name.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {   
        if ($request->ajax()) {

            $template = ChatCategory::where('id', request('id'))->firstOrFail();
            
            $template->update(['name' => request('name')]);
            return  response()->json('success');
        } 
    }


    /**
     * Create category
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createCategory(Request $request)
    {   
        if ($request->ajax()) {

            $code = strtolower($request->name);

            $template = new ChatCategory([
                'name' => $request->name,
                'code' => $code,
                'type' => 'custom',
            ]); 
            
            $template->save();  
            
            toastr()->success(__('New category was successfully created'));
            return  response()->json('success');
        } 
    }

    /**
     * Delete category
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {   
        if ($request->ajax()) {

            $name = ChatCategory::find(request('id'));

            if($name) {

                $name->delete();

                return response()->json('success');

            } else{
                return response()->json('error');
            } 
        } 
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function prompt(Request $request)
    {
        if ($request->ajax()) {
            $data = ChatPrompt::orderBy('group', 'asc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    $actionBtn = '<div>      
                                    <a class="editButton" href="'. route("admin.davinci.chat.prompt.edit", $row["id"] ). '"><i class="fa fa-edit table-action-buttons view-action-button" title="'. __('Edit Prompt') .'"></i></a>     
                                    <a class="activateButton" id="' . $row["id"] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="'. __('Activate Prompt') .'"></i></a>
                                    <a class="deactivateButton" id="' . $row["id"] . '" href="#"><i class="fa fa-close table-action-buttons delete-action-button" title="'. __('Deactivate Prompt') .'"></i></a>                
                                    <a class="deleteButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="'. __('Delete Prompt') .'"></i></a> 
                                </div>';     
                    return $actionBtn;
                })
                ->addColumn('updated-on', function($row){
                    $created_on = '<span class="font-weight-bold">'.date_format($row["updated_at"], 'd/m/Y').'</span><br><span>'.date_format($row["updated_at"], 'H:i A').'</span>';
                    return $created_on;
                })
                ->addColumn('custom-group', function($row){
                    $user = '<span class="font-weight-bold">'. ucfirst(__($row['group'])) .'</span>';
                    return $user;
                })  
                ->addColumn('custom-status', function($row){
                    $status = ($row['status']) ? __('Active') : __('Deactive');
                    $custom_voice = '<span class="cell-box status-'.strtolower($status).'">'. $status.'</span>';
                    return $custom_voice;
                })
                ->rawColumns(['actions', 'updated-on', 'custom-status', 'custom-group'])
                ->make(true);
                    
        }

        return view('admin.davinci.chats.prompt');
    }

    /**
     * Create new chat prompt
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function promptCreate()
    {   
        $groups = ChatPrompt::where('status', true)->groupBy('group')->pluck('group'); 

        return view('admin.davinci.chats.prompt-create', compact('groups'));     
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function promptStore(Request $request)
    {   
        $group = (!is_null(request('custom'))) ? request('custom') : request('group');

        $prompt = new ChatPrompt([
            'status' => true,
            'title' => request('title'),
            'prompt' => request('prompt'),
            'group' => $group
        ]); 

        $prompt->save();

        toastr()->success(__('Chat prompt has been successfully created'));
        return redirect()->route('admin.davinci.chat.prompt');     
    }


    /**
     * Edit prompt
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function promptEdit($id)
    {   
        $prompt = ChatPrompt::where('id', $id)->first();
        $groups = ChatPrompt::where('status', true)->groupBy('group')->pluck('group'); 

        return view('admin.davinci.chats.prompt-edit', compact('groups', 'prompt'));     
    }
    

    /**
     * Update the specified prompt
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function promptUpdate($id)
    {   
        $group = (!is_null(request('custom'))) ? request('custom') : request('group');

        $prompt = ChatPrompt::where('id', $id)->first();

        $prompt->update([
            'title' => request('title'),
            'prompt' => request('prompt'),
            'group' => $group
        ]);

        toastr()->success(__('Chat prompt has been successfully updated'));
        return redirect()->route('admin.davinci.chat.prompt');     
    }


    /**
     * Enable the specified prompt.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function promptActivate(Request $request)
    {
        if ($request->ajax()) {

            $prompt = ChatPrompt::where('id', request('id'))->firstOrFail();  

            if ($prompt->status == true) {
                return  response()->json('active');
            }

            $prompt->update(['status' => true]);

            return  response()->json('success');
        }
    }


    /**
     * Disable the specified prompt.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function promptDeactivate(Request $request)
    {
        if ($request->ajax()) {

            $prompt = ChatPrompt::where('id', request('id'))->firstOrFail();  

            if ($prompt->status == false) {
                return  response()->json('deactive');
            }

            $prompt->update(['status' => false]);

            return  response()->json('success');
        }    
    }


    /**
     * Delete prompt
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function promptDelete(Request $request)
    {   
        if ($request->ajax()) {

            $name = ChatPrompt::find(request('id'));

            if($name) {

                $name->delete();

                return response()->json('success');

            } else{
                return response()->json('error');
            } 
        } 
    }
}
