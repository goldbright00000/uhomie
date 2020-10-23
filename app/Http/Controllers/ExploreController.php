<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Amenity, Membership, City, Commune, PropertyFor, PropertyType, 
	Country, Property, ServiceList, Project, Photo, Useradmin};
use \Auth;
use DB;
use App\DbViews\{Property as vProperty, PropertyWU as vPropertyWu};
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;
use Recombee\RecommApi\Exceptions as Ex;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\ImageException;


class ExploreController extends Controller
{
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
			
				
				$communes = Commune::select(DB::raw("CONCAT(communes.name, ', ', cities.name, ', Chile') as name"), 'cities.id as citie_id', 'communes.id as commune_id')
									->join('cities', 'communes.city_id', '=', 'cities.id')
									->where('communes.name', 'like', '%'.$request->input('query').'%')
									->orWhere('cities.name', 'LIKE', '%'.$request->input('query').'%')
									->orderBy('name', 'asc')
									->distinct()
									->get();
				
        $cities = City::select(DB::raw("CONCAT(cities.name, ', ',regions.name,', Chile') as name"), 'cities.id as citie_id')
        				->join('regions', 'cities.region_id', '=', 'regions.id')
        				//->leftJoin('communes', 'cities.id', '=', 'communes.city_id')
                ->where('cities.name', 'like', '%'.$request->input('query').'%')
								->orderBy('name', 'asc')
								->distinct()
                ->get();

