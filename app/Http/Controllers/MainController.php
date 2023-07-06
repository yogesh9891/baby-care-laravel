<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPackage;
use Validator;
use App\Models\User;
use App\Models\Skill;
use App\Models\Milestone;
use App\Models\Activity;
use App\Models\Level;
use App\Models\LevelOption;
use App\Models\AgeGroup;
use App\Models\UserActivity;
use App\Models\CustomActivity;
use Auth, StdClass, Mail;
use Carbon\Carbon;
use App\Services\UserService;

class MainController extends Controller
{
    public function __construct()
    {

        // $this->middleware('auth:api');
    }

    public function userPackage()
    {

        $user = Auth::user();
        $data = userPackage::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        $currentDate = date('Y-m-d');

        if ($data)
        {

            // if (($currentDate >= $data->start_date) && ($currentDate <= $data->end_date)){
            return response()->json(['message' => 'Active Package', 'data' => $data, 'status' => 200], 200);

        }
        else
        {
            return response()->json(['message' => 'No Package Found !!!', 'error' => 'error'], 404);
        }

    }

    public function getSkills()
    {

        $user = User::with('age_group')->findorFail(Auth::id());
        $d = Carbon::parse(now())->diffInMonths($user->dob);

        $d = $user->selected_age ? $user->selected_age - 1 : $d;
        $age_gruop = AgeGroup::where('age_group', $d)->first();
        if (!$age_gruop)
        {
            return response()->json(['message' => 'No Age group found ', 'error' => 'error'], 400);
        }
        $age_group_id = $age_gruop->id;
        $skills = Skill::select('id', 'name', 'color', 'border_color', 's_no')->with('milestones', function ($q) use ($age_group_id)
        {
            // Query the name field in status table
            $q->where('age_group_id', $age_group_id)->whereStatus(1); // '=' is optional
            
        })
            ->whereStatus(1)
            ->get();

        if ($skills->count() == 0)
        {
            return response()
                ->json(['message' => 'No Skill found for this age group', 'error' => 'error'], 400);
        }

        return response()
            ->json(['message' => 'Age Group Skills', 'data' => $skills, 'status' => 200], 200);

    }

    public function get_activities($id)
    {

        $user = User::with('age_group')->findorFail(Auth::id());
        $milestone = Milestone::select('id', 'skill_id', 'age_group_id', 'name', 'description', 'description2', 'other', 'extra', 's_no')->with('activities')
            ->whereStatus(1)
            ->findOrFail($id);

        if ($milestone->count() == 0)
        {
            return response()
                ->json(['message' => 'No Activity found for this Skils group', 'error' => 'error'], 400);
        }

        return response()
            ->json(['message' => 'Activity  list', 'data' => $milestone, 'status' => 200], 200);

    }

    public function get_activity_level($id)
    {

        $user = User::with('age_group')->findorFail(Auth::id());

        $activity = Activity::with('levels', 'milestone.skill')->whereStatus(1)
            ->findOrFail($id);

        if ($activity->count() == 0)
        {
            return response()
                ->json(['message' => 'No Levels found in this activity group', 'error' => 'error'], 400);
        }

        return response()
            ->json(['message' => 'Levels  list', 'data' => $activity, 'status' => 200], 200);

    }

    public function single_level($id)
    {

        $user = User::with('age_group')->findorFail(Auth::id());

        $level = Level::with('options','anwser')->whereStatus(1)
            ->findOrFail($id);
        // dd($level->is_complete);
        if ($level->count() == 0)
        {
            return response()
                ->json(['message' => 'No Levels found in this activity group', 'error' => 'error'], 400);
        }

        return response()
            ->json(['message' => 'Level with option', 'data' => $level, 'status' => 200], 200);

    }

