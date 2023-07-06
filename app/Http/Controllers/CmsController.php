<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CsmData;
use App\Models\UserCms;
use App\Models\Skill;
use App\Models\Milestone;
use App\Models\Activity;
use App\Models\Level;
use App\Models\LevelOption;
use App\Models\AgeGroup;
use App\Models\UserPackage;
use App\Models\Package;
use App\Models\UserActivity;
use App\Models\CustomActivity;
use Auth,StdClass,Validator,Mail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Services\UserService;
class CmsController extends Controller
{
    public function __construct() {
         date_default_timezone_set('Asia/Calcutta'); 
        $this->middleware('auth');
    }



    public function index(){

        $user = User::role('csm')->get()->toJson(JSON_PRETTY_PRINT);
        return response($user, 200);

    }




    public function store(Request $request){


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'mobile' => 'required|unique:users',
        ]);

            // 'password' => 'required|string|min:6',
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }




        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->save();
        
        $csm = new CsmData;
        $csm->user_id = $user->id;
        $csm->employment_type = $request->employment_type;
        $csm->no_of_children_assigned = $request->no_of_children_assigned;
        $csm->save();

        Mail::send([], [], function ($message) use($user) {
          $message->to($user->email)->subject('Compasstot')->from('admin@compasstot.com')->setBody('Csm Assigned') // assuming text/plain
            // or:
            ->setBody('<h1>Congrats! You are assigned as Csm</h1>', 'text/html'); // for HTML rich messages
        });
     Mail::send([], [], function ($message) use($user) {
          $message->to('admin@compasstot.com')->cc('alwin54889@gmail.com')->from('no-reply@compasstot.com')->subject('Compasstot')->subject('Compasstot')->setBody('New Csm Assigned') // assuming text/plain
            // or:
            ->setBody('<h1>Congrats! '.$user->name.' new CSM Assigned </h1>', 'text/html'); // for HTML rich messages
        });
        $user->assignRole('csm');

         return response()->json([
            'message' => 'CSM Added Successfully',
            'status' => 200
        ], 200);
 

    }



    public function update(Request $request,$id){


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'mobile' => 'required|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->mobile = $request->mobile;
        $data->dob = $request->dob;
        $data->update();


        $csm = CsmData::where('user_id',$data->id)->first();
        $csm->employment_type = $user->employment_type;
        $csm->no_of_children_assigned = $user->no_of_children_assigned;
        $csm->update();



         return response()->json([
            'message' => 'CSM Updated Successfully',
            'status' => 200
        ], 200);
 

    }



    public function updateCsmUser(Request $request){

          $validator = Validator::make($request->all(), [
            'csm_id' => 'required',
            'user_id' => 'required',
    
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user_id = $request->user_id;
        $csm_id = $request->csm_id;
        $user = UserCms::where('user_id',$user_id)->first();
    if($user) {
    
        $user->cms_id = $csm_id;
        $user->update();

            return response()->json([
            'message' => 'User CSM Updated Successfully',
            'status' => 200
        ], 200);
    } else {
        $user = new UserCms;
        $user->cms_id = $csm_id;
        $user->user_id = $user_id;
        $user->save();
        return response()->json([
            'message' => 'User CSM Added Successfully',
            'status' => 200
        ], 200);
    }
 



    }



   


    public function getCsm($id){
         $data = User::find($id);


        if($data){

            return response()->json([
                'message' => 'View CSM',
                'data' => $data,
                'status' => 200
            ], 200);
        }else{
            return response()->json([
                'message' => 'Wrong CSM id',
                'status' => 201
            ], 201);
        }

    }


    public function allUsers(){
         $data = User::role('user')->get();


        if($data){

            return response()->json([
                'message' => 'all users',
                'data' => $data,
                'status' => 200
            ], 200);
        }else{
            return response()->json([
                'message' => 'No Users found',
                'status' => 201
            ], 201);
        }

    }
    public function getCsmUsers($id){

      $users = User::with('users')->FindOrfail($id);

        return response()->json([
            'message' => 'Success',
            'data' => $users,        
            'status' => 200
        ], 200);

    }




    public function deleteCsm($id){

        if(User::where('id', $id)->exists()) {
            $data = User::find($id);
            $data->delete();

            return response()->json([
              "message" => "records deleted"
            ], 202);
          } else {
            return response()->json([
              "message" => "record not found"
            ], 404);
          }

    }  

    
    public function getUserCustomPlan($user_id){ 
        
       
        $user = User::role('user')->find($user_id);
        if(!$user) {
          return response()->json([
              "message" => "User not found"
            ], 404);
        }
    
        
         $customs =  CustomActivity::where('user_id',$user->id)->get();
             foreach($customs as $key =>$activtiy) {
             $customs[$key]->delete_status = false;
                  $user_activty =  UserActivity::where('user_id',$user->id)->where('activity_id',$activtiy->activity_id)->first();
                if($user_activty){
                      $customs[$key]->delete_status = true;
                }
             }
     
        if($customs){

            return response()->json([
                'message' => 'Custom Plan',
                'data' => $customs,
                'status' => 200
            ], 200);
        }else{
            return response()->json([
                'message' => 'No Plan found',
                'status' => 201
            ], 201);
        }
        
    
    }
    public function addCustomPlan(Request $request){ 
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'activities' => 'required',
    
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::role('user')->find($request->user_id);
        if(!$user) {
          return response()->json([
              "message" => "User not found"
            ], 404);
        }

    
            $activties = $request->activities;
                $customs =  CustomActivity::where('user_id',$user->id)->get();
                if((count($activties) >0) && $customs->count()==0){
       
                        UserActivity::where('user_id',$user->id)->delete();
                 }

              if($customs->count() > 0){
               $old_custom_activies =  array_column($customs->toArray(), 'activity_id');
              $diff_custom_activies = array_diff($old_custom_activies,$activties);
                 $delete_customs =  CustomActivity::where('user_id',$user_id)->whereIn('activity_id',$diff_custom_activies)->delete();
              }
<<<<<<< HEAD
    		foreach($activties as $key =>$activtiy_id) {
            	$activity = Activity::find($activtiy_id);
=======
            foreach($activties as $key =>$activtiy_id) {
                $activity = Activity::find($activtiy_id);
>>>>>>> new_deployment
            $activties[$key] = (int)$activtiy_id;
             if($activity){ 
                $custom =  CustomActivity::where('user_id',$user->id)->where('activity_id',$activtiy_id)->first();
             if(!$custom){
                
                $mile_stone = Milestone::find($activity->milestone_id);
                $custom_activity = new  CustomActivity;
                $custom_activity->user_id = $user->id;  
                $custom_activity->skill_id = $mile_stone->skill_id;  
                $custom_activity->milestone_id = $activity->milestone_id;  
                $custom_activity->activity_id = $activity->id;  
                $custom_activity->day = $activity->day;  
                $custom_activity->save();
             }
             }
            
            }
    
        $custom_activities = CustomActivity::select('activity_id','user_id')->where('user_id',$user->id)->pluck('activity_id');
    if($custom_activities) {
            $id_array = $custom_activities->toArray();
            $result_array =array_diff($activties,$id_array);
            CustomActivity::whereIn('id',$result_array)->delete();
    }
    
    
        return response()->json([
            'message' => 'Custom Plan created successfully',      
            'status' => 200
        ], 200);
    
    }



    public function getCsmUser(){

        $user_id = Auth::user()->id;
$users = User::with('users')->FindOrfail($user_id);
        // $users = UserCms::where('cms_id',$user_id)->with('user')->get();

        return response()->json([
            'message' => 'Success',
            'data' => $users,        
            'status' => 200
        ], 200);

    }


   public function myProfile(){

        $data = User::FindOrfail(Auth::id());
          
        return response()->json([
            'message' => 'My Profile',
            'data' => $data,        
            'status' => 200
        ], 200);


    }

    public function getCsmUserProfile($id){

        $data = User::with('user_package','my_csm')->find($id);
            $d=  Carbon::parse(now())->diffInMonths($data->dob);

            $data->age = $d;
          $userService = new UserService($data);
            $data->da_age = $userService->getDaAge();
        return response()->json([
            'message' => 'Success',
            'data' => $data,        
            'status' => 200
        ], 200);


    }


    public function updateUserPackage(Request $request){

          $validator = Validator::make($request->all(), [
            'user_package_id' => 'required',
            'package_id' => 'required',
    
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $package_id = $request->package_id;
        $id = $request->user_package_id;
        $user_package = UserPackage::FindOrfail($id);
        $package = Package::FindOrfail($package_id);
          $user_package->start_date = date('Y-m-d');
          $user_package->package_id  = $package->id;
          $user_package->start_date = date('Y-m-d');
                        $user_package->end_date = date('Y-m-d',strtotime('+'.$package->duration_days.' day'));
        $user_package->update();
            return response()->json([
            'message' => 'UserPackage Updated Successfully',
            'status' => 200
        ], 200);
 



    }


    public function userProgress($id){

        $user = User::with('age_group','user_package')->findorFail($id);
 
  $userService = new UserService($user);
        
        $age_arrray = $userService->getAgeList();

        $skills = Skill::select('id', 'name', 'color', 'border_color', 's_no','skill_order')->with('milestones', function ($q) use ($age_arrray)
        {
            // Query the name field in status table
            $q->whereIn('age_group_id', $age_arrray)->whereStatus(1);

        })->whereStatus(1)->get();

    

        if ($skills->count() == 0)
        {
            return response()
                ->json(['message' => 'No Skill found for this age group', 'error' => 'error'], 400);
        }
// dd($skills[2]);
        foreach ($skills as $key => $skill)
        {
        
            $skills[$key]->user_package = $user->user_package ?? null;
            $total_milestones = 0;
            $complete__mile_stone = 0;
            if($userService->IsCustomPlan()){
                  if(!$userService->IsUserSkill($skill->id))
                  {
                  
                        unset($skills[$key]);       
                                    
                   }
                         
                 }
        if(array_key_exists($key,$skills->toArray())){
    
   
        $skill_milestone = $skill->milestones->sortBy('age_group_id');
        if($skill->skill_order =='desc'){
  
            $skill_milestone = $skill->milestones->sortByDesc('age_group_id');
        }
     
    
            foreach ($skill_milestone as $key1 => $milestone)
            {
            
                $mile = milestone_status($milestone->id, $user->id);
                $total_milestones += $milestone->total_milestone;
                $skill_milestone[$key1]->complete_status = $mile['status'];
                $complete__mile_stone += $this->user_total_activity($milestone->id, $user->id);
                $skill_milestone[$key1]->current_activity = $mile['activity'];
            if($userService->IsCustomPlan()){
                  if(!$userService->IsUserMilestone($milestone->id))
                  {
                  
                              unset($skill_milestone[$key1]);
                                       
                   }
                         
                 }
                // $skill->milestones[$key]->totototottot= $total_milestones;
                // $milestone->put('complete_status',$d['status']);
                
            }

            if ($total_milestones > 0)
            {
                $skills[$key]->bar = ($complete__mile_stone / $total_milestones) * 100;

            }
        
           unset($skill->milestones);
            $skill->milestones = array_values($skill_milestone->toArray());
        }
        }
    // $new_Skills =array_values($skills->toArray());
    // dd($new_Skills);
        return response()->json(['message' => 'Age Group Skills', 'data' => $skills->toArray(), 'status' => 200], 200);

    }


  public  function userChart(Request $request,$id)
    {
         
        $user = User::with('age_group','user_package')->findorFail($id);    
<<<<<<< HEAD
       $age = $request->age;
=======
     $age = $request->age;
>>>>>>> new_deployment
        if(!$age){
            return response()->json(['message' => 'Age required', 'data' => [], 'status' => 400], 400);
        }
            $userService = new UserService($user);
            $resultArray =$userService->getUserCurve($age);
<<<<<<< HEAD
            $user->da_age = $resultArray['da_point'] ?? $user->selected_age;
            $user->update();
=======
            // $user->da_age = $resultArray['da_point'] ?? $user->selected_age;
            // $user->update();
            if(isset($resultArray['da_point'])){

            unset($resultArray['da_point']);
            }
>>>>>>> new_deployment
            $user->age = $userService->getBioAge();

            if(isset($resultArray['status']) && $resultArray['status']==false){

             return response()->json(['message' => $resultArray['message'], 'data' => [], 'status' => 400], 400);
            }
  
  
        $data = [];
        $data['user'] = $user;
<<<<<<< HEAD
        $data['month'] = $resultArray;
        return response()->json(['message' => 'Charts', 'data' => $data, 'status' => 200], 200);
    }

       public function user_age()
=======
        $data['month'] = $resultArray['month'];
        return response()->json(['message' => 'Charts', 'data' => $data, 'status' => 200], 200);
    }

       public function user_age($id)
>>>>>>> new_deployment
    {   
         $user = User::with('age_group','user_package')->findorFail($id);   
            $userService = new UserService($user);

<<<<<<< HEAD
        $ageArray = array_values($userService->getAgeList());
          asort($ageArray);
             return response()->json(['message' => 'Age package found','data' =>$ageArray,'status' => true, ], 200);
=======
       $ageArray = array_values($userService->getAgeListFromBioAge());
          asort($ageArray);
             return response()->json(['message' => 'Age package found','data' =>$ageArray,'status' => true, ], 200);


    }
        public function ageChart(Request $request,$id)
    {
        $user = User::with('age_group', 'user_package')->findorFail($id);
             $month =  $request->month;
        if($month ==3 || $month==6){
            $userService = new UserService($user);
            $user->age = $userService->getBioAge();
            $resultArray =$userService->getUserAgeCurve($month);
            if(isset($resultArray['status']) && $resultArray['status']==false){

             return response()->json(['message' => $resultArray['message'], 'data' => [], 'status' => 400], 400);
            }
  
  
        $data = [];
        $data['user'] = $user;
        $data['month'] = $resultArray;
>>>>>>> new_deployment


    }
        public function ageChart(Request $request,$id)
    {
        $user = User::with('age_group', 'user_package')->findorFail($id);
        $month =  $request->month;
        if($month ==3 || $month==6){
            $userService = new UserService($user);
            $user->age = $userService->getBioAge();
            $resultArray =$userService->getUserAgeCurve($month);
            if(isset($resultArray['status']) && $resultArray['status']==false){

             return response()->json(['message' => $resultArray['message'], 'data' => [], 'status' => 400], 400);
            }
  
  
        $data = [];
        $data['user'] = $user;
        $data['month'] = $resultArray;
        return response()->json(['message' => 'Charts', 'data' => $data, 'status' => 200], 200);
        } else {
            return response()->json(['message' => 'Month is Invalid', 'data' => [], 'status' => 400], 400);

        }
    }
  public function user_total_activity($milestone_id,$user_id)
    {
           $activities = Activity::where('milestone_id',$milestone_id)->get();
             $status = true;
        $total = 0;
             foreach($activities as $activity){
                
               $activities = UserActivity::where('activity_id',$activity->id)->where('user_id',$user_id)->where('status',1)->get();
         
                $total += $activities->count();

             }
        return $total;

  }
public function user_milestone($user){

        
  $age=  Carbon::parse(now())->diffInMonths($user->dob);
  $age  = $user->selected_age?$user->selected_age:$age;
   
         $startDate = new Carbon($user->user_package->start_date);
        $endDate =  new Carbon($user->user_package->end_date);
        $diff = $endDate->diffInMonths($startDate);

        
        $age_group = [(int)$age];

for($i=0;$i<$diff;$i++){

    $age++;
    array_push($age_group,$age);


}

        return $age_group;
}

    public function profile(Request $request)
    {
      $user = User::find(Auth::id());
         $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            // 'child_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users,email,'.$user->id,
        ]);

            // 'password' => 'required|string|min:6',
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->update();
          return response()->json([
                    'message' => 'CSM Update ',
                    'success' => true,
                ], 200);

    }
}

