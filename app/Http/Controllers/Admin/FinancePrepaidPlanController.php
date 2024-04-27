<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrepaidPlan;
use DataTables;

class FinancePrepaidPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        if ($request->ajax()) {
            $data = PrepaidPlan::all()->sortByDesc("created_at");          
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>                                            
                                        <a href="'. route("admin.finance.prepaid.show", $row["id"] ). '"><i class="fa-solid fa-file-invoice-dollar table-action-buttons edit-action-button" title="'. __('View Plan') .'"></i></a>
                                        <a href="'. route("admin.finance.prepaid.edit", $row["id"] ). '"><i class="fa-solid fa-file-pen table-action-buttons view-action-button" title="'. __('Update Plan') .'"></i></a>
                                        <a class="deletePlanButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="'. __('Delete Plan') .'"></i></a>
                                    </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["created_at"], 'd/m/Y').'</span><br><span>'.date_format($row["created_at"], 'H:i A').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-status', function($row){
                        $custom_priority = '<span class="cell-box plan-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $custom_priority;
                    })
                    ->addColumn('custom-model', function($row){
                        switch ($row['model']) {
                            case 'text-davinci-003': 
                                $model = 'GPT 3 Davinci';
                                break;
                            case 'gpt-3.5-turbo': 
                                $model = 'GPT 3.5 Turbo';
                                break;
                            case 'gpt-3.5-turbo-16k': 
                                $model = 'GPT 3.5 Turbo (16K)';
                                break;
                            case 'gpt-4': 
                                $model = 'GPT 4 (8K)';
                                break;
                            case 'gpt-4-32k': 
                                $model = 'GPT 4 (32K)';
                                break;
                            case 'gpt-4-1106-preview': 
                                $model = 'GPT 4 Turbo';
                                break;
                            case 'gpt-4-vision-preview': 
                                $model = 'GPT 4 Turbo (Vision)';
                                break;
                            default:
                                $model = 'Generic';
                                break;
                        }
                        $custom_storage = '<span>'.$model.'</span>';
                        return $custom_storage;
                    })
                    ->addColumn('custom-credits', function($row){
                        $words = ($row["words"] == -1) ? 'Unlimited' : number_format($row["words"]);
                        $dalle_images = ($row["dalle_images"] == -1) ? 'Unlimited' : number_format($row["dalle_images"]);
                        $sd_images = ($row["sd_images"] == -1) ? 'Unlimited' : number_format($row["sd_images"]);
                        $characters = ($row["characters"] == -1) ? 'Unlimited' : number_format($row["characters"]);
                        $minutes = ($row["minutes"] == -1) ? 'Unlimited' : number_format($row["minutes"]);
                        $custom_credits = '<span>'. $words .' / '. $dalle_images . ' / '. $sd_images . ' / '. $characters . ' / ' . $minutes .'</span>';
                        return $custom_credits;
                    })
                    ->addColumn('custom-name', function($row){
                        $custom_name = '<span class="font-weight-bold">'.$row["plan_name"].'</span><br><span>' . $row["price"] . ' ' . $row["currency"].'</span>';
                        return $custom_name;
                    })
                    ->addColumn('custom-featured', function($row){
                        $icon = ($row['featured'] == true) ? '<i class="fa-solid fa-circle-check text-success fs-16"></i>' : '<i class="fa-solid fa-circle-xmark fs-16"></i>';
                        $custom_featured = '<span class="font-weight-bold">'.$icon.'</span>';
                        return $custom_featured;
                    })
                    ->addColumn('custom-frequency', function($row){
                        $custom_status = '<span class="cell-box payment-prepaid">'.ucfirst($row["pricing_plan"]).'</span>';
                        return $custom_status;
                    })
                    ->rawColumns(['actions', 'custom-status', 'created-on', 'custom-model', 'custom-name', 'custom-featured', 'custom-frequency', 'custom-credits'])
                    ->make(true);
                    
        }

        return view('admin.finance.plans.prepaid.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.finance.plans.prepaid.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate([
            'plan-status' => 'required',
            'plan-name' => 'required',
            'price' => 'required|numeric',
            'currency' => 'required',
            'words' => 'required|integer|min:0',
            'dalle_images' => 'required|integer|min:0',
            'sd_images' => 'required|integer|min:0',
            'characters' => 'required|integer|min:0',
            'minutes' => 'required|integer|min:0',
        ]);
        
        $frequency = 'prepaid';

        $plan = new PrepaidPlan([
            'status' => request('plan-status'),
            'plan_name' => request('plan-name'),
            'price' => request('price'),
            'currency' => request('currency'),
            'pricing_plan' => $frequency,
            'featured' => request('featured'),
            'words' => request('words'),
            'dalle_images' => request('dalle_images'),
            'sd_images' => request('sd_images'),
            'characters' => request('characters'),
            'minutes' => request('minutes'),
        ]); 
            
        $plan->save();            

        toastr()->success(__('New prepaid plan has been created successfully'));
        return redirect()->route('admin.finance.prepaid');        
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PrepaidPlan $id)
    {
        return view('admin.finance.plans.prepaid.show', compact('id'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PrepaidPlan $id)
    {
        return view('admin.finance.plans.prepaid.edit', compact('id'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PrepaidPlan $id)
    {        
        request()->validate([
            'plan-status' => 'required',
            'plan-name' => 'required',
            'price' => 'required|numeric',
            'currency' => 'required',
            'words' => 'required|integer|min:0',
            'dalle_images' => 'required|integer|min:0',
            'sd_images' => 'required|integer|min:0',
            'characters' => 'required|integer|min:0',
            'minutes' => 'required|integer|min:0',
        ]);

        $id->update([
            'status' => request('plan-status'),
            'plan_name' => request('plan-name'),
            'price' => request('price'),
            'currency' => request('currency'),
            'words' => request('words'),
            'dalle_images' => request('dalle_images'),
            'sd_images' => request('sd_images'),
            'characters' => request('characters'),
            'minutes' => request('minutes'),
            'featured' => request('featured'),
        ]); 

        toastr()->success(__('Selected prepaid plan has been updated successfully'));
        return redirect()->route('admin.finance.prepaid');

    }


    public function delete(Request $request)
    {   
        if ($request->ajax()) {

            $plan = PrepaidPlan::find(request('id'));

            if($plan) {

                $plan->delete();

                return response()->json('success');

            } else{
                return response()->json('error');
            } 
        } 
    }
}
