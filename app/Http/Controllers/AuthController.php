<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\AgeGroup;
use Validator;
use Mail;
use Carbon\Carbon;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    public $email;

    public function __construct() {
     date_default_timezone_set('Asia/Calcutta'); 
        $this->middleware('auth:api', ['except' => ['login','login_otp','admin_login','admin_login_otp', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
	
    public function login_otp(Request $request){

       

        $user = User::withTrashed()->where('mobile',$request->mobile)->with('roles')->first();

        if($user){
            if($user->id == 1){
                $userToken=auth()->login($user);
                     return response()->json([
                'message' => 'User Loggen in',
                'user' => $user,
                'token' => $userToken,
   
            ], 201);
            }
        if($user->hasRole('user')){
        
            $status = true;
            $orders = Order::where('user_id',$user->id)->get();
            foreach ($orders as $order) {
                if($order->status =='success'){
  
                    if($guest_token =  $this->generateGuestToken()){
                        $response = $this->sendOtp($guest_token,$request->mobile,$request->country_code);
                        if($response){
                          return response()->json([
                                'message' => 'Success',
                                'data' => $response,
                    

                                'guest_token' => $guest_token,
                            ], 200);

                        }
                    }
                  } else {
                         $status = false;
                    $order->delete();
                  }
            }

            if($status==false){
              $user->forceDelete();
                return response()->json([
                        'message' => 'Error',
                        'data' => 'User Not Found!!',
                        'status' => false,
                    ], 201);
            }
        }
              if($guest_token =  $this->generateGuestToken()){
                        $response = $this->sendOtp($guest_token,$request->mobile,$request->country_code);
                        if($response){
                          return response()->json([
                                'message' => 'Success',
                                'data' => $response,
                             
                                'guest_token' => $guest_token,
                            ], 200);

                        }
              }
        }else{
            return response()->json([
                        'message' => 'Error',
                        'data' => 'User Not Found!!',
                        'status' => false,
                    ], 201);

        }


    }



    public function login(Request $request){

      

        $response = $this->checkOtp($request->guest_token,$request->mobile,$request->country_code,$request->otp);

 
     

     

        if($response->status == '400'){
            return response()->json([
                'message' => 'Invalid Otp',
                'status' => 400
            ], 400);
        }else{
        
            $auth_token = $response->data->auth_token;
            
            $user = User::with('roles','user_package')->where('mobile',$request->mobile)->first();
            $favcy_user_id = $response->data->user_id;
            $user = User::with('roles','user_package')->where('mobile',$request->mobile)->first();
            if(empty($user->favcy_user_id)){
                
                $user->favcy_user_id = $favcy_user_id;
                $user->update();
            }


            // if(empty($user)){

            //     $user = new User;
            //     $user->mobile = $request->mobile;
            //     $user->save();
            // }

            if (!$userToken=auth()->login($user)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

     
            if($user->user_package){
              $to = Carbon::parse($user->user_package->start_date);
              $current = Carbon::now();
              $length = $to->diffInDays($current);

               $user->today = $length;
            }
         date_default_timezone_set('Asia/Calcutta'); 
         $d=  Carbon::parse(now())->diffInMonths($user->dob);

               $age_gruop =AgeGroup::where('age_group',$d)->first();
               if($age_gruop){
                $user->age = $age_gruop->id;
               }

            return response()->json([
                'message' => 'User Logged in',
                'user' => $user,
                'token' => $userToken,
                   'valid_package'=>$this->package_valid($user),
                 'free_test'=>$this->free_test_vaild($user),
                'auth_token' => $auth_token
            ], 201);


        }


        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        //     'password' => 'required|string|min:6',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // if (! $token = auth()->attempt($validator->validated())) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'child_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
            'dob' => 'required',
            'mobile' => 'required|unique:users,mobile,NULL,id,deleted_at,NULL',
        ]);

            // 'password' => 'required|string|min:6',
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

           $d = Carbon::parse(now())->diffInMonths($request->dob);

            $age_gruop = AgeGroup::where('age_group', $d)->first();

          if(!$age_gruop){
            return response()->json(['message' => 'Your Age in no loger our system', 'data' => [], 'status' => 400], 400);
        }

           $user = User::withTrashed()->where('mobile',$request->mobile)->with('roles')->first();
     
             if($user&&$user->hasRole('user')){
        
            $status = true;
            $orders = Order::where('user_id',$user->id)->get();
             if(!$orders){
             	 $status = false;
             }
            foreach ($orders as $order) {
                if($order->status =='success'){
  
                    if($guest_token =  $this->generateGuestToken()){
                        $response = $this->sendOtp($guest_token,$request->mobile,$request->country_code);
                        if($response){
                          return response()->json([
                                'message' => 'Success',
                                'data' => $response,
                                'guest_token' => $guest_token,
                            ], 200);

                        }
                    }
                  } else {
                         $status = false;
                    $order->delete();
                  }
            }

            if($status==false){
              $user->forceDelete();
            }
        }
    	
        $user = new User;
        $user->name = $request->name;
        $user->child_name = $request->child_name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->dob = $request->dob;
        $user->password = bcrypt($request->password);
        $user->save();
        

        $user->assignRole('user');

           $data = [
            'data' => $user->toArray(),   
            ];

            $this->email = $user->email;

             Mail::send('emails.welcomeMail', $data, function($message) use ($user) {
                $message->to($user->email)->subject('Welcome in Compasstot Family');
                $message->from('no-reply@compasstot.com','Compasstot');
            });
    
    
        Mail::send([], [], function ($message) use ($user) {
          $message->to('admin@compasstot.com')->cc('alwin54889@gmail.com')->from('no-reply@compasstot.com')
            ->subject('Compasstot')
            // here comes what you want
            ->setBody('New User Registered.Name '.$user->name); // assuming text/plain
        // for HTML rich messages
        });
       
   
            if($guest_token =  $this->generateGuestToken()){
                $country_code = 91;
                $response = $this->sendOtp($guest_token,$user->mobile,$country_code);
                if($response){
                  return response()->json([
                        'message' => 'Success',
                        'data' => $response,
                        'guest_token' => $guest_token,
                    ], 201);

                }
            }else{
                 return response()->json([
                    'message' => 'Something went wrong',
                    'error' => true
                ], 201);
            }






        // return response()->json([
        //     'message' => 'User successfully registered',
        //     'user' => $user
        // ], 201);



        // if (!$userToken=auth()->login($user)) {
        //     return response()->json(['error' => 'invalid_credentials'], 401);
        // }

        // return response()->json([
        //     'message' => 'User Loggen in',
        //     'user' => $user,
        //     'token' => $userToken
        // ], 201);




        // $user = User::create(array_merge(
        //             $validator->validated(),
        //             ['password' => bcrypt($request->password)]
        //         ));

    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {

      $data = User::with('roles')->find(Auth::id());
        return response()->json($data);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }







    protected function generateGuestToken(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://staging.favcy.com//api/2.0/auth-token?parent_id=0&start_point=something',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_HTTPHEADER => array(
            'authority: staging.favcy.com',
            'content-length: 0',
            'pragma: no-cache',
            'cache-control: no-cache',
            'accept: application/json',
            'app-id: ayqsmsovcu-1547',
            'appid: ayqsmsovcu-1547',
            'sec-ch-ua-mobile: ?0'
          ),
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        if($response->status == '201'){
            return $response->data->token;
        }else{
           return false;
        }


    }




    protected function sendOtp($guest_token,$mobile,$country_code){

       $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://staging.favcy.com//api/3.1/otp/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('otp_mobile_num' => $mobile,'otp_mobile_cc' => $country_code),
          CURLOPT_HTTPHEADER => array(
            'auth-token:'.$guest_token.''
          ),
        ));

        $response = json_decode(curl_exec($curl));

        return $response;


      

    }






    protected function checkOtp($guest_token,$mobile,$country_code,$otp){

      
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://staging.favcy.com/api/3.1/login',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('otp_mobile_num' => $mobile,'otp_mobile_cc' => $country_code,'otp' => $otp),
          CURLOPT_HTTPHEADER => array(
            'auth-token: '.$guest_token
          ),
        ));

        $response = json_decode(curl_exec($curl));
        
        curl_close($curl);

        return $response;

    }




     public function package_valid($user)
    {
        $user->load('user_packages','user_package');
        $package = $user->user_packages->where('status','active')->first();
        if(!$package){
          return false;
        }
        if($package->end_date  > date('Y-m-d')){
          return true;
        } else {
          return false;
        }
    }



     public function free_test_vaild($user)
    {
        $user->load('free_test');
        if($user->free_test){
          return true;
        } else {
          return false;
        }
    }

    public function profile(Request $request)
    {
      $user = User::find(Auth::id());
         $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'child_name' => 'required|string|between:2,100',
            'email' => 'required|string|email',
        ]);

            // 'password' => 'required|string|min:6',
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user->name = $request->name;
        $user->child_name = $request->child_name;
        $user->email = $request->email;
        $user->update();
          return response()->json([
                    'message' => 'User Update ',
                    'success' => true,
                ], 200);

    }

}
