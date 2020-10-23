<?php
/*  By: Dixán Santiesteban
    PERFIL DE AGENTES
*/

namespace App\Http\Controllers;

use App\CivilStatus;
use App\Notification;
use App\File;
use App\Membership;
use App\Privacy;
use App\ApplyProperty;
use App\Visit;
use http\Env\Response;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Property;
use Illuminate\Support\Facades\DB;
use App\Payment;
use App\Photo;
use App\Company;
use App\{UserProperty, Schedule};

class AgentProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   function agentProfileIndexAction()
    {
        if($user = Auth::user()){
            $redirect = User::where('id', $user->id)->first();
            if($redirect->agent_profile_redirect == null){
                return view('profiles/agent', [
                    'user' => \Auth::user(),
                    'role-id' => 3
                ]);
            } else {
                return redirect()->route($redirect->agent_profile_redirect);
            }
        }
    }

    function getInfoAction()
    {
        if ($user = Auth::user()) {

            $data = $this->getUserData($user);
            $data['memberships'] = Membership::getAgentMemberships();
            
            $data['payments'] = Payment::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();

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
                'text' => $pf->name,
                'type' => $pf->type
            ];
        }

        $all = \App\Space::all();
        $pspaces = [];
        foreach ($all as $ps) {
            $pspaces[] = [
                'id' => $ps->id,
                'name' => $ps->name
            ];
        }

        $filters['photos_spaces'] = [
            'options' => $pspaces
        ];

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


        $filters['property_amenities'] = [
            'options' => $pa
        ];


        $all = \App\Amenity::where('type', true)->get();
        $ca = [];
        foreach ($all as $pf) {
            $ca[] = [
                'id' => $pf->id,
                'text' => $pf->name
            ];
        }

        $filters['common_amenities'] = [
            'options' => $ca
        ];

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
            // LISTA DE CAMPOS DISPONIBLES PARA SALVAR
            $fillable = [
                'email', 'firstname', 'lastname', 'document_type', 'document_number', 'metas', 'profile_picture', 'active',
                'birthdate', 'address', 'address_details', 'latitude', 'longitude', 'landline', 'phone', 'phone_code', 'mail_verified', 'phone_verified',
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
                'password' => "required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/"
            ];

            $reject = [];
            $validations = [];

            if ($action = $request->get('action')) {
                $data = $request->get('data');
                foreach ($data as $id => $value)
                    $user->{$action}()->updateExistingPivot($id,['active' => $value=='true'?true:false ]);

                return response([
                    'success' => true,
                    'user' => $user,
                ]);
            }

            foreach ($request->all() as $field => $value)
                if (in_array($field, $fillable)) {
                    $user->{$field} = $value;
                    if (isset($validatedData[$field])) {
                        $validations[$field] = $validatedData[$field];
                    }
                } else
                    $reject[] = $field;

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

    public function saveCompanyAction(Request $request) {
        if($user = Auth::user()){

            $fillable = [
                'name', 'invoice', 'giro', 'rut', 'phone', 'cell_phone', 'email', 'website', 'description',
                'city_id', 'address', 'address_details','latitude','longitude'
            ];

            $company = Company::find($user->getAgentCompany()->id);

            $reject = [];

            foreach ($request->all() as $field => $value)
                if (in_array($field, $fillable)) {
                    $company->{$field} = $value;
                } else
                    $reject[] = $field;

            $company->save();

            return response([
                'success' => true,
                'reject' => $reject,
            ]);

        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }
    }

    function saveEmploymentAction(Request $request)
    {
        if ($user = Auth::user()) {

            $fillable = [
                '_token'
            ];

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
            elseif ($request->employment_type == User::AGENT_EMPLOYMENT_TYPE) {
                $user->employment_type = User::AGENT_EMPLOYMENT_TYPE;
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


    // ******************************
    function getDataAction($id)
    {
        $datasource = [
            0 => 'myProfile',
            1 => 'myPostulations',
            2 => 'myHoldings',
            3 => 'myForms',
            4 => 'getSchedules'
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
    {
        $age = Carbon::parse($user->birthdate)->age;

        $documents = [
            'id_front', 'id_back', 'afp', 'dicom', 'first_settlement', 'second_settlement', 'third_settlement', 'work_constancy', 'other_income', 'last_invoice'
        ];

        foreach ($documents as $docs) {
            $$docs = '';
            if ($exist = File::where('user_id', $user->id)->where('name', $docs)->first())
                if (!is_null($exist->path))
                    $$docs = $exist->id;
        }

        $membership = strtolower(Membership::getAgentMemberships()[0]->name);

        return [
            'avatar' => '',
            'name' => "{$user->firstname} {$user->lastname}",
            'type' => 'Agente',
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
    {
        $properties = $user->getAppliedProperties();

        return $properties->map(function ($prop) use ($user) {
            $prop['applied_membership'] = $user->getAgentMerbership();

            $postulation = $prop->applications()->where('user_id', $user->id)->first();

            switch ($postulation->state) {
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

            $prop['postulation'] = [
                'status_int' => $postulation->state,
                'status' => $status,
                'text' => $text,
                'created_at' => $datePos->format('d/m/Y')
            ] ;
            $prop['agent'] = $prop->getAgent();

            $agent_mb = $prop->getAgent()->getAgentMerbership();

            $prop['agent_membership'] = [
                'name' => strtolower($agent_mb->name)
            ];
            $prop['scoring'] = $user->scoring;
            $prop['slug'] = $user->slug;
            return $prop;
        });
    }

    private function myHoldings($user)
    {
        return $user->getFavouriteProperties()->map(function($prop) {
            return [
                'id' => $prop->id,
                'imagePath' => $prop->photos[0]->path,
                'name' => $prop->name,
                'slug' => $prop->slug,
                'description' => $prop->description,
                'membership' => $prop->getAgent()->getAgentMerbership(),
                'demand' => $prop->getDemandAttribute(),
                'scoring' => $prop->get,
                'verified' => $prop->verified,
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
    {
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

    // 21-02-2019 --- Datos para el Perfil **********************************************

    private function getUserData($user) {
        $data = $user->toArray();

        $data['utype'] = 'Agente';
        $data['age'] = Carbon::parse($user->birthdate)->age;
        $data['avatar'] = '';
        $data['postulations'] = count($user->getAppliedProperties());
        $data['fav_holdings'] = count($user->getFavouriteProperties());
        $data['civilstatus'] = CivilStatus::find($user->civil_status_id)->name;
        $membership_data = $user->getAgentMerbership();

        //$dateMembership = new \DateTime($membership_data->pivot->expires_at);
        //$membership_data->pivot->expires_at = $dateMembership->format('d/m/Y');

        $data['membership_data'] = $membership_data;
        if ($data['membership_data'] != null) {
            $data['membership_data'] = $data['membership_data']->toArray();
        }
        $documents = [
            'id_front', 'id_back', 'afp', 'dicom', 'first_settlement', 'second_settlement', 'third_settlement', 'work_constancy', 'other_income', 'last_invoice', 'employment_type'
        ];
        $data['role'] = 3;
        $data['documents'] = [];

        $data['company'] = $user->getAgentCompany();
        $data['logo'] = Photo::where(['company_id' => $user->getAgentCompany()->id, 'logo' => true])->get();
        $data['logo_get'] = route('users.agents.get-logo', ['company_id' => $user->getAgentCompany()->id]);
        $data['logo_save'] = route('users.agents.save-logo', ['company_id' => $user->getAgentCompany()->id]);
        $data['logo_del'] = route('users.agents.del-logo');

        $data['aplications'] = 0;
        $properties = $this->getProperties();
        
        $data['projects'] = [];
        foreach ($properties as $key => $prop) {
            $prop['applied_membership'] = $user->getTenantMerbership();
            $postulations = $prop->applications;

            $agent_mb = $prop->getAgent()->getAgentMerbership();
            $active = $membership_data->getFeatures();

            $prop['charts'] = $this->getCharts($prop->id);

            $prop['agent_membership'] = $agent_mb;
            $count = NULL;
            foreach ($postulations as $key => $postulation) {
                    
                $count = $count + 1;
                /*switch($owner_mb->name){
                    case 'Basic':
                        if($count <= $active->applications_received_count){
                            $postulation['enabled'] = true;
                        } else {
                            $postulation['enabled'] = false;
                        }
                        break;
                    case 'Select':
                        if($count <= $active->applications_received_count){
                            $postulation['enabled'] = true;
                        } else {
                            $postulation['enabled'] = false;
                        }
                        break;
                    case 'Premium':
                        $postulation['enabled'] = true;
                        break;
                }*/

                

                switch ($postulation->state) {
                    case 6: 
                        $status = 'refused';
                        $text = 'CONTRATO CANCELADO';
                        break;
                    case 5: 
                        $status = 'signed';
                        $text = 'FIRMADO';
                        break;
                    case 4: 
                        $status = 'approved';
                        $text = 'VERIFICADOS';
                        break;
                    case 3: 
                        $status = 'approved';
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

                $datePos = new \DateTime($postulation['created_at']);
                $postulation['status'] = $status;
                $postulation['status_text'] = $text;
                //$postulation['created_at'] = $datePos->format('d/m/Y');
                $postulation['owner'] = $prop->getAgent();

                //Revizar
                /*if($prop->type_stay == 'SHORT_STAY'){
                    $postulation['applied_days'] = json_decode(DB::table('users_has_postulates_days')->select('days')->where('id', $postulation['id'])->first()->days);
                }*/
                $tenant = $postulation->postulant();

                $tenant->collateral = [];

                if ($collateral = $tenant->collateral()->first()) {

                    $documents = [
                        'id_front', 'id_back', 'afp', 'dicom', 'first_settlement', 'second_settlement', 'third_settlement', 'work_constancy', 'other_income', 'last_invoice', 'employment_type', 'saves'
                    ];

                    $documents_collateral = [];

                    foreach ($documents as $docs) {
                        if ($exist = File::where('user_id', $collateral->id)->where('name', $docs)->first()) {
                            $documents_collateral[$docs] = $exist;
                        }
            
                    }


                    $tenant->collateral = [
                        'firstname' => $collateral->firstname,
                        'lastname' => $collateral->lastname,
                        'email' => $collateral->email,
                        'document_type' => $collateral->document_type,
                        'document_number' => $collateral->document_number,
                        'age' => Carbon::parse($collateral->birthdate)->age,
                        'country_id' => $collateral->country_id,
                        'address' => $collateral->address,
                        'files' => $documents_collateral
                    ];
                } else {
                    $data['collateral'] = [
                        false
                    ];
                }
                $date = date_create($tenant->move_date);
                $tenant->move_date = date_format($date, 'd-m-Y');

                $tenant->files = DB::table('files')->where('user_id', $tenant->id)->get();
                 
                $tinfo = DB::table('users')->select('country_id')->where('id', '=', $tenant->id)->first();
                $membershipTenant = DB::table('users')
                ->select('memberships.name')
                ->join('users_has_memberships', 'users_has_memberships.user_id', '=', 'users.id')
                ->join('memberships', 'users_has_memberships.membership_id', '=', 'memberships.id')
                ->where('memberships.role_id', '=', 1)
                ->where('users.id', '=', $tenant->id)
                ->first();
                $membershipTenant->name = strtolower($membershipTenant->name);

                $tenant->logo_membership = $membershipTenant->name;

                if($membershipTenant->name == 'select') $membershipTenant->name = 'selects';

                $tenant->logscoring = $membershipTenant->name;

                $tenant->score = \DB::select("select sf_score(?,?) as scoring", [$tenant->id, $prop->id])[0]->scoring;
                
                $tenant->amenities = $this->getOnlyId($tenant->amenities()->get());

                $membershipOwner = DB::table('users_has_memberships')->select('memberships.name')
                ->join('memberships', 'users_has_memberships.membership_id', '=', 'memberships.id')
                ->where('memberships.role_id', 2)
                ->where('users_has_memberships.user_id', Auth::user()->id)
                ->first();

                switch (strtolower($membershipOwner->name)) {
                    case 'basic':
                        $tenant->email = false;
                        $tenant->phone = false;
                        $tenant->phone_code = false;
                        break;
                    case 'select':
                        $tenant->phone = false;
                        $tenant->phone_code = false;
                        break;
                }
                $tenant->chat = true;

                $tenant->age = Carbon::parse($tenant->birthdate)->age;

                $postulation['tenant'] = $tenant;
                $owner_mb = $prop->getAgent()->getAgentMerbership();
                $prop['scoring'] = $tenant->getScoring($prop->id);
                $postulation['slug'] = $tenant->slug;
            
                $postulation = $postulation->toArray();

                //$postulation['created_at'] = $datePos->format('d/m/Y');

                $data['aplications'] = $data['aplications'] + 1;

            }

            $prop['applications'] = $postulations;

            $visitors = DB::table('users')
                ->join('users_has_properties', 'users.id', '=', 'users_has_properties.user_id')
                ->join('schedules', 'users_has_properties.id', '=', 'schedules.id')
                ->where('users_has_properties.property_id', $prop->id)
                ->where('users_has_properties.type', 4)
                ->get();

            $visitors_set = [];

            $membershipAgent = DB::table('users_has_memberships')->select('memberships.name')
                ->join('memberships', 'users_has_memberships.membership_id', '=', 'memberships.id')
                ->where('memberships.role_id', 3)
                ->where('users_has_memberships.user_id', Auth::user()->id)
                ->first();

            

            

            foreach ($visitors as $visitor) {
                switch (strtolower($membershipAgent->name)) {
                    case 'basic':
                        $visitor->email = false;
                        $visitor->phone = false;
                        $visitor->phone_code = false;
                        break;
                    case 'select':
                        $visitor->phone = false;
                        $visitor->phone_code = false;
                        break;
                }
                $visitor_set = [
                    'firstname' => $visitor->firstname,
                    'lastname' => $visitor->lastname,
                    'schedule_range' => $visitor->schedule_range,
                    'id' => $visitor->id,
                    'property_id' => $visitor->property_id,
                    'schedule_state' => $visitor->schedule_state,
                    'schedule_date' => $visitor->schedule_date,
                    'email' => $visitor->email,
                    'phone' => $visitor->phone,
                    'phone_code' => $visitor->phone_code,
                    'chat' => true

                ];
                array_push($visitors_set,$visitor_set);
            }

            $prop['visitors'] = $visitors_set;

            $data['projects'][] = $prop;
            
        }


        $data['collateral'] = [];
        if ($collateral = $user->collateral()->first()) {
            $data['collateral'] = [
                'firstname' => $collateral->firstname,
                'lastname' => $collateral->lastname,
                'email' => $collateral->email,
            ];
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

        $data['membership'] = strtolower(Membership::getAgentMemberships()[0]->name);

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

        $data['schedules'] = $this->getSchedules();

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

    // 04-03-2019

    function getProperties()
    {

        $ret = [];
        $all = Auth::user()
        ->properties()
        //->with(['photos.space'])
        ->where('type', 5)->get();
        
        foreach ($all as $prop) {


            $address = $prop->address;
            $pt = explode(",", $address);
            $city = '';
            if (count($pt) > 1) $city = array_pop($pt);
            $prop['address'] = implode(',', $pt);
            $prop['city'] = $city;

            $prop['amenities'] = $this->getOnlyId($prop->amenities()->get());
            $prop['properties_for'] = $this->getOnlyId($prop->propertiesFor()->get());
            $prop['photos'] = Photo::where('property_id', $prop->id)->get();

            switch($prop->status){
                case 0: 
                    //$prop['status'] = "Publicado";
                    $prop['statusname'] = "publicado";
                    break;
                case 1: 
                    //$prop['status'] = "En Pausa";
                    $prop['statusname'] = "en-pausa";
                    break;
                case 2: 
                    //$prop['status'] = "Arrendado";
                    $prop['statusname'] = "arrendado";
                    break;
                case 3: 
                    //$prop['status'] = "Cancelado";
                    $prop['statusname'] = "cancelado";
                    break;
                    
            }

            //$prop['rent'] = number_format($prop->rent,0,",",".");

            $prop['postulation'] = DB::table('users_has_properties')->where('type', '2')->where('property_id', $prop->id)->count('type');

            $prop['amenities'] = $this->getOnlyId($prop->amenities()->get());
            $prop['properties_for'] = $this->getOnlyId($prop->propertiesFor()->get());


            //$prop['image'] = '/images/viewhome.png';
             //$prop['image'] = '/images/viewhome.png';
             $imagen = DB::table('photos')->where("property_id", $prop->id)->first();
             if(!empty($imagen->path)){
                 $prop['image'] = $imagen->path;
             } else {
                 $prop['image'] = null;
             }

            $prop['files'] = DB::table('files')->where('property_id', $prop->id)->get();

            $ret[] = $prop;
        }

        return $ret;

    }

    function getOnlyId($w)
    {
        $ret = [];
        foreach ($w as $v)
            $ret[] = $v->id;

        return $ret;
    }

    private function getSchedules(){

        if($user = Auth::user()){

            $my_properties = UserProperty::where('user_id', $user->id)->where('type', 5)->get();
            $properties = [];
            $my_schedules = [];
            foreach ($my_properties as $prop) {
                $schedules = Schedule::where('property_id', $prop->property_id)->get();
                foreach ($schedules as $schedule) {
                    $property = Property::where('id',$schedule->property_id)->first();
                    $user = User::where('id',$schedule->user_id)->first();
                    $user_list = (object)[
                        'id' => $user->id,
                        'name' => $user->firstname . ' ' . $user->lastname
                    ];

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
                        $schedule['visitor'] = $user_list;
                        array_push($my_schedules, $schedule);
                    }
                }
            }
            return $my_schedules;
        }

    }
    private function getCharts($id){
        if($user = Auth::user()){
            $schedules = Schedule::selectRaw('year(schedule_date) year, monthname(schedule_date) month, count(*) data')
                ->where('property_id', $id)
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->get();
            $postulations = ApplyProperty::selectRaw('year(created_at) year, monthname(created_at) month, count(*) data')
                ->where('property_id', $id)
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->get();
            $visits = Visit::selectRaw('year(created_at) year, monthname(created_at) month, count(*) data')
                ->where('property_id', $id)
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->get();

            $schedules_data = [];
            $postulations_data = [];
            $visits_data = [];
            $data_labels = [];

            foreach ($schedules as $schedule) {
                $schedule->label = $schedule->month . ' ' . $schedule->year;
                if(!in_array($schedule->label, $data_labels)){
                    array_push($data_labels,$schedule->label);
                }
                array_push($schedules_data,$schedule);
            }

            foreach ($postulations as $postulation) {
                $postulation->label = $postulation->month . ' ' . $postulation->year;
                if(!in_array($postulation->label, $data_labels)){
                    array_push($data_labels,$postulation->label);
                }
                array_push($postulations_data,$postulation);
            }

            foreach ($visits as $visit) {
                $visit->label = $visit->month . ' ' . $visit->year;
                if(!in_array($visit->label, $data_labels)){
                    array_push($data_labels,$visit->label);
                }
                array_push($visits_data,$visit);
            }

            $fechas = [
                'schedules' => $schedules_data,
                'postulations' => $postulations_data,
                'visits' => $visits_data,
                'labels' => $data_labels];
        }
        return $fechas;
    }
}