    public function level_submit(Request $request)
    {
        // dd(new UserActivity);

        $user = User::with('age_group')->findorFail(Auth::id());
        $userService = new UserService($user);
        if ((!$request->level_id) || (!$request->activity_id) || (!$request->option_id))
        {
            return response()
                ->json(['message' => 'Level or Activity Or option is missing', 'error' => 'error'], 400);
        }
    $current_age_grup_id = $userService->getBioAge()+1;
        $user_activity = UserActivity::where('user_id', $user->id)
            ->where('level_id', $request->level_id)
            ->where('activity_id', $request->activity_id)
            ->latest()
            ->first();

        $levels = Level::find($request->level_id);
     if(!$levels)
            {
            return response()
                ->json(['message' => 'Invalid level', 'error' => 'error'], 400);
        }
        $level_option = LevelOption::find($request->option_id);
    if(!$level_option)
            {
            return response()
                ->json(['message' => 'Invalid option', 'error' => 'error'], 400);
        }
        $max_points = $levels->options()
            ->max('points');

        $status = 0;

        if ($level_option->points == $max_points)
        {
            $status = 1;
        }
        if (!$user_activity)
        {

            $user_activity = UserActivity::create(['user_id' => $user->id,'age_group_id'=>($current_age_grup_id), 'activity_id' => $request->activity_id, 'level_id' => $request->level_id, 'level_option_id' => $request->option_id, 'points' => $level_option->points, 'status' => $status,'day'=>$userService->getToday()]);
            $this->userPoints($level_option->points);
            return response()->json(['message' => 'Successfully Submitted', 'data' => $user_activity, 'status' => 200], 200);

            return response()->json(['message' => 'Please Try Again', 'error' => 'error'], 400);

        }

        if ($user_activity->status == 1)
        {
            return response()
                ->json(['message' => 'Already submitted level', 'error' => 'error'], 400);

        }
        else
        {
            $points  = $level_option->points;
            if($level_option->points==0){
                $points = 0;
            } else {
              $points = $level_option->points - $user_activity->points;
              $points = $user_activity->points + $points;
            }
         if ($points == $max_points)
        {
            $status = 1;
        }
        
        

        if($user_activity->day != ($userService->getToday())) {
            $user_activity = UserActivity::create(['user_id' => $user->id,'age_group_id'=>($current_age_grup_id), 'activity_id' => $request->activity_id, 'level_id' => $request->level_id, 'level_option_id' => $request->option_id, 'points' => $points, 'status' => $status,'day'=>$userService->getToday()]);
                 $this->userPoints($points);

            return response()->json(['message' => 'Successfully Submitted', 'data' => $user_activity, 'status' => 200], 200);
        }
        
           $user_activity->update(['user_id' => $user->id, 'activity_id' => $request->activity_id, 'age_group_id'=>$current_age_grup_id,'level_id' => $request->level_id, 'level_option_id' => $request->option_id, 'points' => $points, 'status' => $status,'day'=>$userService->getToday()]);
                 $this->userPoints($points);

            return response()->json(['message' => 'Successfully Submitted', 'data' => $user_activity, 'status' => 200], 200);

            return response()->json(['message' => 'Please Try Again', 'error' => 'error'], 400);
        }

    }


    public function actvity_calendar()
    {

        $user = User::with('user_package')->findorFail(Auth::id());
        if (!$user->user_package)
        {
            return response()
                ->json(['message' => 'user does not have package', 'error' => 'error'], 400);
        }
            
        $userService = new UserService($user);

        $monthArray = array_values($userService->getAgeList());
        // dd($monthArray);

        // dd($userService->getCalender());
         $totalDays = $userService->getCalender() +30;
    

    if($userService->IsCustomPlan()){
    
    $totalDays = 360;
    
    $totalDays =  count($monthArray)*30;
    }
    $day = 1;
    $activities = [];
        // $age_group_id = $age_gruop->id;
        for ($i = 1;$i <= $totalDays;$i++)
        {
            $activities[$i] = [];
           
        }

        return response()->json(['message' => 'Successfully Submitted', 'data' => $activities, 'status' => 200], 200);
    }

