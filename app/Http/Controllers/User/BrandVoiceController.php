<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BrandVoice;
use App\Models\Category;
use Yajra\DataTables\DataTables;

class BrandVoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BrandVoice::where('user_id', auth()->user()->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    $actionBtn = '<div>      
                                    <a href="'. route("user.brand.edit", $row["id"] ). '"><i class="fa fa-edit table-action-buttons view-action-button" title="'. __('Edit Brand') .'"></i></a>   
                                    <a class="deleteTemplate" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="'. __('Delete Brand') .'"></i></a> 
                                </div>';
                    
                    return $actionBtn;
                })
                ->addColumn('custom-name', function($row){
                    $user = '<span class="font-weight-bold">'. $row['name'] .'</span>';
                    return $user;
                }) 
                ->rawColumns(['actions', 'custom-name'])
                ->make(true);
                    
        }

        return view('user.brand.index');
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
        
        $fields = array();
        $total = 0;

        foreach ($request->names as $key => $value) {
            if (!is_null($value)) {
                $fields[$key]['name'] = $value;
                $fields[$key]['description'] = $request->descriptions[$key];
                $fields[$key]['type'] = $request->types[$key];  
            }

            $total++;
        }

        $brand = new BrandVoice([
            'user_id' => auth()->user()->id,
            'description' => $request->description,
            'name' => $request->name,
            'website' => $request->website,
            'audience' => $request->audience,
            'tagline' => $request->tagline,
            'industry' => $request->industry,
            'tone' => $request->tone,
            'products' => $fields,
            'total' => $total,
        ]); 
        
        $brand->save();            

        toastr()->success(__('Brand voice was successfully created'));
        return redirect()->back();       
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.brand.create');   
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BrandVoice $id)
    {
        if ($id->user_id == auth()->user()->id) {
            return view('user.brand.edit', compact('id'));
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
    public function update(Request $request, BrandVoice $id)
    {   
        request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]);     
        
        $fields = array();
        $total = 0;

        foreach ($request->names as $key => $value) {
            if (!is_null($value)) {
                $fields[$key]['name'] = $value;
                $fields[$key]['description'] = $request->descriptions[$key];
                $fields[$key]['type'] = $request->types[$key];  
            }

            $total++;
        }

        if ($id->user_id == auth()->user()->id) {

            $id->update([
                'user_id' => auth()->user()->id,
                'description' => $request->description,
                'name' => $request->name,
                'website' => $request->website,
                'audience' => $request->audience,
                'tagline' => $request->tagline,
                'industry' => $request->industry,
                'tone' => $request->tone,
                'products' => $fields,
                'total' => $total,
            ]); 

            toastr()->success(__('Brand voice was successfully updated'));
            return redirect()->route('user.templates.custom');
        } else {
            toastr()->warning(__('Access denied'));
            return redirect()->back();   
        }

    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {

            $result = BrandVoice::where('id', request('id'))->firstOrFail();  

            if ($result->user_id == auth()->user()->id){

                $result->delete();

                return response()->json('success');    
    
            } else{
                return response()->json('error');
            } 
        }              
    }





}
