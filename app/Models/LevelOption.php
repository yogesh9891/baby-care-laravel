<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelOption extends Model
{
    use HasFactory;


	    protected $fillable = [ 'points' ];
     public function level() 
   {
      return $this->belongsTo(Level::class);
   }

   public function anwser() 
   {
      return $this->hasOne(UserActivity::class);
   }
}