    public function day_activity($days)
    {

        $user = User::with('user_package', 'custom_activity')->findorFail(Auth::id());
        if (!$user->user_package)
        {
            return response()
                ->json(['message' => 'user does not have package', 'error' => 'error'], 400);
        }
        

         $userService = new UserService($user);
        $totalDays = $userService->getCalender() +30;
        if($days > $totalDays){
             return response()
                ->json(['message' => 'user does not have access', 'error' => 'error'], 400);
        }

         $monthArray = array_values($userService->getAgeList());
         sort($monthArray);
    // dd($monthArray);
        $userMinAge = min($monthArray);
        $userMaxAge = max($monthArray);
        $month = floor($days/30);
        $remaning_day = $days%30;
    if($remaning_day == 0){
        $month--;
    }
        $milestones_array_month =[];
        $actualGroupId =  $userMinAge  + (int)$month;
        $newactualGroupId = $userMinAge;
    
    //     if($days >30){
    // if($userMinAge <$actualGroupId ){
    //     $newactualGroupId  = $actualGroupId -1;
    // }
    //     }
    // dd($userMinAge,$month,$actualGroupId);   
        $milstones = Milestone::select('id', 'age_group_id')->whereBetween('age_group_id', [$userMinAge, $actualGroupId])->whereStatus(1)->orderBy('age_group_id')->get();
        // $milestones_array = array_unique(array_column($milstones->groupBy('age_group_id')->toArray() , 'id'));
    $age_groupBy = $milstones->groupBy('age_group_id')->toArray();


        $activities_array  =[];
//      
   
                foreach($age_groupBy as $age => $age_group){
                $milestones_array = array_unique(array_column($age_group , 'id'));
                
             $activities =   Activity::with('levels', 'milestone.skill')->whereIn('milestone_id', $milestones_array)->orderBy('day')->get()->toArray();
              
                        if (count($activities)>0)
                        {

                            // dd($activities);
                            foreach ($activities as $key => $activity)
                            {   
                        
                                if($userService->IsCustomPlan()){
                                        if($userService->IsUserActivty($activity['id'])){
                              
                                            if ($activity['complete_status'] == false)
                                          {
                                            $aday = $activity['day'] ;
                                            $inedx = array_search($activity['age_group'],$monthArray);
                                            if($inedx > 0){ 
                                             $activity['day'] = $inedx*30+ $activity['day'];
                                            }
                                            if($activity['age_group'] ==$actualGroupId && $remaning_day >0){
                                                if($aday <=$remaning_day){
                                                $a[0] = $activity;
                                             array_push($activities_array, $a);
                                                }
                                            }else {
                                                      $a[0] = $activity;
                                             array_push($activities_array, $a);
                                            }
                                            
                                                

                                         }
                                        }
                                
                                } else {
                                    if ($activity['complete_status'] == false)
                                {   
                                         $aday = $activity['day'] ;
                                        $inedx = array_search($activity['age_group'],$monthArray);
                                    if($inedx > 0){ 
                                           $activity['day'] = $inedx*30+ $activity['day'];
                                    
                                    }
                                       if($activity['age_group'] ==$actualGroupId && $remaning_day >0){
                                                if($aday <=$remaning_day){
                                                $a[0] = $activity;
                                             array_push($activities_array, $a);
                                                }
                                            }else {
                                                      $a[0] = $activity;
                                             array_push($activities_array, $a);
                                            }
                                            
                             

                                }
                                
                                }

                            }
                        }
          
           
                }

        return response()->json(['message' => 'Day' . $days . 'Activities', 'data' => $activities_array, 'status' => 200], 200);
    }

    
    public function pending_activity()
    {

        $user = User::with('user_package','custom_activity')->findorFail(Auth::id());
        if (!$user->user_package)
        {
            return response()
                ->json(['message' => 'user does not have package', 'error' => 'error'], 400);
        }
        
            $userService = new UserService($user);
     $monthArray = array_values($userService->getAgeList());
         sort($monthArray);
    // dd($monthArray);
        $userMinAge = min($monthArray);
    $today = $userService->getToday();
        $userMaxAge = max($monthArray);
        $month = floor($today/30);
        $remaning_day = $today%30;
    if($remaning_day == 0){
        $month--;
    }
        $milestones_array_month =[];
        $actualGroupId =  $userMinAge  + (int)$month;
        $newactualGroupId = $userMinAge;
    
    //     if($days >30){
    // if($userMinAge <$actualGroupId ){
    //     $newactualGroupId  = $actualGroupId -1;
    // }
    //     }
    // dd($userMinAge,$month,$actualGroupId);   
        $milstones = Milestone::select('id', 'age_group_id')->whereBetween('age_group_id', [$userMinAge, $actualGroupId])->whereStatus(1)->orderBy('age_group_id')->get();
        // $milestones_array = array_unique(array_column($milstones->groupBy('age_group_id')->toArray() , 'id'));
    $age_groupBy = $milstones->groupBy('age_group_id')->toArray();


        $activities_array  =[];
//      
   
                foreach($age_groupBy as $age => $age_group){
                $milestones_array = array_unique(array_column($age_group , 'id'));
                
             $activities =   Activity::with('levels', 'milestone.skill')->whereIn('milestone_id', $milestones_array)->orderBy('day')->get()->toArray();
              
                        if (count($activities)>0)
                        {

                            // dd($activities);
                            foreach ($activities as $key => $activity)
                            {   
                        
                                if($userService->IsCustomPlan()){
                                        if($userService->IsUserActivty($activity['id'])){
                              
                                            if ($activity['complete_status'] == false)
                                          {
                                            $aday = $activity['day'] ;
                                            $inedx = array_search($activity['age_group'],$monthArray);
                                            if($inedx > 0){ 
                                             $activity['day'] = $inedx*30+ $activity['day'];
                                            }
                                            if($activity['age_group'] ==$actualGroupId && $remaning_day >0){
                                                if($aday <=$remaning_day){
                                                $a[0] = $activity;
                                             array_push($activities_array, $a);
                                                }
                                            }else {
                                                      $a[0] = $activity;
                                             array_push($activities_array, $a);
                                            }
                                            
                                                

                                         }
                                        }
                                
                                } else {
                                    if ($activity['complete_status'] == false)
                                {   
                                         $aday = $activity['day'] ;
                                        $inedx = array_search($activity['age_group'],$monthArray);
                                    if($inedx > 0){ 
                                           $activity['day'] = $inedx*30+ $activity['day'];
                                    
                                    }
                                       if($activity['age_group'] ==$actualGroupId && $remaning_day >0){
                                                if($aday <=$remaning_day){
                                                $a[0] = $activity;
                                             array_push($activities_array, $a);
                                                }
                                            }else {
                                                      $a[0] = $activity;
                                             array_push($activities_array, $a);
                                            }
                                            
                             

                                }
                                
                                }

                            }
                        }
          
           
                }

           
        return response()->json(['message' => 'ongoing Activities', 'data' => $activities_array, 'status' => 200], 200);

    }



