<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Contract, Property};

class ContractController extends Controller
{
	public function getContracts(){
    $db_contracts = Contract::all();

    $contracts = [];

    foreach ($db_contracts as $c) {

    	$property = $c->getPropertyFromContract();

    	$status = 'Generado';
    	if(isset($c->path_file)) {
    		$status = 'Pago, en proceso de firma';
    	}
    	if($c->is_declined) {
    		$status = 'Rechazado';
    	}
    	if($c->is_complete) {
    		$status = 'Completo';
    	}

    	$contracts[] = (object) [
    		"id" => $c->id,
    		"status" => $status,
    		"is_complete" => $c->is_complete,
    		"property_link" => $property ? route('explore.view.property', ['id' => $property->id, 'name' => $property->name]) : null,
    		"path_file_pre" => $c->path_file_pre,
    		"path_file" => $c->path_file,
    		"owner" => $c->getOwnerFromContract() ? $c->getOwnerFromContract()->pivot : null,
    		"tenant" => $c->getTenantFromContract() ? $c->getTenantFromContract()->pivot : null,
    		"collateral" => $c->getCollateralFromContract() ? $c->getCollateralFromContract()->pivot : null
    	];
    }

    return response([ "records" => $contracts ]);
  }
}
