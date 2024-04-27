<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Services\PaymentPlatformResolverService;
use App\Services\Statistics\UserRegistrationYearlyService;
use App\Services\Statistics\UserRegistrationMonthlyService;
use App\Services\Statistics\DavinciUsageService;
use App\Models\SubscriptionPlan;
use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Cache;


class AdminUserController extends Controller
{
    
    protected $paymentPlatformResolver;

    
    public function __construct(PaymentPlatformResolverService $paymentPlatformResolver)
    {
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    /**
     * Display user management dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $registration_yearly = new UserRegistrationYearlyService($year);
        $registration_monthly = new UserRegistrationMonthlyService($month);

        $user_data_year = [
            'total_free_tier' => $registration_yearly->getTotalFreeRegistrations(),
            'total_users' => $registration_yearly->getTotalUsers(),
            'top_countries' => $this->getTopCountries(),
        ];
        
        $chart_data['free_registration_yearly'] = json_encode($registration_yearly->getFreeRegistrations());
        $chart_data['current_registered_users'] = json_encode($registration_monthly->getRegisteredUsers());
        $chart_data['user_countries'] = json_encode($this->getAllCountries());


        $cachedUsers = json_decode(Cache::get('isOnline', []), true);
        $users_online = count($cachedUsers);

        $users_today = User::whereNotNull('last_seen')->whereDate('last_seen', Carbon::today())->count();

        return view('admin.users.dashboard.index', compact('chart_data', 'user_data_year', 'users_online', 'users_today'));
    }


    /**
     * Display all users
     *
     * @return \Illuminate\Http\Response
     */
    public function listUsers(Request $request)
    {  
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn ='<div>
                                        <a href="'. route("admin.user.show", $row["id"] ). '"><i class="fa-solid fa-clipboard-user table-action-buttons view-action-button" title="'. __('View User') .'"></i></a>
                                        <a href="'. route("admin.user.edit", $row["id"] ). '"><i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="'. __('Edit User Group') .'"></i></a>
                                        <a class="deleteUserButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="'. __('Delete User') .'"></i></a>
                                    </div>';
                        return $actionBtn;
                    })
                    ->addColumn('user', function($row){
                        if ($row['profile_photo_path']) {
                            $path = asset($row['profile_photo_path']);
                            $user = '<div class="d-flex">
                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . $path . '"></div>
                                    <div class="widget-user-name"><span class="font-weight-bold">'. $row['name'] .'</span> <br> <span class="text-muted">'.$row["email"].'</span></div>
                                </div>';
                        } else {
                            $path = URL::asset('img/users/avatar.png');
                            $user = '<div class="d-flex">
                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" class="rounded-circle" src="' . $path . '"></div>
                                    <div class="widget-user-name"><span class="font-weight-bold">'. $row['name'] .'</span> <br> <span class="text-muted">'.$row["email"].'</span></div>
                                </div>';
                        }
                        
                        return $user;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["created_at"], 'd/m/Y').'</span><br><span>'.date_format($row["created_at"], 'H:i A').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-status', function($row){
                        if ($row["status"]) {
                            $custom_status = '<span class="cell-box user-'.$row["status"].'">'.ucfirst($row["status"]).'</span>';
                        } else {
                            $custom_status = '';
                        }
                        
                        return $custom_status;
                    })
                    ->addColumn('custom-group', function($row){
                        if ($row["group"]) {
                            $custom_group = '<span class="cell-box user-group-'.$row["group"].'">'.ucfirst($row["group"]).'</span>';
                        } else {
                            $custom_group = '';
                        }
                        
                        return $custom_group;
                    })
                    ->addColumn('custom-credits', function($row){
                        $words = ($row["available_words"] == -1) ? 'Unlimited' : number_format($row["available_words"] + $row['available_words_prepaid']);
                        $dalle_images = ($row["available_dalle_images"] == -1) ? 'Unlimited' : number_format($row["available_dalle_images"] + $row["available_dalle_images_prepaid"]);
                        $sd_images = ($row["available_sd_images"] == -1) ? 'Unlimited' : number_format($row["available_sd_images"] + $row["available_sd_images_prepaid"]);
                        $characters = ($row["available_chars"] == -1) ? 'Unlimited' : number_format($row["available_chars"] + $row['available_chars_prepaid']);
                        $minutes = ($row["available_minutes"] == -1) ? 'Unlimited' : number_format($row["available_minutes"] + $row['available_minutes_prepaid']);
                        $custom_credits = '<span class="font-weight-bold">'. $words .' / '. $characters . ' / ' . $minutes . ' / ' . $dalle_images . ' / '. $sd_images . '</span>';
                        return $custom_credits;
                    })
                    ->rawColumns(['actions', 'custom-status', 'custom-group', 'created-on', 'user', 'custom-credits'])
                    ->make(true);                    
        }

        return view('admin.users.list.index');
    }


    /**
     * Display user activity
     *
     * @return \Illuminate\Http\Response
     */
    public function activity(Request $request)
    {
        $result = DB::table('sessions')
                ->join('users', 'sessions.user_id', '=', 'users.id')
                ->whereNotNull('sessions.user_id')
                ->select('sessions.ip_address', 'sessions.user_agent', 'sessions.last_activity', 'users.email', 'users.group')
                ->orderBy('sessions.last_activity', 'desc')
                ->get()->toArray();

        return view('admin.users.activity.index', compact('result'));
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.list.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::min(8)],
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country' => $request->country,
            'job_role' => $request->job_role,
            'phone_number' => $request->phone_number,
            'company' => $request->company,
            'website' => $request->website,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
        ]);       
        
        $user->syncRoles($request->role);
        $user->status = 'active';
        $user->group = $request->role;
        $user->email_verified_at = now();
        $user->referral_id = strtoupper(Str::random(15));
        $user->available_words = config('settings.free_tier_words');
        $user->available_dalle_images = config('settings.free_tier_dalle_images');
        $user->available_sd_images = config('settings.free_tier_sd_images');
        $user->available_chars_prepaid = config('settings.voiceover_welcome_chars');
        $user->available_minutes_prepaid = config('settings.whisper_welcome_minutes');
        $user->default_voiceover_language = config('settings.voiceover_default_language');
        $user->default_voiceover_voice = config('settings.voiceover_default_voice');
        $user->save();        

        toastr()->success(__('Congratulation! New user has been created'));
        return redirect()->back();
    }


    /**
     * Display the details of selected user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {   
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $davinci = new DavinciUsageService($month, $year);

        $data = [
            'words' => $davinci->userTotalWordsGenerated($user->id),
            'images' => $davinci->userTotalImagesGenerated($user->id),
            'characters' => $davinci->userTotalCharactersSynthesized($user->id),
            'minutes' => $davinci->userTotalMinutesTranscribed($user->id),
        ];
        
        $chart_data['word_usage'] = json_encode($davinci->userMonthlyWordsChart($user->id));
        
        $subscription = Subscriber::where('status', 'Active')->where('user_id', $user->id)->first();
        if ($subscription) {
             if(Carbon::parse($subscription->active_until)->isPast()) {
                 $subscription = false;
             } 
        } else {
            $subscription = false;
        }

        $user_subscription = ($subscription) ? SubscriptionPlan::where('id', $user->plan_id)->first() : '';
        
        $progress = [
            'words' => ($user->total_words > 0) ? (($user->available_words / $user->total_words) * 100) : 0,
        ];

        return view('admin.users.list.show', compact('user', 'data', 'chart_data', 'user_subscription', 'progress', 'subscription'));
    }


    /**
     * Show the form for editing the specified user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.list.edit', compact('user'));
    }


    /**
     * Show users credit capacity
     */
    public function credit(User $user)
    {
        return view('admin.users.list.increase', compact('user'));
    }


    /**
     * Show users subscription
     */
    public function subscription(User $user)
    {
        if (!is_null($user->plan_id)) {
            $plan = SubscriptionPlan::where('id', $user->plan_id)->first();
            $plan = $plan->plan_name;
        } else {
            $plan = __('None');
        }

        $plans = SubscriptionPlan::orderBy('payment_frequency', 'DESC')->get();

        return view('admin.users.list.subscription', compact('user', 'plan', 'plans'));
    }


    /**
     * Change user credit capacity
     */
    public function increase(Request $request, User $user)
    {
        $request->validate([
            'words' => 'nullable|integer',
            'chars' => 'nullable|integer',
        ]);

        $user->available_words = request('words');
        $user->available_dalle_images =  request('dalle-images');
        $user->available_sd_images =  request('sd-images');
        $user->available_chars = request('chars');
        $user->available_minutes = request('minutes');
        $user->available_words_prepaid = request('words_prepaid');
        $user->available_dalle_images_prepaid =  request('dalle_images_prepaid');
        $user->available_sd_images_prepaid =  request('sd_images_prepaid');
        $user->available_chars_prepaid = request('chars_prepaid');
        $user->available_minutes_prepaid = request('minutes_prepaid');
        $user->save();

        toastr()->success(__('Credits have been updated successfully'));
        return redirect()->back();
    }


    /**
     * Change user subscription
     */
    public function assignSubscription(Request $request, User $user)
    {
        $plan = SubscriptionPlan::where('id', $request->plan)->first();

        if (!is_null($user->plan_id)) {
            if ($user->plan_id == $request->plan) {
                toastr()->warning(__('User has already this plan assigned, select a different plan'));
                return redirect()->back();
            } else {
                $subscriber = Subscriber::where('status', 'Active')->where('user_id', $user->id)->first();

                if ($subscriber) {
                    $this->stopSubscription($subscriber->id);
                }
            }
        }


        $subscription_id = strtoupper(Str::random(10));

        switch ($plan->payment_frequency) {
            case 'monthly':
                $days = 30;
                break;
            case 'yearly':
                $days = 365;
                break;
            case 'lifetime':
                $days = 18250;
                break;
        }

        Subscriber::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'Active',
            'created_at' => now(),
            'gateway' => 'Manual',
            'frequency' => $plan->payment_frequency,
            'plan_name' => $plan->plan_name,
            'words' => $plan->words,
            'dalle_images' => $plan->dalle_images,
            'sd_images' => $plan->sd_images,
            'characters' => $plan->characters,
            'minutes' => $plan->minutes,
            'subscription_id' => $subscription_id,
            'active_until' => Carbon::now()->addDays($days),
        ]);  
        

        $group = ($user->hasRole('admin')) ? 'admin' : 'subscriber';

        $user->syncRoles($group);    
        $user->group = $group;
        $user->plan_id = $plan->id;
        $user->total_words = $plan->words;
        $user->total_dalle_images = $plan->dalle_images;
        $user->total_sd_images = $plan->sd_images;
        $user->total_chars = $plan->characters;
        $user->total_minutes = $plan->minutes;
        $user->available_words = $plan->words;
        $user->available_dalle_images = $plan->dalle_images;
        $user->available_sd_images = $plan->sd_images;
        $user->available_chars = $plan->characters;
        $user->available_minutes = $plan->minutes;
        $user->member_limit = $plan->team_members;
        $user->save(); 

        toastr()->success(__('Subscription plan has been assigned successfully'));
        return redirect()->back();
    }


    /**
     * Update selected user data
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        $user->update(request()->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','string','email','max:255',Rule::unique('users')->ignore($user)],
            'job_role' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'phone_number' => 'nullable|max:20',
            'address' => 'nullable|string|max:255',            
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'string|max:255',
        ]));

        toastr()->success(__('User profile was successfully updated'));
        return redirect()->back();
    }

    /**
     * Change user group/status/password
     */
    public function change(Request $request, User $user)
    {        
        $request->validate([
            'password' => ['nullable', 'confirmed', Rules\Password::min(8)],
            'status' => 'required',
            'group' => 'required'
        ]);
        
        if ($user->group) {
            $user->removeRole($user->group);
        }		
        $user->assignRole($request->group);
        $user->status = $request->status;
        $user->group = $request->group;
        $user->google2fa_enabled = $request->twoFactor_status;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();   

        toastr()->success(__('User data was successfully updated'));
        return redirect()->back();
    }


    /**
     * Delete selected user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {

            $user = User::find(request('id'));

            if($user) {

                $user->delete();

                return response()->json('success');

            } else{
                return response()->json('error');
            } 
        }     
    }


    /**
     * Show list of all countries
     */
    public function getAllCountries()
    {        
        $countries = User::select(DB::raw("count(id) as data, country"))
                ->groupBy('country')
                ->orderBy('data')
                ->pluck('data', 'country');    
        
        return $countries;        
    }


    /**
     * Show top 30 countries
     */
    public function getTopCountries()
    {        
        $countries = User::select(DB::raw("count(id) as data, country"))
                ->groupBy('country')
                ->orderByDesc('data')
                ->pluck('data', 'country')
                ->take(30)
                ->toArray();    

        return $countries;        
    }


    /**
     * Cancel active subscription
     */
    public function stopSubscription($id)
    {   
            
        $id = Subscriber::where('id', $id)->first();

        if ($id->status == 'Cancelled') {
            $data['status'] = 200;
            $data['message'] = __('This subscription was already cancelled before');
            return $data;
        } elseif ($id->status == 'Suspended') {
            $data['status'] = 400;
            $data['message'] = __('Subscription has been suspended due to failed renewal payment');
            return $data;
        } elseif ($id->status == 'Expired') {
            $data['status'] = 400;
            $data['message'] = __('Subscription has been expired, please create a new one');
            return $data;
        }

        if ($id->frequency == 'lifetime') {
            $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())]);
            $user = User::where('id', $id->user_id)->firstOrFail();
            $user->plan_id = null;
            $user->group = 'user';
            $user->total_words = 0;
            $user->total_images = 0;
            $user->total_chars = 0;
            $user->total_minutes = 0;
            $user->member_limit = null;
            $user->save();

            $data['status'] = 200;
            $data['message'] = __('Subscription has been successfully cancelled');
            return $data;

        } else {

            switch ($id->gateway) {
                case 'PayPal':
                    $platformID = 1;
                    break;
                case 'Stripe':
                    $platformID = 2;
                    break;
                case 'BankTransfer':
                    $platformID = 3;
                    break;
                case 'Paystack':
                    $platformID = 4;
                    break;
                case 'Razorpay':
                    $platformID = 5;
                    break;
                case 'Mollie':
                    $platformID = 7;
                    break;
                case 'Flutterwave':
                    $platformID = 10;
                    break;
                case 'Yookassa':
                    $platformID = 11;
                    break;
                case 'Paddle':
                    $platformID = 12;
                    break;
                case 'Manual':
                case 'FREE':
                    $platformID = 99;
                    break;
                default:
                    $platformID = 1;
                    break;
            }
            

            if ($id->gateway == 'PayPal' || $id->gateway == 'Stripe' || $id->gateway == 'Paystack' || $id->gateway == 'Razorpay' || $id->gateway == 'Mollie' || $id->gateway == 'Flutterwave' || $id->gateway == 'Yookassa' || $id->gateway == 'Paddle') {
                $paymentPlatform = $this->paymentPlatformResolver->resolveService($platformID);

                $status = $paymentPlatform->stopSubscription($id->subscription_id);

                if ($platformID == 2) {
                    if ($status) {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())]);
                        $user = User::where('id', $id->user_id)->firstOrFail();
                        $user->plan_id = null;
                        $user->group = 'user';
                        $user->total_words = 0;
                        $user->total_images = 0;
                        $user->total_chars = 0;
                        $user->total_minutes = 0;
                        $user->member_limit = null;
                        $user->save();
                    }
                } elseif ($platformID == 4) {
                    if ($status->status) {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())]);
                        $user = User::where('id', $id->user_id)->firstOrFail();
                        $group = ($user->hasRole('admin'))? 'admin' : 'user';
                        $user->syncRoles($group); 
                        $user->plan_id = null;
                        $user->group = $group;
                        $user->total_words = 0;
                        $user->total_images = 0;
                        $user->total_chars = 0;
                        $user->total_minutes = 0;
                        $user->member_limit = null;
                        $user->save();
                    }
                } elseif ($platformID == 5) {
                    if ($status->status == 'cancelled') {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())]);
                        $user = User::where('id', $id->user_id)->firstOrFail();
                        $group = ($user->hasRole('admin'))? 'admin' : 'user';
                        $user->syncRoles($group); 
                        $user->plan_id = null;
                        $user->group = $group;
                        $user->total_words = 0;
                        $user->total_images = 0;
                        $user->total_chars = 0;
                        $user->total_minutes = 0;
                        $user->member_limit = null;
                        $user->save();
                    }
                } elseif ($platformID == 7) {
                    if ($status->status == 'Cancelled') {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())]);
                        $user = User::where('id', $id->user_id)->firstOrFail();
                        $group = ($user->hasRole('admin'))? 'admin' : 'user';
                        $user->syncRoles($group); 
                        $user->plan_id = null;
                        $user->group = $group;
                        $user->total_words = 0;
                        $user->total_images = 0;
                        $user->total_chars = 0;
                        $user->total_minutes = 0;
                        $user->member_limit = null;
                        $user->save();
                    }
                } elseif ($platformID == 10) {
                    if ($status == 'cancelled') {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())]);
                        $user = User::where('id', $id->user_id)->firstOrFail();
                        $group = ($user->hasRole('admin'))? 'admin' : 'user';
                        $user->syncRoles($group); 
                        $user->plan_id = null;
                        $user->group = $group;
                        $user->total_words = 0;
                        $user->total_images = 0;
                        $user->total_chars = 0;
                        $user->total_minutes = 0;
                        $user->member_limit = null;
                        $user->save();
                    }
                } elseif ($platformID == 11) {
                    if ($status == 'cancelled') {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())]);
                        $user = User::where('id', $id->user_id)->firstOrFail();
                        $group = ($user->hasRole('admin'))? 'admin' : 'user';
                        $user->syncRoles($group); 
                        $user->plan_id = null;
                        $user->group = $group;
                        $user->total_words = 0;
                        $user->total_images = 0;
                        $user->total_chars = 0;
                        $user->total_minutes = 0;
                        $user->member_limit = null;
                        $user->save();
                    }
                } elseif ($platformID == 12) {
                    if ($status == 'cancelled') {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())]);
                        $user = User::where('id', $id->user_id)->firstOrFail();
                        $group = ($user->hasRole('admin'))? 'admin' : 'user';
                        $user->syncRoles($group); 
                        $user->plan_id = null;
                        $user->group = $group;
                        $user->total_words = 0;
                        $user->total_images = 0;
                        $user->total_chars = 0;
                        $user->total_minutes = 0;
                        $user->member_limit = null;
                        $user->save();
                    }
                } elseif ($platformID == 99) { 
                    $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())]);
                    $user = User::where('id', $id->user_id)->firstOrFail();
                    $group = ($user->hasRole('admin'))? 'admin' : 'user';
                    $user->syncRoles($group); 
                    $user->plan_id = null;
                    $user->group = $group;
                    $user->total_words = 0;
                    $user->total_images = 0;
                    $user->total_chars = 0;
                    $user->total_minutes = 0;
                    $user->member_limit = null;
                    $user->save();
                } else {
                    if (is_null($status)) {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())]);
                        $user = User::where('id', $id->user_id)->firstOrFail();
                        $group = ($user->hasRole('admin'))? 'admin' : 'user';
                        $user->syncRoles($group); 
                        $user->plan_id = null;
                        $user->group = $group;
                        $user->total_words = 0;
                        $user->total_images = 0;
                        $user->total_chars = 0;
                        $user->total_minutes = 0;
                        $user->member_limit = null;
                        $user->save();
                    }
                }
            } else {
                $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())]);
                $user = User::where('id', $id->user_id)->firstOrFail();
                $group = ($user->hasRole('admin'))? 'admin' : 'user';
                $user->syncRoles($group); 
                $user->plan_id = null;
                $user->group = $group;
                $user->total_words = 0;
                $user->total_images = 0;
                $user->total_chars = 0;
                $user->total_minutes = 0;
                $user->member_limit = null;
                $user->save();
            }
            
            $data['status'] = 200;
            $data['message'] = __('Subscription has been successfully cancelled');
            return $data;
        }
    }


    public function hiddenPlans(Request $request)
    {
        if ($request->ajax()) {
   
            $status = ($request->status == 'true') ? 1 : 0;
            $user = User::where('id', $request->user_id)->first();
            $user->hidden_plan = $status;
            $user->save();

            $data['status'] = 200;
            return $data;
        }  
    }
}   
