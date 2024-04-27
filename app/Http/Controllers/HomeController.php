<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Mail\ContactFormEmail;
use App\Models\SubscriptionPlan;
use App\Models\PrepaidPlan;
use App\Models\Setting;
use App\Models\CustomTemplate;
use App\Models\Template;
use App\Models\Blog;
use App\Models\Review;
use App\Models\Page;
use App\Models\Faq;
use App\Models\Category;
use App\Models\FrontendStep;
use App\Models\FrontendTool;
use App\Models\FrontendFeature;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Show home page
     */
    public function index()
    {

        $review_exists = Review::count();   
        $review_second_exists = Review::where('row', 'second')->count();   
        $reviews = Review::all();

        $information = $this->metadataInformation();

        $faq_exists = Faq::count();        
        $faqs = Faq::where('status', 'visible')->get();

        $blog_exists = Blog::count();
        $blogs = Blog::where('status', 'published')->orderBy('created_at', 'desc')->get();

        $monthly = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'monthly')->count();
        $yearly = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'yearly')->count();
        $lifetime = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'lifetime')->count();
        $prepaid = PrepaidPlan::where('status', 'active')->count();

        $monthly_subscriptions = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'monthly')->get();
        $yearly_subscriptions = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'yearly')->get();
        $lifetime_subscriptions = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'lifetime')->get();
        $prepaids = PrepaidPlan::where('status', 'active')->get();

        $other_templates = Template::where('status', true)->orderBy('group', 'desc')->get();   
        $custom_templates = CustomTemplate::where('status', true)->orderBy('group', 'desc')->get();   
        
        $check_categories = Template::where('status', true)->groupBy('group')->pluck('group')->toArray();
        $check_custom_categories = CustomTemplate::where('status', true)->groupBy('group')->pluck('group')->toArray();
        $active_categories = array_unique(array_merge($check_categories, $check_custom_categories));
        $categories = Category::whereIn('code', $active_categories)->orderBy('name', 'asc')->get(); 

        $steps = FrontendStep::orderBy('order', 'asc')->get();
        $tools = FrontendTool::where('status', true)->get();

        $features = FrontendFeature::where('status', true)->get();

        return view('home', compact('information', 'steps', 'tools', 'features', 'blog_exists', 'blogs', 'faq_exists', 'faqs', 'review_exists', 'review_second_exists', 'reviews', 'monthly', 'yearly', 'monthly_subscriptions', 'yearly_subscriptions', 'prepaids', 'prepaid', 'other_templates', 'custom_templates', 'lifetime', 'lifetime_subscriptions', 'categories'));
    }


    /**
     * Display terms & conditions page
     * 
     */
    public function termsAndConditions() 
    {
        $information = $this->metadataInformation();

        $pages_rows = ['terms'];
        $pages = [];
        $page = Page::all();

        foreach ($page as $row) {
            if (in_array($row['name'], $pages_rows)) {
                $pages[$row['name']] = $row['value'];
            }
        }

        return view('service-terms', compact('information', 'pages'));
    }


    /**
     * Display privacy policy page
     * 
     */
    public function privacyPolicy() 
    {
        $information = $this->metadataInformation();

        $pages_rows = ['privacy'];
        $pages = [];
        $page = Page::all();

        foreach ($page as $row) {
            if (in_array($row['name'], $pages_rows)) {
                $pages[$row['name']] = $row['value'];
            }
        }

        return view('privacy-policy', compact('information', 'pages'));
    }


    /**
     * Frontend show blog
     * 
     */
    public function blogShow($slug)
    {
        $blog = Blog::where('url', $slug)->firstOrFail();

        $information_rows = ['js', 'css'];
        $information = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $information_rows)) {
                $information[$row['name']] = $row['value'];
            }
        }

        $information['author'] = $blog->created_by;
        $information['title'] = $blog->title;
        $information['keywords'] = $blog->keywords;
        $information['description'] = $blog->title;

        return view('blog-show', compact('information', 'blog'));
    }


    /**
     * Frontend show contact
     * 
     */
    public function contactShow()
    {
        $information = $this->metadataInformation();

        return view('contact', compact('information'));
    }


    /**
     * Frontend show about us
     * 
     */
    public function aboutUs()
    {
        $information = $this->metadataInformation();

        $pages_rows = ['about'];
        $pages = [];
        $page = Page::all();

        foreach ($page as $row) {
            if (in_array($row['name'], $pages_rows)) {
                $pages[$row['name']] = $row['value'];
            }
        }

        $blog_exists = Blog::count();
        $blogs = Blog::where('status', 'published')->orderBy('created_at', 'desc')->get();

        return view('about', compact('information', 'pages', 'blogs', 'blog_exists'));
    }


    /**
     * Frontend contact us form record
     * 
     */
    public function contactSend(Request $request)
    {
        request()->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        if (config('services.google.recaptcha.enable') == 'on') {

            $recaptchaResult = $this->reCaptchaCheck(request('recaptcha'));

            if ($recaptchaResult->success != true) {
                toastr()->error(__('Google reCaptcha Validation has Failed'));
                return redirect()->back();
            }

            if ($recaptchaResult->score >= 0.5) {

                try {

                    Mail::to(config('mail.from.address'))->send(new ContactFormEmail($request));
 
                    if (Mail::flushMacros()) {
                        toastr()->error(__('Sending email failed, please try again.'));
                        return redirect()->back();
                    }
                    
                } catch (\Exception $e) {
                    toastr()->error(__('Sending email failed, please contact support team.'));
                    return redirect()->back();
                }

                toastr()->success(__('Email was successfully sent'));
                return redirect()->back();

            } else {
                toastr()->error(__('Google reCaptcha Validation has Failed'));
                return redirect()->back();
            }
        
        } else {

            try {

                Mail::to(config('mail.from.address'))->send(new ContactFormEmail($request));
 
                if (Mail::flushMacros()) {
                    toastr()->error(__('Sending email failed, please try again.'));
                    return redirect()->back();
                }

            } catch (\Exception $e) {
                toastr()->error(__('Sending email failed, please contact support team.'));
                return redirect()->back();
            }

            toastr()->success(__('Email was successfully sent'));
            return redirect()->back();
        }  
    }


    /**
     * Verify reCaptch for frontend contact us page (if enabled)
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


    public function metadataInformation()
    {
        $information_rows = ['title', 'author', 'keywords', 'description', 'js', 'css'];
        $information = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $information_rows)) {
                $information[$row['name']] = $row['value'];
            }
        }

        return $information;
    }

}
