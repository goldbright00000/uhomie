<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\{Service,ServiceList,ServiceType,Country,CivilStatus,File,Company,Membership,Photo,User};
use App\DbViews\Service as vService;
use App\Notifications\{ServiceProfile};

class ServiceController extends Controller
{

	public function getServices( Request $request ){
  	$db_services = vService::with('company')
  						->with('user')
  						->with('city')
  						->get();
    $records = [];    

    foreach ($db_services as $p) {

      $records[] = [
        "company" => [
        	'id' => $p->company_id,
        	'name' => $p->company->name
        ],
        "user" => [
        	'id' => $p->user_id,
        	'name' => $p->user->lastname.', '.$p->user->firstname,
        	'email' => $p->user->email
        ],
        'city' => $p->city->name,
        "name" => $p->name,
        'description' => $p->description,
        'email' => $p->email,
        'address' => $p->address,
        'phone' => $p->phone,
        'cell_phone' => $p->cell_phone,
        'membership' => strtolower($p->membership_name)
      ];
    }
    return response([ 'records' => $records ]);
	}

	public function personalData(Request $request){
		$user = User::find($request->userId);
		$user->saveBasicData($request);
		$user->updateProfileRedirect('users.services.first-step.two','Servicio');
		return response(['success' => true]);
	}
	public function locationData(Request $request){
		$user->saveLocationData($request);
		$user->updateProfileRedirect('users.services.second-step','Servicio');
		return response(['success' => true]);
	}
	public function companyData(Request $request){
		$user = User::find($request->userId);
		if (!is_null($user->getServiceCompany())) {
		  $company = $user->getServiceCompany();
		}else{
		  $company = new Company();
		}
		$company->saveBasicData($request);
		if (!$request->invoice) {
		  $company->personal_publish = 1;
		  $user->updateProfileRedirect('users.services.memberships','Servicio');
		}
		$user->updateProfileRedirect('users.services.second-step.two','Servicio');
		$company->save();
		return response(['success' => true]);
	}
}


