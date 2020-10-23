<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Role};
class RoleController extends Controller
{
    //
    public function getRoles(Request $request){
      $roles = Role::all();
      return response([ "records" => $roles ]);
  	}
}
