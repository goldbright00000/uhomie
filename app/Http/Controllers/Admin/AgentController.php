<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{User, Country, CivilStatus, Amenity, Membership, City, File, Property, PropertyType,Company ,Photo};
use Carbon\Carbon;
use App\Notifications\AgentProfile;
class AgentController extends Controller
{
    //
	public function personalData(Request $request){
	    $user = User::find($request->userId);
        $user->saveBasicData($request,false);
        $user->updateProfileRedirect('users.agents.first-step.two','Agente');
        File::generateFile($user, File::DICOM, File::FIN_FILES_TYPE);
        return response(['success' => true]);
	}
	public function locationData(Request $request){
	    $user = User::find($request->userId);
        $user->saveLocationData($request);
		$user->updateProfileRedirect('users.agents.first-step.three','Agente');
        return response(['success' => true]);
	}
	public function companyData(Request $request){
	    $user = User::find($request->userId);
		if (!is_null($user->getAgentCompany())) {
		    $company = $user->getAgentCompany();
		}else{
		    $company = new Company();
		}
		$company->saveBasicData($request,false);
		$user->updateProfileRedirect('users.agents.first-step.four','Agente');
        return response(['success' => true]);
	}
	public function companyLocationData(Request $request){
	    $user = User::find($request->userId);
        $company = $user->getAgentCompany();
        $company->personal_address = $request->personal_address;
        if ($request->personal_address == 1) {
    		$company->city_id = $user->city_id;
    	    $company->address = $user->address;
    	    $company->address_details = $user->address_details;
    	    $company->latitude = $user->latitude;
    	    $company->longitude = $user->longitude;
        }else{
    		$company->city_id = $request->city;
    	    $company->address = $request->address;
    	    $company->address_details = $request->address_details;
    	    $company->latitude = $request->latitude;
    	    $company->longitude = $request->longitude;
        }
        $company->save();
       	$user->updateProfileRedirect('users.agents.memberships','Agente');
        return response(['success' => true]);
	}
}
