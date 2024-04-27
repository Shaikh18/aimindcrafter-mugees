<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Events\RegistrationReferrerBonus;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Exception;

class SocialAuthController extends Controller
{   
    /**
     * Login with social OAuth feature
     * 
     */
    public function redirectToProvider($driver)
    {      
        return Socialite::driver($driver)->redirect();
    }


    public function handleProviderCallback(Request $request, $driver)
    {
        try {
     
            $user = Socialite::driver($driver)->user();

            $existing_user = User::where('oauth_id', $user->getId())->first();
      
            if ($existing_user) {
      
                Auth::login($existing_user, true);
     
                return redirect()->route('user.dashboard');
      
            } else {

                $check_user = User::where('email', $user->getEmail())->first();

                if ($check_user) {
                    $check_user->oauth_id = $user->getId();
                    $check_user->oauth_type = $driver;
                    $check_user->save(); 
                    Auth::login($check_user, true);

                    return redirect()->route('user.dashboard');
                    
                } else {
                    $new_user = User::create([
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                        'oauth_id'=> $user->getId(),
                        'oauth_type'=> $driver,                    
                        'country'=> config('settings.default_country'),                    
                        'password' => Hash::make($user->getEmail()."-".rand(1000,10000)),
                    ]);
    
                    event(new Registered($new_user));
    
                    $referral_code = ($request->hasCookie('referral')) ? $request->cookie('referral') : ''; 
                    $referrer = ($referral_code != '') ? User::where('referral_id', $referral_code)->firstOrFail() : '';
                    $referrer_id = ($referrer != '') ? $referrer->id : '';
            
                    $new_user->assignRole(config('settings.default_user'));
                    $new_user->status = 'active';
                    $new_user->group = config('settings.default_user');
                    $new_user->email_verified_at = now();
                    $new_user->available_words = config('settings.free_tier_words');
                    $new_user->available_dalle_images = config('settings.free_tier_dalle_images');
                    $new_user->available_sd_images = config('settings.free_tier_sd_images');
                    $new_user->available_chars = config('settings.voiceover_welcome_chars');
                    $new_user->available_minutes = config('settings.whisper_welcome_minutes');
                    $new_user->default_voiceover_language = config('settings.voiceover_default_language');
                    $new_user->default_voiceover_voice = config('settings.voiceover_default_voice');
                    $new_user->default_template_language = config('settings.default_language');
                    $new_user->job_role = 'Happy Person';
                    $new_user->referral_id = strtoupper(Str::random(15));
                    $user->referred_by = $referrer_id;
                    $new_user->save();  

                    toastr()->success(__('Congratulations! Your account is activated successfully.'));

                    Auth::login($new_user, true);

                    return redirect()->route('user.dashboard');
                } 
                
            }
     
        } catch (Exception $e) {
            toastr()->error(__('Login with your social media account has failed, try again or register with email'));
            return redirect()->route('login');
        }
    }

}
