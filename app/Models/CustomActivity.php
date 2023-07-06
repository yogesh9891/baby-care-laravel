<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomActivity extends Model
{
    use HasFactory;

  public function levels() 
   {
      return $this->hasMany(Level::class,'activity_id','activity_id')->with('options');
   }

public function milestone() 
   {
      return $this->belongsTo(Milestone::class,'milestone_id','milestone_id');
   }


 public function user_activity() 
   {
      return $this->hasOne(UserActivity,'level_id')->where('user_id',Auth::id());
   }


     public function activities() 
   {
      return $this->hasMany(UserActivity::class,'activity_id','activity_id');
   }


}
