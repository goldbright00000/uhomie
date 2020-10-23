<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use App\Http\Requests\StoreSchedule;
use App\{Schedule, UserProperty, User, Property,Photo};
use App\Notifications\Schedule as ScheduleNotification;
use App\Notifications\ScheduleState as ScheduleStateNotification;
use Carbon\Carbon;
use App\Notifications\TwilioPush;

class ScheduleController extends Controller {
    
    public function store(StoreSchedule $request) {
        $schedule = new Schedule($request->all());
        $schedule->user_id = \Auth::user()->id;

        $tenant = \Auth::user();

        $owner_id = \DB::table('users_has_properties')->select('user_id')->where('property_id', $request->property_id)->where('type', $request->type)->first();

        $owner = User::find($owner_id->user_id);

        $property = Property::find($request->property_id);

        if($schedule->save()) {

            $owner->notify(new TwilioPush("El arrendatario " . $tenant->firstname . " " . $tenant->lastname . " agendo una cita a tu propiedad ID:".  $property->id . " " . $property->id . " para la fecha:". Carbon::parse($schedule->schedule_date)->format('d/m/Y') ." ingresa a " .url('login'). " para mas informacion."));

            \Notification::route('mail', $owner->email)
                ->notify(new ScheduleNotification($schedule->id, 0));

            \Notification::route('mail', $tenant->email)
                ->notify(new ScheduleNotification($schedule->id, 1));


            return "Se ha registrado en la agenda correctamente.";


            
            // Send notifications
           
            
        }
        
        
    }

    public function update(Request $request){
        $schedule = Schedule::find($request->id);
        $schedule->schedule_state = $request->schedule_state;

        $property = Property::where('id',$schedule->property_id)->first();

        $user_visitor = UserProperty::where('id',$schedule->id)->first();

        $user_owner = UserProperty::where('property_id', $schedule->property_id)->where('type','1')->first();

        $user_visitor = User::find($user_visitor->user_id);

        $user_owner = User::find($user_owner->user_id);

        $id_property = $property->id;

        $visitor = $user_visitor->id;
        $owner = $user_owner->id;
        

        if($schedule->save()){
            if($schedule->schedule_state == 2){
                
                \Notification::route('mail', $user_visitor->email)
                ->notify(new ScheduleStateNotification($visitor, $owner, $id_property, $schedule->schedule_date, $schedule->schedule_range, 1));
                return response([
                    'message' => 'Se ha aprobado la visita',
                    'info' => $this->getSchedulesInfo()
                ]);
            }
            if($schedule->schedule_state == 1){
                \Notification::route('mail', $user_visitor->email)
                ->notify(new ScheduleStateNotification($visitor, $owner, $id_property, $schedule->schedule_date, $schedule->schedule_range, 2));
                return response([
                    'message' => 'Se ha rechazado la visita',
                    'info' => $this->getSchedulesInfo()
                ]);
            }
        } else {
            return response('A sucedido una complicación', 500);
        }

    }
    public function saveSchedule(Request $request){
        if ($user = \Auth::user()){

            $user_owner = UserProperty::where('property_id', $request->id)->where('user_id', $user->id)->where('type','1')->first();
            //return $user_owner->id;
            
            //Mañana de 9-12
            $mornings = explode(",", $request->morning);
            $mornings_dates = [];
            for ($i = 0; $i < count($mornings); $i++ ) {
                array_push($mornings_dates, $mornings[$i]);
            }
            //Mediodia 12-3
            $noons = explode(",", $request->noon);
            $noons_dates = [];
            for ($i = 0; $i < count($noons); $i++ ) {
                array_push($noons_dates, $noons[$i]);
            }
            //Tarde 3-7
            $afternoons = explode(",", $request->afternoon);
            $afternoons_dates = [];
            for ($i = 0; $i < count($afternoons); $i++ ) {
                array_push($afternoons_dates, $afternoons[$i]);
            }

            $from_date = array(
                'morning' => $mornings_dates,
                'noon' => $noons_dates,
                'afternoon' => $afternoons_dates
            );

            $schedule = json_encode($from_date);

            $property = Property::find($user_owner->property_id);
            $property->schedule_dates = $schedule;
            if($property->save()){
                return response($schedule,200);
            } else {
                return response('Ha ocurrido una falla', 500);
            }
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }
    }
    public function getSchedule(Request $request){
        if ($user = \Auth::user()){
            $user_owner = UserProperty::where('property_id', $request->id)->where('user_id', $user->id)->first();
            $property = Property::find($user_owner->property_id);
            if($property){
                return response(['schedule_dates' => $property->schedule_dates, 'visit' => $property->visit], 200);
            } else {
                return response('Ha ocurrido un error', 500);
            }
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }
    }

    private function getSchedulesInfo(){

        if($user = \Auth::user()){

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
        
                    if($property->is_project == 1){
                        $schedule['url'] = url('agentes/'.$property->id);
                        $schedule['schedule_title'] = 'Cita '.$schedule_state. ' para la ' . $schedule_range . ' en el proyecto ' . $property->name . '.';
                    } else {
                        $schedule['url'] = url('explorar/'.$property->id.'/'.$property->slug);
                        $schedule['schedule_title'] = 'Cita '.$schedule_state. ' para la ' . $schedule_range . ' en la propiedad ' . $property->name . '.';
                    }


                    if($property != null){


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
    public function getScheduleShortStay(Request $request){
        if ($user = \Auth::user()){
            $user_owner = UserProperty::where('property_id', $request->id)->where('user_id', $user->id)->first();
            $property = Property::find($user_owner->property_id);
            if($property){
                return response(['schedule_dates' => $property->schedule_dates, 'visit' => $property->visit], 200);
            } else {
                return response('Ha ocurrido un error', 500);
            }
        } else {
            return response('No se encuentra autenticado para realizar esta acción', 401);
        }
    }
}