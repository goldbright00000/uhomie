<?php
/*  By: Dixán Santiesteban
    PERFIL DE SERVICIOS
*/

namespace App\Http\Controllers;

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
use App\Property;
use App\Company;
use App\ServiceList;
use Illuminate\Support\Facades\DB;
use App\Photo;
use App\Payment;

class ServiceProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   function serviceProfileIndexAction()
    {
        if($user = Auth::user()){
            $redirect = User::where('id', $user->id)->first();
            if($redirect->service_profile_redirect == null){
                return view('profiles/service', [
                    'user' => Auth::user(),
                    'role-id' => 4
                ]);
            } else {
                return redirect()->route($redirect->service_profile_redirect);
            }
        }
        
    }

    function getInfoAction()
    { 
        if ($user = Auth::user()) {

            $data = $this->getUserData($user);

            $membership_data = $user->getServiceMerbershipOnce();

            $data['memberships'] = Membership::getServiceMemberships();

            //$dateMembership = new \DateTime($membership_data->pivot->expired_at);
            //$membership_data->pivot->expires_at = $dateMembership->format('d/m/Y');

            $data['membership_data'] = $membership_data;
            $company = DB::table('companies')->where('user_id', $user->id)->first();
            $services = DB::table('companies_has_services_list')
                ->select('services_list.id as list_id','services_type.id as type_id','services_list.name as list_name','services_type.name as type_name','companies_has_services_list.service_list_id','companies_has_services_list.id','companies_has_services_list.description')
                ->join('services_list', 'services_list.id', '=','companies_has_services_list.service_list_id')
                ->join('services_type', 'services_list.service_type_id', '=', 'services_type.id')
                ->where('companies_has_services_list.company_id',$company->id)->get();

            foreach($services as $service){
                $photos = Photo::where('service_list_id',$service->id)->get();
                if(empty($photos)){
                    $photos['path'] = null;
                }
                $service->photos = $photos;
                $photo = Photo::where('service_list_id',$service->id)->first();
                if(empty($photo)){
                    $photo['path'] = null;
                }
                $service->photo = $photo;
            }
            $data['services'] = $services;

            $data['company'] = $user->getServiceCompany();
            $data['logo'] = Photo::where(['company_id' => $user->getServiceCompany()->id, 'logo' => true])->get();
            $data['logo_get'] = route('users.services.get-logo', ['company_id' => $user->getServiceCompany()->id]);
            $data['logo_save'] = route('users.services.save-logo', ['company_id' => $user->getServiceCompany()->id]);
            $data['logo_del'] = route('users.services.del-logo');

            $data['payments'] = Payment::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();


            //$company = $user->companies();
            //$services = ServicesList::find($company->id);

            //$data['services'] = 

            return response([
                'success' => true,
                'info' => $data,
            ]);

        } else {
            return response('No está autenticado.', 401);
        }
    }


    function getFiltersAction()
    { 
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

        $all = \App\PropertyType::all();
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

        $all = \App\ServiceType::all();
        $stype = [];
        foreach ($all as $st) {
            $stype[] = [
                'id' => $st->id,
                'text' => $st->name
            ];
        }

        $all = \App\ServiceList::all();
        $slist = [];
        foreach ($all as $sl) {
            $slist[] = [
                'id' => $sl->id,
                'text' => $sl->name,
                'type' => $sl->service_type_id
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

        $filters['service_type'] = [
            'options' => $stype
        ];

        $filters['service_list'] = [
            'options' => $slist
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
            // LISTA DE CAMPOS DISPONIBLES PARA SALVAR
            $fillable = [
                'email', 'firstname', 'lastname', 'document_type', 'document_number', 'metas', 'profile_picture', 'active',
                'birthdate', 'address', 'address_details', 'latitude', 'longitude', 'landline', 'phone', 'phone_code', 'mail_verified', 'phone_verified',
                'employment_details', 'expenses_limit', 'common_expenses_limit',
                'warranty_months_quantity', 'months_advance_quantity', 'tenanting_months_quantity', 'move_date', 'property_type', 'property_condition',
                'property_for', 'pet_preference', 'furnished', 'smoking_allowed', 'created_by_reference', 'confirmed_collateral', 'last_invoice_amount',
                'position', 'company', 'job_type', 'worked_from_date', 'worked_to_date', 'amount', 'saves', 'save_amount', 'afp', 'invoice', 'bank', 'bank_id', 'account_type', 'account_number',
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

            $company = Company::find($user->getServiceCompany()->id);

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
            elseif ($request->employment_type == User::SERVICE_EMPLOYMENT_TYPE) {
                $user->employment_type = User::SERVICE_EMPLOYMENT_TYPE;
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

        $membership = strtolower(Membership::getServiceMemberships()[0]->name);

        return [
            'avatar' => '',
            'name' => "{$user->firstname} {$user->lastname}",
            'type' => 'Servicios',
            'membership' => $membership,

            'membership_exp' => '10/19/2019',

            'age_cstatus' => "$age años, " . CivilStatus::find($user->civil_status_id)->name,
            'rut' => $user->document_number,
            'address' => $user->address . '',
            'postulations' => '1',
            'phone' => "Celular: {$user->phone}",
            'fav_holding' => '1',

            // 'obverse' => '' . $id_front,
            // 'reverse' => '' . $id_back,
            // 'employ_cert' => $work_constancy,
            // 'first_paying' => $first_settlement,
            // 'second_paying' => $second_settlement,
            // 'third_paying' => $third_settlement,
            // 'AFP_cert' => $afp,
            // 'DICOM_cert' => $dicom,
            // 'others' => $other_income
        ];
    }

    private function myPostulations($user)
    { 
        $properties = $user->getAppliedProperties();

        return $properties->map(function ($prop) use ($user) {
            $prop['applied_membership'] = $user->getServiceMerbership();

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
            $prop['service'] = $prop->getService();

            $service_mb = $prop->getService()->getServiceMerbership();

            $prop['service_membership'] = [
                'name' => strtolower($service_mb->name)
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
                'membership' => $prop->getService()->getServiceMerbership(),
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

        $data['utype'] = 'Servicios';
        $data['age'] = Carbon::parse($user->birthdate)->age;
        $data['avatar'] = '';
        $data['postulations'] = count($user->getAppliedProperties());
        $data['fav_holdings'] = count($user->getFavouriteProperties());
        $data['civilstatus'] = CivilStatus::find($user->civil_status_id)->name;
        $documents = [
            'id_front', 'id_back', 'afp', 'dicom', 'first_settlement', 'second_settlement', 'third_settlement', 'work_constancy', 'other_income', 'last_invoice', 'employment_type'
        ];
        $data['role']=4;
        $data['role_name'] = "service";
        $data['documents'] = [];

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

        $data['membership'] = strtolower(Membership::getServiceMemberships()[0]->name);

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
    public function saveServiceAction(Request $request){
        if ($user = Auth::user()) {
            $service = DB::table('companies_has_services_list')->where('id', $request->id)->update([
                'service_list_id' => $request->list_id,
                'description' => $request->description
            ]);
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }
    }
}