    public function completed_activity()
    {
     
        $user = User::with('user_package','custom_activity')->findorFail(Auth::id());
   
        $userService = new UserService($user);
     $monthArray = array_values($userService->getAgeList());
         sort($monthArray);
  
    
    //     if($days >30){
    // if($userMinAge <$actualGroupId ){
    //     $newactualGroupId  = $actualGroupId -1;
    // }
    //     }
    // dd($userMinAge,$month,$actualGroupId);   
        $milstones = Milestone::select('id', 'age_group_id')->whereIn('age_group_id', $monthArray)->whereStatus(1)->orderBy('age_group_id')->get();
        // $milestones_array = array_unique(array_column($milstones->groupBy('age_group_id')->toArray() , 'id'));
    $age_groupBy = $milstones->groupBy('age_group_id')->toArray();


        $activities_array  =[];
//      
   
                foreach($age_groupBy as $age => $age_group){
                $milestones_array = array_unique(array_column($age_group , 'id'));
                
             $activities =   Activity::with('levels', 'milestone.skill')->whereIn('milestone_id', $milestones_array)->orderBy('day')->get()->toArray();
              
                        if (count($activities)>0)
                        {

                            // dd($activities);
                            foreach ($activities as $key => $activity)
                            {   
                        
                                if($userService->IsCustomPlan()){
                                        if($userService->IsUserActivty($activity['id'])){
                              
                                            if ($activity['complete_status'] == true)
                                          {
                                            $aday = $activity['day'] ;
                                            $inedx = array_search($activity['age_group'],$monthArray);
                                            if($inedx > 0){ 
                                             $activity['day'] = $inedx*30+ $activity['day'];
                                            }
                                                      $a[0] = $activity;
                                             array_push($activities_array, $a);
                                        
                                         
                                         }
                                        }
                                
                                } else {
                                    if ($activity['complete_status'] == true)
                                {   
                                         $aday = $activity['day'] ;
                                        $inedx = array_search($activity['age_group'],$monthArray);
                                    if($inedx > 0){ 
                                           $activity['day'] = $inedx*30+ $activity['day'];
                                    
                                    }
                                                      $a[0] = $activity;
                                             array_push($activities_array, $a);
                                  
                                            
                             

                                }
                                
                                }

                            }
                        }
          
           
                }

           

           
        
        return response()->json(['message' => 'Completed Activities', 'data' => $activities_array, 'status' => 200], 200);

    }

 
    public function upcoming_activity()
    {

        $user = User::with('user_package')->findorFail(Auth::id());
        if (!$user->user_package)
        {
            return response()
                ->json(['message' => 'user does not have package', 'error' => 'error'], 400);
        }
    
        $userService = new UserService($user);
        $monthArray = $userService->getAgeList();
        asort($monthArray);
        $monthArray = array_values($monthArray);
        $userMinAge = min($userService->getAgeList());
        $userMaxAge = max($userService->getAgeList());
        $today = $userService->getToday();
  
    
        if($today > 30){
            $month = $today/30;
            $today = $today%30;
            $userMinAge =  $userMinAge  + (int)$month;
        
        }
  
        $milstones = Milestone::select('id', 'age_group_id')->whereBetween('age_group_id', [$userMinAge, $userMaxAge])->whereStatus(1)->orderBy('age_group_id')->get();
        // $milestones_array = array_unique(array_column($milstones->groupBy('age_group_id')->toArray() , 'id'));
    $age_groupBy = $milstones->groupBy('age_group_id')->toArray();

// dd($age_groupBy);
        $activities_array  =[];
//      
   
                foreach($age_groupBy as $age => $age_group){
                $milestones_array = array_unique(array_column($age_group , 'id'));
                
             $activities =   Activity::with('levels', 'milestone.skill')->whereIn('milestone_id', $milestones_array)->orderBy('day')->get()->toArray();
              
                        if (count($activities)>0)
                        {

                            // dd($activities);
                            foreach ($activities as $key => $activity)
                            {   
                        
                                if($userService->IsCustomPlan()){
                                        if($userService->IsUserActivty($activity['id'])){
                              
                                            if ($activity['complete_status'] == false)
                                          {
                                            $aday = $activity['day'] ;
                                            $inedx = array_search($activity['age_group'],$monthArray);
                                            if($inedx > 0){ 
                                             $activity['day'] = $inedx*30+ $activity['day'];
                                            }
                                            if($activity['age_group'] ==$userMinAge && $today >0){
                                                if($aday >=$today){
                                                $a[0] = $activity;
                                             array_push($activities_array, $a);
                                                }
                                            }else {
                                                      $a[0] = $activity;
                                             array_push($activities_array, $a);
                                            }
                                            
                                                

                                         }
                                        }
                                
                                } else {
                                    if ($activity['complete_status'] == false)
                                {         $aday = $activity['day'] ;
                                        $inedx = array_search($activity['age_group'],$monthArray);
                                    if($inedx > 0){ 
                                           $activity['day'] = $inedx*30+ $activity['day'];
                                    
                                    }
                                       if($activity['age_group'] ==$userMinAge && $today >0){
                                                if($aday >=$today){
                                                $a[0] = $activity;
                                             array_push($activities_array, $a);
                                                }
                                            }else {
                                                      $a[0] = $activity;
                                             array_push($activities_array, $a);
                                            }
                                            
                             

                                }
                                
                                }

                            }
                        }
          
           
                }

        return response()->json(['message' => 'UPcoming Activities', 'data' => $activities_array, 'status' => 200], 200);

    }

