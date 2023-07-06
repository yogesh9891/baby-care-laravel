<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;
	protected $fillable =['user_id','activity_id','age_group_id','level_id','level_option_id','points','status','day','activity'];

	   public function activity() 
   {
      return $this->belongsTo(Activity::class)->withCount('levels');
   }

}
