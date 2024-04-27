<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\CustomTemplate;
use App\Models\Category;
use Yajra\DataTables\DataTables;

class UserCustomTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CustomTemplate::where('user_id', auth()->user()->id)->orderBy('group', 'asc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    $actionBtn = '<div>      
                                    <a href="'. route("user.templates.custom.show", $row["id"] ). '"><i class="fa fa-edit table-action-buttons view-action-button" title="'. __('Edit Template') .'"></i></a>      
                                    <a class="activateButton" id="' . $row["id"] . '" type="' . $row['type'] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="'. __('Activate Template') .'"></i></a>
                                    <a class="deactivateButton" id="' . $row["id"] . '" type="' . $row['type'] . '" href="#"><i class="fa fa-close table-action-buttons delete-action-button" title="'. __('Deactivate Template') .'"></i></a>  
                                    <a class="deleteTemplate" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="'. __('Delete Template') .'"></i></a> 
                                </div>';
                    
                    return $actionBtn;
                })
                ->addColumn('updated-on', function($row){
                    $created_on = '<span class="font-weight-bold">'.date_format($row["updated_at"], 'd/m/Y').'</span><br><span>'.date_format($row["updated_at"], 'H:i A').'</span>';
                    return $created_on;
                })
                ->addColumn('custom-name', function($row){
                    $user = '<span class="font-weight-bold">'. $row['name'] .'</span></div>';
                    return $user;
                }) 
                ->addColumn('custom-status', function($row){
                    $status = ($row['status']) ? __('Active') : __('Deactive');
                    $custom_voice = '<span class="cell-box status-'.strtolower($status).'">'. $status.'</span>';
                    return $custom_voice;
                })
                ->rawColumns(['actions', 'updated-on', 'custom-name', 'custom-status'])
                ->make(true);
                    
        }

        $categories = Category::orderBy('name', 'asc')->get();

        return view('user.templates.custom.index', compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]);     
        
        $template_code = strtoupper(Str::random(5));
        $status = (isset($request->activate)) ? true : false;
        $icon = ($request->category == 'text') ? str_replace('"></i>', ' main-icon"></i>', $request->icon) : str_replace('"></i>', ' ' . $request->category . '-icon"></i>', $request->icon);

        $fields = array();

        foreach ($request->names as $key => $value) {
            if (!is_null($value)) {
                if($request->input_field[$key] == 'input' || $request->input_field[$key] == 'textarea') {
                    $fields[$key]['name'] = $value;
                    $fields[$key]['placeholder'] = $request->placeholders[$key];
                    $fields[$key]['input'] = $request->input_field[$key];
                    $fields[$key]['code'] = $request->code[$key];
                    $fields[$key]['status'] = $request->status_field[$key];
                } else {
                    $options = explode(',', $request->placeholders[$key]);
                    $fields[$key]['name'] = $value;
                    $fields[$key]['placeholder'] = $options;
                    $fields[$key]['input'] = $request->input_field[$key];
                    $fields[$key]['code'] = $request->code[$key];
                    $fields[$key]['status'] = $request->status_field[$key];
                }
                
            }
        }

        $template = new CustomTemplate([
            'user_id' => auth()->user()->id,
            'description' => $request->description,
            'status' => $status,
            'professional' => false,
            'template_code' => $template_code,
            'name' => $request->name,
            'icon' => $icon,
            'group' => $request->category,
            'slug' => 'custom-template',
            'prompt' => $request->prompt,
            'tone' => true,
            'fields' => $fields,
            'package' => 'all',
            'type' => 'private'
        ]); 
        
        $template->save();            

        toastr()->success(__('Custom Template was successfully created'));
        return redirect()->back();       
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CustomTemplate $id)
    {
        if ($id->user_id == auth()->user()->id) {
            $categories = Category::orderBy('name', 'asc')->get();

            return view('user.templates.custom.edit', compact('id', 'categories'));
        } else {
            toastr()->warning(__('Access denied'));
            return redirect()->back();     
        }
        
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomTemplate $id)
    {        
        request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]); 

        $status = (isset($request->activate)) ? true : false;
        $icon = ($request->category == 'text') ? str_replace('"></i>', ' main-icon"></i>', $request->icon) : str_replace('"></i>', ' ' . $request->category . '-icon"></i>', $request->icon);

        $fields = array();

        foreach ($request->names as $key => $value) {
            if (!is_null($value)) {
                if($request->input_field[$key] == 'input' || $request->input_field[$key] == 'textarea') {
                    $fields[$key]['name'] = $value;
                    $fields[$key]['placeholder'] = $request->placeholders[$key];
                    $fields[$key]['input'] = $request->input_field[$key];
                    $fields[$key]['code'] = $request->code[$key];
                    $fields[$key]['status'] = $request->status_field[$key];
                } else {
                    $options = explode(',', $request->placeholders[$key]);
                    $fields[$key]['name'] = $value;
                    $fields[$key]['placeholder'] = $options;
                    $fields[$key]['input'] = $request->input_field[$key];
                    $fields[$key]['code'] = $request->code[$key];
                    $fields[$key]['status'] = $request->status_field[$key];
                }
                
            }
        }

        if ($id->user_id == auth()->user()->id) {

            $id->update([
                'description' => $request->description,
                'status' => $status,
                'name' => $request->name,
                'icon' => $icon,
                'group' => $request->category,
                'prompt' => $request->prompt,
                'fields' => $fields,
                'package' => $request->package,
            ]); 

            toastr()->success(__('Custom Template was successfully updated'));
            return redirect()->route('user.templates.custom');
        } else {
            toastr()->warning(__('Access denied'));
            return redirect()->back();   
        }

    }


    /**
     * Update the description.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function descriptionUpdate(Request $request)
    {   
        if ($request->ajax()) {

            $template = CustomTemplate::where('id', request('id'))->firstOrFail();

            if ($template->user_id == auth()->user()->id) {            
                $template->update(['description' => request('name')]);
                return  response()->json('success');
            } else {
                return response()->json('error');
            }
        } 
    }


    /**
     * Activate template
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function templateActivate(Request $request)
    {
        if ($request->ajax()) {

            $template = CustomTemplate::where('id', request('id'))->firstOrFail();

            if ($template->user_id == auth()->user()->id) {
                if ($template->status == true) {
                    return  response()->json(true);
                }

                $template->update(['status' => true]);

                return  response()->json('success');

            } else {
                return response()->json('error');
            }
        }
    }


    /**
     * Deactivate template.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function templateDeactivate(Request $request)
    {
        if ($request->ajax()) {

            $template = CustomTemplate::where('id', request('id'))->firstOrFail();

            if ($template->user_id == auth()->user()->id) {
                if ($template->status == false) {
                    return  response()->json(false);
                }
    
                $template->update(['status' => false]);
    
                return  response()->json('success');
            } else {
                return response()->json('error');
            }
            
        }
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteTemplate(Request $request)
    {
        if ($request->ajax()) {

            $result = CustomTemplate::where('id', request('id'))->firstOrFail();  

            if ($result->user_id == auth()->user()->id){

                $result->delete();

                return response()->json('success');    
    
            } else{
                return response()->json('error');
            } 
        }              
    }





}
