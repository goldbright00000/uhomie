<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{User, Country, Commune, CivilStatus, Amenity, Membership, City, PropertyType, Property, PropertyFor, File};
use App\Notifications\{CollateralMail, TenantProfile};
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmacionAval;
use App\Payment;

class TenantController extends Controller
{
    public function registrationSelectStayStep(Request $request)
    {
        if ($request->isMethod('get')) {
            $user = \Auth::user();
            $user->long_stay = true;
            $user->save();
            $user->updateProfileRedirect('users.tenants.first-step','Arrendatario');
            return redirect()->route('users.tenants.first-step');
            return view('users.tenants.forms.select-stay')->with(['user' => \Auth::user()]);
        } elseif($request->isMethod('post')){
            //dd($request);
            $user = $request->userId ? User::find($request->userId) : \Auth()->user();
            if( $request->estadia == 'short' ){
                $user->short_stay = true;
            }
            if( $request->estadia === 'long' ){
                $user->long_stay = true;
            }
            $user->save();
            /**
             * Guardar el dato de estadia baja o estadia larga
             */

            $user->updateProfileRedirect('users.tenants.first-step','Arrendatario');
            return redirect()->route('users.tenants.first-step');
        }
        
    }

    public function registrationFirstStep(){
		return view('users.tenants.forms.first-step')->with(['user' => \Auth::user()]);

    }

