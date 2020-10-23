<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use App\Property;
use App\User;
use App\ApplyProperty;

class ContractController extends Controller
{
    public function generateContract(){
        $contrato = new Contract();
        //return $contrato->toPdfWithoutAval(User::find(13), User::find(15), Property::find(1));
        //return $contrato->generarContratoConAval();
    }
    public function streamBorradorContrato($owner_id, $tenant_id, $property_id){
        $owner = User::findOrFail($owner_id);
        $tenant = User::findOrFail($tenant_id);
        $property = Property::findOrFail($property_id);
        return Contract::streamBorrador($owner, $tenant, $property);
    }
    public function streamContract($contract_id)
    {
        if($user = \Auth::user()){ // añadir logica para que se pueda accesar solo el usuario postulante aceptado, propietario o aval.
            $contrato = Contract::findOrFail($contract_id);
            if( $contrato->users()->where('user_id', $user->id)->count() ){
                return $contrato->generarContratoStream();
            }else{
                return abort(403, 'No estas autorizado');
            } 
            
        }
        else {
            return abort(403, 'No estas autorizado');
        }
    }
    /*
    public function getContractSigningStatus($contract_id){
        $contrato = Contract::findOrFail($contract_id);
        if($user = \Auth::user()){
            if( $user->contracts()->where('contract_id', $contract_id)->count() ){
                foreach( $contrato->users as $firmante ){
                    
                    $objeto[]
                }
            } else {
                abort(403, 'No coincide contrato con usuario');
            }
        } else {
            abort(403, 'No estas autorizado');
        }
    }
    */
    public function gettingHSResults(Request $request)
    {
        
        if( $r = json_decode( $request->json ) ){
            
            switch( $r->event->event_type ){
                /**
                 *  Considerando Casos segun la documentacion de HelloSign
                 *  https://app.hellosign.com/api/eventsAndCallbacksWalkthrough
                 */
                
                case 'signature_request_signed':
                    if( $id = $r->signature_request->signature_request_id ){
                        if ( $contrato = Contract::where('signature_request_id_hs', $id)->first() ){
                            /**
                             * Actualizar los pivotes de contratos <--> usuarios
                             */
                            $email_tenant = $contrato->users()->wherePivot('signer_role', 'tenant')->first()->email;
                            $email_owner = $contrato->users()->wherePivot('signer_role', 'owner')->first()->email;
                            $email_collateral = $contrato->users()->wherePivot('signer_role', 'collateral')->first() ? $contrato->users()->wherePivot('signer_role', 'collateral')->first()->email : 'null';
                            foreach( $r->signature_request->signatures as $s ){
                                switch( $s->signer_email_address ){
                                    case $email_tenant:
                                        $user_id = $contrato->users()->wherePivot('signer_role', 'tenant')->first()->id;
                                        $contrato->users()->updateExistingPivot($user_id, ['status_code' => $s->status_code]);
                                        $contrato->save();
                                        break;
                                    case $email_owner:
                                        $user_id = $contrato->users()->wherePivot('signer_role', 'owner')->first()->id;
                                        $contrato->users()->updateExistingPivot($user_id, ['status_code' => $s->status_code]);
                                        $contrato->save();
                                        break;
                                    case $email_collateral:
                                        $user_id = $contrato->users()->wherePivot('signer_role', 'collateral')->first()->id;
                                        $contrato->users()->updateExistingPivot($user_id, ['status_code' => $s->status_code]);
                                        $contrato->save();
                                        break;
                                }
                            }
                            $contrato->save();
                        }
                    }
                    break;
                case 'signature_request_declined':
                    if( $id = $r->signature_request->signature_request_id ){
                        if ( $contrato = Contract::where('signature_request_id_hs', $id)->first() ){
                            $contrato->is_declined = true;
                            $contrato->save();
                            $email_tenant = $contrato->users()->wherePivot('signer_role', 'tenant')->first()->email;
                            $email_owner = $contrato->users()->wherePivot('signer_role', 'owner')->first()->email;
                            $email_collateral = $contrato->users()->wherePivot('signer_role', 'collateral')->first() ? $contrato->users()->wherePivot('signer_role', 'collateral')->first()->email : 'null';
                            foreach( $r->signature_request->signatures as $s ){
                                switch( $s->signer_email_address ){
                                    case $email_tenant:
                                        $user_id = $contrato->users()->wherePivot('signer_role', 'tenant')->first()->id;
                                        $contrato->users()->updateExistingPivot($user_id, ['status_code' => $s->status_code]);
                                        $contrato->save();
                                        break;
                                    case $email_owner:
                                        $user_id = $contrato->users()->wherePivot('signer_role', 'owner')->first()->id;
                                        $contrato->users()->updateExistingPivot($user_id, ['status_code' => $s->status_code]);
                                        $contrato->save();
                                        break;
                                    case $email_collateral:
                                        $user_id = $contrato->users()->wherePivot('signer_role', 'collateral')->first()->id;
                                        $contrato->users()->updateExistingPivot($user_id, ['status_code' => $s->status_code]);
                                        $contrato->save();
                                        break;
                                }
                            }
                            $tenant = $contrato->users()->wherePivot('signer_role', 'tenant')->first();
                            $property = $contrato->property()->first();
                            $postulacion_id = $tenant->properties()
                            ->where('type',
                                Property::TYPE_APPLICATION)->where('espera', 0)->where('property_id', $property->id)
                            ->orderBy('id', 'ASC')->join('postulates', "users_has_properties.id", "=", "postulates.id");
                            $p = ApplyProperty::find($postulacion_id);
                            $p->state = ApplyProperty::REFUSED_STATE; // POniendo la postulacion en estado CANCELADO (REFUSED_STATE)
                            $p->save();
                        }
                    }
                    break;
                case 'signature_request_all_signed':
                    if( $id = $r->signature_request->signature_request_id ){
                        if ( $contrato = Contract::where('signature_request_id_hs', $id)->first() ){
                            $contrato->is_complete = true;
                            
                            /**
                             * Actualizar los pivotes de contratos <--> usuarios
                             */
                            $email_tenant = $contrato->users()->wherePivot('signer_role', 'tenant')->first()->email;
                            $email_owner = $contrato->users()->wherePivot('signer_role', 'owner')->first()->email;
                            $email_collateral = $contrato->users()->wherePivot('signer_role', 'collateral')->first() ? $contrato->users()->wherePivot('signer_role', 'collateral')->first()->email : 'null';
                            foreach( $r->signature_request->signatures as $s ){
                                switch( $s->signer_email_address ){
                                    case $email_tenant:
                                        $user_id = $contrato->users()->wherePivot('signer_role', 'tenant')->first()->id;
                                        $contrato->users()->updateExistingPivot($user_id, ['status_code' => $s->status_code]);
                                        $contrato->save();
                                        break;
                                    case $email_owner:
                                        $user_id = $contrato->users()->wherePivot('signer_role', 'owner')->first()->id;
                                        $contrato->users()->updateExistingPivot($user_id, ['status_code' => $s->status_code]);
                                        $contrato->save();
                                        break;
                                    case $email_collateral:
                                        $user_id = $contrato->users()->wherePivot('signer_role', 'collateral')->first()->id;
                                        $contrato->users()->updateExistingPivot($user_id, ['status_code' => $s->status_code]);
                                        $contrato->save();
                                        break;
                                }
                            }
                            $contrato->save();
                            /**
                             * Actualizando estado de la postulacion a Firmado (Flujo concluye)
                             */
                            $tenant = $contrato->users()->wherePivot('signer_role', 'tenant')->first();
                            $property = $contrato->property()->first();
                            $postulacion_id = $tenant->properties()
                            ->where('type',
                                Property::TYPE_APPLICATION)->where('espera', 0)->where('property_id', $property->id)
                            ->orderBy('id', 'ASC')->join('postulates', "users_has_properties.id", "=", "postulates.id");
                            $p = ApplyProperty::find($postulacion_id);
                            $p->state = ApplyProperty::SIGNED_STATE;
                            $p->save();

                            $property_expire = Property::find($property->id);
                            $property_expire->expire_at = Carbon::now()->addDays(30)->format('Y-m-d');
                            $property_expire->available = false;
                            $property_expire->status = 2;
                            $property_expire->save();
                        }
                    }
                    break;
            }
        }

        return response('Hello API Event Received');
    }
}
