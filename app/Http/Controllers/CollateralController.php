<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\CollateralProfile;
use App\{User, Country, CivilStatus, File};

class CollateralController extends Controller
{

    public function acceptanceForm($activation_token, $creditor_id){
  		$user = User::where('activation_token', $activation_token)->first();
  		$creditor = User::find($creditor_id);
  		if ($user) {
  			$user->mail_verified = true;
  		}elseif(is_null($user) ||  is_null($creditor) || $creditor->confirmed_collateral) {
  			abort(404);
  		}
      \Auth::login($user);
      return	view('users.collaterals.forms.acceptance')->with(['user' => $user, 'creditor' => $creditor]);
  	}

	public function accept(Request $request){
        $creditor = User::find($request->creditor_id);
        $user = \Auth::user();
        if ($request->answer) {
            $user->mail_verified = true;
            $user->employment_type = User::EMPLOYEE_EMPLOYMENT_TYPE;
            File::generateEmployeeFiles($user);
            File::generateFile($user, File::DICOM, File::FIN_FILES_TYPE);

            $creditor->confirmed_collateral = true;
            $creditor->save();
            $user->employment_type = false;
            $user->save();
            $user->updateProfileRedirect('users.collaterals.first-step','Aval');
            return redirect('users/collateral/registration/first-step');
        }else {

            $creditor->collateral_id = null;
            if ($user->created_by_reference) {
                \Auth::logout($user);
                $user->delete();
            }
            $creditor->save();

            return redirect('/');
        }
	}

    public function registrationFirstStepForm(Request $request){
        $user = \Auth::user();
        if ($request->isMethod('get')) {
            return view('users.collaterals.forms.first-step')->with([
                'user' => $user,
            ]);
        }elseif($request->isMethod('post')){
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            if ($request->password) {
                $user->password = \Hash::make($request->password);
            }
            $user->created_by_reference = false;
            $user->save();
            $user->updateProfileRedirect('users.collaterals.second-step','Aval');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('users/collateral/registration/second-step');
            }
        }
    }

    public function registrationSecondStepForm(Request $request){
        $user = \Auth::user();
        if ($request->isMethod('get')) {
            return view('users.collaterals.forms.second-step')->with(['user' => $user,
                                                                    'countries' => Country::all(),
                                                                    'civil_status' => CivilStatus::all()]);
        }elseif($request->isMethod('post')){
            $user->saveBasicData($request);
            $user->updateProfileRedirect('users.collaterals.third-step','Aval');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/collateral/registration/third-step');
            }
        }
    }

    public function registrationThirdStepForm(Request $request){
        $user = \Auth()->user();
        if ($request->isMethod('get')) {
            return view('users.collaterals.forms.third-step')->with(['user' => $user,
                                                            'cities' => Country::find(39)->cities]);
        }elseif($request->isMethod('post')){
            $user->saveLocationData($request);
            $user->updateProfileRedirect('users.collaterals.fourth-step','Aval');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/collateral/registration/fourth-step-start');
            }
        }
    }

    public function registrationFourthStepStart(){
        return view('users.collaterals.forms.fourth-step-start');
    }

    public function registrationFourthStepForm(Request $request){
        $user = \Auth()->user();
        if ($request->isMethod('get')) {
            return view('users.collaterals.forms.fourth-step')->with(['user' => $user,
                                                        ]);
        }elseif($request->isMethod('post')){
            /**
            TO DO:
                ¡make validation! Update check and so on~
            */
            $files = $request->files->all();

            foreach ($files as $fileName => $file) {
                try {
                    $db_file = $user->files()->where('name', $fileName)->first();
                    \Storage::disk('local')->putFileAs('users/'.$user->id.'/files/',$file,$fileName);
                    $db_file->original_name = $file->getClientOriginalName();
                    $db_file->path = 'users/'.$user->id.'/files/'.$fileName;;
                    $db_file->save();
                } catch (Exception $e) {
                }
            }
            $user->notify(new CollateralProfile());
            $user->updateProfileRedirect(null,'Aval');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/profile/collateral#/');
            }
        }
    }
}