        //Devolver ambos resultados
        $data = $communes->toBase()->merge($cities);

                  
        return response()->json($data);
    }


	public function index(Request $request){

		if ($request->raw) {
			// City & Comune not found, raw text
			$address = str_replace(' ', '%', $request->raw);

			$commune = Commune::where('name', 'like', '%'.$address.'%')->first();

			if (!$commune) {
				$city = Country::find(39)->cities()->where('cities.name', 'like', '%'.$request->raw.'%')->first();
				if ($city) {
					return view('pages.explore')->with(['city' => $city]);
				}
			}else {
				// Return commune
				return view('pages.explore')->with(['village' => $commune]);
			}
		}elseif ($request->commune) {
			// Receive commune id
			$commune = Commune::where('name', $request->commune)->first();
			return view('pages.explore')->with(['village' => $commune]);
		}

		return view('pages.explore');
	}

	public function getBasicFilters(){
        return response( [
            // 'region' => Region::select('region.id as id', 'region.name as text')->get(),
			'cities' => Country::find(39)->cities()->select('cities.id as id', 'cities.name as text')->orderBy('cities.name')->get(),
			'villages' => Commune::select('communes.id', 'communes.name as text')->orderBy('communes.name')->get(),
      'memberships' => Membership::where(['role_id' => Membership::TYPE_OWNER, 'enabled' => true])->select('id', 'name as text')->get(),
			'propertyTypes' => PropertyType::select('id', 'name as text')->get(),
			// 'projectStatus' => Project::select('id', 'name as text')->get(),
			// 'projectStatus' => Project::where(['role_id' => Membership::TYPE_OWNER, 'enabled' => true])->select('id', 'name as text')->get(),
			'profiles' => PropertyFor::select('id', 'name as text')->get(),
			'service' => ServiceList::select('id', 'name as text')->get(),
			
            'unitAmenities' => Amenity::where('type', false)->select('id', 'name as text')->get(),
            'condoAmenities' => Amenity::where('type', true)->select('id', 'name as text')->get()
        ]);
    }
	public function getRecommendedProperties(Request $request, $cantidad){
		if( ctype_digit($cantidad) ){
			if( $numero_parametro = intval($cantidad) ){
				if ( $numero_parametro > 0 && $numero_parametro <= 8 ){
					$cantidad_recomendaciones = $numero_parametro;
				}else{
					$cantidad_recomendaciones = 4;
				}
			} else {
				$cantidad_recomendaciones = 4;
			}
		} else {
			$cantidad_recomendaciones = 4;
		}
		$arreglo = array();
		if( $user = \Auth::user() ){
			try
				{
					$client = new Client(env('RECOMBEE_PROD_ID_DATABASE', 'uhomy-prod'), env('RECOMBEE_PROD_PRIVATE_TOKEN', 'g5cvt1drVG6ULsopNvfuf5P6UEQXnQuU7EKUkc4O7VENS9LEdqv6BIyAMp4QyK8b') );
					$recommended = $client->send( new Reqs\RecommendItemsToUser("user_{$user->document_number}", $cantidad_recomendaciones) );
					foreach( $recommended['recomms'] as $r ){
						if( $propiedad = Property::find(substr($r['id'], 9))  ){
							$arreglo[] = $propiedad;
						}
						
					}
				}
			catch(Ex\ApiException $e)
			{
				//return dd($e);
			}
		} else {
			try
				{
					$client = new Client(env('RECOMBEE_PROD_ID_DATABASE', 'uhomy-prod'), env('RECOMBEE_PROD_PRIVATE_TOKEN', 'g5cvt1drVG6ULsopNvfuf5P6UEQXnQuU7EKUkc4O7VENS9LEdqv6BIyAMp4QyK8b') );
					$recommended = $client->send( new Reqs\RecommendItemsToUser("user_guest_".$request->session()->get('_token'), 4) );
					foreach( $recommended['recomms'] as $r ){
						if( $propiedad = Property::find(substr($r['id'], 9))  ){
							$arreglo[] = $propiedad;
						}
						
					}
				}
			catch(Ex\ApiException $e)
			{
				//return dd($e);
			}
		}
		$properties = array();
		$contadorFor = 1;
		$contadorArreglo = 0;
		foreach ($arreglo as $key => $propiedad) {
			$cover_photo = 'images/explore/img_propiedad.png';
			if( isset($user) ){
				if( DB::table('users_has_properties')->select('*')->where('type', 3)->where('property_id', $propiedad->id)->where('user_id', $user->id)->count() > 0){
					$favorite = true;
				}
			}
			if( isset($user) ){
				$query = DB::table('users_has_properties')->select('*')->where('type', 2)->where('property_id', $propiedad->id)->where('user_id', $user->id);
				if( $query->count() > 0 ){
					$query2 = DB::table('postulates')->select('*')->where('id', $query->first()->id );
					if($query2->first()->espera == 0){
						$applied = true;
					}
				}
			}
			$property = (object)[
				'id' => (int)$propiedad->id,
				'imagePath' => $propiedad->photos()->first()->path,
				'name' => $propiedad->name,
				'slug' => strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $propiedad->name))),
				'description' => $propiedad->description,
				'price' => (float)$propiedad->rent,
				'scoring' => isset($user) ? (int)DB::select('select sf_score(?,?) as score', [ $user->id , $propiedad->id ])[0]->score : 0 ,
				'membership' => strtolower($propiedad->getOwner()->getOwnerMerbershipOnce()->name),
				'verified' => (bool)$propiedad->verified,
				'demand' => (int)DB::select('select sf_demand_property(?) as demand', [$propiedad->id])[0]->demand,
				'favorite' => isset($favorite) ? true : false,
				'applied' => isset($applied) ? true : false,
				'bathNumber' => (int)$propiedad->bathrooms,
				'roomNumber' => (int)$propiedad->bedrooms,
				'parkingNumber' => (int)$propiedad->private_parking,
				'imagesDir' => asset('images'),
				'latitude' => $propiedad->latitude,
				'longitude' => $propiedad->longitude,
				'available' => (bool)$propiedad->available
			];
			if( $contadorFor == 1){
				$properties[] = array();
				$properties[$contadorArreglo][] = $property;
			} else {
				if( $contadorFor%2 == 0 ){ // si es par
					$properties[$contadorArreglo][] = $property;
					$contadorArreglo++;
				} else { // si es impar
					$properties[] = array();
					$properties[$contadorArreglo][] = $property;
				}
			}
			$contadorFor++;
		}
		return response()->json( $properties );
	}
	public function getSimilarProperties(Request $request, $id){
		$arreglo = array();
		if( $user = \Auth::user() ){
			try
				{
					$client = new Client(env('RECOMBEE_PROD_ID_DATABASE', 'uhomy-prod'), env('RECOMBEE_PROD_PRIVATE_TOKEN', 'g5cvt1drVG6ULsopNvfuf5P6UEQXnQuU7EKUkc4O7VENS9LEdqv6BIyAMp4QyK8b'));
					$recommended = $client->send( new Reqs\RecommendItemsToItem("property_{$id}","user_{$user->document_number}", 4, [ 'logic' => 'recombee:similar' ]) );
					foreach( $recommended['recomms'] as $r ){
						if( $propiedad = Property::find(substr($r['id'], 9)) ){
							$arreglo[] = $propiedad;
						}
					}
				}
			catch(Ex\ApiException $e)
			{
				
			}
		} else {
			try
				{
					$client = new Client(env('RECOMBEE_PROD_ID_DATABASE', 'uhomy-prod'), env('RECOMBEE_PROD_PRIVATE_TOKEN', 'g5cvt1drVG6ULsopNvfuf5P6UEQXnQuU7EKUkc4O7VENS9LEdqv6BIyAMp4QyK8b'));
					$recommended = $client->send( new Reqs\RecommendItemsToItem("property_{$id}","user_guest_".$request->session()->get('_token'), 4) );
					foreach( $recommended['recomms'] as $r ){
						if( $propiedad = Property::find(substr($r['id'], 9)) ){
							$arreglo[] = $propiedad;
						}
						
					}
				}
			catch(Ex\ApiException $e)
			{
				
			}
		}
		$properties = array();
		$contadorFor = 1;
		$contadorArreglo = 0;
		foreach ($arreglo as $key => $propiedad) {
			$cover_photo = 'images/explore/img_propiedad.png';
			if( isset($user) ){
				if( DB::table('users_has_properties')->select('*')->where('type', 3)->where('property_id', $propiedad->id)->where('user_id', $user->id)->count() > 0){
					$favorite = true;
				}
			}
			if( isset($user) ){
				if( DB::table('users_has_properties')->select('*')->where('type', 2)->where('property_id', $propiedad->id)->where('user_id', $user->id)->count() > 0 ){
					$applied = true;
				}
			}
			
			$property = (object)[
				'id' => (int)$propiedad->id,
				'imagePath' => $propiedad->photos()->first()->path,
				'name' => $propiedad->name,
				'slug' => strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $propiedad->name))),
				'description' => $propiedad->description,
				'price' => (float)$propiedad->rent,
				'scoring' => isset($user) ? (int)DB::select('select sf_score(?,?) as score', [ $user->id , $propiedad->id ])[0]->score : 0 ,
				'membership' => strtolower($propiedad->getOwner()->getOwnerMerbershipOnce()->name),
				'verified' => (bool)$propiedad->verified,
				'demand' => (int)DB::select('select sf_demand_property(?) as demand', [$propiedad->id])[0]->demand,
				'favorite' => isset($favorite) ? true : false,
				'applied' => isset($applied) ? true : false,
				'bathNumber' => (int)$propiedad->bathrooms,
				'roomNumber' => (int)$propiedad->bedrooms,
				'parkingNumber' => (int)$propiedad->private_parking,
				'imagesDir' => asset('images'),
				'latitude' => $propiedad->latitude,
				'longitude' => $propiedad->longitude,
				'available' => (bool)$propiedad->available
			];
			if( $contadorFor == 1){
				$properties[] = array();
				$properties[$contadorArreglo][] = $property;
			} else {
				if( $contadorFor%2 == 0 ){ // si es par
					$properties[$contadorArreglo][] = $property;
					$contadorArreglo++;
				} else { // si es impar
					$properties[] = array();
					$properties[$contadorArreglo][] = $property;
				}
			}
			$contadorFor++;
		}
		return response()->json( $properties );
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

		// 1 is owner and 5 is agents
		//$qb->where('type_user',1);

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
		if ($request->type_user) $qb->where('type_user', $request->type_user);
		if ($request->type_stay) $qb->where('type_stay', $request->type_stay);
		
		if ($request->features) {
			$request->features = json_decode($request->features);

			if ($request->features->bathrooms) $qb->where('bathrooms', '>=' ,$request->features->bathrooms);
			if ($request->features->rooms) $qb->where('bedrooms', '>=' ,$request->features->rooms);
			if ($request->features->propertyVerified) $qb->where('verified', $request->features->propertyVerified);
			if ($request->features->pets) $qb->where('pet_preference', '<>', 'no');
			if ($request->features->parking) $qb->where('private_parking', '>=', $request->features->parking);
			if ($request->features->furnished) $qb->where('furnished', $request->features->furnished);		
		}
		$db_properties = $qb->select($select_fields)->distinct()->limit($limit)->offset($offset)->orderBy('property_id', 'DESC')->get();
		
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
					'type_stay' => $dbp->type_stay,
					'type_user' => $dbp->type_user
				];
				$properties[] = $property;
			}
		}

		return response([
			'properties' => $properties,
			'offset' => $offset
		]);
	}
	public function getDocumentProperty(Request $request){
		$property = Property::find($request->id);

		$properties_for = $property->propertiesFor()->get();
		$properties_type = $property->propertyType()->first();

		$units = $property->amenities()->where('type', false)->get();
		$communes = $property->amenities()->where('type', true)->get();

		$data_property_for = '';

		$money = number_format($property->rent, 0, ',', '.');

		foreach ($properties_for as $i => $for) {
			if($i == count($properties_for) - 1){
				$data_property_for = $data_property_for . $for->name;
			} else {
				$data_property_for = $data_property_for . $for->name . ', ' ;
			}
		}
		
		$collateral_require = $property->collateral_require == '1' ? 'Si': 'No';

		if($property->warranty_months_quantity <= 1){
			$warranty_months_quantity = '1 Mes';
		} else {
			$warranty_months_quantity = $property->warranty_months_quantity . ' Meses';
		}

		if($property->months_advance_quantity <= 1){
			$months_advance_quantity = '1 Mes';
		} else {
			$months_advance_quantity = $property->months_advance_quantity . ' Meses';
		}
		
		if($property->tenanting_months_quantity <= 1){
			$tenanting_months_quantity = '1 Mes';
		} else {
			$tenanting_months_quantity = $property->tenanting_months_quantity . ' Meses';
		}

		$condition = $property->condition == 1 ? "Nueva" : "Usada";

		$public_parking = $property->public_parking ? "Si" : "No";

		$furnished = $property->furnished ? "Si" : "No";
		
		$cellar = $property->cellar ? "Si" : "No";

		$tenanting_insurance = $property->tenanting_insurance ? "Si" : "No";

		$photo = Photo::with('space')->where(['property_id' => $property->id, 'cover' => true])->first();

		$data_units = '';
		if(count($units) >= 1){
			foreach ($units as $key => $value) {
				$data_units = $data_units . '<li>'. $value->name .'</li>';
			}
		} else {
			$data_units = $data_units . '<li>Esta propiedad no posee ninguno de estos servicios</li>';
		}

		$data_communes = '';
		if(count($communes) >= 1){
			foreach ($communes as $key => $value) {
				$data_communes = $data_communes . '<li>'. $value->name .'</li>';
			}
		} else {
			$data_communes = $data_communes . '<li>Esta propiedad no posee ninguno de estos servicios</li>';
		}
		

		$cover = url('/') . $photo->path;
		//$cover = 'https://www.uhomie.cl/storage/properties/7/photos/photo_-20190712070712151119.jpeg';
		$link = url('/') . '/explorar/' . $property->id . '/' . $property->slug;

        try {
            $html2pdf = new Html2Pdf('P', 'LETTER', 'es');
            $html2pdf->setDefaultFont('helvetica');
			$html2pdf->writeHTML('
			<page backtop="12mm" backbottom="12mm" backleft="10mm" backright="12mm"> 
				<page_header>
					<img style="width: 100px" src="https://www.uhomie.cl/images/logo_completo.png"/> 
					<hr style="color: #7a7a7a;">  
				</page_header>
				<page_footer>
					<hr style="color: #7a7a7a;"> 
					<div style="color: #7a7a7a;">
						<div style="float: left;">&copy; <a href="www.uhomie.cl">www.uhomie.cl</a> </div>
						<div style="float: right;">Pagina <b>[[page_cu]]</b> de <b>[[page_nb]]</b></div>
					</div>
				</page_footer>
				<div style="width: 100%; color: #7a7a7a;">
					<table style="width: 100%;">
						<tr>
							<td style="width: 30%">
								<!--<div style="width: 100%; height: 100px; background-image: url('.$cover.'); background-size: 300px 100px; background-repeat: no-repeat; margin-top: 0px; margin-left: 0px;">
								</div>-->
								<a href="'. $link .'">
									<img style="width: 100%" src="'.$cover.'"/>
								</a>
							</td>
							<td style="width: 70%">
								<div style="margin-left: 15px;">
									<div>
										<h1>
											<a style="color: #7a7a7a; text-decoration: none;" href="'. $link .'">Información de la Propiedad ID '. $property->id .'</a>
										</h1>
									</div>
									<hr style="color: #7a7a7a;">
									<div><b>Nombre:</b> '. $property->name .'</div>
									<div><b>Tipo de Propiedad:</b> '. $properties_type->name .'</div>
									<div><b>Precio:</b> $ '. $money.' CPL</div>
									<div><b>Dirección:</b> '. $property->address .'</div>
									<div><b>Descripción:</b> '. $property->description .'</div>
								</div>
							</td>
						</tr>
					</table>
					<hr style="color: #7a7a7a;">
					<table style="width: 100%;">
						<tr>
							<td style="width: 20%;">
								<div style="text-align: center;"><b>Habitaciones</b></div>
								<div style="text-align: center;">'. $property->bedrooms .'</div>
							</td>
							<td style="width: 20%;">
								<div style="text-align: center;"><b>Baños</b></div>
								<div style="text-align: center;">'. $property->bathrooms .'</div>
							</td>
							<td style="width: 20%;">
								<div style="text-align: center;"><b>Estacionamientos Privados</b></div>
								<div style="text-align: center;">'. $property->private_parking .'</div>
							</td>
							<td style="width: 20%;">
								<div style="text-align: center;"><b>Amoblado</b></div>
								<div style="text-align: center;">'. $furnished .'</div>
							</td>
							<td style="width: 20%;">
								<div style="text-align: center;"><b>Metros Cuadrados</b></div>
								<div style="text-align: center;">'. $property->meters .'<b>m<sup>2</sup></b></div>
							</td>
						</tr>
					</table>
					<hr style="color: #7a7a7a;">
					<table style="width: 100%;">
						<tr>
							<td style="width: 40%;">
								<div><b>Condiciones de Arriendo</b></div>
								<hr style="color: #7a7a7a;">
								<div>Esta propiedad es apta para: '. $data_property_for .'</div>
								<br>
								<table style="width: 100%;">
									<tr>
										<td>Aval:</td>
										<td>'. $collateral_require .'</td>
									</tr>
									<tr>
										<td>Meses de Garantia:</td>
										<td>'. $warranty_months_quantity .'</td>
									</tr>
									<tr>
										<td>Meses de Adelanto:</td>
										<td>'. $months_advance_quantity  .'</td>
									</tr>
									<tr>
										<td>Tiempo minimo de arriendo:</td>
										<td>'. $tenanting_months_quantity .'</td>
									</tr>
									<tr>
										<td>Condición:</td>
										<td>'. $condition .'</td>
									</tr>
									<tr>
										<td>Estacionamiento Público:</td>
										<td>'. $public_parking .'</td>
									</tr>
									<tr>
										<td>Amoblada:</td>
										<td>'. $furnished .'</td>
									</tr>
									<tr>
										<td>Bodega:</td>
										<td>'. $cellar .'</td>
									</tr>
									<tr>
										<td>Seguro de Arrendatario:</td>
										<td>'. $tenanting_insurance .'</td>
									</tr>
								</table>
							</td>
							<td style="width: 30%;">
								<div><b>Servicios de la Unidad</b></div>
								<hr style="color: #7a7a7a;">
								<ul>
									'.$data_units.'
								</ul>
							</td>
							<td style="width: 30%;">
								<div><b>Servicios de Condominio</b></div>
								<hr style="color: #7a7a7a;">
								<ul>
									'.$data_communes.'
								</ul>
							</td>
						</tr>
					</table>
				</div>
			</page>
			');
            $html2pdf->output('example00.pdf');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
	}
	public function getExecutive(Request $request){
		$property = Property::find($request->id);
		if($property->executive_id != null){
			$executive = Useradmin::where('id',$property->executive_id)->first();

			return response($executive);
		} else {
			return null;
		}
		
	}
}