<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Auth;
use Carbon\Carbon;
use App\{Service,ServiceList,ServiceType,Country,CivilStatus,File,Company,Membership,Photo};
use App\Notifications\{ServiceProfile};
use Illuminate\Support\Facades\DB;
use App\Payment;

class ServiceListController extends Controller
{
    public function registrationFirstStepOne(Request $request){
        $user = \Auth::user();
        $payment = Payment::where('order', $request->order)->where('user_id', $user->id)->first();
        if($payment){
          switch($payment->status){
            case 0:
                $payment->statusname = 'Anulada';
            break;
            case 1:
                $payment->statusname = 'Aprovada';
            break;
            case 2:
                $payment->statusname = 'Cancelada';
            break;
            default: 
                $payment->statusname = 'Inconclusa';
            break;
          }
        } else {
          $payment = null;
        }
        
        if ($request->isMethod('get')) {
            $company = Company::where('user_id', $user->id)->first();
            $photos = Photo::where('service_list_id', '>=', 1)->where('company_id', $company->id)->get();

            if (!is_null($photos)) {
                foreach($photos as $photo){
                $photo->delete();
                }
            }
            $user->updateProfileRedirect('services.first-step.one','Servicio');
            $service_memberships = Membership::getServiceMemberships();
            $user = \Auth::user();
            foreach ($service_memberships as $sm) {
                if ($sm->users->contains($user->id)) {
                  $features = json_decode($sm->features,true);
                  break;
                }
            }
            return view('services.forms.first-step-one')->with(
              [
                'user' => \Auth::user(),
                'services_list'=>ServiceList::all(),
                'services_type'=>ServiceType::all(),
                "main_services_limit" => $features["main_services"],
                "secondary_services_limit" => $features["secondary_services"],
                "payment" => $payment
              ]
            );

        }elseif($request->isMethod('post')){

            $company = $user->getServiceCompany();
            $company->servicesList()->sync($request->secondary_services);
            $company->description = $request->description;
            $company->save();
            $user->updateProfileRedirect('services.first-step.two','Servicio');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect()->route('services.first-step.two');
            }
        }
    }
    public function registrationFirstStepTwo(Request $request){

        $user = \Auth::user();

        if ($request->isMethod('get')) {

            $company = Company::where('user_id', $user->id)->first();
            $photos = Photo::where('service_list_id', '>=', 1)->where('company_id', $company->id)->get();

            if (!is_null($photos)) {
                foreach($photos as $photo){
                $photo->delete();
                }
            }

            $service_memberships = Membership::getServiceMemberships();
            $user = \Auth::user();
            $company_services = DB::table('companies')
              ->select('services_list.name','companies_has_services_list.service_list_id','companies_has_services_list.id')
              ->join('companies_has_services_list', 'companies_has_services_list.company_id', '=', 'companies.id')
              ->join('services_list', 'services_list.id', '=', 'companies_has_services_list.service_list_id')
              ->where('companies.user_id', $user->id)
              ->get();
            foreach ($service_memberships as $sm) {
      					if ($sm->users->contains($user->id)) {
      						$features = json_decode($sm->features,true);
      						break;
      					}
            }
            return view('services.forms.first-step-two')->with(['user' => \Auth::user(),'id' => 1, 'photos' => null,'photo_limit' => $features['photos_per_project'] , 'services' => $company_services ]);
        }elseif($request->isMethod('post')){
            $company = $user->getServiceCompany();
            $company->save();
            //$user->updateProfileRedirect(null,'Servicio');
            //$service
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect()->route('services.first-step.three');
            }
        }
    }
    public function registrationFirstStepThree(Request $request){

        $user = \Auth::user();
        if ($request->isMethod('get')) {
            $service_memberships = Membership::getServiceMemberships();
            $user = \Auth::user();
            $company_services = DB::table('companies')
              ->select('services_list.name','companies_has_services_list.service_list_id','companies_has_services_list.id','companies_has_services_list.description')
              ->join('companies_has_services_list', 'companies_has_services_list.company_id', '=', 'companies.id')
              ->join('services_list', 'services_list.id', '=', 'companies_has_services_list.service_list_id')
              ->where('companies.user_id', $user->id)
              ->get();
            foreach ($service_memberships as $sm) {
                if ($sm->users->contains($user->id)) {
                  $features = json_decode($sm->features,true);
                  break;
                }
            }
            return view('users.services.forms.second-step-five')->with(['user' => \Auth::user(), 'services' => $company_services]);

        }elseif($request->isMethod('post')){

            $description = $request->description;
            $id = $request->id;

            foreach ($id as $key => $value) {
              DB::table('companies_has_services_list')
                ->where('id', $id[$key])
                ->update([
                  'description' => $description[$key]
                ]);
            }
            //$user->updateProfileRedirect(null,'Servicio');

            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/profile/service');
            }
        }
    }
}
