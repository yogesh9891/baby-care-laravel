<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;




public function activities() 
   {
      return $this->hasMany('App\Models\Activity');
   }

public function skill() 
   {
      return $this->belongsTo(Skill::class,'skill_id');
   }

public function getTotalMilestoneAttribute() 
   {
			$total= 0;
	
      foreach($this->activities as $activity){
      	$activity->loadCount('levels');
   		   $total +=$activity->levels_count;
      	
      }
	return $total;
   }

}
