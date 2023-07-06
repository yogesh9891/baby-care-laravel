<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Package;
use App\Models\UserPackage;
use App\Models\UserCms;
use App\Models\Transaction;
use App\Models\CsmData;
use Validator;
use DB;
use Mail;

class OrderController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['assignUserCsm']]);
    	 date_default_timezone_set('Asia/Calcutta'); 
    }


    public function create_order(Request $request){

        $validator = Validator::make($request->all(), [
            'auth_token' => 'required',
            'type' => 'required',
            'package_id' => 'required',
        ]);


        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }


        if($this->checkFavcyUser($request->auth_token)){

                $package = Package::find($request->package_id);
                if(!$package){
                    return response()->json([
                        'message' => 'No Package Found !!!',
                        'error' => 'Error'
                    ], 404);
                }


                $amount = $package->price;
                $discount = 0;

                if(!empty($request->coupon_code)){


                    $coupon = Coupon::where('coupon_code',$request->coupon_code)->first();

                    if(empty($coupon)){
                        return response()->json([
                            'message' => 'Coupon Invalid',
                            'error' => 'Error'
                        ], 404);
                    }

                    $orderCoupon = Order::where('coupon_id',$request->code)->get();
                    $orderUserCoupon = Order::where('coupon_id',$request->code)->where('user_id',Auth::user()->id)->where('status','completed')->get();

                    $currentDate = date('Y-m-d');

                    if($coupon->start_date){

                        if ($currentDate <= $coupon->start_date){
                            return response()->json([
                                'message' => 'Coupon Invalid',
                                'error' => 'Error'
                            ], 404);

                        }
                    }

                    if($coupon->end_date){

                        if ($currentDate >= $coupon->end_date){ 
                            return response()->json([
                                'message' => 'Coupon Invalid',
                                'error' => 'Error'
                            ], 404);

                        }
                    }


                    if($coupon->minimum_spend){
                        if($coupon->minimum_spend >= $amount){

                            return response()->json([
                                'message' => 'Order Value must be greater than '.$coupon->minimum_spend.'',
                                'error' => 'Error'
                            ], 404);

                        }
                    }


                    if($coupon->maximum_spend){
                        if($coupon->maximum_spend < $amount){

                            return response()->json([
                                'message' => 'Order Value must be less than '.$coupon->maximum_spend.'',
                                'error' => 'Error'
                            ], 404);

                        }
                    }


                    if($coupon->usage_limit_per_coupon){
                        if($coupon->usage_limit_per_coupon <= count($orderCoupon)){

                            return response()->json([
                                'message' => 'Coupon limit exceeded',
                                'error' => 'Error'
                            ], 404);

                        }
                    }


                    if($coupon->usage_limit_per_coupon){
                        if($coupon->usage_limit_per_user <= count($orderUserCoupon)){

                            return response()->json([
                                'message' => 'You have exceeded the coupon limit',
                                'error' => 'Error'
                            ], 404);

                        }
                    }




                    if($coupon->type == 'fixed'){

                        $discount = $coupon->value;
                    }else{

                        $discount = ($amount * $coupon->value)/100;
                    }

                    

                }


            

                if($package->type == 'free'){

                    $user_package = UserPackage::with('package')->where('user_id',Auth::user()->id)->where('status','active')->first();
                  if($user_package){
                        if($user_package->package && $user_package->package->type == 'free'){
                               return response()->json([
                                'message' => 'You cannot buy trial package again',
                                'error' => 'Error'
                            ], 404);
                      
                  }
                 }
                    $payment_method = 'not available';
                    $status = 'success';
                }else{
                    $payment_method = 'razorpay';
                    $status = 'pending_payment';
                }

                $user = Auth::user();

                $order = new Order;
                $order->user_id = $user->id;
                $order->favcy_user_id = $user->favcy_user_id;
                $order->package_id = $request->package_id;
                $order->name = $user->name;
                $order->email = $user->email;
                $order->phone = $user->phone;
                $order->sub_total = $amount;
                $order->coupon_id = $request->coupon_code ? $request->coupon_code: '' ;
                $order->discount = $discount;
                $order->total = $amount-$discount;
                $order->payment_method = $payment_method;
                $order->currency = 'INR';
                $order->status = $status;
                $order->save();

                if($order){


                    if($order->status == 'success'){

                        $old_package = UserPackage::where('user_id',Auth::user()->id)->where('status','active')->first();
                        if($old_package){
                            $old_package->status= 'Inactive';
                            $old_package->update();
                        }
                        $user_package = new UserPackage;

                        $user_package->user_id = Auth::user()->id;
                        $user_package->order_id = $order->id;
                        $user_package->package_id = $order->package_id;
                        $user_package->start_date = date('Y-m-d');
                        $user_package->end_date = date('Y-m-d',strtotime('+'.$package->duration_days.' day'));
                        $user_package->status = 'active';
                        $user_package->save();


                        $this->assignUserCsm(Auth::user()->id);


                    }



                    return response()->json([
                        'message' => 'Order Created',
                        'success' => true,
                        'order_id' => $order->id, 
                        'favcy_user_id' => $user->favcy_user_id, 
                         
                    ], 200);


                }else{
                    return response()->json([
                        'message' => 'Something went wrong !!!',
                        'error' => 'Unknown'
                    ], 404);
                }


        }else{


             return response()->json([
                'message' => 'Token expired please login first !!!',
                'error' => 'Unauthorized'
            ], 401);

        }



    }



    protected function checkFavcyUser($token){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://staging.favcy.com/api/3.0/users/me',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Auth-token:'.$token.''
          ),
        ));

        $response = json_decode(curl_exec($curl));

        if(isset($response->data)){
            return true;
        }

        return false;
    }




   public function getOrder($id){


    $order = Order::find($id);
    $order->total =   $order->total*100;
        return response($order->toJson(JSON_PRETTY_PRINT), 200);

   }


   public function updateOrder(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'favcy_transaction_id' => 'required',
            'order_id' => 'required',
            'status' => 'required',
        ]);


        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }



        $order = Order::find($request->order_id);

        if($order){

            $order->status = $request->status;
            $order->update();

            $transaction = new Transaction;

            $transaction->user_id = Auth::user()->id;
            $transaction->order_id = $request->order_id;
            $transaction->transaction_id = $request->favcy_transaction_id;
            $transaction->payment_method = $request->payment_method?$request->payment_method:'razorpay';
            $transaction->payment_status = $request->status;

            $transaction->save();

            if($request->status == 'success'){

                $package = Package::find($order->package_id);
				   $old_package = UserPackage::where('user_id',Auth::user()->id)->where('status','active')->first();
                        if($old_package){
                            $old_package->status= 'Inactive';
                            $old_package->update();
                        }
                $user_package = new UserPackage;

                $user_package->user_id = Auth::user()->id;
                $user_package->order_id = $request->order_id;
                $user_package->package_id = $order->package_id;
                $user_package->start_date = date('Y-m-d');
                $user_package->end_date = date('Y-m-d',strtotime('+'.$package->duration_days.' day'));
                $user_package->status = 'active';

                $user_package->save();

                $this->assignUserCsm(Auth::user()->id);


            }
                
            

            return response()->json([
                'message' => 'Successfull',
                'success' => 'true',
                'data' => $order
            ], 200);



        }else{
            return response()->json([
                'message' => 'Something went wrong !!!',
                'error' => 'error'
            ], 404);
        }






   }
    





   protected function assignUserCsm($user_id){
   $cms = User::role('csm')->get();

    $a = [];

    foreach($cms as $c){

        $cms_count = UserCms::where('cms_id',$c->id)->get();

        $a[$c->id] = count($cms_count);


    }

    asort($a);

$csm_id =false;
$status = false;
	

    foreach($a as $key=> $b){

        $checkLimit = CsmData::where('user_id',$key)->first();
    if($checkLimit){
    
        if($b < (int)$checkLimit->no_of_children_assigned){

            // $data = new UserCms;
            // $data->user_id = $user_id;
            // $data->cms_id = $key;
            // $data->save();
        $user = UserCms::where('user_id',$user_id)->where('cms_id',$key)->first();
        if(!$user){
            $csm_id = $key;
            $status = true;
             break;
        }
        }
    }
    }
         

if($csm_id && $status){
	


     $data = new UserCms;
            $data->user_id = $user_id;
            $data->cms_id = $csm_id;
            $data->save();
              return true;
        } else {

        $c =     array_keys($a, min($a));
             $data = new UserCms;
            $data->user_id = $user_id;
            $data->cms_id = $c[0];
            $data->save();
          $d =   CsmData::where('user_id',$c[0])->first();
          $d->no_of_children_assigned =min($a)+1;
          $d->update();


        Mail::send([], [], function ($message) {
          $message->to('admin@compasstot.com')->cc('alwin54889@gmail.com')->from('no-reply@compasstot.com')
            ->subject('Compasstot')
            // here comes what you want
            ->setBody('Csm maximum alot limit is exceeded') // assuming text/plain
            // or:
            ->setBody('<h1>Csm maximum alot limit is exceeded.Please Update limit of csm</h1>', 'text/html'); // for HTML rich messages
        });
       

        return true;
        }
}






}
