<?php

namespace App\Services;

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
use App\Services\TimeService;
use App\Services\MilestoneService;
use App\Models\FreeTestActivity;
class UserService {

     protected $user;
     protected $timeService;
     protected $milestoneService;
    
     public function __construct(User $user)
    {
        $this->user = $user;
        $this->timeService = new TimeService();
        $this->milestoneService = new MilestoneService();
       
    }

     public function getBioAge()
     {
        $dob = $this->user->dob;
        $age = $this->timeService->getAgeInMonths($dob);
        return $age;
     }
    
 public function getTotalDaysInMnnth($date)
     {
 
        $month_days = $this->timeService->getDaysInMonth($date);
        return $month_days;
     }
     public function getToday()
     {
        $start_date = $this->user->user_package->start_date;
       $user_packages = UserPackage::where('user_id',$this->user->id)->get();
        if($user_packages->count() > 1){
          $user_package = $user_packages->first();
            $start_date =  $user_package->start_date;
        } 
        $day = $this->timeService->getDayFromDate($start_date);

        return $day+1;
     }

    public function getDaAge()
    {
        if($this->user->selected_age){
        return $this->user->selected_age - 1;
        } else {
            return $this->getBioAge();
        }
    }

    public function getAgeGroup()
    {
        return AgeGroup::find($this->user->selected_age)->toArray();
    }

    public function getCalender()
    {
        $start_date = $this->user->user_package->start_date;
        $end_date = $this->user->user_package->end_date;
           $user_packages = UserPackage::where('user_id',$this->user->id)->get();
        if($user_packages->count() > 1){
          $user_package = $user_packages->first();
            $start_date =  $user_package->start_date;
        }
      return  $months =  $this->timeService->getDaysBetweenDates($start_date,$end_date);
    }

    public function getMonthCalender()
    {
        $start_date = $this->user->user_package->start_date;
        $end_date = $this->user->user_package->end_date;
        return $months =  $this->timeService->getAgeInMonths($start_date,$end_date);
    }
    public function getMonthPackage()
    {
        $start_date = $this->user->user_package->start_date;
        $end_date = $this->user->user_package->end_date;
        $total = $this->user->user_package->package->duration_days/30;
        $user_packages = UserPackage::where('user_id',$this->user->id)->get();
        if($user_packages->count() > 1){
          $user_package = $user_packages->first();
            $start_date =  $user_package->start_date;
           $total =  $this->timeService->getDaysBetweenDates($start_date,$end_date);
           $months =  $this->timeService->getMonthsBetweenDates($start_date,$end_date);
        $total = 0;      
    
            foreach ($months as $dt) {
             $total++;
             $dt->format("M");

        }
          
        } 

    return $total;
  
    }

        public function IsCustomPlan()
    {
        $status = false;
        if($this->user->custom_activity->count() > 0){
            $status = true;
        }
        return $status;
    }

    public function getAgeListFromBioAge()
    {
            $mobile = $this->user->mobile;
            $free_tests = FreeTestActivity::where('mobile',$mobile)->first();
            $age = $this->getBioAge();
            if($free_tests){
                $age = $free_tests->age;
            }
            $end_age = $this->getBioAge();
            $range = array_values(range($age, $end_age));

    sort($range);
            
             return $range;
        
    }
    
    public function getAgeList()
    {   
    
    $total = (int)$this->getMonthPackage();
         $age = $this->user->selected_age;

    $range = range($age, $total);
    $extarMonth = $total +1;
    $ageArray = array_slice($range,0,$extarMonth);
    sort($ageArray);
        // $ageArray = [];
        // for($i = 0;$i<$total;$i++){
        // $ageArray[$i] = (int)$age;
        // $age++;
        // }
            
        if($this->IsCustomPlan()){
            $ageArray = $this->ageGroupId();
        }
           return $ageArray;
    }
    public function getActivitiyMonthsList()
    {
     $total = $this->getMonthPackage();
        $ageArray = [];
         $age = $this->user->selected_age;
        for($i = 0;$i<$total;$i++){
            $ageArray[$i] = (int)$age;
            $age++;
        }
            
        if($this->IsCustomPlan()){
            $ageArray = $this->getCustomPalnMonth($ageArray);
        }
           return $ageArray;
    }

    public function getCustomPalnMonth($ageGroupArray)
    {
            $customAgeGroupArray =  $this->ageGroupId();
            $minAge = min($customAgeGroupArray);
            $maxAge = max($customAgeGroupArray);
            $minUserAge = min($ageGroupArray);
            $maxUserAge = max($ageGroupArray);
            $min = $minAge > $minUserAge ?$minUserAge:$minAge;
            $max = $maxAge > $maxUserAge ?$maxAge:$maxUserAge;
            $newageGroupArray = [];
    
            for($i = $min;$i<$max;$i++){
                array_push($newageGroupArray,(int)$i);
           }
        
    return  $newageGroupArray;
        
    }

