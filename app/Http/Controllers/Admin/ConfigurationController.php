<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Configuration};
use Carbon\Carbon;

class ConfigurationController extends Controller
{
    public function getConfigurarions(){
        return Configuration::all();
    }

    public function updateConfigurations(Request $request){
        $configurations = $request->configurations;
        foreach ($configurations as $value) {
            $configuration = Configuration::find($value['id']);
            $configuration->enabled = $value['enabled'];
            $configuration->save();
        }
        return response(['success' => true], 200);
    }
}