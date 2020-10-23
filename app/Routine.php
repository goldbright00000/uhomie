<?php

namespace App;
use App\DbViews\{Service, Project, Property, PropertyWU};

/*
 * Class wich wraps the Calls to database routines (functions and procedures)
 *
*/
class Routine
{
	public static function calculateUserScoring($user_id, $property_id){
		try{
			return \DB::select("select sf_score(?,?) as scoring", [$user_id, $property_id])[0]->scoring;
		} catch ( \Exception $e){
			return -1;
		}
	}

	public static function calculatePropertyDemand($property_id){
		return \DB::select("select sf_demand(?) as demand", [$property_id])[0]->demand;
	}
}
