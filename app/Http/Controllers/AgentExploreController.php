<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\{User, Company, Membership, Country, City, Commune, PropertyType, Project, Property, Photo};
use App\DbViews\Project as vProject;
use App\DbViews\Agent as vAgent;
use App\DbViews\{Property as vProperty, PropertyWU as vPropertyWu};

class AgentExploreController extends Controller
{
	public function index(Request $request){


		return view('pages.agents');
	}

	public function getAgents(Request $request)
	{
		$limit = $request->limit ? $request->limit : 6;
		$offset = $request->offset ? $request->offset : 0;

		$companies = DB::table('v_agent')
			->offset($offset)
			->limit($limit)
			->get();

    /*$companies = Company::select()
    				->whereExists(function($query) {
    					$query->select(DB::raw(1))
    							->from('v_agent')
    							->whereRaw('user_id = v_agent.agent_id');
    				})
    				->with('user')
    				///->with(['photos' => function($query) {
    					//$query->where('logo', '=', 1)->limit(1);
    				//}])
    				->where('companies.type', '=', 0) //Agentes
//    				->has('photos')
    				->offset($offset)
    				->limit($limit)
					->get();*/

   // $companies = vAgent::distinct('comapany_id')
    //		->with('company')->get();

    //return response([ 'records' => $companies ]);

    $agents = [];    

		foreach ($companies as $ag) {
			
			$cover = Photo::where('company_id', $ag->company_id)->where('logo', 1)->first();
			$agents[] = (object)[
				'id' => $ag->company_id,
				'imagePath' => $cover != null ? $cover->path : 'https://uhomie.cl/images/img_notfound.jpg',
				'name' => $ag->name,
				'phone' => $ag->phone,
				'cell_phone' => $ag->phone,
				'website' => $ag->website,
				'email' => $ag->email,
				'description' => $ag->description,
				'latitude' => $ag->latitude,
				'longitude' => $ag->longitude,
				'membership' => strtolower($ag->membership_name),
				'agent_id' => $ag->agent_id
			];
		};		

    return response([
		'records' => $agents,
		'offset' => $offset ]);
	}

	public function getFilters(){
		return response( [
			'cities' => Country::find(39)->cities()->select('cities.id as id', 'cities.name as text')->orderBy('cities.name')->get(),
			'villages' => Commune::select('communes.id', 'communes.name as text')->orderBy('communes.name')->get(),
			'memberships' => Membership::where(['role_id' => Membership::TYPE_AGENT, 'enabled' => true])->select('id', 'name as text')->get(),
			'propertyTypes' => PropertyType::select('id', 'name as text')->get()
		]);
	}

