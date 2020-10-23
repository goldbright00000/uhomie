<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Country, ServiceList, Membership,Company, Commune, Photo};
use App\DbViews\Service as vService;

class ServiceExploreController extends Controller
{
	public function index(Request $request){

		//$servicesList = ServiceList::orderBy('name')->get();

		return view('pages.services');
	}

	public function getFilters(){
		return response( [
			'cities' => Country::find(39)->cities()->select('cities.id as id', 'cities.name as text')->orderBy('text')->get(),
			'services_list' => ServiceList::select('id', 'name as text')->orderBy('name')->get(),
			'villages' => Commune::select('communes.id', 'communes.name as text')->orderBy('communes.name')->get(),
			'memberships' => Membership::where(['role_id' => Membership::TYPE_SERVICE, 'enabled' => true])->select('id', 'name as text')->get()
		]);
	}

	/*
	*	Retorna sobre las 3 categorias princiaples en los cuales se tienen mas servicios
	*/
	public function fetchInitialServices(Request $request){

		//Devuelvo los 3 que mas companias tienen
		$servicesList = ServiceList::with('firstsCompanies')
						->withCount(['firstsCompanies as firsts_companies_count' => function($query) {
								$query->whereIn('company_id', function($query) {
									$query->select('company_id')->from('v_services');
								});
						}])
						->orderBy('firsts_companies_count', 'DESC')
						->having('firsts_companies_count', '>', '0')
						->limit(3)
						->get();

		$services = [];
		foreach ($servicesList as $s) {
			$records = [];    
	    foreach ($s->firstsCompanies as $p) {
	      $records[] = [
	        "id" => $p->id,
	        "user_id" => $p->user_id,
	        'city_id' => $p->city_id,
	        "name" => $p->name,
	        'description' => $p->description,
	        'profession' => $s->name,
	        'rating' => 0,
	        'email' => $p->email,
	        'address' => $p->address,
	        'phone' => $p->phone,
	        'cell_phone' => $p->cell_phone,
	        "imagePath" => $p->path,
	        'membership' => strtolower($p->membership_name)
	      ];
	    }
	    $services[] = [
	    	'id' => $s->id,
	    	'name' => $s->name,
	    	'service_type_id' => $s->service_type_id,
	    	'companies' => $records
	    ];
		}

    return response([ 'records' => $services ]);

	}

	public function getServices(Request $request){
		
		$qb = vService::query();

		$limit = $request->limit ? $request->limit : 4;
		$offset = $request->offset ? $request->offset : 0;

		if ($request->city) $qb->where('city_id', $request->city);
		if ($request->membership) $qb->where('membership_id', $request->membership);
		if ($request->city) $qb->where('city_id', $request->city);


		// if ($request->village) $qb->where('commune_id', $request->village);
		$db_services = $qb->limit($limit)->offset($offset)->get();

		$services = [];
		
		foreach ($db_services as $dbp) {
			$company = Company::with('servicesList')->find($dbp->company_id);

			$profession = null;
			foreach ($company->servicesList as $s) {
				$profession .= $profession ? ', '.$s->name : $s->name;
			}

			$filter = false;
			if($request->serviceListId) {
				$filter = $company->servicesList->contains($request->serviceListId) ? false : true;
			}

			if (!$filter) {
				$cover_photo = 'images/explore/img_propiedad.png';
				$service = (object)[
					'id' => $dbp->id,
					'imagePath' => $dbp->path,
					'rating' => 0,
					'name' => $dbp->user_firstname . ' ' . $dbp->user_lastname,
					'serviceType' => $dbp->service_type_name,
					'serviceName' => $dbp->service,
					'description' => $dbp->description,
					'membership' => strtolower($dbp->membership_name),
					'profession' => $profession,
					'imagesDir' => '/images',
					'serviceDescription' => $dbp->service_description,
					'phone' => $dbp->phone,
					'email' => $dbp->email,
					'address' => $dbp->company_address
					
				];
				$services[] = $service;
			}
		};
		return response([			
			'services' => $services,
			'offset' => $offset,
			'service' => $request->serviceListId
		]);
	}
	public function getService(Request $request){
		$qb = vService::query();

		$dbp = $qb->where('id', $request->id)->first();

		//dd($dbp);

		$service = (object)[
			'id' => $dbp->id,
			'user_id' => $dbp->user_id,
			'path' => $dbp->path,
			'rating' => 0,
			'name' => $dbp->user_firstname . ' ' . $dbp->user_lastname,
			'service_type_name' => $dbp->service_type_name,
			'service' => $dbp->service,
			'description' => $dbp->description,
			'membership' => strtolower($dbp->membership_name),
			'imagesDir' => '/images',
			'service_description' => $dbp->service_description,
			'phone' => $dbp->phone,
			'email' => $dbp->email,
			'address' => $dbp->address,
			'photos' => Photo::where('service_list_id',$dbp->id)->get()
		];
		return response([
			'data' => $service
			]); 
	}
}