<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{CivilStatus};
class CivilStatusController extends Controller
{
    //
    public function getCivilStatus(){
  		$civil_status = CivilStatus::all();
      return response([ "records" => $civil_status ]);
  	}
}
