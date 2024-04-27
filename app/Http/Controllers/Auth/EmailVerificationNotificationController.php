<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use App\Mail\WelcomeMessage;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Exception;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1')->with('verified', true);
        }

        if (config('settings.email_verification') == 'enabled') {

            $digits = '0123456789';
            $digitsLength = strlen($digits);
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $digits[rand(0, $digitsLength - 1)];
            }

            $user = User::where('id', $request->user()->id)->first();
            $user->verification_code = $code;
            $user->save();

            try {
                Mail::to($request->user())->send(new EmailVerification($code));
                toastr()->success(__('Email verification code has been successfully sent'));
            } catch (Exception $e) {
                toastr()->error(__('SMTP settings are not setup yet, please contact support team'));
            }

            return back();
        }
        
    }


    public function check(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1')->with('verified', true);
        }

        $user = User::where('id', $request->user()->id)->first();
        
        if(strip_tags($request->verificationcode) === $user->verification_code) {
            try {
                Mail::to($request->user())->send(new WelcomeMessage());
            } catch (Exception $e) {
                \Log::info('SMTP settings are not configured yet');
            }

            $request->user()->markEmailAsVerified();
                
            event(new Verified($request->user()));

            $user->status = 'active';
            $user->save();
            
            
            toastr()->success(__('Congratulations! Your account is fully active now'));
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');

        } else {
            $data['status'] = 'error';
            $data['message'] = __('Please provide a valid email verification code');

            return $data;
        }


        

        
        

        
       
        
    }
}
