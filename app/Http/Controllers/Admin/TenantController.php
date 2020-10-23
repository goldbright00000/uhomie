<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{User, Country, CivilStatus, Amenity, Membership, City, PropertyType, Property, PropertyFor, File};
use App\Notifications\{CollateralMail, TenantProfile};
use Carbon\Carbon;

class TenantController extends Controller
{
    //
   	public function personalData(Request $request){
   	    $user = User::find($request->userId);
        $user->saveBasicData($request);
        $user->updateProfileRedirect('users.tenants.first-step.two','Arrendatario');
        File::generateFile($user, File::DICOM, File::FIN_FILES_TYPE);
        return response(['success' => true]);
   	}
   	public function locationData(Request $request){
   	    $user = User::find($request->userId);
        $user->saveLocationData($request);
        $user->updateProfileRedirect('users.tenants.first-step.three','Arrendatario');
        return response(['success' => true]);
   	}
   	public function collateralData(Request $request){
   	    $user = User::find($request->userId);
        if ($request->collateral == 1) {
            if ($request->collateral_email) {
                $collateral = User::where('email', $request->collateral_email)->first();
                $user->collateral_id = $collateral->id;
            }else {
                if ($user->collateral && $user->collateral->email == $request->email) {

                }else {
                    if ($user->collateral && $user->collateral->created_by_reference) {
                        $user->collateral_id = null;
                        $user->save();
                        $user->collateral->delete();
                    }
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
        }else {
            $user->collateral_id = null;
        }
        if ($request->action) { $user->confirmed_action = 0; }
        $user->save();
        if (!$request->action) $user->updateProfileRedirect('users.tenants.second-step','Arrendatario');
        return response(['success' => true]);
   	}
   	public function employmentData(Request $request){
   	    $user = User::find($request->userId);
     	if ($user->employment_type !== $request->employment_type) File::deleteJobFiles($user);
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
            $user->saves = false;
            $user->save_amount = 0;
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
        return response(['success' => true]);
   	}
   	public function paymentPreferences(Request $request){
   	    $user = User::find($request->userId);
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
        return response(['success' => true]);
   	}
   	public function tenantingPreferences(Request $request){
   	    $user = User::find($request->userId);
        $user->property_type = $request->property_type;
        $user->property_condition = $request->property_condition;
        $user->property_for = $request->property_for;
        $user->furnished = $request->furnished;
        $user->pet_preference = $request->pet_preference;
        $user->smoking_allowed = $request->smoking_allowed;
        $user->save();
        $user->updateProfileRedirect('users.tenants.third-step.three','Arrendatario');
        return response(['success' => true]);
   	}
}
