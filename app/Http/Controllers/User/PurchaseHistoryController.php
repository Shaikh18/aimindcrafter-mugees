<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Subscriber;
use App\Models\Payment;
use App\Models\User;
use DataTables;
use Carbon\Carbon;

class PurchaseHistoryController extends Controller
{
    /**
     * List all user payments
     */
    public function index(Request $request)
    {   
        if ($request->ajax()) {
            $data = Payment::where('user_id', Auth::user()->id)->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        if ($row["gateway"] == 'BankTransfer') {
                            $actionBtn = '<div>                                            
                                        <a href="'. route("user.purchases.show", $row["id"] ). '"><i class="fa-solid fa-file-invoice-dollar table-action-buttons view-action-button" title="'. __('View Transaction') .'"></i></a>
                                        <a class="uploadConfirmation" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-file-upload table-action-buttons edit-action-button" title="'. __('Upload Confirmation') .'"></i></a>
                                    </div>';
                        } else {
                            $actionBtn = '<div>                                            
                                        <a href="'. route("user.purchases.show", $row["id"] ). '"><i class="fa-solid fa-file-invoice-dollar table-action-buttons view-action-button" title="'. __('View Transaction') .'"></i></a>
                                    </div>';
                        }
                        
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["created_at"], 'd/m/Y').'</span><br><span>'.date_format($row["created_at"], 'H:i A').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-status', function($row){
                        $custom_status = '<span class="cell-box payment-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-frequency', function($row){
                        $custom_status = '<span class="cell-box payment-'.strtolower($row["frequency"]).'">'.ucfirst($row["frequency"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-words', function($row){
                        $words = ($row["words"] == -1) ? __('Unlimited') : number_format($row["words"]);
                        $custom_words = '<span>'.$words.'</span>';
                        return $custom_words;
                    })
                    ->addColumn('custom-order', function($row){
                        $custom_storage = '<span>'.$row["order_id"].'</span>';
                        return $custom_storage;
                    })
                    ->addColumn('custom-plan-name', function($row){
                        $custom_status = '<span class="font-weight-bold">'.ucfirst($row["plan_name"]).'</span><br><span class="text-muted">'.$row["price"] . ' ' .$row['currency'].'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-gateway', function($row){
                        switch ($row['gateway']) {
                            case 'PayPal':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="PayPal Gateway" class="w-30" src="' . URL::asset('img/payments/paypal.svg') . '"></div>';                             
                                break;
                            case 'Stripe':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Stripe Gateway" class="w-20" src="' . URL::asset('img/payments/stripe.svg') . '"></div>';
                                break;
                            case 'Paystack':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Paystack Gateway" class="w-40" src="' . URL::asset('img/payments/paystack.svg') . '"></div>';
                                break;
                            case 'Razorpay':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Razorpay Gateway" class="w-40" src="' . URL::asset('img/payments/razorpay.svg') . '"></div>';
                                break;
                            case 'BankTransfer':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="BankTransfer Gateway" class="w-40" src="' . URL::asset('img/payments/bank-transfer.png') . '"></div>';
                                break;
                            case 'Coinbase':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Coinbase Gateway" class="w-40" src="' . URL::asset('img/payments/coinbase.svg') . '"></div>';
                                break;
                            case 'Mollie':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Mollie Gateway" class="w-40" src="' . URL::asset('img/payments/mollie.svg') . '"></div>';
                                break;
                            case 'Braintree':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Braintree Gateway" class="w-40" src="' . URL::asset('img/payments/braintree.svg') . '"></div>';
                                break;
                            case 'Midtrans':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Midtrans Gateway" class="w-40" src="' . URL::asset('img/payments/midtrans.png') . '"></div>';
                                break;
                            case 'Flutterwave':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Flutterwave Gateway" class="w-40" src="' . URL::asset('img/payments/flutterwave.svg') . '"></div>';
                                break; 
                            case 'Yookassa':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Yookassa Gateway" class="w-40" src="' . URL::asset('img/payments/yookassa.svg') . '"></div>';
                                break;
                            case 'Paddle':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Paddle Gateway" class="w-40" src="' . URL::asset('img/payments/paddle.svg') . '"></div>';
                                break;
                            case 'Iyzico':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Iyzico Gateway" class="w-40" src="' . URL::asset('img/payments/iyzico.svg') . '"></div>';
                                break;
                            case 'TwoCheckout':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="TwoCheckout Gateway" class="w-40" src="' . URL::asset('img/payments/twocheckout.svg') . '"></div>';
                                break;
                            case 'Manual':
                                $custom_gateway = '<div>Manual Assign</div>';
                                break;
                            case 'FREE':
                                $custom_gateway = '<div>Free Plan</div>';
                                break;
                            default:
                                $custom_gateway = '<div class="overflow-hidden">Unknown</div>';
                                break;
                        }
                        
                        return $custom_gateway;
                    })
                    ->rawColumns(['actions', 'custom-status', 'created-on', 'custom-gateway', 'custom-amount', 'custom-plan-name', 'custom-order', 'custom-frequency', 'custom-words'])
                    ->make(true);
                    
        }

        return view('user.purchase.index');
    }


    /**
     * List user susbsriptions
     */
    public function subscriptions(Request $request)
    {        
        if ($request->ajax()) {
            $data = Subscriber::select('subscribers.*', 'subscription_plans.plan_name')->join('subscription_plans', 'subscription_plans.id', '=', 'subscribers.plan_id')->where('subscribers.user_id', Auth::user()->id)->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn =  '<div>                                            
                                            <a class="cancelSubscriptionButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-file-slash table-action-buttons delete-action-button" title="'. __('Cancel Subscription') .'"></i></a>
                                        </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["created_at"], 'd/m/Y').'</span><br><span>'.date_format($row["created_at"], 'H:i A').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-until', function($row){
                        $custom_until = '<span class="font-weight-bold">'.date_format(Carbon::parse($row["active_until"]), 'd/m/Y').'</span><br><span>'.date_format(Carbon::parse($row["active_until"]), 'H:i A').'</span>';
                        return $custom_until;
                    })
                    ->addColumn('custom-subscription-id', function($row){
                        $custom = '<span>'.$row["subscription_id"].'</span>';
                        return $custom;
                    })
                    ->addColumn('custom-status', function($row){
                        $custom_status = '<span class="cell-box subscription-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-frequency', function($row){
                        $custom_status = '<span class="cell-box payment-'.strtolower($row["frequency"]).'">'.ucfirst($row["frequency"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-plan-name', function($row){
                        $words = ($row["words"] == -1) ? __('Unlimited') : number_format($row["words"]);
                        $custom_status = '<span class="font-weight-bold">'.ucfirst($row["plan_name"]).'</span><br><span class="text-muted">'. $words . ' ' . __('words') .'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-gateway', function($row){
                        switch ($row['gateway']) {
                            case 'PayPal':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="PayPal Gateway" class="w-30" src="' . URL::asset('img/payments/paypal.svg') . '"></div>';                             
                                break;
                            case 'Stripe':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Stripe Gateway" class="w-20" src="' . URL::asset('img/payments/stripe.svg') . '"></div>';
                                break;
                            case 'Paystack':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Paystack Gateway" class="w-30" src="' . URL::asset('img/payments/paystack.svg') . '"></div>';
                                break;
                            case 'Razorpay':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Razorpay Gateway" class="w-30" src="' . URL::asset('img/payments/razorpay.svg') . '"></div>';
                                break;
                            case 'BankTransfer':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="BankTransfer Gateway" class="w-30" src="' . URL::asset('img/payments/bank-transfer.png') . '"></div>';
                                break;
                            case 'Mollie':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Mollie Gateway" class="w-40" src="' . URL::asset('img/payments/mollie.svg') . '"></div>';
                                break;
                            case 'Midtrans':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Midtrans Gateway" class="w-40" src="' . URL::asset('img/payments/midtrans.png') . '"></div>';
                                break;
                            case 'Flutterwave':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Flutterwave Gateway" class="w-40" src="' . URL::asset('img/payments/flutterwave.svg') . '"></div>';
                                break; 
                            case 'Yookassa':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Yookassa Gateway" class="w-40" src="' . URL::asset('img/payments/yookassa.svg') . '"></div>';
                                break;
                            case 'Paddle':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Paddle Gateway" class="w-40" src="' . URL::asset('img/payments/paddle.svg') . '"></div>';
                                break;
                            case 'Iyzico':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Iyzico Gateway" class="w-40" src="' . URL::asset('img/payments/iyzico.svg') . '"></div>';
                                break;
                            case 'TwoCheckout':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="TwoCheckout Gateway" class="w-40" src="' . URL::asset('img/payments/twocheckout.svg') . '"></div>';
                                break;
                            case 'Manual':
                                $custom_gateway = '<div>Manual Assign</div>';
                                break;
                            case 'FREE':
                                $custom_gateway = '<div>Free Plan</div>';
                                break;
                            default:
                                $custom_gateway = '<div class="overflow-hidden">Unknown</div>';
                                break;
                        }
                        
                        return $custom_gateway;
                    })
                    ->rawColumns(['actions', 'custom-status', 'created-on', 'custom-gateway', 'custom-until', 'custom-plan-name', 'custom-subscription-id', 'custom-frequency'])
                    ->make(true);
                    
        }

        return view('user.purchase.subscription');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $id)
    {
        if ($id->user_id == Auth::user()->id){

            return view('user.purchase.show', compact('id'));     

        } else{
            return redirect()->route('user.purchases');
        }
    }


    public function uploadConfirmation(Request $request) {

        if (request()->has('confirmation')) {
        
            try {
                request()->validate([
                    'confirmation' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5048'
                ]);
                
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'PHP FileInfo: ' . $e->getMessage());
            }
            
            $invoice = request()->file('confirmation');

            $name = Str::random(15);

            $payment = Payment::find(request('id'));

            $folder = 'storage/';
            
            $filePath = $folder . $name . '.' . $invoice->getClientOriginalExtension();
            
            $this->uploadImage($invoice, $folder, 'public', $name);
            
            $payment->invoice = $filePath;
            $payment->save();

            return  response()->json('success');
        }
    }


    /**
     * Upload voice avatar image
     */
    public function uploadImage(UploadedFile $file, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $image = $file->storeAs($folder, $name .'.'. $file->getClientOriginalExtension(), $disk);

        return $image;
    }

}
