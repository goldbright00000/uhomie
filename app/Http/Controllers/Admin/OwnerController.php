<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\{User, Country, CivilStatus, City, Membership, Property,PropertyType,File,Amenity,PropertyFor, Photo, Space, Bank};
use App\Notifications\{OwnerProfile};
class OwnerController extends Controller
{
    //
	public function personalData(Request $request){
	    $user = User::find($request->userId);
        $user->saveBasicData($request);
        $user->updateProfileRedirect('users.owners.first-step.two','Propietario');
        return response(['success' => true]);
	}
	public function locationData(Request $request){
	    $user = User::find($request->userId);
        $user->saveLocationData($request);
        $user->updateProfileRedirect('users.owners.second-step','Propietario');
        return response(['success' => true]);
	}

}
