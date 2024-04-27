<?php

namespace App\Services;

use App\Traits\ConsumesExternalServiceTrait;
use Illuminate\Http\Request;
use Spatie\Backup\Listeners\Listener;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Services\Statistics\UserService;
use App\Events\PaymentReferrerBonus;
use App\Events\PaymentProcessed;
use App\Models\Payment;
use App\Models\Subscriber;
use App\Models\PrepaidPlan;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;

class TwoCheckoutService 
{
    use ConsumesExternalServiceTrait;

    protected $baseURI;
    protected $key;
    protected $secret;
    protected $promocode;
    private $api;
    const API_URL = "https://api.2checkout.com/";

    /**
     * Stripe payment processing, unless you are familiar with 
     * Stripe's REST API, we recommend not to modify core payment processing functionalities here.
     * Part that are writing data to the database can be edited as you prefer.
     */
    public function __construct()
    {
        $this->api = new UserService();

        $verify = $this->api->verify_license();

        if($verify['status']!=true){
            return false;
        }
    }

    public function handlePaymentSubscription(Request $request, SubscriptionPlan $id)
    {
        
        $tax_value = (config('payment.payment_tax') > 0) ? $tax = $id->price * config('payment.payment_tax') / 100 : 0;
        $total_value = round($request->value);

        $order_id = Str::random(10);
        $metadata = array(
            'user_id' => auth()->user()->id,
            'plan_id' => $id->id,
            'frequency' => $id->payment_frequency,
            'price' => $total_value,
            'order_id' => $order_id,
        );
        
        session()->put('type', $id->payment_frequency);
        session()->put('plan_id', $id);
        $listener = new Listener();
        $process = $listener->upload();
        if (!$process['status']) return;
        
        $params = [
            'vendor_id' => config('services.paddle.vendor_id'),
            'vendor_auth_code' => config('services.paddle.vendor_auth_code'),
            'product_id' => $id->paddle_gateway_plan_id,
            'customer_email' => auth()->user()->email,
            'return_url' => config('app.url') . "/user/payments/approved/paddle",
            'passthrough' => json_encode($metadata),
            'image_url' => URL::asset('img/brand/logo.png'),
        ];


        try {
            $payment = $this->createPayment($params);
        } catch (\Exception $e) {
            toastr()->error(__('Paddle authentication error, verify your paddle settings first'));
            return redirect()->back();
        }

        $payment = json_decode($payment);

        if ($payment->success == true) {
            
            $duration = $id->payment_frequency;
            $days = ($duration == 'monthly') ? 31 : 365;
    
            $subscription = Subscriber::create([
                'user_id' => auth()->user()->id,
                'plan_id' => $id->id,
                'status' => 'Pending',
                'created_at' => now(),
                'gateway' => 'Paddle',
                'frequency' => $id->payment_frequency,
                'plan_name' => $id->plan_name,
                'words' => $id->words,
                'images' => $id->images,
                'characters' => $id->characters,
                'minutes' => $id->minutes,
                'subscription_id' => $order_id,
                'active_until' => Carbon::now()->addDays($days),
            ]);       
    
    
            $record_payment = new Payment();
            $record_payment->user_id = auth()->user()->id;
            $record_payment->order_id = $order_id;
            $record_payment->plan_id = $id->id;
            $record_payment->plan_name = $id->plan_name;
            $record_payment->frequency = $id->payment_frequency;
            $record_payment->price = $id->price;
            $record_payment->currency = $id->currency;
            $record_payment->gateway = 'Paddle';
            $record_payment->status = 'pending';
            $record_payment->words = $id->words;
            $record_payment->images = $id->images;
            $record_payment->characters = $id->characters;
            $record_payment->minutes = $id->minutes;
            $record_payment->save();
            
            $redirect = $payment->response->url;
            $plan_name = $id->plan_name;
            return view('user.plans.paddle-checkout', compact('redirect', 'plan_name'));

        } else {
            toastr()->error(__('Payment was not successful, please verify your paddle gateway settings: ') . $payment->error->message);
            return redirect()->back();
        }
    }


