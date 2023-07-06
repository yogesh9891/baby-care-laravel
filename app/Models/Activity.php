<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Level;
use App\Models\LevelOption;
use Auth;
class Activity extends Model
{
    use HasFactory;



     protected $appends = [
        'complete_status' ,'age_group'
    ];

public function levels() 
   {
      return $this->hasMany(Level::class)->with('options');
   }

public function milestone() 
   {
      return $this->belongsTo(Milestone::class,'milestone_id');
   }


 public function user_activities() 
   {
      return $this->hasMany('App\Models\UserActivity','activity_id')->where('user_id',Auth::id());
   }

    public function user_activities_complete() 
   {
      return $this->hasMany('App\Models\UserActivity','activity_id')->where('user_id',Auth::id())->where('status',1);
   }


    public function getCompleteStatusAttribute(){
      $this->loadCount('user_activities_complete','levels');
      $status = false;
      if($this->user_activities_complete_count ==$this->levels_count){
      $status =true;
    }
    return $status;
 }
	
	    public function getAgeGroupAttribute(){
     		 $this->loadCount('milestone');
   		return $this->milestone->age_group_id;
    	
 }
 
}
