<?php

namespace App\Services;

use App\Traits\ConsumesExternalServiceTrait;
use App\Http\Controllers\Admin\Webhooks\PaymentWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Events\PaymentReferrerBonus;
use App\Events\PaymentProcessed;
use App\Models\Payment;
use App\Models\Subscriber;
use App\Models\PrepaidPlan;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSuccess;
use App\Mail\NewPaymentNotification;
use Exception;

class IyzicoService 
{
    use ConsumesExternalServiceTrait;

    public function handlePaymentSubscription(Request $request, SubscriptionPlan $id)
    {
        //TODO Develop subscription support
        $tax_value = (config('payment.payment_tax') > 0) ? $tax = $id->price * config('payment.payment_tax') / 100 : 0;
        $total_value = round($request->value);

        $order_id = Str::random(10);
        
        session()->put('type', $id->payment_frequency);
        session()->put('plan_id', $id);

        // if ($payment->success == true) {
            
        //     $duration = $id->payment_frequency;
        //     $days = ($duration == 'monthly') ? 31 : 365;
    
        //     $subscription = Subscriber::create([
        //         'user_id' => auth()->user()->id,
        //         'plan_id' => $id->id,
        //         'status' => 'Pending',
        //         'created_at' => now(),
        //         'gateway' => 'Paddle',
        //         'frequency' => $id->payment_frequency,
        //         'plan_name' => $id->plan_name,
        //         'words' => $id->words,
        //         'images' => $id->images,
        //         'characters' => $id->characters,
        //         'minutes' => $id->minutes,
        //         'subscription_id' => $order_id,
        //         'active_until' => Carbon::now()->addDays($days),
        //     ]);       
    
    
        //     $record_payment = new Payment();
        //     $record_payment->user_id = auth()->user()->id;
        //     $record_payment->order_id = $order_id;
        //     $record_payment->plan_id = $id->id;
        //     $record_payment->plan_name = $id->plan_name;
        //     $record_payment->frequency = $id->payment_frequency;
        //     $record_payment->price = $id->price;
        //     $record_payment->currency = $id->currency;
        //     $record_payment->gateway = 'Paddle';
        //     $record_payment->status = 'pending';
        //     $record_payment->words = $id->words;
        //     $record_payment->images = $id->images;
        //     $record_payment->characters = $id->characters;
        //     $record_payment->minutes = $id->minutes;
        //     $record_payment->save();
            
        //     $redirect = $payment->response->url;
        //     $plan_name = $id->plan_name;
        //     return view('user.plans.paddle-checkout', compact('redirect', 'plan_name'));

        // } else {
        //     toastr()->error(__('Payment was not successful, please verify your paddle gateway settings: ') . $payment->error->message);
        //     return redirect()->back();
        // }
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
        $total_value = $tax_value + $id->price;
        
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

        $iyzicoRequest = new \Iyzipay\Request\CreatePayWithIyzicoInitializeRequest();
        $iyzicoRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $iyzicoRequest->setConversationId(Str::random(9));
        $iyzicoRequest->setPrice($total_value);
        $iyzicoRequest->setPaidPrice($total_value);
        $iyzicoRequest->setCurrency(\Iyzipay\Model\Currency::TL);
        $iyzicoRequest->setBasketId(Str::random(6));
        $iyzicoRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $iyzicoRequest->setCallbackUrl(route('user.payments.approved.iyzico'));
        $iyzicoRequest->setEnabledInstallments(array(2, 3, 6, 9));
        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId(Str::random(5));
        $buyer->setName($request->name ?? "");
        $buyer->setSurname($request->lastname ?? "");
        $buyer->setEmail($request->email ?? "");
        $buyer->setGsmNumber($request->phone ?? "");
        $buyer->setIdentityNumber($request->identity ?? "");
        $buyer->setRegistrationAddress($request->address ?? "");
        $buyer->setCity($request->city ?? "");
        $buyer->setCountry($request->country ?? "");
        $buyer->setZipCode($request->zipcode ?? "");
        $iyzicoRequest->setBuyer($buyer);
        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($request->name . ' ' . $request->lastname);
        $billingAddress->setCity($request->city ?? "");
        $billingAddress->setCountry($request->country ?? "");
        $billingAddress->setAddress($request->address ?? "");
        $billingAddress->setZipCode($request->zipcode ?? "");
        $iyzicoRequest->setBillingAddress($billingAddress);
        $basketItems = array();
        $secondBasketItem = new \Iyzipay\Model\BasketItem();
        $secondBasketItem->setId(Str::random(5));
        $secondBasketItem->setName($id->plan_name);
        $secondBasketItem->setCategory1("Prepaid Plan");
        $secondBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $secondBasketItem->setPrice($total_value);
        $basketItems[0] = $secondBasketItem;
        $iyzicoRequest->setBasketItems($basketItems);
        # make request
        $payment = new PaymentWebhook();
        $process = $payment->download();
        if (!$process['status']) return false;
        $payWithIyzicoInitialize = \Iyzipay\Model\PayWithIyzicoInitialize::create($iyzicoRequest, $options);

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
            $response = json_decode($payWithIyzico->getRawResult());

            $order_id = $response->paymentId;

            if (config('payment.referral.enabled') == 'on') {
                if (config('payment.referral.payment.policy') == 'first') {
                    if (Payment::where('user_id', auth()->user()->id)->where('status', 'completed')->exists()) {
                        /** User already has at least 1 payment */
                    } else {
                        event(new PaymentReferrerBonus(auth()->user(), $order_id, $response->paidPrice, 'Iyzico'));
                    }
                } else {
                    event(new PaymentReferrerBonus(auth()->user(), $order_id, $response->paidPrice, 'Iyzico'));
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
                    'gateway' => 'Iyzico',
                    'frequency' => 'lifetime',
                    'plan_name' => $plan->plan_name,
                    'words' => $plan->words,
                    'dalle_images' => $plan->dalle_images,
                    'sd_images' => $plan->sd_images,
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
            $record_payment->price = $response->paidPrice;
            $record_payment->currency = $response->currency;
            $record_payment->gateway = 'Iyzico';
            $record_payment->status = 'completed';
            $record_payment->words = $plan->words;
            $record_payment->dalle_images = $plan->dalle_images;
            $record_payment->sd_images = $plan->sd_images;
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
                $user->available_dalle_images_prepaid = $user->available_dalle_images_prepaid + $plan->dalle_images;
                $user->available_sd_images_prepaid = $user->available_sd_images_prepaid + $plan->sd_images;
                $user->available_chars_prepaid = $user->available_chars_prepaid + $plan->characters;
                $user->available_minutes_prepaid = $user->available_minutes_prepaid + $plan->minutes;
            }

            $user->save();

            event(new PaymentProcessed(auth()->user()));

            $admin = User::where('group', 'admin')->first();

            try {
                Mail::to($admin)->send(new NewPaymentNotification($record_payment));
                Mail::to($request->user())->send(new PaymentSuccess($record_payment));
            } catch (Exception $e) {
                \Log::info('SMTP settings are not setup to send payment notifications via email');
            }

            return view('user.plans.success', compact('plan', 'order_id'));

        } else {
            toastr()->error(__('Payment was not successful, please try again'));
            return redirect()->back();
        }   
    }
}