<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Space;

class SpaceController extends Controller
{
    //
    public function getSpaces(){
        return response([ 'spaces' => Space::orderBy('name', 'asc')->get() ]);
    }
}
