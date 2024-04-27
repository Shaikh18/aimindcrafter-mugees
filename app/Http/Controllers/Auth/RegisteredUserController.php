<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use App\Models\Setting;
use App\Models\SubscriptionPlan;
use App\Models\PaymentPlatform;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use Illuminate\Auth\Events\Verified;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Mail\WelcomeMessage;
use Exception;

use Spatie\Permission\Traits\HasRoles;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (auth()->user()) {
            return redirect()->route('user.dashboard');
        } else {
            $information_rows = ['title', 'author', 'keywords', 'description', 'css', 'js'];
            $information = [];
            $settings = Setting::all();

            foreach ($settings as $row) {
                if (in_array($row['name'], $information_rows)) {
                    $information[$row['name']] = $row['value'];
                }
            }

            if (config('settings.subscribe') == 'disabled') {
                return view('auth.register', compact('information'));
            } else {

                return view('auth.subscribe-one', compact('information'));
            }
        }
    }


    public function stepTwo(Request $request)
    {
        $monthly = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'monthly')->count();
        $yearly = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'yearly')->count();
        $lifetime = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'lifetime')->count();

        $monthly_subscriptions = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'monthly')->get();
        $yearly_subscriptions = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'yearly')->get();
        $lifetime_subscriptions = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'lifetime')->get();

        return view('auth.subscribe-two', compact('monthly', 'yearly', 'lifetime', 'monthly_subscriptions', 'yearly_subscriptions', 'lifetime_subscriptions'));
    }


    public function stepThree(Request $request)
    {
        $id = SubscriptionPlan::where('id', $request->id)->first();
        $payment_platforms = PaymentPlatform::where('subscriptions_enabled', 1)->get();

        $tax_value = (config('payment.payment_tax') > 0) ? $tax = $id->price * config('payment.payment_tax') / 100 : 0;

        $total_value = $tax_value + $id->price;
        $currency = $id->currency;
        $gateway_plan_id = $id->gateway_plan_id;

        $bank_information = ['bank_instructions', 'bank_requisites'];
        $bank = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $bank_information)) {
                $bank[$row['name']] = $row['value'];
            }
        }

        $bank_order_id = 'BT-' . strtoupper(Str::random(10));
        session()->put('bank_order_id', $bank_order_id);

        return view('auth.subscribe-three', compact('id', 'payment_platforms', 'tax_value', 'total_value', 'currency', 'gateway_plan_id', 'bank', 'bank_order_id'));
    }


    public function stepTwoStore(Request $request)
    {
      
        return redirect()->route('register.subscriber.payment', ['id' => $request->id]);
    }


    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::min(8)],
            'country' => 'required',
            'agreement' => 'required',
        ]);

        if (config('services.google.recaptcha.enable') == 'on') {

            $recaptchaResult = $this->reCaptchaCheck(request('recaptcha'));

            if ($recaptchaResult->success != true) {
                toastr()->error(__('Google reCaptcha Validation has Failed'));
                return redirect()->back();
            }

            if ($recaptchaResult->score >= 0.3) {

                $this->createNewUser($request);

                if (config('settings.email_verification') == 'enabled') {
                    return redirect()->route('login');
                } else {
                    return redirect()->route('login');
                }

            } else {
                toastr()->error(__('Google reCaptcha Validation has Failed'));
                return redirect()->back();
            }

        } else {

            $this->createNewUser($request);

            if (config('settings.email_verification') == 'enabled') {
                return redirect()->route('login');
            } else {
                return redirect()->route('login');
            }
        }               

    }


    /**
     * Create new user
     * 
     */
    public function createNewUser(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country' => $request->country
        ]);
        
        event(new Registered($user));

        $referral_code = ($request->hasCookie('referral')) ? $request->cookie('referral') : ''; 
        $referrer = ($referral_code != '') ? User::where('referral_id', $referral_code)->firstOrFail() : '';
        $referrer_id = ($referrer != '') ? $referrer->id : '';

        $status = (config('settings.email_verification') == 'disabled') ? 'active' : 'pending';
        
        $user->assignRole(config('settings.default_user'));
        $user->status = $status;
        $user->group = config('settings.default_user');
        $user->available_words = config('settings.free_tier_words');
        $user->available_dalle_images = config('settings.free_tier_dalle_images');
        $user->available_sd_images = config('settings.free_tier_sd_images');
        $user->available_chars = config('settings.voiceover_welcome_chars');
        $user->available_minutes = config('settings.whisper_welcome_minutes');
        $user->default_voiceover_language = config('settings.voiceover_default_language');
        $user->default_voiceover_voice = config('settings.voiceover_default_voice');
        $user->default_template_language = config('settings.default_language');
        $user->job_role = 'Happy Person';
        $user->referral_id = strtoupper(Str::random(15));
        $user->referred_by = $referrer_id;
        $user->save();     

        Auth::login($user, true);
        
        if (config('settings.email_verification') == 'enabled') {

            $digits = '0123456789';
            $digitsLength = strlen($digits);
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $digits[rand(0, $digitsLength - 1)];
            }
            $user->verification_code = $code;
            $user->save();

            try {
                Mail::to($user)->send(new EmailVerification($code));
                toastr()->success(__('Email verification code has been successfully sent'));
            } catch (Exception $e) {
                toastr()->error(__('SMTP settings are not setup yet, please contact support team'));
            }
            
            return view('auth.verify-email');
        } else {

            $request->user()->markEmailAsVerified();
                
            event(new Verified($request->user()));

            $user->status = 'active';
            $user->save();

            try {
                Mail::to($request->user())->send(new WelcomeMessage());
            } catch (Exception $e) {
                \Log::info('SMTP settings are not configured yet');
            }
            
            toastr()->success(__('Congratulations! Your account is fully active now'));
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }
     
    }


    /**
     * Validate reCaptcha (if enabled)
     * 
     */
    private function reCaptchaCheck($recaptcha)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $remoteip = $_SERVER['REMOTE_ADDR'];

        $data = [
                'secret' => config('services.google.recaptcha.secret_key'),
                'response' => $recaptcha,
                'remoteip' => $remoteip
        ];

        $options = [
                'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
                ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);

        return $resultJson;
    }


    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeSubscriber(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::min(8)],
            'agreement' => 'required',
        ]);


        $user = User::create([
            'name' => $request->name . ' ' . $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country' => $request->country
        ]);
        
        event(new Registered($user));

        $referral_code = ($request->hasCookie('referral')) ? $request->cookie('referral') : ''; 
        $referrer = ($referral_code != '') ? User::where('referral_id', $referral_code)->firstOrFail() : '';
        $referrer_id = ($referrer != '') ? $referrer->id : '';

        //$status = (config('settings.email_verification') == 'disabled') ? 'active' : 'pending';
        
        $user->assignRole(config('settings.default_user'));
        $user->status = 'active';
        $user->group = config('settings.default_user');
        $user->available_words = config('settings.free_tier_words');
        $user->available_dalle_images = config('settings.free_tier_dalle_images');
        $user->available_sd_images = config('settings.free_tier_sd_images');
        $user->available_chars = config('settings.voiceover_welcome_chars');
        $user->available_minutes = config('settings.whisper_welcome_minutes');
        $user->default_voiceover_language = config('settings.voiceover_default_language');
        $user->default_voiceover_voice = config('settings.voiceover_default_voice');
        $user->default_template_language = config('settings.default_language');
        $user->job_role = 'Happy Person';
        $user->referral_id = strtoupper(Str::random(15));
        $user->referred_by = $referrer_id;
        $user->subscription_required = true;
        $user->save();  

        Auth::login($user, true);

        toastr()->success(__('Account successfully created, select your plan to subscribe'));
        return redirect()->route('register.subscriber.plans');

    }

}
