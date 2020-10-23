<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Country,City};
class CityController extends Controller
{
    //
    public function getCities(){
  		$cities = Country::find(39)->cities;
      return response([ "records" => $cities ]);
  	}
}
