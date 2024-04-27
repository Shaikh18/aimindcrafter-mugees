<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\PaymentPlatform;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\SubscriptionPlan;
use App\Models\PrepaidPlan;
use App\Models\Payment;
use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;

class PlanController extends Controller
{   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hidden_plan) {
            $monthly = SubscriptionPlan::where('payment_frequency', 'monthly')->where(function ($q) { $q->where('status', 'active')->orWhere('status', 'hidden'); })->count();
            $yearly = SubscriptionPlan::where('payment_frequency', 'yearly')->where(function ($q) { $q->where('status', 'active')->orWhere('status', 'hidden'); })->count();
            $lifetime = SubscriptionPlan::where('payment_frequency', 'lifetime')->where(function ($q) { $q->where('status', 'active')->orWhere('status', 'hidden'); })->count();
        } else {
            $monthly = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'monthly')->count();
            $yearly = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'yearly')->count();
            $lifetime = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'lifetime')->count();
        }
        
        $prepaid = PrepaidPlan::where('status', 'active')->count();

        if (auth()->user()->hidden_plan) {
            $monthly_subscriptions = SubscriptionPlan::where('payment_frequency', 'monthly')->where(function ($q) { $q->where('status', 'active')->orWhere('status', 'hidden'); })->get();
            $yearly_subscriptions = SubscriptionPlan::where('payment_frequency', 'yearly')->where(function ($q) { $q->where('status', 'active')->orWhere('status', 'hidden'); })->get();
            $lifetime_subscriptions = SubscriptionPlan::where('payment_frequency', 'lifetime')->where(function ($q) { $q->where('status', 'active')->orWhere('status', 'hidden'); })->get();
        } else {
            $monthly_subscriptions = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'monthly')->get();
            $yearly_subscriptions = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'yearly')->get();
            $lifetime_subscriptions = SubscriptionPlan::where('status', 'active')->where('payment_frequency', 'lifetime')->get();
        }
        
        $prepaids = PrepaidPlan::where('status', 'active')->get();

        return view('user.plans.index', compact('monthly', 'yearly', 'monthly_subscriptions', 'yearly_subscriptions', 'prepaids', 'prepaid', 'lifetime', 'lifetime_subscriptions'));
    }


    /**
     * Checkout for Subscription plans only.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscribe(SubscriptionPlan $id)
    {           
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

        if ($id->free) {

            if (auth()->user()->used_free_tier) {
                toastr()->warning(__('You can only apply once to Free subscription plans'));
                return redirect()->route('user.plans');
            } else {
                $order_id = $this->registerFreeSubscription($id);
                $plan = SubscriptionPlan::where('id', $id->id)->first();
                $user = User::where('id', auth()->user()->id)->first();
                $user->used_free_tier = true;
                $user->save();

                toastr()->success(__('Free subscription plan added successfully'));
                return view('user.plans.success', compact('plan', 'order_id'));
            }            
            
        } else {
            return view('user.plans.subscribe-checkout', compact('id', 'payment_platforms', 'tax_value', 'total_value', 'currency', 'gateway_plan_id', 'bank', 'bank_order_id'));
        }

    } 


    /**
     * Checkout for Prepaid plans only.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {   
        if ($request->type == 'lifetime') {
            $id = SubscriptionPlan::where('id', $request->id)->first();
            $type = 'lifetime';
        } else {
            $id = PrepaidPlan::where('id', $request->id)->first();
            $type = 'prepaid';
        }

        $payment_platforms = PaymentPlatform::where('enabled', 1)->get();
        
        $tax_value = (config('payment.payment_tax') > 0) ? $tax = $id->price * config('payment.payment_tax') / 100 : 0;

        $total_value = $tax_value + $id->price;
        $currency = $id->currency;

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
        
        return view('user.plans.prepaid-checkout', compact('id', 'type', 'payment_platforms', 'tax_value', 'total_value', 'currency', 'bank', 'bank_order_id'));
    }


     /**
     * Register free subscription
     */
    private function registerFreeSubscription(SubscriptionPlan $plan)
    {
        $order_id = strtoupper(Str::random(10));
        $subscription = strtoupper(Str::random(10));
        $duration = $plan->payment_frequency;

        if (!is_null($plan->days)) {
            $days = $plan->days;
        } else {
            switch ($duration) {
                case 'lifetime':
                    $days = 18250;
                    break;
                case 'yearly':
                    $days = 365;
                    break;
                case 'monthly':
                    $days = 30;
                    break;
                default:
                    $days = 30;
                    break;
            }
        }
        

        $record_payment = new Payment();
        $record_payment->user_id = auth()->user()->id;
        $record_payment->plan_id = $plan->id;
        $record_payment->frequency = $plan->payment_frequency;
        $record_payment->order_id = $order_id;
        $record_payment->plan_name = $plan->plan_name;
        $record_payment->price = 0;
        $record_payment->currency = $plan->currency;
        $record_payment->gateway = 'FREE';
        $record_payment->status = 'completed';
        $record_payment->words = $plan->words;
        $record_payment->characters = $plan->characters;
        $record_payment->minutes = $plan->minutes;
        $record_payment->save();

        $subscription = Subscriber::create([
            'user_id' => auth()->user()->id,
            'plan_id' => $plan->id,
            'status' => 'Active',
            'created_at' => now(),
            'gateway' => 'FREE',
            'frequency' => $plan->payment_frequency,
            'words' => $plan->words,
            'characters' => $plan->characters,
            'minutes' => $plan->minutes,
            'subscription_id' => $subscription,
            'active_until' => Carbon::now()->addDays($days),
        ]); 
        
        $group = (auth()->user()->hasRole('admin'))? 'admin' : 'subscriber';

        $user = User::where('id', auth()->user()->id)->first();
        $user->syncRoles($group);    
        $user->group = $group;
        $user->plan_id = $plan->id;
        $user->total_words = $plan->words;
        $user->total_chars = $plan->characters;
        $user->total_minutes = $plan->minutes;
        $user->available_words = $plan->words;
        $user->available_chars = $plan->characters;
        $user->available_minutes = $plan->minutes;
        $user->member_limit = $plan->team_members;
        $user->total_dalle_images = $plan->dalle_images;
        $user->total_sd_images = $plan->sd_images;
        $user->available_dalle_images = $plan->dalle_images;
        $user->available_sd_images = $plan->sd_images;
        $user->save();       
        
        return $order_id;
    } 
}