    public function userProgress()
    {

        $user = User::with('age_group', 'user_package', 'custom_activity')->findorFail(Auth::id());
        
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

    public function userChart(Request $request)
    {
        $user = User::with('age_group', 'user_package')->findorFail(Auth::id());
    
    
        $age = $request->age;
        if(!$age){
            return response()->json(['message' => 'Age required', 'data' => [], 'status' => 400], 400);
        }
            $userService = new UserService($user);
            $resultArray =$userService->getUserCurve($age);

            // $user->da_age = $resultArray['da_point'] ?? $user->selected_age;
            // $user->update();
            if(isset($resultArray['da_point'])){

            unset($resultArray['da_point']);
            }
            $user->age = $userService->getBioAge();

            if(isset($resultArray['status']) && $resultArray['status']==false){

             return response()->json(['message' => $resultArray['message'], 'data' => [], 'status' => 400], 400);
            }
  
  
        $data = [];
        $data['user'] = $user;
        $data['month'] = $resultArray['month'];
        return response()->json(['message' => 'Charts', 'data' => $data, 'status' => 200], 200);
    }

    public function user_age(Request $request)
    {
        $user = User::with('free_test')->find(Auth::id());
        $userService = new UserService($user);
        if ($request->isMethod('post'))
        {

            $validator = Validator::make($request->all() , ['age' => 'required',

            ]);

            if ($validator->fails())
            {
                return response()
                    ->json($validator->errors() , 422);
            }

            $user->selected_age = $request->age;
            $user->da_age = ($request->age-1);
            $user->update();
            $user->today = $userService->getToday();
            // Mail::send([], [], function ($message) use ($user)
            // {
            //     $message->to($user->email)
            //         ->cc('aadmin@compasstot.com')
            //         ->cc('alwin54889@gmail.com')
            //         ->from('no-reply@compasstot.com')
            //         ->subject('Compastot')
            //     // here comes what you want
            //     // assuming text/plain
            //     // or:
                
            //         ->setBody('<h1>Your Child activities are show according to ' . ($user->selected_age - 1) . ' Months </h1>', 'text/html'); // for HTML rich messages
                
            // });
            return response()
                ->json(['message' => 'User Age', 'user' => $user, ], 200);

        }
        if ($user)
        {
            // date_default_timezone_set('Asia/Calcutta');
            $d = Carbon::parse(now())->diffInMonths($user->dob);

            $age_gruop = AgeGroup::where('age_group', $d)->first();
            if ($age_gruop)
            {
                $user->age = $userService->getBioAge();
            }
            if ($user->user_package)
            {
                $to = Carbon::parse($user
                    ->user_package
                    ->start_date);
                $current = Carbon::now();
                $length = $to->diffInDays($current);

                $user->today = $length;
            }
            return response()->json(['message' => 'User Age', 'user' => $user, 'valid_package' => $this->package_valid($user) , 'free_test' => $this->free_test_vaild($user) , ], 200);

        }

        return response()->json(['message' => 'NO Free test Found Age', 'status' => false, ], 400);
    }

    public function package_valid($user)
    {
        $user->load('user_packages', 'user_packages');
        $package = $user
            ->user_packages
            ->where('status', 'active')
            ->first();
        if (!$package)
        {
            return false;
        }
        if ($package->end_date > date('Y-m-d'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function free_test_vaild($user)
    {
        $user->load('free_test');
        if ($user->free_test)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function user_free_package()
    {
        $user = UserPackage::whereHas('package', function ($query)
        {
            $query->where('type', 'free');
        })
            ->where('user_id', Auth::id())
            ->first();
        if ($user)
        {
            return response()->json(['message' => 'Free package found', 'status' => true, ], 200);
        }
        else
        {
            return response()
                ->json(['message' => 'NO Free test Found ', 'status' => false, ], 200);
        }

    }
    public function user_total_activity($milestone_id, $user_id)
    {
        $activities = Activity::where('milestone_id', $milestone_id)->get();
        $status = true;
        $total = 0;
        foreach ($activities as $activity)
        {

            $activities = UserActivity::where('activity_id', $activity->id)
                ->where('user_id', $user_id)->where('status', 1)
                ->get();

            $total += $activities->count();

        }
        return $total;

    }

    public function user_milestone($user)
    {

        $age = Carbon::parse(now())->diffInMonths($user->dob);
        $age = $user->selected_age ? $user->selected_age : $age;

        $startDate = new Carbon($user
            ->user_package
            ->start_date);
        $endDate = new Carbon($user
            ->user_package
            ->end_date);
        $diff = $endDate->diffInMonths($startDate);

        $age_group = [(int)$age];

        for ($i = 0;$i < $diff;$i++)
        {

            $age++;
            array_push($age_group, $age);

        }

        return $age_group;
    }

    public function get_user_age()
    {   
        $user = User::with('age_group', 'user_package')->findorFail(Auth::id());
            $userService = new UserService($user);

        $ageArray = array_values($userService->getAgeListFromBioAge());
          // asort($ageArray);
             return response()->json(['message' => 'Age package found','data' =>$ageArray,'status' => true, ], 200);


    }

    public function ageChart(Request $request)
    {
        $user = User::with('age_group', 'user_package')->findorFail(Auth::id());
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


        public function userPoints($points)
    {
        $user = User::findorFail(Auth::id());
        $userService = new UserService($user);
        $userTotalPoints = $userService->getUserLevelTotalPoints();
         if($points > 0){
        $percent = $points/$total_points;
        $daPercentMonth = $percent*12;
        // $daAge = $this->getDaAge();  
        // $Age = $this->getBioAge();   
        // $daMonth = $daAge +  $daPercentMonth;    
        // $da = $daMonth -$Age;

      $user->da_age =  $user->da_age +  $daPercentMonth;
      $user->update();
        }
    }
}

