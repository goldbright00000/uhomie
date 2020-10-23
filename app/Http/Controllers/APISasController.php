<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sasapplicant;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as RequestGuzzle;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use PHPUnit\Framework\MockObject\Stub\Exception;
use App\Notifications\TwilioPush;
use Google\Cloud\Translate\TranslateClient;
use App\{Property, UserProperty, ApplyProperty};
use App\Notifications\{SasNotification, ApplyPropertyTenant, ApplyPropertyOwner};
use Illuminate\Support\Facades\DB;

class APISasController extends Controller
{
    
    /**
     * ENDPOINT Que recibe datos de Sum&Substance
     */
    /*
    public function gettingVerificationResults(Request $request)
    {
        if($request->isJson()){
            $query = Sasapplicant::where('applicant_id', $request->input('applicantId') )->orderBy('created_at','desc')->get();
            if(!$query->count()) return abort(403, 'Applicant ID not found');
            $s = $query->first();
            //Campos en comun, ya sea respuesta 'GREEN' o 'RED'
            $s->applicant_id = $request->input('applicantId');
            $s->inspection_id = $request->input('inspectionId');
            $s->correlation_id = $request->input('correlationId');
            $s->type = $request->input('type');
            $s->review_status = $request->input('reviewStatus');
            $s->review_answer = $request->input('reviewResult.reviewAnswer');
            
            // Campos en comun, ya sea respuesta 'GREEN' o 'RED' 
            switch ( $s->review_answer ){
                case 'RED':
                    $s->review_moderation_comment = $request->input('reviewResult.moderationComment');
                    $s->review_client_comment = $request->input('reviewResult.clientComment'); 
                    $s->review_reject_type = $request->input('reviewResult.reviewRejectType');
                    $user = $s->user()->first();
                    $user->notify(new TwilioPush("Te informamos que la verificacion de identidad en uHomie ha fracasado. Te invitamos a seguir navegando en ".url('')));
                    
                    break;
                case 'GREEN':
                    
                    $carnet_frontal = $s->user()->get()->first()->files()->where('name','id_front')->get()->first();
                    $carnet_frontal->verified = 1;
                    $carnet_frontal->save();
                    $carnet_trasero = $s->user()->get()->first()->files()->where('name','id_back')->get()->first();
                    $carnet_trasero->verified = 1;
                    $carnet_trasero->save();
                    
                    
                    //Colocando las postulaciones en espera activas, por orden fecha creacion ascendente
                    
                    $user = $s->user()->first();
                    if ( $user->getTenantMerbershipOnce() ){
                        $postulaciones_restantes = $user->applications_left;
                        foreach( $user->getAppliedPropertiesEspera() as $postulacion ){
                            if($postulaciones_restantes > 0){
                                $postulacion->espera = 0;
                                $postulacion->save();
                                $postulaciones_restantes--;
                            }
                        }
                    }
                    if ($user->getOwnerMerbershipOnce() ){
                        

                        $properties_user = UserProperty::where('user_id', $user->id)->get();
                        foreach ($properties_user as  $property) {
                            $prop = Property::find($property->property_id);
                            $prop->active = true;
                            $prop->save();
                        }
                    }
                    $user->notify(new TwilioPush("Te informamos que la verificacion de identidad en uHomie ha resultado positiva. Te invitamos a seguir navegando en ".url('')));
                    break;
            }
            $s->save();
            return response()->json(['status' => 'OK']);
        } else {
            return abort(403, 'Contenido incorrecto');
        }
        
    }
    */
    /**
     * ENDPOINT 2 Que recibe datos de Sum&Substance
     */
    public function endpointSas(Request $request)
    {
        try{
            $requestDecode = json_decode($request->getContent());
            if(!$requestDecode) return response()->json([
                                                    'status' => 'error',
                                                    'description' => 'Contenido Incorrecto',
                                                    'code' => '450'
                                                ]);
            $query = Sasapplicant::where('applicant_id', $requestDecode->applicantId )->orderBy('created_at','desc')->get();
            if(!$query->count()) return response()->json([
                                        'status' => 'error',
                                        'description' => 'applicant not found',
                                        'code' => '440'
                                    ]);
            $s = $query->first();
            $s->applicant_id = $requestDecode->applicantId;
            $s->inspection_id = $requestDecode->inspectionId;
            $s->correlation_id = $requestDecode->correlationId;
            $s->type = $requestDecode->type;
            $s->review_status = $requestDecode->reviewStatus;
            $s->review_answer = $requestDecode->reviewResult->reviewAnswer;
            switch ( $s->review_answer ){
                case 'RED':
                    $user = $s->user()->first();
                    $carnet_frontal = $user->files()->where('name','id_front')->get()->first();
                    $carnet_trasero = $user->files()->where('name','id_back')->get()->first();
                    $carnet_frontal->verified = 0;
                    $carnet_trasero->verified = 0;
                    $carnet_frontal->save();
                    $carnet_trasero->save();

                    $s->review_moderation_comment = isset($requestDecode->reviewResult->moderationComment)? $requestDecode->reviewResult->moderationComment : null;
                    $s->review_client_comment = isset($requestDecode->reviewResult->clientComment)? $requestDecode->reviewResult->clientComment : null; 
                    $s->review_reject_type = isset($requestDecode->reviewResult->reviewRejectType)? $requestDecode->reviewResult->reviewRejectType : null;
                    
                    if( $s->review_moderation_comment && $s->review_moderation_comment != ''){
                        try{
                            # Your Google Cloud Platform project ID
                            $projectId = env('GOOGLE_PROJECT_ID', 'verdant-doodad-245719');
                            # Instantiates a client
                            $translate = new TranslateClient([
                                'projectId' => $projectId,
                                'keyFilePath' => env('GOOGLE_KEY_FILE_PATH', 'C:\Users\alexa\Desktop\My First Project-7010ed79cd4b.json')
                            ]);
                            # The text to translate
                            $text = $s->review_moderation_comment;
                            # The target language
                            $target = 'es';
                            # Translates some text into Russian
                            $translation = $translate->translate($text, [
                                'target' => $target
                            ]);
                            $user->notify(new TwilioPush("Te informamos que la verificacion de identidad en uHomie ha fracasado. Razon: ".$translation['text'].". Te invitamos a seguir navegando en: ".url('')));    
                            $user->notify(new SasNotification($translation['text'], false));
                        } catch(\Exception $e){
                            $s->save();
                            $user->notify(new TwilioPush("Te informamos que la verificacion de identidad en uHomie ha fracasado. Te invitamos a seguir navegando en: ".url('')));
                            $user->notify(new SasNotification(null, false));
                            return response()->json(['alert' => 'se cayo google']);
                        }
                        
                        
                    }else{
                        $user->notify(new TwilioPush("Te informamos que la verificacion de identidad en uHomie ha fracasado. Te invitamos a seguir navegando en: ".url(''))); 
                        $user->notify(new SasNotification(null, false));
                    }
                        
                    break;
                case 'GREEN':
                    $carnet_frontal = $s->user()->first()->files()->where('name','id_front')->first();
                    $carnet_frontal->verified = 1;
                    $carnet_frontal->save();
                    $carnet_trasero = $s->user()->first()->files()->where('name','id_back')->first();
                    // Si tiene el carnet trasero no vigente ahora se le permitirá postular / verificarse
                    $carnet_trasero->verified =  1;
                    $carnet_trasero->save();
                    $user = $s->user()->first();
                    if ( $user->getTenantMerbershipOnce() ){
                        $postulaciones_restantes = $user->applications_left;
                        //dd( $user->getAppliedPropertiesEspera() );
                        $postulaciones = $user->getAppliedPropertiesEspera();
                        //dd($postulaciones);
                        foreach( $postulaciones as $postulacion ){
                            //dd($postulaciones_restantes);
                            if($postulaciones_restantes > 0){
                                $p = ApplyProperty::find($postulacion->id);
                                $p->espera = 0;
                                $p->save();
                                $postulaciones_restantes--;
                                
                                $property = $p->property()->first();
                                $owner_property = DB::table('users')->select('users.email','users.id','users.firstname','users.lastname')
                                ->join('users_has_properties', 'users.id', '=', 'users_has_properties.user_id')
                                ->where('users_has_properties.property_id', $property->id)->first();
                                \Notification::route('mail', $user->email)
                                //->notify(new ApplyPropertyNotification($application));
                                ->notify(new ApplyPropertyTenant($user->firstname,$user->lastname,$postulacion->created_at,$property->id, $property->name));
                                
                                \Notification::route('mail', $owner_property->email)
                                ->notify(new ApplyPropertyOwner($property->id,$property->name,$owner_property->firstname,$owner_property->lastname));
                            }
                        }
                    }
                    if ($user->getOwnerMerbershipOnce() ){
                        /**Edgardo */

                        $properties_user = UserProperty::where('user_id', $user->id)->get();
                        foreach ($properties_user as  $property) {
                            $prop = Property::find($property->property_id);
                            $prop->active = true;
                            $prop->save();
                        }
                    }
                    $user->notify(new TwilioPush("Te informamos que la verificacion de identidad en uHomie ha resultado positiva. Te invitamos a seguir navegando en ".url('')));
                    $user->notify(new SasNotification(null, true));
                    break;
            }
            $s->save();
            return response()->json([
                'status' => 'ok',
                'description' => '',
                'code' => '200'
            ]);
        } catch (\Exception $e){
            //dd($e);
            return response()->json([
                'status' => 'error',
                'description' => 'Ha currido un error'.$e,
                'code' => '401'
            ]);
        }
        
    }
    /*
    public function sendApplicant()
    {
        $client = new Client();
        $headers = ['Content-Type' => 'application/json', 
                    'Authorization' => 'Basic '.env('HS_LOGIN', 'dWhvbWllX2pvc2VfY29saW5hX3Rlc3Q6ZWdxQnVKSmdhY1Y0Y0E')]; // Aqui van los datos de logueo (user y pass) encriptados
        $request = new RequestGuzzle('POST', env('HS_URL', 'https://test-api.sumsub.com').'/resources/auth/login', $headers);
        // Request para Obtener el token de logueo
        try {
            $response = $client->send($request);
            //sleep(3);
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        }
        //Token de logueo
        
        $tokenSas = json_decode($response->getBody()->getContents())->payload;
       
        $headers = ['Content-Type' => 'application/json', 
                    'Authorization' => 'Bearer '.$tokenSas];
        // TO-DO: completar  con datos del usuario
        $body = [
            'externalUserId' => '15469047-6',
            'email' => 'gonzalo.valenzuela@uhomie.cl',
            'info' => [ 'country' => 'CHL', 
                        'firstName' => 'Gonzalo',
                        'lastName' => 'Valenzuela',
                        'phone' => '+56981378726',
                        'dob' => '1983-05-03',
                        'placeOfBirth' => 'Santiago'
            ],
            'requiredIdDocs' => [
                        'country' => 'CHL',
                        'docSets' => [
                            [   'idDocSetType' => 'IDENTITY',
                                'types' => ["ID_CARD"],
                                'subTypes' => [ "FRONT_SIDE", "BACK_SIDE" ]
                            ],
                            [   'idDocSetType' => 'SELFIE',
                                'types' => ["SELFIE"],
                                'subTypes' => null
                            ]
                        ]
            ],
        ];
        
        $tokenPropio = Str::random(32);
        
        //$aplicante->token = $tokenPropio;
        
        $request = new RequestGuzzle('POST', env('HS_URL', 'https://test-api.sumsub.com').'/resources/applicants', $headers, json_encode($body)); 
        try {
            $response = $client->send($request);
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        }
        $respuestaConId = json_decode($response->getBody()->getContents());
        
        $id = $respuestaConId->id;
        
        // TO-DO: Almacenar id en BD
        $nuevaUrl = env('HS_URL', 'https://test-api.sumsub.com').'/resources/applicants/'.$id.'/info/idDoc';
        //$nuevaUrl2 = 'https://hookb.in/aBZxaWpy6nUlwlbxENEg';
        $headers2 = ['Content-Type' => 'multipart/form-data', 'Authorization' => 'Bearer '.$tokenSas] ;
        
        // initialise the curl request
        $request = curl_init($nuevaUrl);

        // send a file
        curl_setopt($request, CURLOPT_POST, true);
        $headers = [
            //'Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW',
            'Authorization: '.'Bearer '.$tokenSas
        ];
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);

        curl_setopt(
            $request,
            CURLOPT_POSTFIELDS,
            array(
            //'content' => '@' . realpath('1_1.jpg'),
            'content' => curl_file_create('1_1.jpg','image/jpeg','content'),
            'metadata' => '{"idDocType": "ID_CARD",  "country": "CHL"}'
            ));

        // output the response
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        echo curl_exec($request);

        // close the session
        curl_close($request);
        dd('Si muestra este mensaje, estamos OK');
    }
    */
    public function identityCheck(Request $request)
    {
        if( !($user = \Auth::user()) ) return abort(403, 'No esta logueado');
        $intentos_restantes = $user->verifyng_attempts;
        if( $user->isVerified() ){
                $objeto['paso'] = 0;
                $objeto['state'] = 3;
                $objeto['mensaje'] = 'Tu validación ha sido un exito, ahora puedes continuar postulando con tranquilidad. En uHomie te hacemos la vida mas fácil.';
                return response()->json($objeto);
            }
        $query = $user->sasapplicant()->get();
        if($query->count()){

            $aplicante = $user->sasapplicant()->orderBy('created_at','desc')->get()->first();
            $client = new Client();
            $headers = ['Content-Type' => 'application/json', 
                        'Authorization' => 'Basic '.env('HS_LOGIN', 'dWhvbWllX2pvc2VfY29saW5hX3Rlc3Q6ZWdxQnVKSmdhY1Y0Y0E')]; // Aqui van los datos de logueo (user y pass) encriptados
            $request = new RequestGuzzle('POST', env('HS_URL', 'https://test-api.sumsub.com').'/resources/auth/login', $headers);
            
            // Request para Obtener el token de logueo
            try {
                $response = $client->send($request);
            } catch (RequestException $e) {
                echo Psr7\str($e->getRequest());
                if ($e->hasResponse()) {
                    echo Psr7\str($e->getResponse());
                }
            }
            
            //Token de logueo
            $tokenSas = json_decode($response->getBody()->getContents())->payload;
            $headers = ['Content-Type' => 'application/json', 
                    'Authorization' => 'Bearer '.$tokenSas];
            //dd($aplicante->applicant_id);
            $request = new RequestGuzzle('GET', env('HS_URL', 'https://test-api.sumsub.com').'/resources/applicants/'.$aplicante->applicant_id.'/state', $headers);
            try {
                $response = $client->send($request);
            } catch (RequestException $e) {
                echo Psr7\str($e->getRequest());
                if ($e->hasResponse()) {
                    echo Psr7\str($e->getResponse());
                }
            }
            $resultado = json_decode($response->getBody()->getContents());
            
            $estado = $resultado->status->reviewStatus;
            if($estado == 'completed'){
                $aplicante->review_answer = $resultado->status->reviewResult->reviewAnswer;
                if( $resultado->status->reviewResult->reviewAnswer == "GREEN" ){
                    $file = $user->files()->where('name','id_front')->get()->first();
                    $file->verified = 1;
                    $file->save();
                    $file2 = $user->files()->where('name','id_back')->get()->first();
                    $file2->verified = $file2->verified_ocr ? 1 : 0;
                    $file2->save();
                    $objeto['paso'] = 0;
                    $objeto['state'] = 3;
                    $objeto['mensaje'] = 'Tu validación ha sido un exito, ahora puedes continuar postulando con tranquilidad. En uHomie te hacemos la vida mas fácil.';
                    
                    if ( $user->getTenantMerbershipOnce() ){
                        $postulaciones_restantes = $user->applications_left;
                        foreach( $user->getAppliedPropertiesEspera() as $postulacion ){
                            if($postulaciones_restantes > 0){
                                $postulacion->espera = 0;
                                $postulacion->save();
                                $postulaciones_restantes--;
                            }
                        }
                    }
                } else { // RED
                    $carnet_frontal = $user->files()->where('name','id_front')->get()->first();
                    $carnet_trasero = $user->files()->where('name','id_back')->get()->first();
                    $carnet_frontal->verified = 0;
                    $carnet_trasero->verified = 0;
                    $carnet_frontal->save();
                    $carnet_trasero->save();
                    $aplicante->review_client_comment = $resultado->status->reviewResult->clientComment;
                    $objeto['paso'] = 0;
                    $objeto['state'] = 2;
                    foreach( $resultado->documentStatus as $document){
                        $aplicante->review_moderation_comment = null;
                        $rf['tipo_doc'] = $document->idDocType;
                        if( isset($document->idDocSubType) ){
                            $rf['subtipo_doc'] = $document->idDocSubType;
                        } else {
                            $rf['subtipo_doc'] = null;
                        }
                        if( $document->reviewResult->reviewAnswer == 'GREEN' ){
                            $rf['mensaje'] = 'OK';
                            if( isset($document->idDocSubType) ){
                                $aplicante->review_moderation_comment .= $document->idDocType.' - '.$document->idDocSubType.': OK ';
                            }else{
                                $aplicante->review_moderation_comment .= $document->idDocType.': OK ';
                            }
                            
                        }else{
                            if( $document->reviewResult->moderationComment != '' ){
                                if( isset($document->idDocSubType) ){
                                    $aplicante->review_moderation_comment .= $document->idDocType.' - '.$document->idDocSubType.': '.$document->reviewResult->moderationComment.' ';
                                } else{
                                    $aplicante->review_moderation_comment .= $document->idDocType.': '.$document->reviewResult->moderationComment.' ';
                                }
                                try{
                                    # Your Google Cloud Platform project ID
                                    $projectId = env('GOOGLE_PROJECT_ID', 'verdant-doodad-245719');
                                    $translate = new TranslateClient([
                                        'projectId' => $projectId,
                                        'keyFilePath' => env('GOOGLE_KEY_FILE_PATH', 'C:\Users\alexa\Desktop\My First Project-7010ed79cd4b.json')
                                    ]);
                                    # El texto para traducir
                                    $text = $document->reviewResult->moderationComment;
                                    # El idioma objetivo
                                    $target = 'es';
                                    # Traduciendo el texto a español
                                    $translation = $translate->translate($text, [
                                        'target' => $target
                                    ]);
                                    $rf['mensaje'] = $translation['text'];
                                } catch(\Exception $e){
                                    $rf['mensaje'] = $document->reviewResult->moderationComment;
                                }
                            }
                        }
                        $objeto['documentos'][] = $rf;
                    }
                }
                $aplicante->save();
            } else {
                $objeto['paso'] = 0;
                $objeto['state'] = 4;
                $objeto['mensaje'] = 'Estamos Verificando... este proceso demora normalmente entre 10 y 20 minutos después de subir la selfie, y en algunos casos puede llegar hasta 24 horas. De todas formas, te avisaremos via sms/email cuando tu validación se haya completado. ';
            }
        } else{ 
            $query2 = $user->files()->where('name','id_front')->get();
            $query3 = $user->files()->where('name','id_back')->get();
            // Ahora le permitiremos validar identidad si el carnet trasero no está vigente, osea con el verified_ocr == 0
            //if( $query2->first()->path && $query3->first()->path && $query2->first()->verified_ocr && $query3->first()->verified_ocr ){
            if( $query2->first()->path && $query3->first()->path && $query2->first()->verified_ocr  ){
                $objeto['paso'] = 0;
                $objeto['state'] = 1;
                $objeto['mensaje'] = 'Excelente!. Comenzaremos tomándote una selfie junto a tu carnet de identidad/pasaporte o bien puedes subir dicha selfie mediante un archivo .jpg, como en el caso en verde de la imagen de abajo, para continuar el proceso de verificación de documento de identidad.';

            } else {
                $objeto['paso'] = 0;
                $objeto['state'] = 0;
                $objeto['mensaje'] = 'Ups!. Debes validar al menos tu foto del carnet por la parte frontal y subir una foto por la parte trasera en la pantalla inicial de "Mi perfil" para continuar con el proceso de verificación. Recuerda debes subir una foto completa y de buena calidad y en formato .jpg como en la imagen de abajo.';
            }
        }
        return response()->json($objeto);
    }
    public function sendImage(Request $request)
    {
        if( !($user = \Auth::user()) ) return abort(403, 'No esta logueado');
        // Si viene una imagen en el request en el campo dataimage y Si el usuario tiene su imagen de foto carnet frontal subida
        if( isset($request->dataimage) && $user->files()->where('name','id_front')->get()->first()->path && $user->files()->where('name','id_back')->get()->first()->path ){
            if( $user->verifyng_attempts < 1 ){
                $objeto['paso'] = 0;
                $objeto['state'] = 2;
                $objeto['mensaje'] = 'Has agotado la cantidad de intentos posibles. Si tienes problemas con este proceso te recomendamos comunicarte con soporte uHomie.';
            
                return response()->json($objeto);
            } else {
                $user->verifyng_attempts--;
                $user->save();
            }
            $rutaImagenSelfie = 'storage/images/'.uniqid().'_selfie.jpg';
            \Image::make($request->dataimage)->save($rutaImagenSelfie);
            
            $client = new Client();
            $headers = ['Content-Type' => 'application/json', 
                        'Authorization' => 'Basic '.env('HS_LOGIN', 'dWhvbWllX2pvc2VfY29saW5hX3Rlc3Q6ZWdxQnVKSmdhY1Y0Y0E')]; // Aqui van los datos de logueo (user y pass) encriptados
            $request = new RequestGuzzle('POST', env('HS_URL', 'https://test-api.sumsub.com').'/resources/auth/login', $headers);
            // Request para Obtener el token de logueo
            try {
                $response = $client->send($request);
            } catch (RequestException $e) {
                
                echo Psr7\str($e->getRequest());
                if ($e->hasResponse()) {
                    
                    echo Psr7\str($e->getResponse());
                }
            }
            //Token de logueo
            
            $tokenSas = json_decode($response->getBody()->getContents())->payload;
        
            $headers = ['Content-Type' => 'application/json', 
                        'Authorization' => 'Bearer '.$tokenSas];
            // TO-DO: completar  con datos del usuario
            $body = [
                'externalUserId' => $user->document_number,
                'email' => $user->email,
                'info' => [ 'country' => 'CHL', 
                            'firstName' => $user->firstname,
                            'lastName' => $user->lastname,
                            'phone' => '+'.$user->phone_code.$user->phone,
                            'dob' => $user->birthdate,
                            'nationality' => $user->country()->first()->code,
                ],
                'requiredIdDocs' => [
                            //'country' => $user->country()->first()->code,
                            'docSets' => [
                                [   'idDocSetType' => 'IDENTITY',
                                    'types' => [ ($user->document_type == 'PASSPORT') ? 'PASSPORT' : 'ID_CARD' ],
                                    'subTypes' => ($user->document_type == 'PASSPORT') ? null : [ "FRONT_SIDE" , "BACK_SIDE" ]
                                ],
                                [   'idDocSetType' => 'SELFIE',
                                    'types' => ["SELFIE"],
                                    'subTypes' => null
                                ]
                            ]
                ],
            ];
            
            $request = new RequestGuzzle('POST', env('HS_URL', 'https://test-api.sumsub.com').'/resources/applicants', $headers, json_encode($body)); 
            try {
                $response = $client->send($request);
            } catch (RequestException $e) {
                echo Psr7\str($e->getRequest());
                if ($e->hasResponse()) {
                    echo Psr7\str($e->getResponse());
                }
            }
            //sleep(3);
            $respuestaConId = json_decode($response->getBody()->getContents());
            //dd($respuestaConId->id);
            $id = $respuestaConId->id;
            
            // TO-DO: Almacenar id en BD
            if( !$user->sasapplicant()->first() ){
                $nuevo_aplicante = new Sasapplicant();
            }else{
                $nuevo_aplicante = $user->sasapplicant()->first();
            }
            
            $nuevo_aplicante->applicant_id = $id;
            $nuevo_aplicante->tokenSas = $tokenSas;
            //$tokenPropio = Str::random(32);
            
            //$nuevo_aplicante->token = $tokenPropio;
            $nuevo_aplicante->user()->associate(\Auth::user());
            $nuevo_aplicante->save();
            
            $nuevaUrl = env('HS_URL', 'https://test-api.sumsub.com').'/resources/applicants/'.$id.'/info/idDoc';
            //$nuevaUrl2 = 'https://hookb.in/aBZxaWpy6nUlwlbxENEg';
            $headers2 = ['Content-Type' => 'multipart/form-data', 'Authorization' => 'Bearer '.$tokenSas] ;
            
            // rutas de imagenes del carnet de identidad frontal y trasera
            $rutaImagenFront = '../storage/app/'.$user->files()->where('name','id_front')->get()->first()->path;
            $rutaImagenBack = '../storage/app/'.$user->files()->where('name','id_back')->get()->first()->path;
            
            /**
             * Enviando el Carnet Frontal
             */
            $request = curl_init($nuevaUrl);
            curl_setopt($request, CURLOPT_POST, true);
            $headers = [
                //'Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW',
                'Authorization: '.'Bearer '.$tokenSas
            ];
            curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
            curl_setopt(
                $request,
                CURLOPT_POSTFIELDS,
                array(
                'content' => curl_file_create($rutaImagenFront,'image/jpeg','content'),
                'metadata' => '{"idDocType": "'.( ($user->document_type == 'PASSPORT')? 'PASSPORT' : 'ID_CARD' ).'",  "country": "'.( ($user->document_type == 'PASSPORT')? $user->country()->first()->code : 'CHL' ).'"'.( ($user->document_type != 'PASSPORT')? ', "idDocSubType": "FRONT_SIDE"}' : '}')
                ));
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            echo curl_exec($request);
            curl_close($request);
            if ( $user->document_type == 'RUT' || $user->document_type == 'RUT_PROVISIONAL' ){
                /**
                 * Enviando Carnet Trasero
                 */
                $request = curl_init($nuevaUrl);
                curl_setopt($request, CURLOPT_POST, true);
                $headers = [
                    //'Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW',
                    'Authorization: '.'Bearer '.$tokenSas
                ];
                curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
                curl_setopt(
                    $request,
                    CURLOPT_POSTFIELDS,
                    array(
                    'content' => curl_file_create($rutaImagenBack,'image/jpeg','content'),
                    'metadata' => '{"idDocType": "ID_CARD",  "country": "CHL", "idDocSubType": "BACK_SIDE"}'
                    ));
                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                echo curl_exec($request);
                curl_close($request);
            }
            
            /**
             * Enviando Foto Selfie
             */
            $request = curl_init($nuevaUrl);
            curl_setopt($request, CURLOPT_POST, true);
            $headers = [
                //'Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW',
                'Authorization: '.'Bearer '.$tokenSas
            ];
            curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
            curl_setopt(
                $request,
                CURLOPT_POSTFIELDS,
                array(
                'content' => curl_file_create($rutaImagenSelfie,'image/jpeg','content'),
                'metadata' => '{"idDocType": "SELFIE",  "country": "'.$user->country()->first()->code.'"}'
                ));
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            echo curl_exec($request);
            curl_close($request);

            $objeto['paso'] = 2;
            $objeto['state'] = 99;
            $objeto['mensaje'] = 'Estamos Verificando... este proceso demora normalmente entre 10 y 20 minutos después de subir la selfie, y en algunos casos puede llegar hasta 24 horas. De todas formas, te avisaremos via sms/email cuando tu validación se haya completado. ';
        
            return response()->json($objeto);
        } else {
            return abort(403, 'No ha subido imagen de carnet frontal y trasera');
        }
        
        return response('OK');
    }
    
}
