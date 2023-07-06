<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
class Level extends Model
{
    use HasFactory;

     protected $appends = [
        'complete_status'
    ];


    public function options() 
   {
      return $this->hasMany(LevelOption::class);
   }
   public function max_options()
   {
    return $this->belongsTo('App\Models\LevelOption')->max('points');
   }

   public function activity() 
   {
      return $this->belongsTo('App\Models\Activity');
   }

   public function user_activity() 
   {
      return $this->hasOne('App\Models\UserActivity','level_id')->where('user_id',Auth::id());
   }

 public function user_level_complete() 
   {
      return $this->hasOne('App\Models\UserActivity','level_id')->where('user_id',Auth::id())->where('status',1);
   }

	  public function anwser() 
   {
      return $this->hasOne(UserActivity::class);
   }

     public function level_activities() 
   {
      return $this->hasMany(UserActivity::class,'level_id','id');
   }

       public function level_activities_complete() 
   {
      return $this->hasMany(UserActivity::class,'level_id','id')->where('status',1);
   }


    public function getCompleteStatusAttribute(){
      $this->load('user_level_complete');
      $status = false;
      if($this->user_level_complete && $this->user_level_complete->status ==1){
      $status =true;
    }
    return $status;
 }
 

}
