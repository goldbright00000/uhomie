<?php
/*  By: José Gutierrez
    PERFIL DEL AVAL
*/

namespace App\Http\Controllers;

use App\Notification;
use App\Property;
use Illuminate\Http\Request;
use App\User;
use App\Privacy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\CivilStatus;
use App\File;
use App\Membership;
use App\ApplyProperty;
use Illuminate\Support\Facades\DB;

class CollateralProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function collateralProfileIndexAction()
    {
        return view('profiles/collateral', [
            'user' => \Auth::user(),
            'role-id' => 5
        ]);
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
            $data['utype'] = 'Aval';
            $data['age'] = Carbon::parse($user->birthdate)->age;
            $data['avatar'] = $user->photo;
            $data['postulations'] = $user->properties()->where('type', 1)->count();
            $data['aplications'] = $user->properties()->where('type', 2)->count();
            $data['fav_holdings'] = $user->properties()->where('type', 3)->count();
            // $membership_data = $user->getOwnerMerbership();

            // $dateMembership = new \DateTime($membership_data->pivot->expires_at);
            // $membership_data->pivot->expires_at = $dateMembership->format('d/m/Y');

            // $data['membership_data'] = $membership_data;
            // if ($data['membership_data'] != null) {
            //     $data['membership_data'] = $data['membership_data']->toArray();
            // }
            $data['civilstatus'] = CivilStatus::find($user->civil_status_id)->name;


            $documents = [
                'id_front', 'id_back', 'work_constancy', 'first_settlement', 'second_settlement', 'third_settlement', 'afp', 'dicom', 'other_income'
            ];


            $data['role'] = 5;

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
            // $data['membership'] = strtolower($user->getOwnerMerbership()->name);
            $all_notifs = Notification::whereIn('id', [10, 2, 11, 4, 5, 6, 7, 8, 9, 12])->get();
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
            //$data['tenants'] = $user->creditor->count();
            $tenant_info = [];
            $tenants = $user->creditor()->get();
            foreach($tenants as $key => $tenant){
                $membership_tenant = User::find($tenant->id)->getInfoTenantMerbership();
                $dateMembership = Carbon::parse($membership_tenant->pivot->expires_at);
                $membership_tenant->pivot->expires_at = $dateMembership->format('d/m/Y');
                $tenant->age = Carbon::parse($tenant->birthdate)->age;
                $tenant['membership_data'] = $membership_tenant;
                if ($tenant['membership_data'] != null) {
                    $tenant['membership_data'] = $tenant['membership_data']->toArray();
                }
                $tenant['membership'] = $membership_tenant->name;

                $tenant_info = $tenant->toArray();
            }
            
            $data['tenants'][] = $tenant_info;

            // $properties = $this->getProperties();

            // foreach ($properties as $key => $prop) {
            //     $prop['applied_membership'] = $user->getTenantMerbership();
            //     $postulations = $prop->applications;

            //     $owner_mb = $prop->getOwner()->getOwnerMerbership();

            //     $prop['owner_membership'] = $owner_mb;

            //     foreach ($postulations as $key => $postulation) {

            //         switch ($postulation->state) {
            //             case 2:
            //                 $status = 'approved';
            //                 $text = 'APROBADO';
            //                 break;

            //             case 1:
            //                 $status = 'refused';
            //                 $text = 'RECHAZADO';
            //                 break;

            //             default:
            //                 $status = 'waiting_for_acceptance';
            //                 $text = 'EN REVISIÓN';
            //                 break;
            //         }

            //         $datePos = new \DateTime($postulation['created_at']);

            //         $postulation['status_int'] = $postulation->state;
            //         $postulation['status'] = $status;
            //         $postulation['text'] = $text;
            //         //$postulation['created_at'] = $datePos->format('d/m/Y');
            //         $postulation['owner'] = $prop->getOwner();

            //         $tenant = $postulation->postulant();

            //         $postulation['tenant'] = $tenant;
            //         $prop['scoring'] = $tenant->getScoring($prop->id);
            //         $postulation['slug'] = $tenant->slug;

            //         $postulation = $postulation->toArray();

            //         //$postulation['created_at'] = $datePos->format('d/m/Y');

            //     }

            //     $prop['applications'] = $postulations;

            //     $properties[$key] = $prop;
            // }

            // $data['properties'] = $properties;

            /*foreach ($properties as $key => $value) {
                $value->applications = $value->applications;
                array_push($data['properties'], $value);
            }*/
            /**
             * Obtener contratos en los que está involucrado el Aval
             */
            
            return response([
                'success' => true,
                'info' => $data,
            ]);

        } else {
            return response('No está autenticado.', 401);
        }
    }

    public function getCollateralContracts($id)
    {
        if(!$userLogueado = \Auth::user()) return abort(401, 'No estas logueado');

        if($user = User::findOrFail($id)){
            if( $user->id == $userLogueado->id){
                $data = array();
                $contratos = $user->contracts()->get();
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

        $all = \App\Space::all();
        $pspaces = [];
        foreach ($all as $ps) {
            $pspaces[] = [
                'id' => $ps->id,
                'text' => $ps->name
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


        return response([
            'success' => true,
            'filters' => $filters
        ]);

    }


    function getProperties()
    {

        $ret = [];
        $all = Auth::user()->properties()->where('type', 1)->get();
        foreach ($all as $prop) {


            $address = $prop->address;
            $pt = explode(",", $address);
            $city = '';
            if (count($pt) > 1) $city = array_pop($pt);
            $prop['address'] = implode(',', $pt);
            $prop['city'] = $city;

            $prop['amenities'] = $this->getOnlyId($prop->amenities()->get());
            $prop['properties_for'] = $this->getOnlyId($prop->propertiesFor()->get());


            $prop['image'] = '/images/viewhome.png';

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
                'birthdate', 'address', 'address_details', 'latitude', 'longitude', 'landline', 'phone', 'phone_code','mail_verified', 'phone_verified',
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

    function deleteProperty(Request $request, $id) {
        if ($user = Auth::user()) {
            $property = Property::find($id);

            if ($property->status === 2) {
                return response('La propiedad esta arrendada, no puede realizar esta acción', 401);
            }

            $property->detele();

            return response([
                'success' => true,
                'info' => $data,
            ]);
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
                'name', 'address', 'rent', 'description', 'metas', 'images', 'common_expenses', 'common_expenses_support',
                'invoices', 'property_certificate', 'condition', 'meters', 'rooms_count', 'bathrooms_count', 'latitude', 'longitude',
                'expenses_limit', 'common_expenses', 'warranty_months_quantity', 'months_advance_quantity', 'available_date', 'tenanting_months_quantity', 'collateral_require', 'furnished', 'furnished_description', 'schedule_range', 'visit', 'visit_from_date', '
		        visit_to_date', 'bedrooms', 'bathrooms', 'pool', 'garden', 'terrace', 'private_parking', 'public_parking', 'pet_preference', 'smoking_allowed', 'nationals_with_rut', 'foreigners_with_rut', 'foreigners_with_passport', 'tenanting_insurance',

                'property_type_id',
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

}
