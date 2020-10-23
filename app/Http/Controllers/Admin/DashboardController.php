<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{ User, Property, Newsletter };
use Carbon\Carbon;
use App\DbViews\DescriptorNewsletter;
use App\DbViews\LeasedProperty;

class DashboardController extends Controller
{
    //
    public function getDescriptors(){
      //$current_date = Carbon::now()->addDays(1)->format('Y-m-d');
      //$a_week_ago = new Carbon('7 days ago');
      //$a_week_ago = $a_week_ago->format('Y-m-d');
      $newsletters = DescriptorNewsletter::query()->get();

      $users = User::selectRaw('DAY(users.created_at) as day, COUNT(users.id) as quantity')
                    ->groupBy('users.created_at')
                    ->orderBy('users.created_at', 'DESC')
                    ->limit(7)
                    ->get();

      $lastUsers = User::select('id', 'email', 'firstname', 'lastname')
                      ->where(['active' => true])
                      ->orderBy('id', 'DESC')
                      ->limit(5)
                      ->get();

      $properties = Property::selectRaw('DAY(properties.created_at) as day, COUNT(properties.id) as quantity')
                    //->whereBetween('properties.created_at',[$a_week_ago, $current_date])
                    ->groupBy('properties.created_at')
                    ->orderBy('properties.created_at', 'DESC')
                    ->limit(7)
                    ->get();
      $totalProperties = Property::where(['is_project' => false])->count();
      $lastProperties_db = Property::orderBy('properties.id', 'DESC')->limit(5)->get(); 
      //leftJoin('cities', 'cities.id', '=', 'properties.city_id')
      $lastProperties = [];
      foreach ($lastProperties_db as $p) {
        switch ($p->status) {
          case 0:
            $status = 'Publicada';
            break;
          case 1:
            $status = 'Pausada';
            break;
          case 2:
            $status = 'Arrendada';
            break;
          case 3:
            $status = 'Cancelada';
            break;
          default:
            $status = $p->status;
            break;
        }

        $lastProperties[] = (object)[
          'id' => $p->id,
          'name' => $p->name,
          'slug' => $p->slug,
          'status' => $status
        ];
      }

      //$leased_properties = LeasedProperty::query()->whereBetween('date',[$a_week_ago, $current_date])->get();
      
      $totalUsuarios = User::where(['active' => true])->count();

      return response([
        //"leased_properties" => $leased_properties,
        "newsletters" => $newsletters,
        "properties" => $properties,
        "totalProperties" => $totalProperties,
        "totalUsuarios" => $totalUsuarios,
        'last_properties' => $lastProperties,
        'last_users' => $lastUsers,
        'users' =>$users
      ]);
  	}
}