    public function ageGroupId()
    {
         $milestones_array = array_unique(array_column($this->user->custom_activity->toArray() , 'milestone_id'));
         $ageGroupId =  $this->milestoneService->getAgeGroupID($milestones_array);
         return $ageGroupId;
    }

    public function IsUserActivty($activtiyId)
    {   
         $status = false;
            $activity =  CustomActivity::where('activity_id',$activtiyId)->where('user_id',$this->user->id)->first();
         if($activity){
  
                 $status = true;
         }
         return $status;
    }
    public function IsUserSkill($skill_id)
    {   
         $status = false;
            $activity =  CustomActivity::where('skill_id',$skill_id)->where('user_id',$this->user->id)->first();
         if($activity){
  
                 $status = true;
         }
         return $status;
    }
    
    public function IsUserMilestone($milestone_id)
    {   
         $status = false;
            $activity =  CustomActivity::where('milestone_id',$milestone_id)->where('user_id',$this->user->id)->first();
         if($activity){
  
                 $status = true;
         }
         return $status;
    }
    public function getUserMileStones()
    {   
         $actualGroupId = $this->getActivitiyMonthsList();
        $milstones = Milestone::select('id', 'age_group_id')->whereIn('age_group_id', $actualGroupId)->whereStatus(1)->orderBy('age_group_id')->get();
        $milestones_array = array_unique(array_column($milstones->toArray() , 'id'));
    
        return $milestones_array;
    }   
    public function getUserActivity()
    {   
         $milestones_array = $this->getUserMileStones();
      $activities = Activity::select('id', 'milestone_id')->whereIn('milestone_id', $milestones_array)->get();
        $activity_array = array_unique(array_column($activities->toArray() , 'id'));
     if($this->IsCustomPlan()){
         $activity_array = array_unique(array_column($this->user->custom_activity->toArray() , 'activity_id'));
     
   
     }
        return $activity_array;
    }   

        public function getUserLevels()
    {   
         $activity_array = $this->getUserActivity();
          $levels = Level::select('id', 'activity_id')->withMax('options','points')->whereIn('activity_id', $activity_array)->get();
        $levels_array = array_unique(array_column($activities->toArray() , 'id'));
        return $levels_array;
    }   
    
    public function getUserLevelTotalPoints()
    {   
         $activity_array = $this->getUserActivity();
          $levels = Level::select('id', 'activity_id')->withMax('options','points')->whereIn('activity_id', $activity_array)->get();
        $total = 0;
    
        foreach($levels as $level){
       
            $total += $level->options_max_points;
        }
   
        return $total;
    }
    
        public function getAgeMilestone($ageGroupId)
    {   
        
       $milstones = Milestone::select('id', 'age_group_id')->where('age_group_id', $ageGroupId)->whereStatus(1)->get();
        $milestones_array = array_unique(array_column($milstones->toArray() , 'id'));
        return $milestones_array;
   
    }
    public function getAgeActitvity($ageGroupId)
    {   
        
      $milestones_array = $this->getAgeMilestone($ageGroupId);
      $activities = Activity::select('id', 'milestone_id')->whereIn('milestone_id', $milestones_array)->get();
        $activity_array = array_unique(array_column($activities->toArray() , 'id'));
     if($this->IsCustomPlan()){
            $activity =  CustomActivity::select('activity_id','user_id')->whereIn('milestone_id', $milestones_array)->where('user_id',$this->user->id)->get();
         $activity_array = array_unique(array_column($activity->toArray() , 'activity_id'));
   
     }
        return $activity_array;
   
    }
    public function getUserMonthPoints($ageGroupId,$day)
    {   
         // $activity_array = $this->getAgeActitvity($ageGroupId);

            return $total =  UserActivity::select('activity_id', 'points','user_id','day')->where('user_id',$this->user->id)->where('day',$day)->sum('points');
            
//      if($activities->count() > 0) {
        
//      foreach($activities as $activity){
       
//          $total += $activity->points;
//         }
   
//      return $total;
//         }
//         else {
//          return false;
//         }
        
    }
    public function getUserPointDayActivitySubmit($day)
    {
        $user_activties = UserActivity::with('activity')->where('user_id',$this->user->id)->where('day',$day)->get()->groupBy('activity_id');
        $points = 0;
        foreach ($user_activties as $activity_id  => $activity) {
            $user_activty = count($activity);
            $total_activity = $activity[0]->activity->levels_count;
            if($user_activty == $total_activity){
                $points ++;
            }
        }
        
        return $points;
    }
    public function getUserDaByDay($previous_point,$day,$age,$userTotalPoints)
    {
   
         $reduction_point = (-1/365)*12;
        if($day == 1){
            return $previous_point ;
        }

      $dayPoints =   $this->getUserDAMonth($age,$day,$userTotalPoints);
      if(is_numeric($dayPoints)){
      $total_points = $previous_point +$dayPoints;
      } else {
      $total_points = $previous_point +$reduction_point;
      
      }

      return $total_points;
        
    }
    public function getUserDAMonth($ageGroupId,$day,$userTotalPoints)
    {   
        $total_points = $userTotalPoints;
        $month_points = $this->getUserMonthPoints($ageGroupId,$day);
        if($month_points){
        $percent = $month_points/$total_points;
        $daPercentMonth = $percent*12;
        // $daAge = $this->getDaAge();  
        // $Age = $this->getBioAge();   
        // $daMonth = $daAge +  $daPercentMonth;    
        // $da = $daMonth -$Age;
      
        return  $daPercentMonth;
        
        
        }
        
        return false;
    
    }



