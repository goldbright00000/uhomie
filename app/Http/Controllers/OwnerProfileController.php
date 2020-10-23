<?php
/*  By: Dixán Santiesteban
    PERFIL DE ARRENDADOR
*/

namespace App\Http\Controllers;

use App\Notification;
use App\Property;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Privacy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\CivilStatus;
use App\File;
use App\Membership;
use App\ApplyProperty;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notification as NotificationMessage;
use App\Notifications\ApplyPropertyTenant as ApplyPropertyTenantNotification;
use App\Notifications\ApplyPropertyTenantAccept;
use App\Notifications\CancelPropertyTenant as CancelPropertyTenantNotification;
use App\Notifications\ApplyPropertyOwnerAccept;
use App\Notifications\TwilioPush;
use App\Contract;
use App\Payment;
use App\Photo;
use App\UserProperty;
use App\Schedule;
use App\Visit;

class OwnerProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function ownerProfileIndexAction()
    {
        if($user = Auth::user()){
            $redirect = User::where('id', $user->id)->first();
            if($redirect->owner_profile_redirect == null){
                return view('profiles/owner', [
                    'user' => \Auth::user(),
                    'role-id' => 2
                ]);
            } else {
                return redirect()->route($redirect->owner_profile_redirect);
            }
        }
    }

    function getInfoAction()
    {

        if ($user = Auth::user()) {

            if(!$user->activation_token) {
                $user->activation_token = $user->generateToken();
                $user->save();
            }
            $data = $user->toArray();
            unset($data['api_token']);
            $data['secret'] = $user->api_token;
            $data['utype'] = 'Arrendador';
            $data['age'] = Carbon::parse($user->birthdate)->age;
            $data['avatar'] = '';
            $data['postulations'] = $user->properties()->where('type', 1)->count();
            $data['schedules'] = $this->getSchedules();
            //$data['aplications'] = $user->properties()->where('type', 2)->count();
            $data['fav_holdings'] = $user->properties()->where('type', 3)->count();
            $membership_data = $user->getOwnerMerbership();

            //$dateMembership = new \DateTime($membership_data->pivot->expires_at);
            //$membership_data->pivot->expires_at = $dateMembership->format('d/m/Y');

            $data['membership_data'] = $membership_data;
            if ($data['membership_data'] != null) {
                $data['membership_data'] = $data['membership_data']->toArray();
            }
            $data['civilstatus'] = CivilStatus::find($user->civil_status_id)->name;


            $documents = [
                'id_front', 'id_back',
            ];


            $data['role'] = 2;

            $data['documents'] = [];

            $data['collateral'] = [];
            if ($collateral = $user->collateral()->first()) {
                $data['collateral'] = [
                    'firstname' => $collateral->firstname,
                    'lastname' => $collateral->lastname,
                    'email' => $collateral->email,
                ];
            }

            foreach ($documents as $docs) {
                $$docs = '';
                if ($exist = File::where('user_id', $user->id)->where('name', $docs)->first())
                    if (!is_null($exist->path))
                        $$docs = $exist->id;
            }

            foreach ($documents as $docs) {
                if ($exist = File::where('user_id', $user->id)->where('name', $docs)->first()) {
                    $data['documents'][$docs] = $exist;
                }
    
            }

            $data['membership'] = strtolower($user->getOwnerMerbership()->name);
            $data['memberships'] = Membership::getOwnerMemberships();
            $all_notifs = Notification::whereIn('id', [10, 2, 11, 4, 5, 6, 7, 8, 9, 12])->get();
            $data['payments'] = Payment::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
            
            /*Prueba para imagen base 64
            $url = 'https://uhomie.azurewebsites.net/api/getthumbfromidvindexer?code=aA4DK1jIYvZiXhnCryRfpJgVwi9CLookqpOcYcrcoAaUIKJdZp1GCQ==&idVideoIndexer=969c6dfe18';
            $content = file_get_contents($url); 
            $data['prueba'] = $content;*/
            
            
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

            $all_privacy = Privacy::whereIn('id', [5, 6, 7])->get();
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
            $data['aplications'] = 0;
            $properties = $this->getProperties();
            
            $data['properties'] = [];
            foreach ($properties as $key => $prop) {
                $prop['applied_membership'] = $user->getTenantMerbership();
                $postulations = $prop->applications;

                $owner_mb = $prop->getOwner()->getOwnerMerbership();
                $active = $membership_data->getFeatures();

                $prop['charts'] = $this->getCharts($prop->id);
    
                $prop['owner_membership'] = $owner_mb;
                $count = NULL;
                foreach ($postulations as $key => $postulation) {
                    
                    $count = $count + 1;
                    switch($owner_mb->name){
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
                    }

                    
    
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
                    $postulation['owner'] = $prop->getOwner();

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
                    $owner_mb = $prop->getOwner()->getOwnerMerbership();
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

                $membershipOwner = DB::table('users_has_memberships')->select('memberships.name')
                    ->join('memberships', 'users_has_memberships.membership_id', '=', 'memberships.id')
                    ->where('memberships.role_id', 3)
                    ->where('users_has_memberships.user_id', Auth::user()->id)
                    ->first();

                

                foreach ($visitors as $visitor) {
                    switch (strtolower($membershipOwner->name)) {
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
                $data['properties'][] = $prop;
                
            }

            /*foreach ($properties as $key => $value) {
                $value->applications = $value->applications;
                array_push($data['properties'], $value);
            }*/

            

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

        $all = \App\Amenity::where('type', 2)->get();
        $bc = [];
        foreach ($all as $pf) {
            $bc[] = [
                'id' => $pf->id,
                'text' => $pf->name
            ];
        }

        $filters['basic_services'] = [
            'options' => $bc
        ];

        $all = \App\Amenity::where('type', 3)->get();
        $ra = [];
        foreach ($all as $pf) {
            $ra[] = [
                'id' => $pf->id,
                'text' => $pf->name
            ];
        }

        $filters['rules_amenities'] = [
            'options' => $ra
        ];

        $all = \App\Amenity::where('type', 4)->get();
        $da = [];
        foreach ($all as $pf) {
            $da[] = [
                'id' => $pf->id,
                'text' => $pf->name
            ];
        }

        $filters['details_amenities'] = [
            'options' => $da
        ];

        $all = \App\Amenity::where('type', 5)->get();
        $po = [];
        foreach ($all as $pf) {
            $po[] = [
                'id' => $pf->id,
                'text' => $pf->name
            ];
        }

        $filters['possessions'] = [
            'options' => $po
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


    function getProperties()
    {

        $ret = [];
        $all = Auth::user()
            ->properties()
            //->with(['photos.space'])
            ->where('type', 1)->get();
        
        foreach ($all as $prop) {


            $address = $prop->address;
            $pt = explode(",", $address);
            $city = '';
            if (count($pt) > 1) $city = array_pop($pt);
            $prop['address'] = implode(',', $pt);
            $prop['city'] = $city;

            $prop['photos'] = Photo::where('property_id', $prop->id)->get();

            $prop['amenities'] = $this->getOnlyId($prop->amenities()->get());
            $prop['properties_for'] = $this->getOnlyId($prop->propertiesFor()->get());

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

    function saveProfileAction(Request $request)
    {
        if ($user = Auth::user()) {

            $reject = [];
            $validations = [];

            if ($action = $request->get('action')) {

                $data = $request->get('data');
                foreach ($data as $id => $value)
                    $user->{$action}()->updateExistingPivot($id, ['active' => $value == 'true' ? true : false]);

                return response([
                    'success' => true,
                    //data' => $quepaso,
                    'user' => $user,
                ]);
            }


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
                'user' => $user,
            ]);
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }

    }

    function publishProperty(Request $request, $id) {
        if ($user = Auth::user()) {
            $property = Property::find($id);

            if ($property->status === 2 || $property->status === 3) {
                if ($property->status === 2) $status = 'arrendada'; 
                if ($property->status === 3) $status = 'cancelada'; 
                return response('La propiedad esta '. $status .', no puede realizar esta acción', 401);
            }
            $property->active = 1;
            $property->status = 0;
            $property->save();

            return response([
                'success' => true,
                'info' => $property,
            ]);
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }
    
    }

    function pauseProperty(Request $request, $id) {
        if ($user = Auth::user()) {
            $property = Property::find($id);

            if ($property->status === 2 || $property->status === 3) {
                if ($property->status === 2) $status = 'arrendada'; 
                if ($property->status === 3) $status = 'cancelada'; 
                return response('La propiedad esta '. $status .', no puede realizar esta acción', 401);
            }
            $property->active = 0;
            $property->status = 1;
            $property->save();

            return response([
                'success' => true,
                'info' => $property,
            ]);
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }
    
    }

    function leasedProperty(Request $request, $id) {
        if ($user = Auth::user()) {
            $property = Property::find($id);

            //return $property->status;

            if ($property->status === 3) {
                //if ($property->status === 2) $status = 'arrendada'; 
                if ($property->status === 3) $status = 'cancelada'; 
                return response('La propiedad esta '. $status .', no puede realizar esta acción', 401);
            }
            if($property->available == 1){
                $property->expire_at = Carbon::now()->addDays(30)->format('Y-m-d');
                $property->available = false;
                $property->status = 2;
                $property->save();
                return response([
                    'success' => true,
                    'info' => $property,
                ],200);
            }
            if($property->available == 0){
                $property->expire_at = null;
                $property->available = true;
                $property->status = 0;
                $property->save();
                return response([
                    'success' => true,
                    'info' => $property,
                ],200);
            }

            
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }
    
    }

    function deleteProperty(Request $request, $id) {
        if ($user = Auth::user()) {
            $property = Property::find($id);

            $userp = \App\UserProperty::where('property_id', $property->id)->where('user_id', $user->id)->first();

            $schedule = DB::table('schedules')->where('id', $userp->id);

            $propertyf = DB::table('properties_has_properties_for')->where('property_id', $property->id);
            $propertya = DB::table('properties_has_amenities')->where('property_id', $property->id);
            $photos = DB::table('photos')->where('property_id', $property->id);

            if ($property->status == 2) {
                return response('La propiedad esta arrendada, no puede realizar esta acción', 401);
            }

            /*$photos->delete();
            $propertya->delete();
            $propertyf->delete();
            $schedule->delete();
            $userp->delete();*/
            $property->delete();

            return response([
                'success' => true,
                'id' => $id,
            ], 200);
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }
    }


    function savePropsAction(Request $request)
    {

        //$request->id = 6;
        if ($user = Auth::user()) {

            if ($action = $request->get('action')) {

                $prop = Property::find($request->get('id'));
                $prop->{$action}()->sync($request->get('data'));

                return response([
                    'success' => true,
                    'data' => $request->get('data'),
                ]);

            }


            $reject = [];
            $validations = [];

            $prop = ($request->id == 0) ? new Property() : $user->properties()->where('property_id', $request->id)->first();


            $fillable = [
                'available','name', 'address', 'address_details', 'city_id', 'rent', 'rent_up', 'description', 'builder', 'architect','metas', 'images', 'common_expenses', 'common_expenses_support',
                'invoices', 'property_certificate', 'condition', 'meters', 'rooms_count', 'bathrooms_count', 'latitude', 'longitude',
                'expenses_limit', 'common_expenses', 'warranty_months_quantity', 'months_advance_quantity', 'available_date', 'tenanting_months_quantity', 'collateral_require', 'furnished', 'furnished_description', 'cellar', 'cellar_description', 'schedule_range', 'visit', 'visit_from_date',
                'visit_to_date', 'schedule_dates', 'bedrooms', 'bathrooms', 'pool', 'garden', 'terrace', 'private_parking', 'public_parking', 'pet_preference', 'smoking_allowed', 'nationals_with_rut', 'foreigners_with_rut', 'foreigners_with_passport', 'tenanting_insurance', 'manage',

                'property_type_id','anexo_conditions',"allow_small_child","allow_baby","allow_parties","use_stairs","there_could_be_noise","common_zones",
                "services_limited","survellaince_camera","weaponry","dangerous_animals","pets_friendly",
                "special_sale", "week_sale", "month_sale", "checkin_hour", "minimum_nights",
                'property_type_id','anexo_conditions',
                'term_year','rent_year_1', 'rent_year_2', 'rent_year_3',
                'room_enablement', 'civil_work', 'arquitecture_project', 'work_electric_water', 'building_name', 'level', 'rooms', 'meeting_room', 'exclusions','warranty_ticket','warranty_ticket_price','penalty_fees','cleaning_rate','single_beds','double_beds', 'lot_number'
            ];

            $relations = [
                'properties_for'=>'propertiesFor'
            ];

            // VALIDACIONES PARA LOS CAMPOS QUE VIENEN
            $validatedData = [
                /*'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'email' => "required|unique:users,email,{$user->id},id|max:255",*/
            ];

            foreach ($request->all() as $field => $value)
                if (in_array($field, $fillable)) {
                    $prop->{$field} = $value;
                    if (isset($validatedData[$field])) {
                        $validations[$field] = $validatedData[$field];
                    }
                } elseif (array_key_exists($field, $relations)) {
                    //return response('encontre a '.$value, 401);
                    $prop->save();
                    $prop->{$relations[$field]}()->sync( explode(',',$value));
                } else
                    $reject[] = $field;

            $request->validate($validations);


            //$prop->active = 1;
            //$prop->property_type_id = 1; // casa

            if(isset($request->available) && $request->available == 0){
                $prop->expire_at = Carbon::now()->addDays(30)->format('Y-m-d');
                $prop->available = false;
                $prop->status = 2;
            }

            if(isset($request->available) && $request->available == 1){
                $prop->expire_at = null;
                $prop->available = true;
                $prop->status = 0;
            }


            if ($request->id == 0) {
                $prop->save();
                $user->properties()->attach($prop);
            } else
                $prop->save();


            return response([
                'success' => true,
                'reject' => $reject,
                'user' => $user,
            ]);
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }

    }

    /*public function getTenant(Request $request) {
        $tenant = User::findOrFail($request->$id);
        return $tenant;
    }*/

    function saveConfigAction(Request $request){
        if($user = Auth::user()){

            $user->email = $request->email;
            $user->phone = $request->phone;

            if(!DB::table('users')->select('email')->where('email', '=', $request->email)->first()){
                $user->mail_verified = 0;
            }

            if(!DB::table('users')->select('phone')->where('phone', '=', $request->phone)->first()){
                $user->phone_verified = 0;
            }
            $user->remember_token = $request->_token;

            $user->save();

            return response([
                'success' => true,
                'user' => $user,
            ]);
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }
    }

    function saveStatePostulant(Request $request){
        if($user = Auth::user()){
            $postulation = ApplyProperty::find($request->id);
            $postulation->state = $request->state;

            $tenant = User::find($postulation->user_id);
            $property = Property::find($postulation->property_id);

            if($postulation->save()){
                if($postulation->state == 2){
                    
                    $new_contract = new Contract();
                    $new_contract->property()->associate($property);
                    
                    $new_contract->save();
                    if($collateral = $tenant->getCollateralUser()){
                        if( $collateral->isRegisterPreCompleted() ){
                            $new_contract->users()->attach($tenant, ['signer_role' => 'tenant']);
                            $new_contract->users()->attach($property->getOwner(), ['signer_role' => 'owner']);
                            $new_contract->users()->attach($tenant->getCollateralUser(), ['signer_role' => 'collateral']);
                            $new_contract->putPreContractWithAval($property->getOwner(), $tenant, $tenant->getCollateralUser(), $property);
                            $collateral->notify(new TwilioPush('Te informamos que tu avalado {$tenant->firstname} {$tenant->lastname} ha sido aceptado para la propiedad {$property->name}.'));
                            if( !$collateral->isVerified() ){
                                $collateral->notify(new TwilioPush('Te recordamos que tu avalado necesita de tu verificacion de identidad en '.env('APP_URL','http:/uhomie.cl').' para posteriormente generar el contrato de arriendo. Saludos, uHomie :).'));
                            }
                        }else{
                            $new_contract->users()->attach($tenant, ['signer_role' => 'tenant']);
                            $new_contract->users()->attach($property->getOwner(), ['signer_role' => 'owner']);
                            $new_contract->putPreContractWithoutAval($property->getOwner(), $tenant, $property);
                        }
                        
                    } else {
                        $new_contract->users()->attach($tenant, ['signer_role' => 'tenant']);
                        $new_contract->users()->attach($property->getOwner(), ['signer_role' => 'owner']);
                        $new_contract->putPreContractWithoutAval($property->getOwner(), $tenant, $property);
                    }
                    $url = env('APP_URL', 'https://www.uhomie.cl');
                    $tenant->notify(new ApplyPropertyTenantNotification($tenant->firstname, $tenant->lastname, $postulation->created_at, $property->id, $property->name));
                    $tenant->notify(new TwilioPush("Has sido aceptado para la propiedad {$property->name}. Puedes revisar iniciando sesion en {$url}"));
                    $property->getOwner()->notify(new ApplyPropertyOwnerAccept($property->id, $property, $property->getOwner()->firstname, $property->getOwner()->lastname, $tenant));
                    return response([
                        'success' => true,
                        'user' => $user,
                    ]);
                }
                if($postulation->state == 1){
                    $tenant->notify(new CancelPropertyTenantNotification($tenant->firstname, $tenant->lastname, $postulation->created_at, $property->id, $property->name));
                    return response([
                        'success' => true,
                        'user' => $user,
                    ]);
                }
            }
            throw new \Exception('Ha ocurrido inesperado. No se pudo guardar la postulacion.');
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }
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

    public function savePhoto(Request $request){

		$property = Property::find($request->property_id);        

        $files = $request->files->all();

        $db_file = Photo::find($request->photo_id);      

        if ($db_file) {
            $db_file->delete();
        }
        $db_file = new Photo;
		// return response($files);
		foreach ($files as $file) {            
			// code...
			// $file = $files[$request->photo_name];            
			try {
				if ($request->cover) {
					$old_cover = Photo::where(['property_id' => $request->property_id,'cover' => 1])->get();
					foreach ($old_cover as $c) {
						$c->delete();
					}
					$db_file->cover = true;
				}
				if ($request->space_id == 0) {
					$db_file->space_id = null;
				}else{
					$db_file->space_id = $request->space_id;
				}                
				$date_uuid = Carbon::now()->format('Ymdhmsu');

				$db_file->name = 'photo_'.$request->photo_name.'-'.$date_uuid. '.' . $file->getClientOriginalExtension();
				\Storage::disk('local')->putFileAs('public/properties/'.$property->id.'/photos/',$file,$db_file->name);
				$db_file->property_id = $property->id;
				$db_file->original_name = $file->getClientOriginalName();
				$db_file->path = '/storage/properties/'.$property->id.'/photos/'.$db_file->name;

				$db_file->save();
			} catch (Exception $e) {
			}
		}
		return response(["path" => $db_file->path, "photo_id" => $db_file->id, "space_id" => $db_file->space_id]);
	}
	public function changeSpace(Request $request){
		$photo = Photo::where([ 'id' => $request->photo_id ])->first();
		if ( !is_null($photo) ) {
			$photo->space_id = $request->space_id;
			$photo->save();
			return response(["path" => $photo->path, "photo_id" => $photo->id]);
		}
	}
	public function deletePhoto(Request $request){

        if(isset($request->photo_id)){
            $photo = Photo::find($request->photo_id);
        }
        if(isset($request->filename)){
            $photo = Photo::where('original_name', $request->filename)->first();
        }
		
		if ( !is_null($photo) ) {
			$photo->delete();
			return response( [ "operation" => true ] );
		}
		return response( [ "operation" => false ] );
    }
    public function getOwnerContracts($id)
    {
        if(!$userLogueado = \Auth::user()) return abort(401, 'No estas logueado');

        if($user = User::findOrFail($id)){
            if( $user->id == $userLogueado->id){
                $data = array();
                $contratos = $user->contracts()->whereNotNull('signature_request_id_hs')->get();
                foreach( $contratos as $contrato ){
                    $obj['data'] = $contrato;
                    $property = $contrato->property()->first();
                    $tenant = $contrato->getTenantFromContract();
                    $postulacion_id = DB::table('users_has_properties')->select('id')->where('property_id', $property->id)->where('user_id', $tenant->id)->first()->id;
                    $postulacion = ApplyProperty::find($postulacion_id);
                    $obj['postulacion'] = $postulacion;
                    $obj['property'] = $property;
                    
                    $tenant = $contrato->users()->wherePivot('signer_role', 'tenant')->first();
                    $obj['tenant_name'] = $tenant->firstname.' '.$tenant->lastname;
                    $obj['tenant_avatar'] = $tenant->photo ? $tenant->photo : '/images/husky.png';
                    switch( $tenant->pivot->status_code ){
                        case 'awaiting_signature':
                            $obj['tenant_sign_status'] = 1;
                            break;
                        case 'signed':
                            $obj['tenant_sign_status'] = 2;
                            break;
                        case 'declined':
                            $obj['tenant_sign_status'] = 0;
                            break;
                    }
                    $owner = $contrato->users()->wherePivot('signer_role', 'owner')->first();
                    $obj['owner_name'] = $owner->firstname.' '.$owner->lastname;
                    $obj['owner_avatar'] = $owner->photo ? $owner->photo : '/images/husky.png';
                    switch( $owner->pivot->status_code ){
                        case 'awaiting_signature':
                            $obj['owner_sign_status'] = 1;
                            break;
                        case 'signed':
                            $obj['owner_sign_status'] = 2;
                            break;
                        case 'declined':
                            $obj['owner_sign_status'] = 0;
                            break;
                    }
                    $collateral = $contrato->users()->wherePivot('signer_role', 'collateral')->first();
                    $obj['collateral_name'] = $collateral ? $collateral->firstname.' '.$collateral->lastname : null ;
                    $obj['collateral_avatar'] = $collateral ? ($collateral->photo ? $collateral->photo : '/images/husky.png' ) : null ;
                    if($collateral){
                        switch( $collateral->pivot->status_code ){
                            case 'awaiting_signature':
                                $obj['collateral_sign_status'] = 1;
                                break;
                            case 'signed':
                                $obj['collateral_sign_status'] = 2;
                                break;
                            case 'declined':
                                $obj['collateral_sign_status'] = 0;
                                break;
                        }
                    }
                    $data[] = $obj;
                }
                return response()->json($data);

            } else {
                abort(401, 'No autorizado a acceder a contratos del usuario');
            }
        }else{
            abort(401, 'Usuario no existe');
        }

    }
    private function getSchedules(){

        if($user = Auth::user()){

            $my_properties = UserProperty::where('user_id', $user->id)->where('type', 1)->get();
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
