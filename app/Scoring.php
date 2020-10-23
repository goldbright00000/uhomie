<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Scoring extends Model
{
    //
    protected $table = 'scoring';
    public $timestamps = false;
    /**
  		Fillables and Model Config
  	*/
  	protected $fillable = [
  		'name', 'description'
  	];
  	/**
  		ORM Relationships
  	*/
    public function categories()
  	{
		return $this->hasMany('App\ScoringCategory', 'scoring_id');
	}
	
	public function contactUserScore($vphone,$vmail){
		$points_phone = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 1")[0]->points;
		$points_email = DB::select("SELECT points FROM v_scoring WHERE cat_id = 1 AND scoring_id=1  AND det_id = 2")[0]->points;

		return DB::select('select sf_contact_user_score(?,?,?,?) as contact', [$vphone,$vmail,$points_phone,$points_email])[0]->contact;
	}

	public function identityUserScore($user_id){
		$points_front = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 3")[0]->points;
		$points_back = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 4")[0]->points;

		return DB::select('select sf_identity_user_score(?,?,?) as identity', [$user_id,$points_front,$points_back])[0]->identity;
	}
	
	public function nationalityScoreUser($user_country,$user_document_type){
		$points_national = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 5")[0]->points;
		$points_rut = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 6")[0]->points;
		$points_provisional = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 7")[0]->points;
		$points_passport = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 8")[0]->points;

		$country = DB::select("SELECT id from countries where valid")[0]->id;

		return DB::select("select sf_nationality_score_user(?,?,?,?,?,?,?) as nationality", [$country,$user_country,$user_document_type,$points_national,$points_rut,$points_provisional,$points_passport])[0]->nationality;
	}

	public function guarantorConfirmationScore($user_confirmed_collateral, $user_tenanting_insurance){
		$points_confirmed = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 9")[0]->points;
		$points_unconfirmed = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 10")[0]->points;
		$points_insurance = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 11")[0]->points;

		return DB::select("select sf_endorsement_score_user(?,?,?,?,?) as endorsement", [$user_confirmed_collateral, $points_confirmed, $points_unconfirmed, $user_tenanting_insurance, $points_insurance])[0]->endorsement;
	}

	public function jobUserScore($user_employment_type){
		$points_employ = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 20")[0]->points;
		$points_free = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 27")[0]->points;
		$points_unemploy = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 33")[0]->points;

		return DB::select('select sf_job_user_score(?,?,?,?) as job', [$user_employment_type,$points_employ,$points_free,$points_unemploy])[0]->job;
	}
	
	public function docsUserScore($user_id, $user_employment_type){

		$points_afpE = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 21")[0]->points;
		$points_dicomE = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 22")[0]->points;
		$points_f_set = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 23")[0]->points;
		$points_s_set = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 24")[0]->points;
		$points_t_set = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 25")[0]->points;
		$points_work = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 26")[0]->points;

		$points_afpF = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 28")[0]->points;
		$points_dicomF = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id =29" )[0]->points;
		$points_otherF = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 30")[0]->points;
		$points_savesF = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 31")[0]->points;
		$points_last = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 32")[0]->points;

		$points_afpU = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 34")[0]->points;
		$points_dicomU = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 35")[0]->points;
		$points_otherU = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 36")[0]->points;
		$points_savesU = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 37")[0]->points;

		return DB::select("select sf_docs_user_score(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) as docs",
							[$user_id, $user_employment_type, $points_afpE, $points_afpF, $points_afpU, 0, 0, 0,
							$points_otherF, $points_otherU, $points_savesF, $points_savesU, $points_f_set, $points_s_set, $points_t_set, $points_work, $points_last])[0]->docs;
	}

	public function finantialUserScore($user_id, $user_employment_type, $user_expenses_limit, $user_amount, $user_save_amount, $user_other_income_amount, $user_last_invoice_amount){
		$points_finantial = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 6 AND scoring_id=1  AND det_id = 54")[0]->points;
		return DB::select("select sf_finantial_user_score(?,?,?,?,?,?,?,?) as finantial", [$user_id,$user_employment_type, $user_expenses_limit, $user_amount, $user_save_amount, $user_other_income_amount, $user_last_invoice_amount, $points_finantial])[0]->finantial;
	}

	public function membershipUserScore($user_id){
		$points_membership1 = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 51")[0]->points;
		$points_membership2 = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 52")[0]->points;
		$points_membership3 = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 53")[0]->points;

		return DB::select("select sf_membership_user_score(?, ?, ?, ?) as membership", [$user_id, $points_membership1, $points_membership2, $points_membership3])[0]->membership;
	}

	public function conditionsScore($user_warranty_months_quantity,$user_months_advance_quantity,$user_tenanting_months_quantity){
		
		$points_warranty1 = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 43")[0]->points;
		$points_warranty2 = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 44")[0]->points;
		$points_warranty3 = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 45")[0]->points;
		$points_advancement1 = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 46")[0]->points;
		$points_advancement2 = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 47")[0]->points;
		$points_advancement3 = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 48")[0]->points;
		$points_time1 = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 49")[0]->points;
		$points_time2 = DB::select("SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 50")[0]->points;

		return DB::select('select sf_conditions_score(?,?,?,?,?,?,?,?,?,?,?) as conditions',[$user_warranty_months_quantity,$user_months_advance_quantity,$user_tenanting_months_quantity,$points_warranty1,$points_warranty2,$points_warranty3,$points_advancement1,$points_advancement2,$points_advancement3,$points_time1,$points_time2])[0]->conditions;
	}

	public function dicomScore($user_id){
		$dicom = DB::select("select factor from files where user_id = ? AND name = 'dicom'", [$user_id])[0]->factor;
		return (($dicom*100)/999);
	}
}
