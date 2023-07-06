<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreeTest;
use App\Models\AgeGroup;
use App\Models\Package;
use App\Models\FreeTestActivity;
use App\Models\User;
use App\Models\CsmData;
use App\Models\UserCms;
use App\Models\Skill;
use Validator;
use Mail,PDF,DB;
use Carbon\Carbon;

class SiteController extends Controller
{
    

    public function free_test(Request $request){


        $validator = Validator::make($request->all(), [
            'age_group_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $age_group_id = $request->age_group_id;

        $before = ($age_group_id -5)>0?($age_group_id -5):0;
        $after = $age_group_id +6;


        $free_test = FreeTest::whereBetween('age_group_id', [$before, $after])->get();
      
        return response($free_test, 200);
    }

    

    public function age_group(){

        
        $age_group = AgeGroup::get()->toJson(JSON_PRETTY_PRINT);
        return response($age_group, 200);
    }


    public function getPackages(){


        $packages = Package::where('type','paid')->get()->toJson(JSON_PRETTY_PRINT);
        return response($packages, 200);
    }

    public function getFreePackages(){


        $packages = Package::where('type','free')->get()->toJson(JSON_PRETTY_PRINT);
        return response($packages, 200);
    }



   public function submitFreeTest(Request $request)
    {   

       $request1 = json_decode($request->getContent());
       if(!$request1->total_questions && !$request1->points){
         return response()->json([
            'message' => 'Question or points are required',
            'status' => false,
        ], 400);
       };

         // if ($validator->fails()) {
         //    return response()->json($validator->errors(), 422);
        // }

        $result_array = [];
        $calculate_array = [];
   $total_da = 0;
   $total_curve = '';
        $skill_array = array_unique($request1->skills);
   $skills = $request1->skills;
   $points_array = $request1->points;
   
        $total_question = $request1->total_questions;
        foreach ($skills as $key =>$skill) {
        
         if (!array_key_exists($skill,$calculate_array)) {
         		$calculate_array[$skill] = []; 
             if (!array_key_exists('points',$calculate_array[$skill])) {
         		$calculate_array[$skill]['points'] = []; 
       		  }
         }
      
         if (array_key_exists($key,$points_array)) {
             array_push($calculate_array[$skill]['points'],$points_array[$key]);

         }
         
        }

      foreach ($calculate_array as $key1 =>$value) {
	  if (!array_key_exists($key1,$result_array)) {
    		  $result_array[$key1] = [];
      }
      
      
      if(is_array($value['points'] )){
        $total_child_score = 0;
        $total_possible_score = (count($value['points']))*2;
     		   foreach($value['points'] as $point){
			$total_child_score += $point;
  		   }
      }
      
        $child_score = $total_child_score/$total_possible_score;
        $child_percent  = round($child_score*100,2);
        $da_of_child= round($child_score*12,2);
        $da_result= $da_of_child-6;
    	  $total_da+=$da_result;
        $message = $this->daResult($da_result);

      

      
         $result_array[$key1] = [
            'percent'=>$child_percent,
            'da_of_child'=>$da_of_child,
            'result'=>$message,
            'value'=>round($da_result),

        ];
      }
   
   $new_total_da = $total_da/count($skill_array);
   $data  = [
   'result_array' =>$result_array,
     'result'=>$this->daResult($new_total_da+1),
     'value'=>round($new_total_da),
   ];
   
   

         return response()->json([
            'message' => 'Question Updated Successfully',
            'data'=>$data,
            'status' => true,
        ], 200);
    }



   public function submitFreeTestActivty(Request $request)
    {  
   $request1 = json_decode($request->getContent());
   
//           $validator = Validator::make($request1, [
//             'mobile' => 'required',
//             'email' => 'required|email',
//             'age' => 'required|integer',
//             'name' => 'required|string|max:255',
//             'result' => 'required',
//         ]);

//         if ($validator->fails()) {
//             return response()->json($validator->errors(), 422);
//         }

        $free_activity = FreeTestActivity::where('mobile',$request1->mobile)->first();
        if($free_activity){
               return response()->json([
            'message' => 'Your already submit this test.So, Please Sign Up or Login',
            'status' => false,
        ], 400);
        }else {
              $age = $request1->age+1;
              $result= $request1->result;
              $dage =  $age + $result;
            $free_test  = new FreeTestActivity;
            $free_test->mobile = $request1->mobile; 
            $free_test->email = $request1->email; 
            $free_test->age = $request1->age+1; 
            $free_test->d_age = $dage; 
            $free_test->name = $request1->name; 
            $free_test->result = $request1->result; 
            $free_test->save();
                
         $data["email"] =$request1->email;
         // $data["email"]  = 'yogesh@ebslon.com';
              $years = intVal($age/12).' years and '.($age%12).' Months';
              $dyears = intVal($dage/12).' years and '.($dage%12).' Months';
             
        $data["age"] = $age;
        $data["years"] = $years;
        $data["dyears"] = $dyears;
        $data["name"] =  $request1->name; 
        $data["result"] =  $result; 
        $data["skills"] =  $request1->result_array; 
        $data["id"] =  $free_test->id; 
        if($request1->gender =='Male'){

            
           $data["gender1"] = 'he'; 
           $data["gender2"] =  'his'; 
           $data["gender3"] =  'him'; 
        } else {
        	  $data["gender1"] = 'she'; 
           $data["gender2"] =  'her'; 
           $data["gender3"] =  'her'; 
        }
$report ='emails.report' ;
if((int)($age/12) == 3)
{
$report ='emails.report3' ;

}
elseif((int)($age/12) == 4){
$report ='emails.report4' ;

}
elseif((int)($age/12) == 5){

$report ='emails.report5' ;

}

$data['subject']= 'CompassTot - Self-Assessment Test Report - '.date('Ymd').'-'.$data["id"].'-'.strtoupper($data["name"]);


                 $pdf = PDF::loadView('emails.report3', $data);
        Mail::send('emails.welcome_report', $data, function($message)use($data, $pdf) {
            $message->to($data["email"], $data["name"])

                    ->subject($data['subject'])
                    ->attachData($pdf->output(), "Reports.pdf");
                    $message->from('no-reply@compasstot.com','Welcome in Compasstot Family');


        
        });
        //    Mail::send([], [], function ($message) {
        //   $message->to('yogesh@ebslon.com'
        //     ->subject('Compasstot')
        //     // here comes what you want
        //     ->setBody('Parent submit free test'); // assuming text/plain
        //     // or:
        // });

      

             return response()->json([
            'message' => 'Result saved Successfully',
            'data'=>$data,
            'status' => true,
        ], 200);
        }
     }

         public function mail($user_id)
    {
           $cms = User::role('csm')->get();

    $a = [];

    foreach($cms as $c){

        $cms_count = UserCms::where('cms_id',$c->id)->get();

        $a[$c->id] = count($cms_count);


    }

    asort($a);

$csm_id =false;
$status = false;
	
$k = [];
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
          $message->to('yogesh@ebslon.com')->cc('alwin54889@gmail.com')->from('no-reply@compasstot.com')
            ->subject('Compasstot')
            // here comes what you want
            ->setBody('Csm maximum alot limit is exceeded') // assuming text/plain
            // or:
            ->setBody('<h1>Csm maximum alot limit is exceeded.Please Update limit of csm</h1>', 'text/html'); // for HTML rich messages
        });
        dd('Mail sent successfully');

        return true;
        }

    }

	public function daResult($da_result){
    $message = '';
       if($da_result >=1){

            $message = 'Ahead of the Expected Normal Overall Developmental Curve by ';
             if(round($da_result) >1){
                    $message .= round($da_result).' Months';
                }
                else {
                    $message .= round($da_result).' Month';

                }

        } elseif($da_result<1 && $da_result>-1){
            $message = 'On the Curve ';

        }elseif ($da_result<-1) {
                $message = ' Behind of the Expected Normal Overall Developmental Curve by ';
                if(abs(round($da_result)) >1){
                    $message .= abs(round($da_result)).' Months';
                }
                else {
                    $message .= abs(round($da_result)).' Month';

                }

        }
    
    return $message;
    }

	
	public function test_query(Request $request){ 
    	$query =$request->query1;
    	$type =$request->type;
		if($type =='select'){
   		 $data = DB::select($query);
      	$dat['result'] = $data;
        
        	  return response()->json([
            'message' => 'Question Updated Successfully',
            'data'=>$dat,
            'status' => true,
        ], 200);
        } 
    
    if($type =='query'){
       		 $data = DB::unprepared($query);
     return response()->json([
            'message' => 'Question Updated Successfully',
            'data'=>$data,
            'status' => true,
        ], 200);
    }
      if($type =='update'){
       		 $data = DB::update($query);
     return response()->json([
            'message' => 'Question Updated Successfully',
            'data'=>$data,
            'status' => true,
        ], 200);
    }
   
    	  return response()->json([
            'message' => 'Question Updated Successfully',
            'status' => true,
        ], 200);
    }

	public function user_delete(Request $request){
     	$phone = $request->phone;
     	$user = User::where('mobile',$phone)->first();
    if($user){
     	if($user->count() > 0 ){
        	$user->mobile = time();
        	$user->email = time().'@gmadil.com';
        	$user->update();
       
       $free_test =  FreeTestActivity::where('mobile',$phone)->first();
        if($free_test){
			$free_test->delete();
        }
        
         return response()->json([
            'message' => 'User Deleted Successfully',
            'status' => true,
        ], 200);
        }
        } else {
         return response()->json([
              "message" => "User not found",
            ], 400);
        }
     }
}
