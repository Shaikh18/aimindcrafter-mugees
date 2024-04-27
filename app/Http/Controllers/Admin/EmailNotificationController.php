<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use App\Notifications\GeneralNotification;
use App\Models\User;
use App\Models\Email;
use DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmails;
use App\Jobs\SendEmailsJob;
use Exception;

class EmailNotificationController extends Controller
{
    /**
     * Display all general notifications
     * 
     * @return \Illuminate\Http\Response
     */
    public function templates(Request $request)
    {
        if ($request->ajax()) {
            $data = Email::where('type', 'system')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>
                                            <a href="'. route("admin.email.templates.edit", $row["id"] ). '"><i class="fa-solid fa-message-pen table-action-buttons view-action-button" title="'. __('Edit Email') .'"></i></a>
                                        </div>';
                        return $actionBtn;
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
                    
        }

        return view('admin.email.templates.index');
    }


    /**
     * Display all general notifications
     * 
     * @return \Illuminate\Http\Response
     */
    public function newsletter(Request $request)
    {
        if ($request->ajax()) {
            $data = Email::where('type', 'custom')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>
                                            <a href="'. route("admin.email.newsletter.view", $row["id"] ). '"><i class="fa-solid fa-envelope-circle-check table-action-buttons view-action-button" title="'. __('Send Emails') .'"></i></a>
                                            <a href="'. route("admin.email.newsletter.edit", $row["id"] ). '"><i class="fa-solid fa-message-pen table-action-buttons edit-action-button" title="'. __('Edit Email') .'"></i></a>
                                            <a class="deleteNotificationButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="'. __('Delete Email') .'"></i></a> 
                                        </div>';
                        return $actionBtn;
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
                    
        }

        return view('admin.email.newsletter.index');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editTemplate(Email $id)
    {
        return view('admin.email.templates.edit', compact('id'));
    }


    /**
     * Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateTemplate(Email $id)
    {
        request()->validate([
            'subject' => 'required',
            'message' => 'required',
            'footer' => 'nullable',
        ]);

        $id->update([
            'subject' => request('subject'),
            'message' => request('message'),
            'footer' => request('footer'),
        ]); 

        toastr()->success(__('Email template was successfully updated'));
        return redirect()->back();
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.email.newsletter.create');
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
            'subject' => 'required',
            'message' => 'required',
            'footer' => 'nullable',
        ]);

        $email = new Email([
            'name' => request('name'),
            'subject' => request('subject'),
            'message' => request('message'),
            'footer' => request('footer'),
            'type' => 'custom',
        ]);

        $email->save();


        toastr()->success(__('Email was successfully created'));
        return redirect()->back();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(Email $id)
    {
        return view('admin.email.newsletter.send', compact('id'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {   
        if ($request->ajax()) {  

            if ($request->type == 'all') {
                try {

                    $all = User::where('status', 'active')->get()->pluck('email');
                    SendEmailsJob::dispatch($all, $request->email_id);

                } catch (Exception $e) {
                    \Log::info('SMTP settings are not setup to send emails');
                }

                $data['status'] = 'success';
                $data['message'] = __('Emails are being sent successfully');

                return $data;

            } else {
                if (is_null($request->email)) {
                    $data['status'] = 'error';
                    $data['message'] = __('Email field must be filled if sending to one user');

                    return $data;

                } else {
                    try {
                        Mail::to($request->email)->send(new SendEmails($request->email_id));
                    } catch (Exception $e) {
                        \Log::info('SMTP settings are not setup to send emails');
                    }

                    $data['status'] = 'success';
                    $data['message'] = __('Email has been sent successfully');

                    return $data;
                }
            }
        }

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editEmail(Email $id)
    {
        return view('admin.email.newsletter.edit', compact('id'));
    }


    /**
     * Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(Email $id)
    {
        request()->validate([
            'name' => 'name',
            'subject' => 'required',
            'message' => 'required',
            'footer' => 'nullable',
        ]);

        $id->update([
            'name' => request('name'),
            'subject' => request('subject'),
            'message' => request('message'),
            'footer' => request('footer'),
        ]); 

        toastr()->success(__('Email was successfully updated'));
        return redirect()->back();
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

            $email = Email::where('id', request('id'))->first();

            if($email) {

                $email->delete();

                return response()->json('success');

            } else{
                return response()->json('error');
            } 
        } 
    }

    

}