    public function getUserCurveMonth($da_result){

    $message = '';
       if($da_result >=1){

            $message = 'Ahead of the Curve by ';
             if(round($da_result) >1){
                    $message .= round($da_result).' Months';
                }
                else {
                    $message .= round($da_result).' Month';

                }

        } elseif($da_result >-1 &&$da_result <1){
            $message = 'On the Curve ';

        }elseif ($da_result<=-1) {
                $message = ' Behind the Curve by ';
                if(abs(round($da_result)) >1){
                    $message .= abs(round($da_result)).' Months';
                }
                else {
                    $message .= abs(round($da_result)).' Month';

                }

        }
    
    return $message;
    
    }
    
public function getUserCurve($age){
        
    $ageArray = array_values($this->getAgeListFromBioAge());
    $newArray = [];
    $resultArray = [];
    if(!in_array($age,$ageArray)){
            $newArray['status'] = false;
            $newArray['result'] = [];
            $newArray['message'] = 'Invadild Age';
            return $newArray;
        
    }
    asort($ageArray);
    $addMonth =1;
    $currentAgeIndex  = array_search($age,$ageArray);
    $bioAgeIndex  = array_search($this->getBioAge()+1,$ageArray);
    if($bioAgeIndex){

    $ageArray = array_slice($ageArray,0,$bioAgeIndex+1);
    }
    $newAgeArrayLsit = array_slice($ageArray,0,$currentAgeIndex+1);
    $userTotalPoints = $this->getUserLevelTotalPoints();
    $start_date =  $this->user->user_package->start_date;
        $user_package = UserPackage::where('user_id',$this->user->id)->first();
        if($user_package){
            $start_date =  $user_package->start_date;
        } 
    $end_date = $this->timeService->addMonthInDate($start_date,$addMonth);
    // $day_in_month = $this->getTotalDaysInMnnth($end_date);
    $day_in_month = $this->getTotalDaysInMnnth($start_date);  
    $total_days = $this->timeService->getDaysBetweenDates($start_date,$end_date);
    $start_day = $total_days - $day_in_month;

   if($start_day <=0){
    $start_day = 0;
   }
   $end_index = ($start_day+$day_in_month)-1;
   $start_index = $start_day;
   // $today = $this->getToday();
    $days = 1;
    $newArray = [];
   $start_point = $this->getDaAge() -  $this->getBioAge();
     $previous_point = $start_point;
        $total = 0;
    
        
        $i = 1;
       $end_point = $day_in_month;
        $day_in_plan = 0;
        $previous_point = $start_point;
        $resultArray =[];
        foreach($ageArray as $key => $age){
   // if($today > 0 && $today <=$totalDays){
        $total = 0;

        for ($day=$i; $day <=$end_point ; $day++) {
                $day_in_plan++;
               $da_point =  $this->getUserDaByDay($previous_point ,$day_in_plan,$age,$userTotalPoints);
        $previous_point = $da_point;
        if($day_in_plan <= $this->getToday() && ($key==$currentAgeIndex)) {
            $array = [];
            $array['day'] = $days;
            $array['value'] = $da_point;
            $array['result'] = $this->getUserCurveMonth($da_point);
            $total += $da_point;
            array_push($resultArray,$array);
             $days++;
        }
        // }
   }

        $start_date =  $this->timeService->addMonthInDate($start_date);
        $i = 1;
        $day_in_month = $this->getTotalDaysInMnnth($start_date);
       $end_point =  $day_in_month;
       $days  =1;
       
    }


    $newArray['month'] =  $resultArray;
$da_age_point =  $this->roundOffPoint($previous_point) +$this->getBioAge();
        // $resultArray['status'] = true;
        // $resultArray['start'] = $start_index;
        // $resultArray['end'] =$end_index;

            // $newArray['value'] = round($total,2);
            $newArray['da_point'] = $da_age_point;;
            // $newArray['result'] = $this->getUserCurveMonth($total);
    return  $newArray;
  }
    public function roundOffPoint($point){
        
    if($point<0){
    $point = ceil($point);
    } else {
    $point = floor($point);
    
    }
    
    return $point;
    }