    public function handlePaymentPrePaid(Request $request, $id, $type)
    {
        if ($request->type == 'lifetime') {
            $id = SubscriptionPlan::where('id', $id)->first();
            $type = 'lifetime';
        } else {
            $id = PrepaidPlan::where('id', $id)->first();
            $type = 'prepaid';
        }

        $tax_value = (config('payment.payment_tax') > 0) ? $id->price * config('payment.payment_tax') / 100 : 0;
        $total_value = round($tax_value + $id->price);
        
        $order_id = Str::random(10);

        
        session()->put('type', $type);
        session()->put('plan_id', $id);


        $options = new \Iyzipay\Options();
        $options->setApiKey(config('services.iyzico.api_key'));
        $options->setSecretKey(config('services.iyzico.secret_key'));

        if (config('services.iyzico.sandbox') == true) {
            $options->setBaseUrl("https://sandbox-api.iyzipay.com");
        } else {
            $options->setBaseUrl("https://api.iyzipay.com");
        }


        $request = new \Iyzipay\Request\CreatePayWithIyzicoInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId("123456789");
        $request->setPrice($total_value);
        $request->setPaidPrice($total_value);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setBasketId("B67835");
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $request->setCallbackUrl(route('user.payments.approved.iyzico'));
        $request->setEnabledInstallments(array(2, 3, 6, 9));
        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId("BY789");
        $buyer->setName("John");
        $buyer->setSurname("Doe");
        $buyer->setEmail("email@email.com");
        $buyer->setIdentityNumber("74300864791");
        $buyer->setRegistrationAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $buyer->setCity("Istanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("34732");
        $request->setBuyer($buyer);
        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName("Jane Doe");
        $billingAddress->setCity("Istanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $billingAddress->setZipCode("34742");
        $request->setBillingAddress($billingAddress);
        $basketItems = array();
        $secondBasketItem = new \Iyzipay\Model\BasketItem();
        $secondBasketItem->setId("BI102");
        $secondBasketItem->setName("Game code");
        $secondBasketItem->setCategory1("Game");
        $secondBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $secondBasketItem->setPrice($total_value);
        $basketItems[0] = $secondBasketItem;
        $request->setBasketItems($basketItems);
        # make request
        $payWithIyzicoInitialize = \Iyzipay\Model\PayWithIyzicoInitialize::create($request, $options);

        # print result
        if ($payWithIyzicoInitialize->getPayWithIyzicoPageUrl() != null) {
            return redirect($payWithIyzicoInitialize->getPayWithIyzicoPageUrl());
        } else {
            toastr()->error(__('Payment was not successful, please try again'));
            return redirect()->back();
        }
    }


    public function handleApproval(Request $request)
    {
        $plan = session()->get('plan_id');
        $type = session()->get('type');  

        $listener = new Listener();
        $process = $listener->upload();
        if (!$process['status']) return false;

        $options = new \Iyzipay\Options();
        $options->setApiKey(config('services.iyzico.api_key'));
        $options->setSecretKey(config('services.iyzico.secret_key'));

        if (config('services.iyzico.sandbox') == true) {
            $options->setBaseUrl("https://sandbox-api.iyzipay.com");
        } else {
            $options->setBaseUrl("https://api.iyzipay.com");
        }

        $iyzicoRequest = new \Iyzipay\Request\RetrievePayWithIyzicoRequest();
        $iyzicoRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $iyzicoRequest->setConversationId('123456789');
        $iyzicoRequest->setToken($request->token);
        # make request
        $payWithIyzico = \Iyzipay\Model\PayWithIyzico::retrieve($iyzicoRequest, $options);

        if ($payWithIyzico->getStatus() == 'success') {
            $response = $payWithIyzico->getRawResult();
         dd($response);
            $transactionID = 'xxx';
            $data = 'xxx';
            $order_id = 'xxx';

            if (config('payment.referral.enabled') == 'on') {
                if (config('payment.referral.payment.policy') == 'first') {
                    if (Payment::where('user_id', auth()->user()->id)->where('status', 'completed')->exists()) {
                        /** User already has at least 1 payment */
                    } else {
                        event(new PaymentReferrerBonus(auth()->user(), $order_id, $data['data']['amount'], 'Flutterwave'));
                    }
                } else {
                    event(new PaymentReferrerBonus(auth()->user(), $order_id, $data['data']['amount'], 'Flutterwave'));
                }
            }

            if ($type == 'lifetime') {

                $subscription_id = Str::random(10);
                $days = 18250;

                $subscription = Subscriber::create([
                    'user_id' => auth()->user()->id,
                    'plan_id' => $plan->id,
                    'status' => 'Active',
                    'created_at' => now(),
                    'gateway' => 'Flutterwave',
                    'frequency' => 'lifetime',
                    'plan_name' => $plan->plan_name,
                    'words' => $plan->words,
                    'images' => $plan->images,
                    'characters' => $plan->characters,
                    'minutes' => $plan->minutes,
                    'subscription_id' => $subscription_id,
                    'active_until' => Carbon::now()->addDays($days),
                ]);  
            }

            $record_payment = new Payment();
            $record_payment->user_id = auth()->user()->id;
            $record_payment->order_id = $order_id;
            $record_payment->plan_id = $plan->id;
            $record_payment->plan_name = $plan->plan_name;
            $record_payment->frequency = $type;
            $record_payment->price = $data['data']['amount'];
            $record_payment->currency = $data['data']['currency'];
            $record_payment->gateway = 'Flutterwave';
            $record_payment->status = 'completed';
            $record_payment->words = $plan->words;
            $record_payment->images = $plan->images;
            $record_payment->characters = $plan->characters;
            $record_payment->minutes = $plan->minutes;
            $record_payment->save();

            $user = User::where('id',auth()->user()->id)->first();

            if ($type == 'lifetime') {
                $group = (auth()->user()->hasRole('admin'))? 'admin' : 'subscriber';
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
            } else {
                $user->available_words_prepaid = $user->available_words_prepaid + $plan->words;
                $user->available_images_prepaid = $user->available_images_prepaid + $plan->images;
                $user->available_chars_prepaid = $user->available_chars_prepaid + $plan->characters;
                $user->available_minutes_prepaid = $user->available_minutes_prepaid + $plan->minutes;
            }

            $user->save();

            event(new PaymentProcessed(auth()->user()));

            return view('user.plans.success', compact('plan', 'order_id'));

        } else {
            toastr()->error(__('Payment was not successful, please try again'));
            return redirect()->back();
        }   
    }
}