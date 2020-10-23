<?php

namespace App\Http\Controllers;

use \Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Notification;

class ReferallsController extends Controller
{

	public function landing(Request $request) {

		return view('pages.referalls.landing');
	}

	public function register(Request $request) {

		return view('pages.referalls.register');
	}
}
