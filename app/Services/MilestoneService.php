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
use App\Services\TimeService;

class MilestoneService {
	
	public function getAgeGroupID($milstoneIDs)
    {
    		$milestone = Milestone::select('age_group_id','id')->whereIn('id',$milstoneIDs)->whereStatus(1)->get();
    		$ageGroupIDs = array_unique(array_column($milestone->toArray() , 'age_group_id'));
    		return $ageGroupIDs;
    }
}