	public function getProjects(Request $request){

		$limit = $request->limit ? $request->limit : 8;
		$offset = $request->offset ? $request->offset : 0;

		$select_fields = [
			'property_id',
			'path',
			'name',
			'description',
			'rent',
			'membership_name',
			'verified',
			'demand',
			'bathrooms',
			'bedrooms',
			'private_parking',
			'available',
			'type_stay',
			'latitude',
			'longitude',
			'type_user',
		];

		if(isset($request->company_id)){

			/*$project_id = Project::find($request->company_id);
			$qb = vProject::query()->join('properties', 'v_agent.project_id', '=', 'properties.id');
			$qb->where('v_agent.company_id', $project_id->company_id);*/
			$qb = vProperty::query();
			$qb->where('is_project',1);
			$qb->where('company_id', $request->company_id);
			

		} else {
			//$qb = vProject::query()->join('properties', 'v_agent.project_id', '=', 'properties.id');
			
			$qb = vProperty::query();
			$qb->where('is_project',1);

		}

		

		// Ver mas sobre un agente:
		if ($request->agent) $qb->where('company_id', $request->agent);

		if ($request->city) $qb->where('city_id', $request->city);
		//if ($request->village) $qb->where('commune_id', $request->village);
		if ($request->priceRange) $qb->whereBetween('rent', [$request->priceRange[0], $request->priceRange[1]]);

		if ($request->membership) $qb->where('membership_id', $request->membership);
		if ($request->propertyType) $qb->where('property_type_id', $request->propertyType);

		if ($request->projectStatus) $qb->where('condition', $request->projectStatus);

		$db_projects = $qb->select($select_fields)->distinct()->limit($limit)->offset($offset)->orderBy('property_id', 'DESC')->get();

		

		$projects = [];
		$filter = false;
		foreach ($db_projects as $dbp) {

			//Commune filter
			if(!$filter && $request->village) {
				$village = Commune::find($request->village);
				if($village) {
					//$name = str_replace(' ', '*', $village->name);
					$filter = !preg_match('/'.$village->name.'/i', $p->address) ?  true : $filter;
				}
			}	

			

			if(!$filter) {
				$project = (object)[
					'id' => (int)$dbp->project_id,
					'imagePath' => $dbp->path,
					'name' => $dbp->name,
					'slug' => strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $dbp->name))),
					'description' => $dbp->description,
					'price' => (float)$dbp->rent,
					'membership' => strtolower($dbp->membership_name),
					'imagesDir' => asset('images'),
					'latitude' => isset($p->latitude) ? $p->latitude : '',
					'longitude' => isset($p->longitude) ? $p->longitude : ''
				];
				$projects[] = $project;
			}			
		}
		return response([
			'projects' => $projects,
			'offset' => $offset,
		]);
	}
	public function getProperties(Request $request) {
		$limit = $request->limit ? $request->limit : 8;
		$offset = $request->offset ? $request->offset : 0;
		$select_fields = [
			'property_id',
			'path',
			'name',
			'description',
			'rent',
			'membership_name',
			'verified',
			'demand',
			'bathrooms',
			'bedrooms',
			'private_parking',
			'available',
			'type_stay',
			'latitude',
			'longitude',
			'type_user',
		];

		$wu = false;
		//vPropertyWu falla :: reporte Lunes - 15 julio
		if (Membership::checkTenantMemberships()) {
			$wu = true;
			$qb = vPropertyWu::query();
			//dd($qb->select()->distinct()->limit($limit)->offset($offset)->get());
			$select_fields = array_merge($select_fields, ['score','favorite', 'applied']);
			
			$qb->where('user_id', \Auth::user()->id);
			
		}else {
			$qb = vProperty::query();
		}

		$qb->where('type_user',5);
		// 1 is project 0 is not project
		$qb->where('is_project',0);

		if($request->date) {
			//format date
			$available_date = \DateTime::createFromFormat('d-m-Y', $request->date);
		}
		
		if ($request->city) $qb->where('city_id', $request->city);
		//Commune deprecated
		//if ($request->village) $qb->where('commune_id', $request->village);

		if ($request->priceRange) $qb->whereBetween('rent', [$request->priceRange[0], $request->priceRange[1]]);
		if ($request->scoring && $wu) $qb->whereBetween('score', [$request->scoring[0], $request->scoring[1]]);
		if (isset($available_date)) $qb->where('available_date', '<=', $available_date);
		if ($request->membership) $qb->where('membership_id', $request->membership);
		if ($request->propertyType) $qb->where('property_type_id', $request->propertyType);
		if ($request->profile) $qb->where('property_for_id', $request->profile);
		if ($request->agent) $qb->where('company_id', $request->agent);
		
		if ($request->features) {
			$request->features = json_decode($request->features);

			if ($request->features->bathrooms) $qb->where('bathrooms', '>=' ,$request->features->bathrooms);
			if ($request->features->rooms) $qb->where('bedrooms', '>=' ,$request->features->rooms);
			if ($request->features->propertyVerified) $qb->where('verified', $request->features->propertyVerified);
			if ($request->features->pets) $qb->where('pet_preference', '<>', 'no');
			if ($request->features->parking) $qb->where('private_parking', '>=', $request->features->parking);
			if ($request->features->furnished) $qb->where('furnished', $request->features->furnished);		
		}
		$db_properties = $qb->select($select_fields)->distinct()->limit($limit)->offset($offset)->get();
		
		$properties = [];
		foreach ($db_properties as $dbp) {
			$cover_photo = 'images/explore/img_propiedad.png';

			//ADD Latitude & Longitud in vProperty not exists
			// Refactoring: modificar dbview

			// +filtros
			$filter = false;

			//Address:
			if($request->address) {
				$address = '%'.str_replace(' ', '%', $request->address).'%';
						
				$p = Property::where('properties.id', '=', $dbp->property_id)->where('properties.address', 'LIKE', $address)->first();					
			
				if(!$p) {
					$filter = true;
				}
			} else {
				$p = Property::find($dbp->property_id);
			}		
					

			if (!$filter && $request->features && $request->features->cellar) {
				$filter = $p->cellar ? $filter : true;
			}

			// -> insurance
			if (!$filter && $request->features && $request->features->insurance) {
				$filter = $p->tenanting_insurance ? $filter : true;
			}


			// -> unitAmenities
			if(!$filter && $request->unitAmenities) {
				foreach ($request->unitAmenities as $a) {
					$amenity = Amenity::find($a);
					$filter = $p->amenities->contains($amenity) ? $filter : true;
				}
			}

			// -> condoAmenity
			if(!$filter && $request->condoAmenity) {
				foreach ($request->condoAmenity as $a) {
					$amenity = Amenity::find($a);
					$filter = $p->amenities->contains($amenity) ? $filter : true;
				}
			}

			//Commune
			if(!$filter && $request->village) {
				$village = Commune::find($request->village);
				if($village) {
					//$name = str_replace(' ', '*', $village->name);
					$filter = !preg_match('/'.$village->name.'/i', $p->address) ?  true : $filter;
				}
			}			

			// --------
			if(!$filter) {
				//$membership = Property::find($dbp->property_id)->getAgent()->getAgentMerbershipOnce()->name;
				$property = (object)[
					'id' => (int)$dbp->property_id,
					'imagePath' => $dbp->path,
					'name' => $dbp->name,
					'slug' => strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $dbp->name))),
					'description' => $dbp->description,
					'price' => (float)$dbp->rent,
					'scoring' => (int)$dbp->score,
					'membership' => strtolower($dbp->membership_name),
					'verified' => (bool)$dbp->verified,
					'demand' => (int)DB::select('select sf_demand_property(?) as demand', [$dbp->property_id])[0]->demand,
					'favorite' => (bool) $dbp->favorite,
					'applied' => (bool) @$dbp->applied,
					'bathNumber' => (int)$dbp->bathrooms,
					'roomNumber' => (int)$dbp->bedrooms,
					'parkingNumber' => (int)$dbp->private_parking,
					'imagesDir' => asset('images'),
					//lat & lng
					'latitude' => isset($p->latitude) ? $p->latitude : '',
					'longitude' => isset($p->longitude) ? $p->longitude : '',
					'available' => (boolean)$dbp->available,
					'type_stay' => $dbp->type_stay
				];
				$properties[] = $property;
			}
		}

		return response([
			'properties' => $properties,
			'offset' => $offset
		]);
	}
	public function company(Request $request){
		$project_id = Project::where('company_id', $request->id)->where('deleted_at', null)->first();
		return redirect('/agentes/'.$project_id->id);
	}
}
