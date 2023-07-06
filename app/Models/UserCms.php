<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCms extends Model
{
    use HasFactory;

	protected $fillable = ['user_id','cms_id'];

    public function user() 
   {
      return $this->hasMany('App\Models\User','id','user_id');
   }
    public function csmUser() 
   {
      return $this->hasMany('App\Models\User','id','cms_id');
   }
}