    public function getUserAgeCurve($month)
    {
    $ageArray = array_values($this->getAgeList());
    $newArray = [];
    $resultArray = [];

  
    asort($ageArray);
    $age  =$this->getBioAge()+1;
    $currentAgeIndex  = array_search($age,$ageArray);
    $addMonth = $currentAgeIndex+1;
        $userTotalPoints = $this->getUserLevelTotalPoints();
        if($currentAgeIndex){
        if($month >1){
            $newAgeArrayLsit = array_slice($ageArray,$currentAgeIndex-($month-1),$month);
        }
    $ageArray = array_slice($ageArray,0,$currentAgeIndex+1);

        }
  $start_date =  $this->user->user_package->start_date;
     $user_package = UserPackage::where('user_id',$this->user->id)->first();
        if($user_package){
            $start_date =  $user_package->start_date;
        } 
    $end_date = $this->timeService->addMonthInDate($start_date,$addMonth);
    // $day_in_month = $this->getTotalDaysInMnnth($end_date);
    $day_in_month = $this->getTotalDaysInMnnth($start_date);    
    $total_days = $this->timeService->getDaysBetweenDates($start_date,$end_date);
    $start_day = $total_days - $day_in_month;

   if($start_day <=0){
    $start_day = 0;
   }

   $end_index = ($start_day+$day_in_month)-1;
   $start_index = $start_day;
   // dd($start_day,$total_days,$day_in_month);
   // $today = $this->getToday();
    $days = 1;
    $newArray = [];
    $start_point = $this->getDaAge() -  $this->getBioAge();
     $previous_point = $start_point;
     $total =0;

                    $week = 0;
                $week_days = 1;
                $week_ponts = 0;
                $week__total_ponts = 0;
        $tottalWeekDays  = floor(($month*4.345));
        $i = 1;
       $end_point = $day_in_month;
        $day_in_plan =0;
        $starting_month = round($this->getToday() - ($month*30.4167));
        $endingmonth = $this->getToday();
        // dd($starting_month,$endingmonth);
        $previous_point = $start_point;
        $user_point = $previous_point;
        foreach($ageArray as $key => $age1){
   // if($today > 0 && $today <=$totalDays){
        $total = 0;

        for ($day=$i; $day <=$end_point ; $day++) {
            $day_in_plan++;
              $da_point =  $this->getUserDaByDay($previous_point ,$day,$age1,$userTotalPoints);
        $previous_point = $da_point;
                            $previous_point = $da_point;

             $week__total_ponts += $da_point;
                                   if($day_in_plan%7==0){
                                    $week_ponts =  $week__total_ponts/7;
                                    $week__total_ponts = 0;
                                    
                                    }
                    if($day_in_plan >= $starting_month && $day_in_plan <= $endingmonth){
                        
                            if(($day_in_plan%7==0) && $week_days<= $tottalWeekDays) {
                            
                                $array = [];
                                $array['day'] = $week_days;
                                $array['value'] = $week_ponts;
                                // $array['value'] = $this->roundOffPoint($da_point);
                                $array['result'] = $this->getUserCurveMonth($week_ponts);
                                $total += $week_ponts;

                                array_push($resultArray,$array);
                                 $week_days++;
                              $week_ponts = 0;
                            }

                           
                    }
                
        // }
   }
        $start_date =  $this->timeService->addMonthInDate($start_date);
        $i = $day_in_month+1;
         $day_in_month = $this->getTotalDaysInMnnth($start_date);
       $end_point = $i + $day_in_month;
    }

                   $start_index = 0;
                $end_index = $week_days-2;
       $newArray =  $resultArray;

        // $resultArray['status'] = true;
        // $resultArray['start'] = $start_index;
        // $resultArray['end'] =$end_index;

            $newArray['value'] = round($total,2);
            $newArray['result'] = $this->getUserCurveMonth($total);
    return  $resultArray;
}
}


