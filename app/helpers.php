<?php

use App\Models\Milestone;
use App\Models\Activity;
use App\Models\Level;



	function milestone_status($milestone_id,$user_id)
	{
		   $activities = Activity::where('milestone_id',$milestone_id)->get();
		 	 $status = true;
		 	 $activity_ = null;
		 	 foreach($activities as $activity){
		 	 	
		 	 	$level = activitiy_status($activity->id,$user_id);
		 	 	if($level['status'] == false){
		 	 		$activity->levels = $level['level'];
		 	 		$activity_ = $activity;
		 	 		return $data =[
				 	 	'activity' =>$activity_,
				 	 	'status'=>false,
				 	 ];
				 	 	}
		 	 	
		 	 	
		 	 }

		 	 return $data =[
             
		 	 	'activity' =>$activity_,
		 	 	'status'=>$status,
		 	 ];

	
	}

	function activitiy_status($activity_id,$user_id)
	{
		   $levels = level::with('options','level_activities')->where('activity_id',$activity_id)->with('level_activities', function($q) use($user_id) {
       // Query the name field in status table
                   $q->where('user_id',$user_id)->where('status',1);
                                 
        })->get();


		 	 $status = true;
		 	 $level_ = null;
		 	 foreach($levels as $level){
		 	 	$level_ = $level;
		 	 	if($level->level_activities->count() ==0){

		 	 			 return $data =[
						 	 	'level' =>$level,
						 	 	'status'=>false,
						 	 ];

		 	 	}
		 	 }

		 	 return $data =[
		 	 	'level' =>$level_,
		 	 	'status'=>$status,
		 	 ];

	
	}

