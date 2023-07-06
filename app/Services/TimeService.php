<?php

namespace App\Services;


use Carbon\Carbon;
use Carbon\CarbonPeriod;
use StdClass;
class TimeService {

	protected $user;
	
	 public function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }

	public function getDaysBetweenDates($start_date,$end_date)
    {
    	
    $startDate = new Carbon($start_date);
    $endDate = new Carbon($end_date);
    $diff = $endDate->diffInDays($startDate);
    return $diff;
    
    }

	public function getMonthsBetweenDates($start_date,$end_date)
    {
    	
      return $months = CarbonPeriod::create($start_date, '1 month', $end_date);
    
    }
	public function getMonthsArray($months){
    	
   	 $montth_array = [];
      foreach ($months as $month)
        {
         array_push($montth_array, $month->format('M'));
        }
    
    	return $montth_array;
    }

	public function getAgeInMonths($dob)
    {
    	
       return Carbon::parse(now())->diffInMonths($dob);
    
    }
	public function getDaysInMonth($date)
    {
    	
       return Carbon::parse($date)->daysInMonth;
    
    }
	public function getAgeInYears($dob)
    {
    	
       return Carbon::parse($dob)->age;
    
    }

		public function addMonthInDate($date,$month=1)
    {
    	
      	 $dt =  Carbon::parse($date)->addMonths($month);
        return $dt->format('Y-m-d');
    
    }
	public function getDayFromDate($date)
    {
    	$to = Carbon::parse($date);
        $current = Carbon::now();
        $today = $to->diffInDays($current);
   		 return $today;
    
    }
}

