<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class ServiceList extends Model
{
	/**
		Class Constraints
	*/
	protected $table = "services_list";
	/**
		Fillables and Model Config
	*/
	protected $fillable = [
		'name'
	];
	/**
		ORM Relationships
	*/
	public function companies()
	{
		return $this->belongsToMany('App\Company','companies_has_services_list', 'service_list_id', 'company_id');
	}
	public function serviceType()
	{
		return $this->belongsTo('App\ServiceType');
	}

	public function firstsCompanies()
	{
		//First 8 for initial services explore

		return $this->belongsToMany('App\Company','companies_has_services_list','service_list_id', 'company_id')
				->select('companies.*', 'v_services.*')
	    	->join('v_services', 'companies_has_services_list.company_id', '=', 'v_services.company_id')
				->limit(8)
				->orderBy('companies_has_services_list.company_id', 'desc');
	}
}