<?php
/*  By: Dixán Santiesteban
    PERFIL DE ARRENDATARIO
*/

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\CivilStatus;
use App\Notification;
use App\File;
use App\Membership;
use App\Privacy;
use http\Env\Response;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use App\ApplyProperty;
use App\Conversation;
use App\Property;
use App\Payment;
use App\{Schedule, UserProperty, Photo};
use HelloSign\Client;

class TenantProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function tenantProfileIndexAction()
    {
        
        if($user = Auth::user()){
            $redirect = User::where('id', $user->id)->first();
            if($redirect->tenant_profile_redirect == null){
                return view('profiles/tenant', [
                    'user' => \Auth::user(),
                    'role-id' => 1
                ]);
            } else {
                return redirect()->route($redirect->tenant_profile_redirect);
            }
        }
    }


    function getInfoAction()
    { // filtros de tenant

        //$user = Auth()->user();

        if ($user = Auth::user()) {


            $data = $this->getUserData($user);
            $data['schedules'] = $this->getSchedules($user);
            $data['updateMembershipUrl'] = route('users.tenants.memberships.update');
            $data['upgradeMembershipUrl'] = route('users.tenants.memberships.upgrade');
            $data['payments'] = Payment::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
            $scoring = new \App\Scoring;
            $data['scoring_info']['contact'] = (int)$scoring->contactUserScore($user->phone_verified,$user->mail_verified);
            $data['scoring_info']['identity'] = (int)$scoring->identityUserScore($user->id);
            $data['scoring_info']['nationality'] = (int)$scoring->nationalityScoreUser($user->country_id,$user->document_type);
            if(!is_null($user->collateral_id)){
                $collateral = $user->getCollateralUser();
                $data['scoring_info']['collateral_confirm'] = (int)$scoring->guarantorConfirmationScore($user->confirmed_collateral, $user->tenanting_insurance);
                $data['scoring_info']['collateral_contact'] = (int)$scoring->contactUserScore($collateral->phone_verified,$collateral->mail_verified);
                $data['scoring_info']['collateral_identity'] = (int)$scoring->identityUserScore($collateral->id);
                $data['scoring_info']['collateral_nationality'] = (int)$scoring->nationalityScoreUser($collateral->country_id,$collateral->document_type);
            } else {
                $data['scoring_info']['collateral_confirm'] = (int)0;
                $data['scoring_info']['collateral_contact'] = (int)0;
                $data['scoring_info']['collateral_identity'] = (int)0;
                $data['scoring_info']['collateral_nationality'] = (int)0;
            }
            $data['scoring_info']['employment'] = (int)$scoring->jobUserScore($user->employment_type);
            $data['scoring_info']['docs'] = (int)$scoring->docsUserScore($user->id, $user->employment_type);
            $data['scoring_info']['finantial'] = (int)$scoring->finantialUserScore($user->id, $user->employment_type, $user->expenses_limit, $user->amount, $user->save_amount, $user->other_income_amount, $user->last_invoice_amount);
            $data['scoring_info']['membreship'] = (int)$scoring->membershipUserScore($user->id);
            $data['scoring_info']['conditions'] = (int)$scoring->conditionsScore($user->warranty_months_quantity,$user->months_advance_quantity,$user->tenanting_months_quantity);
            $data['scoring_info']['dicom'] = (int)$scoring->dicomScore($user->id);
            return response([
                'success' => true,
                'info' => $data,
            ]);

        } else {
            return response('No está autenticado.', 401);
        }
    }


    function getFiltersAction()
    { // filtros de tenant
        $filters = [];

        $all = \App\Country::all();
        $countries = [];
        foreach ($all as $country)
            $countries[] = [
                'id' => $country->id,
                'text' => $country->name
            ];


        $all = \App\CivilStatus::all();
        $cstts = [];
        foreach ($all as $cs) {

            $cstts[] = [
                'id' => $cs->id,
                'text' => $cs->name
            ];

        }

        $all = \App\Country::find(39)->cities;
        $citys = [];
        foreach ($all as $city) {

            $citys[] = [
                'id' => $city->id,
                'text' => $city->name
            ];

        }

        $all = \App\PropertyType::where("enabled",true)->get();
        $ptype = [];
        foreach ($all as $pc) {
            $ptype[] = [
                'id' => $pc->id,
                'text' => $pc->name
            ];
        }

        $all = \App\PropertyFor::all();
        $pfor = [];
        foreach ($all as $pf) {
            $pfor[] = [
                'id' => $pf->id,
                'text' => $pf->name
            ];
        }


        $filters['cities'] = [
            'options' => $citys
        ];

        $filters['civilstatus'] = [
            'options' => $cstts
        ];


        $filters['countries'] = [
            'options' => $countries
        ];

        $filters['property_type'] = [
            'options' => $ptype
        ];

        $filters['property_for'] = [
            'options' => $pfor
        ];

        $filters['rut_type'] = [
            'options' =>
                [
                    ['id' => "RUT", 'text' => 'RUT (RESIDENCIA PERMANENTE)'],
                    ['id' => "PASSPORT", 'text' => 'PASAPORTE'],
                    ['id' => "RUT_PROVISIONAL", 'text' => 'RUT (PROVISORIO)'],
                ]
        ];

        $filters['job_type'] = [
            'options' =>
                [
                    ['id' => "FullTime", 'text' => 'Full Time'],
                    ['id' => "PartTime", 'text' => 'Part Time'],
                    ['id' => "Indefinido", 'text' => 'Indefinido'],
                    ['id' => "PorProyecto", 'text' => 'Por Proyecto'],
                    ['id' => "PorHonorarios", 'text' => 'Por Honorarios'],
                    ['id' => "Freelancer", 'text' => 'Freelancer'],
                ]
        ];

        $filters['others_income'] = [
            'options' =>
                [
                    ['id' => "0", 'text' => 'No tengo ingresos adicionales'],
                    ['id' => "1", 'text' => 'Ingresos propios adicionales'],
                    ['id' => "2", 'text' => 'Ingresos de compañero de arriendo'],
                    ['id' => "3", 'text' => 'Conyuge'],

                ]
        ];

        $filters['months'] = [
            'options' =>
                [
                    ['id' => "1", 'text' => '1 Mes'],
                    ['id' => "2", 'text' => '2 Meses'],
                    ['id' => "3", 'text' => '3 Meses'],
                    ['id' => "4", 'text' => '4 Meses'],
                    ['id' => "5", 'text' => '5 Meses'],
                    ['id' => "6", 'text' => '6 Meses'],
                    ['id' => "7", 'text' => '7 Meses'],
                    ['id' => "8", 'text' => '8 Meses'],
                    ['id' => "9", 'text' => '9 Meses'],
                    ['id' => "10", 'text' => '10 Meses'],
                    ['id' => "11", 'text' => '11 Meses'],
                    ['id' => "12", 'text' => '12 Meses'],

                ]
        ];

        $filters['property_condition'] = [
            'options' =>
                [
                    ['id' => "0", 'text' => 'Nuevo'],
                    ['id' => "1", 'text' => 'Usado'],
                    ['id' => "2", 'text' => 'Indiferente'],

                ]
        ];

        $filters['employment'] = [
            'options' =>
                [
                    ['id' => "1", 'text' => 'Empleado'],
                    ['id' => "2", 'text' => 'Cuenta propia'],
                    ['id' => "3", 'text' => 'Desempleado'],

                ]
        ];



        $all = \App\Amenity::where('type', false)->get();
        $pa = [];
        foreach ($all as $pf) {
            $pa[] = [
                'id' => $pf->id,
                'text' => $pf->name
            ];
        }
        $filters['property_amenities'] = $pa;

        $all = \App\Amenity::where('type', true)->get();
        $ca = [];
        foreach ($all as $pf) {
            $ca[] = [
                'id' => $pf->id,
                'text' => $pf->name
            ];
        }
        $filters['common_amenities'] = $ca;

        $banks = \App\Bank::get();
        $bk = [];
        foreach($banks as $bank){
            $bk[] = [
                'id' => $bank->id,
                'text' => $bank->name
            ];
        }

        $filters['banks'] = [
            'options' => $bk
        ];


        return response([
            'success' => true,
            'filters' => $filters
        ]);

    }

    function saveProfileAction(Request $request)
    {

        if ($user = Auth::user()) {

            if ($action = $request->get('action')) {
                $data = $request->get('data');
                foreach ($data as $id => $value)
                    $user->{$action}()->updateExistingPivot($id,['active' => $value=='true'?true:false ]);

                return response([
                    'success' => true,
                    //data' => $quepaso,
                    'user' => $user,
                ]);
            }

            // LISTA DE CAMPOS DISPONIBLES PARA SALVAR

            $fillable = [
                'email', 'firstname', 'lastname', 'document_type', 'document_number', 'metas', 'profile_picture', 'active',
                'birthdate', 'address', 'address_details', 'latitude', 'longitude', 'landline', 'phone', 'phone_code','phone_verified', 'mail_verified',
                'employment_details', 'expenses_limit', 'common_expenses_limit',
                'warranty_months_quantity', 'months_advance_quantity', 'tenanting_months_quantity', 'move_date', 'property_type', 'property_condition',
                'property_for', 'pet_preference', 'furnished', 'smoking_allowed', 'created_by_reference', 'confirmed_collateral', 'last_invoice_amount',
                'position', 'company', 'job_type', 'worked_from_date', 'worked_to_date', 'amount', 'saves', 'save_amount', 'afp', 'invoice', 'bank', 'account_type', 'account_number',
                'country_id', 'civil_status_id', 'city_id', 'other_income_type', 'other_income_amount', 'password'
            ];


            // VALIDACIONES PARA LOS CAMPOS QUE VIENEN
            
            $validatedData = [
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'email' => "required|unique:users,email,{$user->id},id|max:255",
                'document_number' => "required|unique:users,document_number,{$user->id},id|max:255",
                'password' => "required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/"
            ];

            $reject = [];
            $validations = [];


            foreach ($request->all() as $field => $value){
                if (in_array($field, $fillable)) {
                    $user->{$field} = $value;
                    if (isset($validatedData[$field])) {
                        $validations[$field] = $validatedData[$field];
                    }
                } else{
                    $reject[] = $field;
                }
            }

            $request->validate($validations);

            if(isset($request->password)){
                if(isset($request->confir_password)){
                    if($request->password == $request->confir_password){
                        $user->password = bcrypt($request->password);
                    } else {
                        return response(['mensaje' => 'La nueva contraseña y la confirmación no son iguales.'],500);
                    }
                } else {
                    return response(['mensaje' => 'No se ha subido el campo de confirmacin de contraseña.'],500);
                }
            }

            $user->save();

            return response([
                'success' => true,
                'reject' => $reject,
                'user' => $this->getUserData($user),
            ]);
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }

    }

    function saveEmploymentAction(Request $request) 
    {
        //dd('hola');
        if ($user = Auth::user()) {

            // LISTA DE CAMPOS DISPONIBLES PARA SALVAR
            $fillable = [
                '_token'
            ];


            // VALIDACIONES PARA LOS CAMPOS QUE VIENEN
            $validatedData = [
            ];

            $reject = [];
            $validations = [];


            if ($action = $request->get('action')) {
                $data = $request->get('data');
                foreach ($data as $id => $value)
                    $user->{$action}()->updateExistingPivot($id,['active' => $value=='true'?true:false ]);

                return response([
                    'success' => true,
                    //data' => $quepaso,
                    'user' => $user,
                ]);
            }

            if ($user->employment_type != $request->employment_type) File::deleteJobFiles($user);

            foreach ($request->all() as $field => $value)
                if (!in_array($field, $fillable)) {
                    $user->{$field} = $value;
                    if (isset($validatedData[$field])) {
                        $validations[$field] = $validatedData[$field];
                    }
                } else
                    $reject[] = $field;

            $request->validate($validations);

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
                if ($request->other_income_amount > 0) {
                    $user->other_income_type = $request->other_income_type;
                    $user->other_income_amount = str_replace(".","",$request->other_income_amount);
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
                if ($request->invoice) {
                    $user->invoice = $request->invoice;
                    $user->last_invoice_amount = str_replace(".","",$request->last_invoice_amount);
                    File::generateFile($user, File::LAST_INVOICE, File::JOB_FILES_TYPE);
                }
                if ($request->afp){
                    $user->afp = $request->afp;
                    File::generateFile($user, File::AFP, File::JOB_FILES_TYPE);
                }

                if ($request->other_income_amount > 0) {
                    $user->other_income_type = $request->other_income_type;
                    $user->other_income_amount = str_replace(".","",$request->other_income_amount);
                    File::generateFile($user, File::OTHER_INCOME, File::JOB_FILES_TYPE);
                }else{
                   $user->other_income_type = '0';
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
                }
                if ($request->saves) {
                    $user->saves = $request->saves;
                    $user->save_amount = str_replace(".","",$request->save_amount);
                    File::generateFile($user, File::SAVES, File::JOB_FILES_TYPE);
                }else{
                   $user->saves = '0';
                   $user->save_amount = 0;
                }
                if ($request->other_income_amount > 0) {
                    $user->other_income_type = $request->other_income_type;
                    $user->other_income_amount = str_replace(".","",$request->other_income_amount);
                    File::generateFile($user, File::OTHER_INCOME, File::JOB_FILES_TYPE);
                }else{
                   $user->other_income_type = '0';
                    $user->other_income_amount = 0;
                }
            }

            $user->save();

            return response([
                'success' => true,
                'reject' => $reject,
                'user' => $this->getUserData($user),
            ]);
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }

    }

    function getDataAction($id)
    {
        $datasource = [
            0 => 'myProfile',
            1 => 'myPostulations',
            2 => 'myHoldings',
            3 => 'myForms',
            4 => 'myContracts',
            5 => 'getSchedules'
        ];

        return response([
            'success' => true,
            'info' => $this->{$datasource[$id]}(Auth::user())
        ]);
    }


    function samplesServerAction()
    {
        $devdata = [
            'url' => env('APP_URL'),
            'logued' => Auth()->check(),

        ];

        return response([
            'success' => true,
            'data' => $devdata
        ]);
    }


    /* DATOS CARGADOS PARA CADA VISTA DE PERFIL */

    private function myProfile($user)
    { // Menu: Arrendatario -> Mi Perfil

        $age = Carbon::parse($user->birthdate)->age;


        $documents = [
            'id_front', 'id_back', 'afp', 'dicom', 'first_settlement', 'second_settlement', 'third_settlement', 'work_constancy', 'other_income', 'last_invoice', 'saves'
        ];

        foreach ($documents as $docs) {
            $$docs = '';
            if ($exist = File::where('user_id', $user->id)->where('name', $docs)->first())
                if (!is_null($exist->path))
                    $$docs = $exist->id;
        }

        //$membership = $user->memberships()->get();

        //$membership = strtolower(Membership::getTenantMemberships()->name);
        $membership = strtolower(Membership::getTenantMemberships()[0]->name);


        return [
            'avatar' => '',
            'name' => "{$user->firstname} {$user->lastname}",
            'type' => 'Arrendatario',
            'membership' => $membership,

            'membership_exp' => '10/19/2019',

            'age_cstatus' => "$age años, " . CivilStatus::find($user->civil_status_id)->name,
            'rut' => $user->document_number,
            'address' => $user->address . '',
            'postulations' => '1',
            'phone' => "Celular: {$user->phone}",
            'fav_holding' => '1',

            'obverse' => '' . $id_front,
            'reverse' => '' . $id_back,
            'employ_cert' => $work_constancy,
            'first_paying' => $first_settlement,
            'second_paying' => $second_settlement,
            'third_paying' => $third_settlement,
            'AFP_cert' => $afp,
            'DICOM_cert' => $dicom,
            'others' => $other_income
        ];
    }

    private function myPostulations($user)
    { // Menu: Arrendatario -> Mis Postulaciones
        $properties = $user->getAppliedProperties();

        $vacio = false;

        $array_properties = $properties->map(function ($prop) use ($user) {
            //dd($prop);
            
            //dd($postulation);
            //$prop['owner'][] = ['firstname' => 'nombre']; 
            //$prop['owner'][] = ['lastname' => 'nombre']; 
            /*
            $prop['owner_data']['firstname'] = \App\Property::find($prop['id'])->getOwner()->firstname;
            $prop['owner_data']['lastname'] = \App\Property::find($prop['id'])->getOwner()->lastname;
            $prop['owner_data']['chat'] =  false;
            */
            $owner = \App\Property::find($prop['id'])->getOwner();

            if($owner){
                $prop['applied_membership'] = $user->getTenantMerbership();
                $prop['property_payment_url'] = $prop->type_stay == 'LONG_STAY' ? route('users.payments.step-one', ['property_id' => $prop->id]) : route('payments.short_stay.view', ['property_id' => $prop->id]);
                //$postulation = $prop->applications()->where('user_id', $user->id)->first();
                $postulation = \App\ApplyProperty::where('property_id',$prop->id)->where('user_id', $user->id)->where('users_has_properties.id',$prop->pivot->id)->first();

                $prop['owner_data'] = [];
                $prop['owner_data'] = ['firstname' => $owner->firstname, 'chat' => false, 'lastname' => $owner->lastname];
                $prop['tenanting_months_quantity'] = $prop->tenanting_months_quantity;
                $prop['warranty_months_quantity'] = $prop->warranty_months_quantity;
                $prop['months_advance_quantity'] = $prop->months_advance_quantity;
                $prop['available_date'] = $prop->available_date;
                $prop['collateral_require'] = $prop->available_date;
                
                $prop['contract_id'] = '';
                $prop['contract_id'] = $prop->contract()->get()->first()? $prop->contract()->get()->first()->id : '';
                switch ($postulation->state) {
                    case 6:
                        $status = 'refused';
                        $text = 'CANCELADO';
                        break;
                    case 5:
                        $status = 'signed';
                        $text = 'FIRMADO';
                        break;
                    case 4:
                        $status = 'verified';
                        $text = 'VERIFICADO';
                        break;
                    case 3:
                        $status = 'paid_out';
                        $text = 'PAGADO';
                        break;
                        
                    case 2:
                        $status = 'approved';
                        $text = 'APROBADO';
                        break;
                    
                    case 1:
                        $status = 'refused'; 
                        $text = 'RECHAZADO'; 
                        break;
                    
                    default:
                        $status = 'waiting_for_acceptance';    
                        $text = 'EN REVISIÓN';    
                        break;
                }

                $datePos = new \DateTime($postulation->created_at);

                if($prop->type_stay == 'SHORT_STAY'){
                    $days = json_decode(DB::table('users_has_postulates_days')->select('days')->where('id', $postulation['id'])->first()->days);
                } else {
                    $days = null;
                }
                
                $prop['postulation'] = [
                    'integer_distinct' => $postulation->id,
                    'status_int' => $postulation->state,
                    'status' => $status,
                    'text' => $text,
                    'created_at' => $datePos->format('d/m/Y'),
                    'days' => $days
                ] ;
                $prop['tenant'] = $user;

                $tenant_mb = $user->getTenantMerbership();

                $prop['tenant_membership'] = [
                    'name' => strtolower($tenant_mb->name)
                ];
                // Por una coincidencia de nombres con un class style llamado select para los elementos select, se tuvo que cambiar el nombre y
                // posteriormente se agrego la nueva regla, no se modificó
                //dd(\App\Property::find($prop['id'])->getOwner()->getOwnerMerbership());
                $nombreMembresia = strtolower(\App\Property::find($prop['id'])->getOwner()->getOwnerMerbershipOnce()->name) == 'select' ? 'select_style' : strtolower(\App\Property::find($prop['id'])->getOwner()->getOwnerMerbershipOnce()->name) ;
                
                $prop['owner_membership'] = [ 'name' => $nombreMembresia ];
                $prop['tenant'] = $user;
                $prop['scoring'] = $user->getScoring($prop->id);
                
                
                switch( $tenant_mb->name ){
                    case 'Basic':
                        $prop['owner_email'] = 'Mejora tu membresia para acceder a este dato';
                        $prop['owner_phone'] = 'Mejora tu membresia para acceder a este dato';
                        $prop['owner_chat'] = true;
                        break;
                    case 'Select':
                        $prop['owner_email'] = $prop->getOwner()->email;
                        $prop['owner_phone'] = 'Mejora tu membresia para acceder a este dato';
                        $prop['owner_chat'] = true;
                        break;
                    case 'Premium':
                        $prop['owner_email'] = $prop->getOwner()->email;
                        $prop['owner_phone'] = '+'.$prop->getOwner()->phone_code.$prop->getOwner()->phone;
                        $prop['owner_chat'] = true;
                        break;
                    default:
                        $prop['owner_email'] = 'Mejora tu membresia para acceder a este dato';
                        $prop['owner_phone'] = 'Mejora tu membresia para acceder a este dato';
                        $prop['owner_chat'] = true;
                        break;
                }
                $prop['owner_id'] = $prop->getOwner()->id;
            } else {
                $prop->vacio = true;
                $vacio = true;
            }

            return $prop;
        });

        if($vacio == true){
            $array_postulations = array_diff($array_properties, array($array_properties->vacio == true));
        } else {
            $array_postulations = $array_properties;
        }

        return $array_postulations;
    }

    private function myHoldings($user)
    {
        return $user->getFavouriteProperties()->map(function($prop) use ($user) {
            return [
                'id' => $prop->id,
                'imagePath' => $prop->photos[0]->path,
                'name' => $prop->name,
                'slug' => $prop->slug,
                'description' => $prop->description,
                'slug' => strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $prop->name))), 
                'membership' => $prop->getOwner()->getOwnerMerbership(), // Editado por Alejandro Arancibia
                'demand' => $prop->getDemandAttribute(),
                'scoring' => (int)$prop->getScoringAttributeProp($user->id, $prop->id),
                'verified' => (int)$prop->verified,
                'favorite' => $prop->getFavouriteAttribute(),
                'price' => $prop->rent,
                'bathNumber' => $prop->bathrooms,
                'roomNumber' => $prop->bedrooms,
                'parkingNumber' => $prop->public_parking,
                'applied' => $prop->getAppliedAttribute()
            ];
        });
    }

    private function myForms($user)
    { // Menu: Arrendatario -> Mis Formularios

        return [
            'personal' => [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'phone' => $user->phone,
                'email' => $user->email,
                'rut' => $user->document_number,
                'country' => $user->country_id,
                'civilstatus' => $user->civil_status_id,
                'birthdate' => $user->birthdate,
            ],
            'address' => [
                'city' => 'Santiago',
                'address1' => 'Calle Quinta esquina Cuarta',
                'address2' => 'Apto 5',
            ],
            'aval' => [
                'name' => 'Jhon',
                'firstname' => 'Philips',
                'email' => '555-555-5556',
            ],
            'employee' => [
                'position' => 'Programer',
                'factory' => 'Philips',
                'type' => '',
                'datefrom' => '2016-01-01',
                'dateto ' => '2016-01-01',
                'salary ' => '300000'
            ]
        ];
    }

    private function myContracts($user)
    { // Menu: Arrendatario -> Mis contratos
        $contratos = $user->contracts()->whereNotNull('signature_request_id_hs')->get();
        $r = array();
        $clientHS = new Client(env('HELLOSIGN_API_KEY','6c7aa28aee3eda00bd40ceaa982bc95faea4add85a1e5552a2a9d12e2682ab2b')); 
        foreach($contratos as $c)
        {
            $tenant = $c->users()->wherePivot('signer_role', 'tenant')->first();
            $o['tenant_name'] = $tenant->firstname.' '.$tenant->lastname;
            $o['tenant_avatar'] = $tenant->photo ? $tenant->photo : '/images/husky.png';
            switch( $tenant->pivot->status_code ){
                case 'awaiting_signature':
                    $o['tenant_sign_status'] = 1;
                    break;
                case 'signed':
                    $o['tenant_sign_status'] = 2;
                    break;
                case 'declined':
                    $o['tenant_sign_status'] = 0;
                    break;
            }
            $owner = $c->users()->wherePivot('signer_role', 'owner')->first();
            $o['owner_name'] = $owner->firstname.' '.$owner->lastname;
            $o['owner_avatar'] = $owner->photo ? $owner->photo : '/images/husky.png';
            switch( $owner->pivot->status_code ){
                case 'awaiting_signature':
                    $o['owner_sign_status'] = 1;
                    break;
                case 'signed':
                    $o['owner_sign_status'] = 2;
                    break;
                case 'declined':
                    $o['owner_sign_status'] = 0;
                    break;
            }
            $collateral = $c->users()->wherePivot('signer_role', 'collateral')->first();
            $o['collateral_name'] = $collateral ? $collateral->firstname.' '.$collateral->lastname : null ;
            $o['collateral_avatar'] = $collateral ? ($collateral->photo ? $collateral->photo : '/images/husky.png' ) : null ;
            if($collateral){
                switch( $collateral->pivot->status_code ){
                    case 'awaiting_signature':
                        $o['collateral_sign_status'] = 1;
                        break;
                    case 'signed':
                        $o['collateral_sign_status'] = 2;
                        break;
                    case 'declined':
                        $o['collateral_sign_status'] = 0;
                        break;
                }
            }
            

            $o['property'] = $c->property()->first();

            $firmantes = $clientHS->getSignatureRequest($c->signature_request_id_hs)->signatures;
            foreach( $firmantes as $f ){
                /*switch( $f->signer_role ){
                    case 'tenant':
                        swtich( $f->status_code ){
                            case 'awaiting_signature':
                                $o['tenant_status'] = 1;
                                break;
                            case 'signed':
                                $o['tenant_status'] = 2;
                                break;
                            case 'declined':
                                $o['tenant_status'] = 0;
                                break;
                        }
                        break;
                    case 'owner':
                        swtich( $f->status_code ){
                            case 'awaiting_signature':
                                $o['owner_status'] = 1;
                                break;
                            case 'signed':
                                $o['owner_status'] = 2;
                                break;
                            case 'declined':
                                $o['owner_status'] = 0;
                                break;
                        }
                        break;
                    case 'collateral':
                        swtich( $f->status_code ){
                            case 'awaiting_signature':
                                $o['collateral_status'] = 1;
                                break;
                            case 'signed':
                                $o['collateral_status'] = 2;
                                break;
                            case 'declined':
                                $o['collateral_status'] = 0;
                                break;
                        }
                        break;
                }
                */
            }
            $o['id'] = $c->id;
            $r[] = $o;
        }
        return  $r ; // Ok, entendi que este metodo se llama desde otro que SI empaqueta los datos en un response
    }
    private function getSchedules($user){

        $schedules = Schedule::where('user_id', $user->id)->get();
        $my_schedules = [];
        foreach ($schedules as $schedule) {
            $property = Property::where('id',$schedule->property_id)
                ->select('id', 'name','is_project','description')
                ->first();

            $schedule['property'] = $property;
            $schedule['schedule_date'] = $schedule->schedule_date;

            switch ($schedule->schedule_state) {
                case 0:
                    $schedule_state = ' en espera';
                    $schedule->schedule_color = '#ffd900';
                    break;
                case 1:
                    $schedule_state = 'rechazada';
                    $schedule->schedule_color = '#ff3860';
                    break;
                case 2:
                    $schedule_state = 'aprobada';
                    $schedule->schedule_color = '#23d160';
                    break;
                case 3:
                    $schedule_state = 'expirada';
                    $schedule->schedule_color = '#4a4a4a';
                    break;
                
                default:
                    $schedule_state = 'No ingreso a caso';
                    $schedule->schedule_color = '#4a4a4a';
                    break;
            }

            switch ($schedule->schedule_range) {
                case '9-12':
                    $schedule_range = 'Mañana';
                    break;

                case '12-3':
                    $schedule_range = 'Mediodia';
                    break;

                case '3-7':
                    $schedule_range = 'Tarde';
                    break;
                
                default:
                    $schedule_range = 'No ingreso a caso';
                    break;
            }
            $schedule['id'] = $schedule->id;

            if($property != null){
                if($property->is_project == 1){
                    $schedule['url'] = url('agentes/'.$property->id);
                    $schedule['schedule_title'] = 'Cita '.$schedule_state. ' para la ' . $schedule_range . ' en el proyecto ' . $property->name . '.';
                } else {
                    $schedule['url'] = url('explorar/'.$property->id.'/'.$property->slug);
                    $schedule['schedule_title'] = 'Cita '.$schedule_state. ' para la ' . $schedule_range . ' en la propiedad ' . $property->name . '.';
                }
    
                $photo_property = Photo::where(['property_id' => $property->id, 'cover' => true])->first();
    
                $property_data = (object)[
                    'id' => $property->id,
                    'name' => $property->name,
                    'photo' => $photo_property->path,
                    'slug'=> $property->slug,
                    'is_project' => $property->is_project,
                    'description' => $property->description
                ];
    
                $schedule['property'] = $property_data;
                array_push($my_schedules, $schedule);
            }
        }
        return $my_schedules;

    }
    private function getUserData($user) {
        $data = $user->toArray();

        $data['utype'] = 'Arrendatario';
        $data['age'] = Carbon::parse($user->birthdate)->age;

        $data['postulations'] = count($user->getAppliedPropertiesNoEspera());
        $data['providers'] = $user->providers()->get();
        
        $data['fav_holdings'] = count($user->getFavouriteProperties());
        
        $data['civilstatus'] = CivilStatus::find($user->civil_status_id)->name;


        $documents = [
            'id_front', 'id_back', 'afp', 'dicom', 'first_settlement', 'second_settlement', 'third_settlement', 'work_constancy', 'other_income', 'last_invoice', 'employment_type', 'saves'
        ];



        $data['role'] = 1;

        $data['documents'] = [];

        $data['collateral'] = [];
        if ($collateral = $user->collateral()->first()) {
            $data['collateral'] = [
                'firstname' => $collateral->firstname,
                'lastname' => $collateral->lastname,
                'email' => $collateral->email,
                'phone_verified' => $collateral->phone_verified,
                'mail_verified' => $collateral->mail_verified
            ];

            foreach ($documents as $docs) {
                if ($exist = File::where('user_id', $collateral->id)->where('name', $docs)->first()) {
                    $data['collateral']['documents'][$docs] = $exist;
                }
    
            }
        } else {
            $data['collateral'] = [
                'firstname' => "",
                'lastname' => "",
                'email' => "",
            ];
        }


        foreach ($documents as $docs) {
            if ($exist = File::where('user_id', $user->id)->where('name', $docs)->first()) {
                $data['documents'][$docs] = $exist;
            }

        }


        $data['membership'] = strtolower($user->getTenantMerbership()->name);
        $membership_data = $user->getTenantMerbership();
        $data['memberships'] = Membership::getTenantMemberships();

        //$dateMembership = new \DateTime($membership_data->pivot->expires_at);
        //$membership_data->pivot->expires_at = $dateMembership->format('d/m/Y');

        $data['membership_data'] = $membership_data;

        $all_notifs = Notification::whereIn('id', [1, 2, 3, 4, 5, 6, 7, 8, 9])->get();
        $data['notifications'] = [];
        foreach ($all_notifs as $notif) {
            $value = true;
            $n_id = $notif->id;
            $active = true;
            if ($has_notif = $user->notifications()->where('notification_id', $n_id)->first()) {
                $active = $has_notif->pivot->active;
            } else {
                $user->notifications()->attach($notif);
            }
            $data['notifications'][] =
                [
                    'id' => $notif->id,
                    'title' => $notif->name,
                    'value' => $active == true
                ];
        }


        $all_privacy = Privacy::whereIn('id', [1, 2, 3, 4])->get();
        $data['privacies'] = [];
        foreach ($all_privacy as $priv) {
            $value = true;
            $n_id = $priv->id;
            $active = true;
            if ($has_privacy = $user->privacies()->where('privacy_id', $n_id)->first()) {
                $active = $has_privacy->pivot->active;
            } else {
                $user->privacies()->attach($priv);
            }
            $data['privacies'][] =
                [
                    'id' => $priv->id,
                    'title' => $priv->name,
                    'value' => $active == true
                ];
        }

        return $data;
    }

    function getOrderPayment(Request $request){
        $user = Auth::user();
        if($user){
            $payment = Payment::where('user_id', $user->id)->where('order', $request->order)->first();
            if($payment){
                return $payment;
            } else {
                return [
                    'error' => 'No se encontro orden de pago ' . $request->order
                ];
            }
        } else {
            return [
                'error' => 'Usuario no Autenticado'
            ];
        }
    }


}


