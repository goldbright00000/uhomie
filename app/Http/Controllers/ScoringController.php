<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Property, User};
class ScoringController extends Controller
{
    public function test(){
		$properties = Property::all();
		$users = User::where('id', '<>', 1)->get();
		return view('scoring.test')->with([
			'properties' => $properties,
			'users' => $users
		]);
	}
}
