<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Country};
class CountryController extends Controller
{
    //
    public function getCountries(){
  		$countries = Country::all();
      return response([ "records" => $countries ]);
  	}
}
