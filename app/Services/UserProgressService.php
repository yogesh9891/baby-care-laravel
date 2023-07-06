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
use Auth, StdClass, Mail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class UserProgressService {

	protected $user;
	
	 public function __construct(User $user)
    {
        $this->$user = $user;
    }
}