    public function registrationFirstStepOne(Request $request){
        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {
        return view('users.tenants.forms.first-step-one')->with(['user' => $user,
                                                                'countries' => Country::all(),
                                                                'civil_status' => CivilStatus::all()]);
        }elseif($request->isMethod('post')){

            $user->saveBasicData($request);
            $user->updateProfileRedirect('users.tenants.first-step.two','Arrendatario');
            File::generateFile($user, File::DICOM, File::FIN_FILES_TYPE);

            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/tenant/registration/first-step/two');
            }
        }

    }

    public function registrationFirstStepTwo(Request $request){
        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {
            return view('users.tenants.forms.first-step-two')->with(['user' => \Auth::user(),
                                                                   'cities' => Country::find(39)->cities]);
        }elseif($request->isMethod('post')){

            $user->saveLocationData($request);
            $user->updateProfileRedirect('users.tenants.first-step.three','Arrendatario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/tenant/registration/first-step/three');
            }
        }
    }

    public function registrationFirstStepThree(Request $request){
        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {
            return view('users.tenants.forms.first-step-three')->with('user', $user);
        }elseif($request->isMethod('post')){ // desde se setea al collateral
            
            if ($request->collateral == 1) { // si viene el flag
                if ($request->collateral_email) { // collateral_email es un parametro enviado cuando es email existente
                    $collateral = User::where('email', $request->collateral_email)->first();
                    $user->collateral_id = $collateral->id;
                }
                else { // sino, tendria que venir el parametro email
                    //dd($request);
                    if ($user->collateral && $user->collateral->email == $request->email) { // el collateral nuevo es el mismo que el que ya tiene
                        $collateral = $user->collateral;
                    }else { // reemplaza al collateral anterior (?)
                       
                        if ($user->collateral && $user->collateral->created_by_reference) { 
                            $user->collateral_id = null;
                            $user->save();
                            $user->collateral->delete();
                            //dd('entroaas');
                        }
                        //dd('entro a acca');
                        $collateral = new User();
                        $collateral->firstname = $request->firstname;
                        $collateral->lastname = $request->lastname;
                        $collateral->email = $request->email;
                        $collateral->created_by_reference = true;
                        $collateral->activation_token = User::generateToken();
                        $password = $collateral->generateStrongPassword();
                        $collateral->password = \Hash::make($password);
                        $collateral->save();

                        File::generateIdFiles($collateral);
                        File::generateFile($collateral, File::DICOM, File::FIN_FILES_TYPE);
                        $user->collateral_id = $collateral->id;
                    }

                }
                if (!$collateral->memberships()->where('role_id', Membership::TYPE_COLLATERAL)->first()) {
                    $collateral->memberships()->attach(Membership::getCollateralMembership()->id);
                }
                if (!is_null($collateral)) {
                    $collateral->notify(new CollateralMail($collateral->activation_token, $user->fullname, $user->id));
                    $user->collateral_id = $collateral->id;    
                }
            }
            else {
                $user->collateral_id = null;
            }

            if ($request->action) {
                
                $user->confirmed_action = 0; // creo que hay que crear este campo
            }

            $user->save();
            if (!$request->action) $user->updateProfileRedirect('users.tenants.second-step','Arrendatario');
            if ($request->ajax()) {
                // debería entrar aqui cuando modifico el aval y este no existia en el sistema
                //Mail::to($request->email)->send( new ConfirmacionAval('Bienvenido a uHomie <br> El usuario '.$user->firstname.' '.$user->lastname.' nos ha solicitado que ud sea su Aval <br>Para confirmar ingrese al <a href="http://dev.uhomie.cl/">siguiente link</a>'));
                
                
                return response(['success' => true]);
            }else {
                return redirect('/users/tenant/registration/second-step');
            }
        }
    }

    public function registrationSecondStep(Request $request){
        $user = \Auth::user();
        if ($request->isMethod('get')) {

            return view('users.tenants.forms.second-step')->with('user', $user);
        }elseif($request->isMethod('post')){
            $user->updateProfileRedirect('users.tenants.second-step','Arrendatario');
            return redirect('/users/tenant/registration/second-step/employment-details/'.$request->employment_type);
        }
    }

    public function registrationEmploymentDetails(Request $request,$employment_type){
        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {
            $view = null;
            switch ($employment_type) {
                case User::EMPLOYEE_EMPLOYMENT_TYPE:
                    $view = view('users.tenants.forms.second-step-employee')->with('user', $user)->with('employment_type', User::EMPLOYEE_EMPLOYMENT_TYPE)
                                                                                                ->with('other_income_type', User::OTHER_INCOME_TYPE);
                    break;
                case User::OWNER_EMPLOYMENT_TYPE:
                    $view = view('users.tenants.forms.second-step-own')->with('user', $user)->with('employment_type', User::OWNER_EMPLOYMENT_TYPE)
                                                                                                ->with('other_income_type', User::OTHER_INCOME_TYPE);
                    break;
                case User::UNEMPLOYED_EMPLOYMENT_TYPE:
                    $view = view('users.tenants.forms.second-step-unemployed')->with('user', $user)->with('employment_type', User::UNEMPLOYED_EMPLOYMENT_TYPE)
                                                                                                ->with('other_income_type', User::OTHER_INCOME_TYPE);
                    break;
                default:
                    break;
            }

            return $view;
        }elseif($request->isMethod('post')){
            if ($user->employment_type != $request->employment_type) File::deleteJobFiles($user);

            if ($request->employment_type == User::EMPLOYEE_EMPLOYMENT_TYPE) {
                
                $user->employment_type = User::EMPLOYEE_EMPLOYMENT_TYPE;
                $user->position = $request->position;
                $user->company = $request->company;
                $user->job_type = $request->job_type;
                $date = str_replace('/', '-', $request->worked_from_date);
                $user->worked_from_date = date('Y-m-d', strtotime($date));
                $user->worked_to_date = null;
                if (isset($request->amount)){
                  $user->amount = str_replace(".","",$request->amount);
                }else{
                  $user->amount = 0;
                }
                
                $user->afp = false;
                $user->invoice = false;
                $user->last_invoice_amount = 0;

                $user->invoice = $request->invoice;
                File::generateEmployeeFiles($user);
                if ($request->income > 0) {
                    $user->other_income_type = $request->other_type;
                    $user->other_income_amount = str_replace(".","",$request->income);
                    File::generateFile($user, File::OTHER_INCOME, File::JOB_FILES_TYPE);
                }else{
                    $user->other_income_type = '0';
                     $user->other_income_amount = 0;
                }

                if ($request->saves) {
                    $user->saves = $request->saves;
                    $user->save_amount = str_replace(".","",$request->save_amount);
                    File::generateFile($user, File::SAVES);
                }else{
                   $user->saves = false;
                   $user->save_amount = 0;
                }
            }
            elseif ($request->employment_type == User::OWNER_EMPLOYMENT_TYPE) {

                $user->employment_type = User::OWNER_EMPLOYMENT_TYPE;
                $user->position = $request->position;
                $user->company = null;
                $user->worked_from_date = null;
                $user->worked_to_date = null;
                if (isset($request->amount)){
                  $user->amount = str_replace(".","",$request->amount);
                }else{
                  $user->amount = 0;
                }
                if ($request->afp){
                    $user->afp = $request->afp;
                    File::generateFile($user, File::AFP, File::JOB_FILES_TYPE);
                }else{
                  $user->afp = false;
                }

                if ($request->income > 0) {
                    $user->other_income_type = $request->other_type;
                    $user->other_income_amount = str_replace(".","",$request->income);
                    File::generateFile($user, File::OTHER_INCOME, File::JOB_FILES_TYPE);
                }else{
                   $user->other_income_type = false;
                   $user->other_income_amount = 0;
                }
                if ($request->saves) {
                    $user->saves = $request->saves;
                    $user->save_amount = str_replace(".","",$request->save_amount);
                    File::generateFile($user, File::SAVES, File::JOB_FILES_TYPE);
                }else{
                   $user->saves = '0';
                   $user->save_amount = 0;
                }
                if ($request->invoice) {
                    $user->invoice = $request->invoice;
                    $user->last_invoice_amount = str_replace(".","",$request->last_invoice_amount);
                    File::generateFile($user, File::LAST_INVOICE, File::JOB_FILES_TYPE);
                }else{
                   $user->invoice = false;
                   $user->last_invoice_amount = 0;
                }

            }elseif ($request->employment_type == User::UNEMPLOYED_EMPLOYMENT_TYPE) {
                $user->employment_type = User::UNEMPLOYED_EMPLOYMENT_TYPE;
                $user->position = $request->position;
                $user->company = $request->company;

                $worked_from_date = str_replace('/', '-', $request->worked_from_date);
                $user->worked_from_date = date('Y-m-d', strtotime($worked_from_date));
                $worked_to_date = str_replace('/', '-', $request->worked_to_date);
                $user->worked_to_date = date('Y-m-d', strtotime($worked_to_date));
                if (isset($request->amount)){
                  $user->amount = str_replace(".","",$request->amount);
                }else{
                  $user->amount = 0;
                }
                $user->afp = false;
                $user->invoice = false;
                $user->last_invoice_amount = 0;
                File::generateUnemployeeFiles($user);
                if ($request->afp){
                    $user->afp = $request->afp;
                    File::generateFile($user, File::AFP, File::JOB_FILES_TYPE);
                }else{
                  $user->afp = false;
                }
                if ($request->saves) {
                    $user->saves = $request->saves;
                    $user->save_amount = str_replace(".","",$request->save_amount);
                    File::generateFile($user, File::SAVES, File::JOB_FILES_TYPE);
                }else{
                   $user->saves = false;
                   $user->save_amount = 0;
                }
                if ($request->income > 0) {
                    $user->other_income_type = $request->other_type;
                    $user->other_income_amount = str_replace(".","",$request->income);
                    File::generateFile($user, File::OTHER_INCOME, File::JOB_FILES_TYPE);
                }else{
                   $user->other_income_type = '0';
                   $user->other_income_amount = 0;
                }
            }
            $user->save();
            $user->updateProfileRedirect('users.tenants.third-step','Arrendatario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/tenant/registration/third-step')->with('user', $user);
            }
        }
    }

    public function registrationThirdStep(){

        return view('users.tenants.forms.third-step')->with('user', \Auth::user());
    }

    public function registrationThirdStepOne(Request $request){
        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {
            return view('users.tenants.forms.third-step-one')->with('user', $user);
        }elseif($request->isMethod('post')){/* TODO: Arreglar el problema de "truncated data" por los . y , en el entero */

            $user->expenses_limit = str_replace(".","",$request->expenses_limit);
            $user->common_expenses_limit = str_replace(".","",$request->common_expenses_limit);
            $user->tenanting_insurance = $request->tenanting_insurance;
            $user->warranty_months_quantity = $request->warranty_months_quantity;
            $user->months_advance_quantity = $request->months_advance_quantity;
            /* TODO: Arreglar el problema de formato de fecha */
            $move_date = str_replace('/', '-', $request->move_date);

            $user->move_date = date('Y-m-d', strtotime($move_date));
            $user->tenanting_months_quantity = $request->tenanting_months_quantity;
            $user->save();
            $user->updateProfileRedirect('users.tenants.third-step.two','Arrendatario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/tenant/registration/third-step/two');
            }
        }
    }

    public function registrationThirdStepTwo(Request $request){
        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {
            return view('users.tenants.forms.third-step-two')->with([
                    'user' => $user,
                    'property_types' => PropertyType::where("enabled",true)->get(),
                    'properties_for' => PropertyFor::all(),
                    'property_conditions' => Property::PROPERTY_CONDITION_ARRAY,
                ]);
        }elseif($request->isMethod('post')){
            $user->property_type = $request->property_type;
            $user->property_condition = $request->property_condition;
            $user->property_for = $request->property_for;

            $user->furnished = $request->furnished;
            $user->pet_preference = $request->pet_preference;
            $user->smoking_allowed = $request->smoking_allowed;
            $user->save();
            $user->updateProfileRedirect('users.tenants.third-step.three','Arrendatario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/tenant/registration/third-step/three');
            }
        }
    }

    public function registrationThirdStepThree(Request $request){
        $user = \Auth::user();
        if ($request->isMethod('get')) {
            return view('users.tenants.forms.third-step-three')->with(['user' => $user,
                                                                'property_amenities' => Amenity::where('type', false)->get(),
                                                                'common_amenities' => Amenity::where('type', true)->get(),
                                                            ]);
        }elseif($request->isMethod('post')){
            $user->amenities()->sync($request->amenities);
            $user->save();
            $user->updateProfileRedirect('users.tenants.third-step.four','Arrendatario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/tenant/registration/third-step/four');
            }
        }
    }

    public function registrationThirdStepFour(Request $request){
        $user = \Auth::user();
        if ($request->isMethod('get')) {
            return view('users.tenants.forms.third-step-four')->with(['user' => $user]);
        }elseif($request->isMethod('post')){
            /**
            TO DO:
                ¡make validation! Update check and so on~
            */
            $files = $request->files->all();

            foreach ($files as $fileName => $file) {
                try {
                    $db_file = $user->files()->where('name', $fileName)->first();
                    //dd('users/'.$user->id.'/files/'.$fileName);
                    if ( \Storage::disk('local')->exists( 'users/'.$user->id.'/files/'.$fileName) ) {
                        \Storage::disk('local')->delete( 'users/'.$user->id.'/files/'.$fileName) ;
                    }
                    \Storage::disk('local')->putFileAs('users/'.$user->id.'/files/',$file,$fileName);
                    // if (!$request->verified) { $db_file->verified = $request->verified; }
                    $db_file->original_name = $file->getClientOriginalName();
                    $db_file->path = 'users/'.$user->id.'/files/'.$fileName;
                    // Cuando se vayan agregando mas validadores, se tienen que agregar aqui.
                    //if( $fileName == 'id_front' || $fileName == 'id_back' ) $db_file->verified_ocr = 1;
                    
                    $db_file->save();
                } catch (Exception $e) {
                }
            }
            if($request->action == 'upload-profile-document') {
                return response([
                    'success' => true,
                    'file' => $db_file
                ]);
            }
            
            $user->updateProfileRedirect('users.tenants.memberships','Arrendatario');

            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/tenant/memberships');
            }
        }
    }

    public function registrationMembershipsForm(Request $request){
        $memberships = Membership::getTenantMemberships();

        $user = \Auth::user();
        $payment = Payment::where('order', $request->order)->where('user_id', $user->id)->first();
        if($payment){
            switch($payment->status){
                case 0:
                    $payment->statusname = 'Anulada';
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

        return view('users.tenants.forms.memberships')->with([
            'user' => \Auth::user(),
            'memberships' => $memberships,
            'back' => 'users.tenants.third-step.four',
            'route' => 'users.tenants.memberships-checkout',
            'payment' => $payment,
            'close' => 'start'
            ]);
    }
    public function registrationMembershipsFormUpdate(Request $request){
        $memberships = Membership::getTenantMemberships();

        $user = \Auth::user();
        $payment = Payment::where('order', $request->order)->where('user_id', $user->id)->first();
        if($payment){
            switch($payment->status){
                case 0:
                    $payment->statusname = 'Anulada';
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

        return view('users.tenants.forms.memberships')->with([
            'user' => \Auth::user(),
            'memberships' => $memberships,
            'back' => 'profile.tenant',
            'route' => 'users.tenants.memberships-checkout.update',
            'payment' => $payment,
            'close' => 'profile.tenant'
            ]);
    } 

    public function registrationMembershipsFormBack(Request $request){
        $memberships = Membership::getTenantMemberships();
        $user = \Auth::user();
        $payment = Payment::where('order', $request->order)->where('user_id', $user->id)->first();
        if($payment){
            switch($payment->status){
                case 0:
                    $payment->statusname = 'Anulada';
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
        
        return view('users.tenants.forms.memberships-back')->with(['user' => \Auth::user(),
                                                        'memberships' => $memberships,
                                                        'payment'=>$payment]);
    } 
    public function updateMembershipsForm(){
        if(\Auth::user()){
            $memberships = Membership::getTenantMemberships();
            return view('users.tenants.forms.memberships-update')->with(['user' => \Auth::user(),
                                                            'memberships' => $memberships]);
        }
            
    }
    public function upgradeMembershipsForm(){
        if(\Auth::user()){
            $memberships = Membership::getTenantMemberships();
            return view('users.tenants.forms.memberships-upgrade')->with(['user' => \Auth::user(),
                                                            'memberships' => $memberships]);
        }
            
    }
    public function registrationMembershipCheckout(Request $request){
        $user = \Auth::user();
        if ($request->isMethod('get')) {
            $membership = Membership::find($request->membership);
            return view('users.tenants.forms.membership-checkout')->with([
                'user' => $user,
                'membership' => $membership,
                'route' => 'users.tenants.memberships',
                'back' => '/users/tenant/memberships',
                'close' => 'start'
            ]);
        }
        elseif($request->isMethod('post')){
            // $membership = Membership::find($request->membership);
            // foreach ($user->memberships as $m) {
            //     if ($m->role_id == $membership->role_id) {
            //         $user->memberships()->detach($m->id);
            //     }
            // } #TODO: FIX THIS ROUTINE
            // $user->memberships()->attach($request->membership, array('expires_at' =>  Carbon::now()->addDays(30), 'purchased_at' => Carbon::now()));
            // $user->notify(new TenantProfile($user->fullname));
            // if (!is_null($user->collateral)) {
            //     $user->collateral->notify(new CollateralMail($user->collateral->activation_token, $user->fullname, $user->id));
            // }
            // $user->updateProfileRedirect(null,'Arrendatario');
            return response(['success' => true]);

        }
    }
    public function registrationMembershipCheckoutUpdate(Request $request){
        $user = \Auth::user();
        if ($request->isMethod('get')) {
            $membership = Membership::find($request->membership);
            return view('users.tenants.forms.membership-checkout')->with([
                'user' => $user,
                'membership' => $membership,
                'route' => 'users.tenants.memberships.update',
                'back' => '/users/tenant/memberships-u',
                'close' => 'profile.tenant'
            ]);
        }
        elseif($request->isMethod('post')){
            // $membership = Membership::find($request->membership);
            // foreach ($user->memberships as $m) {
            //     if ($m->role_id == $membership->role_id) {
            //         $user->memberships()->detach($m->id);
            //     }
            // } #TODO: FIX THIS ROUTINE
            // $user->memberships()->attach($request->membership, array('expires_at' =>  Carbon::now()->addDays(30), 'purchased_at' => Carbon::now()));
            // $user->notify(new TenantProfile($user->fullname));
            // if (!is_null($user->collateral)) {
            //     $user->collateral->notify(new CollateralMail($user->collateral->activation_token, $user->fullname, $user->id));
            // }
            // $user->updateProfileRedirect(null,'Arrendatario');
            return response(['success' => true]);

        }
    }
    public function tenantMembershipsData(Request $request){
        if(\Auth::user()){
            if ($request->isMethod('get')) {
                $memberships = Membership::getTenantMemberships();
                return json_encode([
                    'memberships' => $memberships
                ]);
            }
        }
    }
    public function updateMembershipCheckout(Request $request){
        $user = \Auth::user();
        if ($request->isMethod('get')) {
            $membership = Membership::find($request->membership);
            return view('users.tenants.forms.membership-checkout-update')->with([
                'user' => $user,
                'membership' => $membership
            ]);
        }
        elseif($request->isMethod('post')){
            // $membership = Membership::find($request->membership);
            // foreach ($user->memberships as $m) {
            //     if ($m->role_id == $membership->role_id) {
            //         $user->memberships()->detach($m->id);
            //     }
            // } #TODO: FIX THIS ROUTINE
            // $user->memberships()->attach($request->membership, array('expires_at' =>  Carbon::now()->addDays(30), 'purchased_at' => Carbon::now()));
            // $user->notify(new TenantProfile($user->fullname));
            // if (!is_null($user->collateral)) {
            //     $user->collateral->notify(new CollateralMail($user->collateral->activation_token, $user->fullname, $user->id));
            // }
            // $user->updateProfileRedirect(null,'Arrendatario');
            return response(['success' => true]);

        }
    }
}
