<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable,HasRoles;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    public $guard_name = 'api';


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    } 




    public function csm_data() 
    {
        return $this->hasOne('App\Models\CsmData');
    }

 public function user_cms() 
    {
        return $this->belongsTo('App\Models\UserCms');
    }

     public function my_csm() 
    {
        return $this->hasOne('App\Models\UserCms','user_id','id');
    }

//     public function getAgeAttribute() 
//     {
//     date_default_timezone_set('Asia/Calcutta'); 
//      return  $years = Carbon::parse(now())->diffInMonths($this->dob);

//     }

    public function age_group()
    {
        return $this->belongsTo(AgeGroup::class,'age','age_group');
    }

      public function getDayAttribute() 
    {
      return $years = \Carbon\Carbon::parse($this->dob)->diff(now())->format('%d');
    }

    public function user_package()
    {
        return $this->hasOne(UserPackage::class,'user_id','id')->where('status','active')->with('package');
    }
    public function user_packages()
    {
        return $this->available_packages()->where('status','active');
    }

    public function available_packages() {
        return $this->hasMany(UserPackage::class,'user_id','id')->with('package');
    }


     public function users() 
    {
        return $this->hasMany('App\Models\UserCms','cms_id')->with('user');
    }

       public function user() 
    {
        return $this->belongsTo('App\Models\UserCms','user_id');
    }


     public function free_test() 
    {
        return $this->hasOne(FreeTestActivity::class,'mobile','mobile');
    }

	  public function custom_activity()
    {
        return $this->hasMany(CustomActivity::class,'user_id','id');
    }

}