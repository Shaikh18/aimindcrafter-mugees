<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Services\Statistics\DavinciUsageService;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use App\Models\SubscriptionPlan;
use App\Models\Subscriber;
use App\Models\Language;
use App\Models\User;
use Carbon\Carbon;
use DB;


class UserController extends Controller
{
    use Notifiable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                         
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $davinci = new DavinciUsageService($month, $year);

        $data = [
            'words' => $davinci->userTotalWordsGenerated(),
            'images' => $davinci->userTotalImagesGenerated(),
            'contents' => $davinci->userTotalContentsGenerated(),
            'synthesized' => $davinci->userTotalSynthesizedText(),
            'transcribed' => $davinci->userTotalTranscribedAudio(),
            'codes' => $davinci->userTotalCodesCreated(),
        ];
        
        $chart_data['word_usage'] = json_encode($davinci->userMonthlyWordsChart());
        $chart_data['image_usage'] = json_encode($davinci->userMonthlyImagesChart());
        
        $subscription = Subscriber::where('status', 'Active')->where('user_id', auth()->user()->id)->first();
        if ($subscription) {
             if(Carbon::parse($subscription->active_until)->isPast()) {
                 $subscription = false;
             } 
        } else {
            $subscription = false;
        }

        $user_subscription = ($subscription) ? SubscriptionPlan::where('id', auth()->user()->plan_id)->first() : '';

        $check_api_feature = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();

        $progress = [
            'words' => (auth()->user()->total_words > 0) ? ((auth()->user()->available_words / auth()->user()->total_words) * 100) : 0,
        ];

        return view('user.profile.index', compact('chart_data', 'data', 'subscription', 'user_subscription', 'progress', 'check_api_feature'));           
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id = null)
    {   
        $check_api_feature = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();

        $storage['available'] = $this->formatSize(auth()->user()->storage_total * 1000000);

        return view('user.profile.edit', compact('storage', 'check_api_feature'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDefaults($id = null)
    {   
        if (is_null(auth()->user()->plan_id)) {
            $vendors = explode(', ', config('settings.voiceover_free_tier_vendors'));
        } else {
           $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
           $vendors = explode(', ', $plan->voiceover_vendors);

        }

        # Set Voice Types
        $languages = DB::table('voices')
            ->join('vendors', 'voices.vendor_id', '=', 'vendors.vendor_id')
            ->join('voiceover_languages', 'voices.language_code', '=', 'voiceover_languages.language_code')
            ->where('vendors.enabled', '1')
            ->where('voices.status', 'active')
            ->whereIn('voices.vendor', $vendors)
            ->select('voiceover_languages.id', 'voiceover_languages.language', 'voices.language_code', 'voiceover_languages.language_flag')                
            ->distinct()
            ->orderBy('voiceover_languages.language', 'asc')
            ->get();

        $voices = DB::table('voices')
            ->join('vendors', 'voices.vendor_id', '=', 'vendors.vendor_id')
            ->where('vendors.enabled', '1')
            ->where('voices.status', 'active')
            ->whereIn('voices.vendor', $vendors)
            ->orderBy('voices.voice_type', 'desc')
            ->orderBy('voices.voice', 'asc')
            ->get();

        $template_languages = Language::orderBy('languages.language', 'asc')->get();

        $check_api_feature = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();

        return view('user.profile.default', compact('languages', 'voices', 'template_languages', 'check_api_feature'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {           
        $user = User::where('id', auth()->user()->id)->first();
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
            'country' => 'nullable|string|max:255',
        ]));
        
        if (request()->has('profile_photo')) {
        
            try {
                request()->validate([
                    'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048'
                ]);
                
            } catch (\Exception $e) {
                toastr()->error($e->getMessage());
                return redirect()->back();
            }
            
            $image = request()->file('profile_photo');

            $name = Str::random(20);
            
            $folder = '/uploads/img/users/';
            
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();

            $imageTypes = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array(Str::lower($image->getClientOriginalExtension()), $imageTypes)) {
                toastr()->error(__('Avatar image must be in png, jpeg or webp formats'));
                return redirect()->back();
            } else {
                $this->uploadImage($image, $folder, 'public', $name);

                $user->profile_photo_path = $filePath;

                $user->save();
            }
            
        }

        toastr()->success(__('Profile Successfully Updated'));
        return redirect()->route('user.profile.edit', compact('user'));
        
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDefaults(Request $request)
    {           
        $user = User::where('id', auth()->user()->id)->first();
        $user->update(request()->validate([
            'default_voiceover_voice' => 'nullable|string|max:255',
            'default_voiceover_language' => 'nullable|string|max:255',
            'default_template_language' => 'nullable|string|max:255',
        ]));

        $user->save();

        toastr()->success(__('Default settings successfully updated'));
        return redirect()->route('user.profile.defaults');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDelete($id = null)
    {   
        $check_api_feature = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();

        return view('user.profile.delete', compact('check_api_feature'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAPI()
    {   
        $check_api_feature = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();

        return view('user.profile.api', compact('check_api_feature'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeAPI(Request $request)
    {           
        $user = User::where('id', auth()->user()->id)->first();
        $user->update([
            'personal_openai_key' => request('openai-key'),
            'personal_sd_key' => request('sd-key'),
        ]);

        $user->save();

        toastr()->success(__('Your personal api keys have been saved successfully'));
        return redirect()->route('user.profile.api');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accountDelete(Request $request)
    {   
        if ($request->concent) {

            $user = User::where('id', auth()->user()->id)->first();
            $user->delete();

            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            toastr()->success(__('Your account has been successfully deleted'));
            return redirect('/');
            
        } else {
            toastr()->warning(__('Please activate the checkbox to confirm account deletion'));
            return redirect()->back();
        }
          
    }


    /**
     * Upload user profile image
     */
    public function uploadImage(UploadedFile $file, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $image = $file->storeAs($folder, $name .'.'. $file->getClientOriginalExtension(), $disk);

        return $image;
    }


    /**
     * Format storage space to readable format
     */
    private function formatSize($size, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $size = max($size, 0); 
        $pow = floor(($size ? log($size) : 0) / log(1000)); 
        $pow = min($pow, count($units) - 1); 
        
        $size /= pow(1000, $pow);

        return round($size, $precision) . $units[$pow]; 
    }


    public function updateReferral(Request $request)
    {
        if ($request->ajax()) {

            $check = User::where('referral_id', $request->value)->first();

            if ($check) {
                $data['status'] = 'error';
                $data['message'] = __('This Referral ID is already in use by another user, please enter another one');
                return $data;
            } else {
                $user = User::where('id', auth()->user()->id)->first();
                $user->referral_id = $request->value;
                $user->save();
            }

            $data['status'] = 'success';
            return $data;
        } 
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function theme(Request $request)
    {           
        $user = User::where('id', auth()->user()->id)->first();
        $user->update(['theme' => $request->theme]);

        $user->save();
    }
}
