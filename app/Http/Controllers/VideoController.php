<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\File;
use function GuzzleHttp\json_encode;
use TheSeer\Tokenizer\Exception;
use App\Notifications\TwilioPush;
use App\ApplyProperty;
use App\Property;
use GuzzleHttp\Psr7;

class VideoController extends Controller
{
    /**
     * Cuando se graba el usuario Mejorado 
     */
    public function handleVideoVerify3(Request $request, $postul_id)
    {
        if(!($user = \Auth::user())) return abort(401, 'no estas logueado');
        
        $videoselfie_actual = null;
        if( $user->files()->where('type', File::VIDEO_FILES_TYPE)->get()->count() ){
            $videoselfie_actual = $user->files()->where('type', File::VIDEO_FILES_TYPE)->get()->first();
        }

        $filePath = 'storage/videos/' . uniqid() .'_'.$_POST['video-filename'];
        if(!$filePath) return 'error en filePath';
        // path to ~/tmp directory
        $tempName = $_FILES['video-blob']['tmp_name'];
        $extension_file = 'mp4';
        // move file from ~/tmp to "uploads" directory
        if (!move_uploaded_file($tempName, $filePath)) {
            // failure report
            echo 'Problem saving file: '.$tempName;
            die();
        }
        $client = new Client();
        try{
            // PASO 1: Enviar URI de videoselfie en uhomie/public/storage(link)/
            ;
            $res = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url=".(env('APP_URL_NGROK', url('') ).'/'.$filePath)."&bucket=videos&extension={$extension_file}");
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                if(!$videoselfie_actual){
                    $new_file = new File();
                    $new_file->user()->associate($user);
                    $new_file->name = File::VIDEOSELFIE;
                    $new_file->type = File::VIDEO_FILES_TYPE;
                    $new_file->path = $filePath;
                    $new_file->s3 = $json->archivo; // el otro parametro que llega es 'video/'+archivo
                    $new_file->save();
                } else {
                    $videoselfie_actual->path = $filePath;
                    $videoselfie_actual->s3 = $json->archivo; // el otro parametro que llega es 'video/'+archivo
                    $videoselfie_actual->save();
                    $new_file = $videoselfie_actual;
                }
               
                //dd('Se envio la url: '.url($filePath).' y retorno: '.$json->archivo);
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 1');
        };

        $s3tmp_videoselfie = null;
        try{
            // PASO 2: Mover a s3 temp el videoselfie recien subido en paso anterior
            $res = $client->get("https://vindexerfaas.azurewebsites.net/api/videoindexer-bkt2bkttmp?code=9IEU4626Il/neWw/nxt0Ou9mNtk2w3PErUoBD97YROOCa8P7gPIu7Q==&bucket=videos&archivo={$new_file->s3}");
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                //dd($json);
                $token_videoselfie = $json->token; // Obtengo la url del archivo en s3 Public
                $s3tmp_videoselfie = $json->s3tmp;
                //return $res->getBody();
                // JSON string: { ... }
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 2');
        };
        if(!$s3tmp_videoselfie || !$token_videoselfie) return 'error en paso 2';

        /**
         * 
         * CARNET DE IDENTIDAD PARTE FRONTAL
         * 
         */
        $query = $user->files()->where('name','id_front')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario no ha subido carnet';
        } 
        /**
         * TO-DO: Añadir condición de que esté verified
         */
        $ruta_destino = 'storage/images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , $ruta_destino );
        $ruta_publica = env('APP_URL_NGROK', url('') ).'/'.$ruta_destino;
        $client = new Client();
        try{
            // PASO 3: Enviar URI publica de foto .JPG de carnet a bucket jpg S3 privado. 
            $res = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica}&bucket=images&extension=jpg");
            if ($res->getBody()) {
                
                $json = json_decode($res->getBody());
                if(!$json) return 'retorno un stream, no el contenido';
                $carnet->s3 = $json->archivo; // Asignando el nombre del archivo en el bucket s3 privado de esta imagen
                $carnet->save();
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 3');
        };
        $s3temp_carnet = null;
        try{
            // PASO 4: Mover a s3 temp el video del carnet recien subido en paso anterior. Igual que paso 2
            
            $res = $client->get("https://vindexerfaas.azurewebsites.net/api/videoindexer-bkt2bkttmp?code=9IEU4626Il/neWw/nxt0Ou9mNtk2w3PErUoBD97YROOCa8P7gPIu7Q==&bucket=images&archivo={$carnet->s3}");
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                $s3temp_carnet = $json->s3tmp; // Obtengo la url del archivo en s3 Public
                $token_carnet = $json->token;
                //return $res->getBody();
                // JSON string: { ... }
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 5');
        };
        if(!$s3temp_carnet || !$token_carnet) return 'error en el paso 5';
        //dd("https://vindexerfaas.azurewebsites.net/api/videoindexer-getsimilitudwithtknpub?code=VicpaeVtAvCyahgyJ2cNfhL8v4e86FyZRlxKgn8keSumaRqJr1avlA==&tknfot={$token_carnet}&tknvid={$token_videoselfie}");
        try{
            // PASO 5: Consultar coincidencia
            $res = $client->get("https://vindexerfaas.azurewebsites.net/api/videoindexer-getsimilitudwithtknpub?code=VicpaeVtAvCyahgyJ2cNfhL8v4e86FyZRlxKgn8keSumaRqJr1avlA==&tknfot={$token_carnet}&tknvid={$token_videoselfie}");
            if ($res->getBody()) {
                if( $json = json_decode($res->getBody()) ){
                    if( $json->coincide == "true" ){
                        $original = Psr7\stream_for(fopen("http://s3.amazonaws.com/static.public.tmp/{$token_videoselfie}-3.jpg", 'r')); 
                        $local = Psr7\stream_for(fopen("storage/thumbnails/{$token_videoselfie}.jpg", 'w')); 
                        $local->write($original->getContents());
                        $new_file->thumbnail = "storage/thumbnails/{$token_videoselfie}.jpg";
                        $new_file->verified = 1;
                        $new_file->save();
                        $new_file->user()->first()->notify(new TwilioPush("La verificacion facial ha sido un exito. Cuando todos las partes verifiquen identidad, deberan firmar el contrato de arriendo, recuerda que tendran un plazo de 48 horas para completarlo. Saludos uHomie."));
                        $postulacion = ApplyProperty::findOrFail($postul_id);
                        $property = Property::find($postulacion->property_id);
                        $contract = $property->contract()->orderBy('created_at','desc')->first();
                        if( $contract->isUserContract($user->id) ){
                            if( $contract->areEveryoneVerifiedVin() ){
                                
                                // Obteniendo arrendatario y arrendador
                                $tenant = $postulacion->postulant();
                                if($tenant->confirmed_collateral) {
                                    try{
                                        $contract->generarContratoConAval();
                                        $contract->save();
                                    } catch( \Exception $e){
                                        dd($e);
                                    }    
                                    
                                } else {
                                    try{
                                        $contract->generarContratoSinAval();
                                        $contract->save();
                                    } catch( \Exception $e){
                                        dd($e);
                                    }
                                    
                                }
                                $postulacion->state = ApplyProperty::VERIFIED_STATE;
                                $postulacion->save();
                            }
                            
                        }
                        
                        return response()->json([ 'respuesta' => true ]);
                    } else {
                        $new_file->verified = 0;
                        $new_file->save();
                        return response()->json([ 'respuesta' => false ]);
                    }
                } else{
                    return 'error en json paso 5';
                }
            }
        } catch (\Exception $e){
            return response()->json([ 'respuesta' => false ]);
        }
    }
    public function verificarEstadoVerificacion()
    {
        if( $user = \Auth::user() ){
            if( $user->isVerified() ){
                if( $user->files()->where('type', File::VIDEO_FILES_TYPE)->get()->count() ){
                    $videoselfie_actual = $user->files()->where('type', File::VIDEO_FILES_TYPE)->get()->first();
                    if($videoselfie_actual->verified){
                        return response()->json(['respuesta' => 'verified']);
                    } else {
                        return response()->json(['respuesta' => 'no-verified']);
                    }
                } else {
                    return response()->json(['respuesta' => 'blank']);
                }
            } elseif( $user->isVerifiedInForce ) {
                return response()->json(['respuesta' => 'no-verified-sas']); // Tiene que estar validado por s&s y carnet trasero vigente para validar por Vin
            } else {
                return response()->json(['respuesta' => 'no-verified-in-force']); // Informandole al usuario que debe tener el carnet trasero vigente
            }
            
        }else{
            return abort(401, 'No ha iniciado sesión');
        }
    }
    /**
     * Cuando se graba el usuario
     */
    /*
    public function handleVideoVerify(Request $request)
    {
        if(!($user = \Auth::user())) return 'no estas logueado';
        
        $videoselfie_actual = null;
        if( $user->files()->where('type', File::VIDEO_FILES_TYPE)->get()->count() ){
            $videoselfie_actual = $user->files()->where('type', File::VIDEO_FILES_TYPE)->get()->first();
        }

        $filePath = 'storage/videos/' . uniqid() .'_'.$_POST['video-filename'];
        if(!$filePath) return 'error en filePath';
        // path to ~/tmp directory
        $tempName = $_FILES['video-blob']['tmp_name'];
        $extension_file = 'mp4';
        // move file from ~/tmp to "uploads" directory
        if (!move_uploaded_file($tempName, $filePath)) {
            // failure report
            echo 'Problem saving file: '.$tempName;
            die();
        }
        $client = new Client();
        try{
            // PASO 1: Enviar URI de videoselfie en uhomie/public/storage(link)/
            $res = $client->get(env('VIN_1').'&url='.(env('APP_URL_NGROK', url('') ).'/'.$filePath).'&bucket=videos&extension='.$extension_file);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                if(!$videoselfie_actual){
                    $new_file = new File();
                    $new_file->user()->associate($user);
                    $new_file->name = File::VIDEOSELFIE;
                    $new_file->type = File::VIDEO_FILES_TYPE;
                    $new_file->path = $filePath;
                    $new_file->s3 = $json->archivo; // el otro parametro que llega es 'video/'+archivo
                    $new_file->save();
                } else {
                    $videoselfie_actual->path = $filePath;
                    $videoselfie_actual->s3 = $json->archivo; // el otro parametro que llega es 'video/'+archivo
                    $videoselfie_actual->save();
                    $new_file = $videoselfie_actual;
                }
               
                //dd('Se envio la url: '.url($filePath).' y retorno: '.$json->archivo);
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 1');
        };
        $s3temp = null;
        try{
            // PASO 2: Mover a s3 temp el videoselfie recien subido en paso anterior
            $res = $client->get(env('VIN_2').'&bucket=videos&archivo='.$new_file->s3);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                //dd($json);
                $s3temp = $json->s3tmp; // Obtengo la url del archivo en s3 Public
                $TEST1 = $s3temp;
                //return $res->getBody();
                // JSON string: { ... }
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 2');
        };
        if(!$s3temp) return 'error';
        
         
         // CARNET DE IDENTIDAD PARTE FRONTAL
         
        $query = $user->files()->where('name','id_front')->get();
        if( $query->first()->path ){
            $carnet = $query->first();
        }
        else{
            return 'Usuario no ha subido carnet';
        } 
        
         //TO-DO: Añadir condición de que esté verified
        
        $ruta_destino = 'storage/images/' . uniqid() .'.jpg';
        copy( storage_path("app/".$carnet->path) , $ruta_destino );
        $ruta_publica = env('APP_URL_NGROK', url('') ).'/'.$ruta_destino;
        $client = new Client();
        try{
            // PASO 4: Enviar URI publica de foto .JPG de carnet a S3 privado y en formato .mp4. 
            $res = $client->get(env('VIN_4').'&url='.$ruta_publica.'&bucket=videos');
            //dd(env('VIN_4').'&url='.$ruta_publica.'&bucket=videos');
            if ($res->getBody()) {
                
                $json = json_decode($res->getBody());
                if(!$json) return 'retorno un stream, no el contenido';
                $carnet->s3 = $json->archivo; // Asignando el nombre del archivo en el bucket s3 privado de esta imagen
                $carnet->save();
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 4');
        };
        $s3temp = null;
        try{
            // PASO 5: Mover a s3 temp el video del carnet recien subido en paso anterior. Igual que paso 2
            $res = $client->get(env('VIN_2').'&bucket=videos&archivo='.$carnet->s3);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                //dd($json);
                $s3temp = $json->s3tmp; // Obtengo la url del archivo en s3 Public
                $TEST2 = $s3temp;
                //return $res->getBody();
                // JSON string: { ... }
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 5');
        };
        if(!$s3temp) return 'error en el paso 5';

        $idVideoIndexerVideoSelfie = null;
        try{
            // PASO 3: Enviar URI  de videoselfie en s3 public a VideoIndexer
            $res = $client->get(env('VIN_3').'&idUnicoUhomie='.$user->document_number.'-SELFIE&url='.$s3temp);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                // Obtener el id de videoIndexer
                $idVideoIndexerVideoSelfie = $json->id;
                $new_file->id_videoindexer = $json->id;
                $new_file->save();
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 3');
        };
        if(!$idVideoIndexerVideoSelfie) return 'error';
        
        $client = new Client();
        try{
            $listo = false;
            $max_iteraciones = 20;
            $contador = 1;
            do {
                sleep(5);
                $res = $client->get(env('VIN_GET_STATUS').'&idVideoIndexer='.$idVideoIndexerVideoSelfie);
                if ($res->getBody()) {
                    $json = json_decode($res->getBody());
                    $estado_analisis = $json->status;
                    if( $estado_analisis == 'Processed'){
                        $listo = true;
                        dd('procesadooo');
                    }
                }
                $contador++;
            } while ( !$listo && $contador <= $max_iteraciones);
            if($contador > $max_iteraciones) return 'sobrepaso el max numeo de iteraciones';
        } catch(Exception $e){
            dd('Ha ocurido un error en Consultar Estado segun videoIndexer 1');
        };
        //dd($TEST1.'   '.$TEST2);
        try{
            // PASO 6: Enviar URI  de videocarnet en s3 public a VideoIndexer
            $res = $client->get(env('VIN_3').'&idUnicoUhomie='.$user->document_number.'-RUT&url='.$s3temp);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                dd($json);
                // Obtener el id de videoIndexer
                $idVideoIndexerVideoCarnet = $json->id;
                $carnet->id_videoindexer = $json->id;
                $carnet->save();
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 3');
        };
        
        return response()->json(['respuesta' => true]);
        //dd($idVideoIndexerVideoSelfie.' videoselfie-----videocarnet '.$idVideoIndexerVideoCarnet);
    }
    public function handleVideoVerify2()
    {
        if(!($user = \Auth::user())) return abort(403, 'No esta logueado');
        $query1 = $user->files()->where('name', File::VIDEOSELFIE)->get();
        $query2 = $user->files()->where('name','id_front')->get(); // 
        if( $query1->count() && $query2->first()->verified ){
            $idVideoIndexerVideoSelfie = $query1->first()->id_videoindexer;
            $idVideoIndexerVideoCarnet = $query2->first()->id_videoindexer;
        } else {
            return response()->json(['respuesta' => 'blank']); // Tiene que sacarse la videoselfie
        }
        
        // PASO 7: Consultar estados de Procesamiento de los Videos en Videoindexer
        
        
        $estados = $this->consultarEstados($idVideoIndexerVideoSelfie, $idVideoIndexerVideoCarnet);
        
        if ( $estados[0] != 'Processed' || $estados[1] != 'Processed' ){
            return response()->json(['respuesta' => 'processing']); // Aun está procesando
        }
        
        
        // PASO 8: Detectar apariciones de rostros
        
        $client = new Client();
        
        try{
            // VideoSelfie
            $res = $client->get(env('VIN_GET_FACES').'&idVideoIndexer='.$idVideoIndexerVideoSelfie);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                //dd($json);
                $error = null;
                $faceId_TEST = null; // faceId videoselfie, ojo ke puede retornar el mensaje error:  "error": "No se han encontrado coincidencias"
                if( isset($json->faceId) ){
                    $faceId_TEST = $json->faceId;
                } else {
                    $error = true;
                }
                
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 8.1');
        };
    
        try{
            //VideoCarnet
            $res = $client->get(env('VIN_GET_FACES').'&idVideoIndexer='.$idVideoIndexerVideoCarnet);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                $user->faceid_videoindexer = $json->faceId;
                $user->save();
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 8.2');
        };
        
        
        //  ============================== IF ================================
         
        if( !($error) ){
            return response()->json(['respuesta' => 'no-face']);
        }
        if($faceId_TEST != $user->faceid_videoindexer){
            return response()->json(['respuesta' => 'no-match']);
        }
        
        // PASO 9: Obtener PersonModelId  segun faceID y id de VideoIndexer
        
        
        $personModelId = null;
        try{
            $res = $client->get(env('VIN_GET_PERSON_MODEL_ID').'&idVideoIndexer='.$idVideoIndexerVideoCarnet.'&faceId='.$user->faceid_videoindexer);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                $personModelId = $json->personModelId;
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 8.2');
        };
        if(!$personModelId) return 'error en personModelId';

        
        // PASO 10: Crea un PersonID asociado a PersonModelID
        

        try{
            $res = $client->get(env('VIN_CREA_PERSON_ID').'&name='.$user->firstname.' '.$user->lastname.' ['.$user->document_number.'-RUT]&personModelId='.$personModelId);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                $user->personid_videoindexer = $json->id;
                $user->save();
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 8.2');
        };

        
        // PASO 11: Asigna Face encontrado a un PersonID
        

        try{
            $res = $client->get(env('VIN_UPDATE_FACE_WITH_PERSON').'&faceId='.$user->faceid_videoindexer.'&personId='.$user->personid_videoindexer.'&idVideoIndexer='.$idVideoIndexerVideoCarnet);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                
                // DO ?
                
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en PASO 8.2');
        };

        return response()->json(['respuesta' => 'match']);
    }
    private function consultarEstados($idVideoIndexerVideoSelfie, $idVideoIndexerVideoCarnet){
        $client = new Client();
        try{
            $res = $client->get(env('VIN_GET_STATUS').'&idVideoIndexer='.$idVideoIndexerVideoSelfie);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                $estados[0] = $json->status;
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en Consultar Estado segun videoIndexer 1');
        };
        try{
            $res = $client->get(env('VIN_GET_STATUS').'&idVideoIndexer='.$idVideoIndexerVideoCarnet);
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                $estados[1] = $json->status;
            }
        } catch(Exception $e){
            dd('Ha ocurido un error en Consultar Estado segun videoIndexer 2');
        };

        return $estados;
    }
    */
    public function iniciarSesion(Request $request)
    {
        $client = new Client();
        try {
            $res = $client->get('https://vindexerfaas.azurewebsites.net/api/videoindexer-iniciaflujo?code=Q5G6cvcIjlwsKyg5RMMCvt2FRWJOTOhEDITnJnvK6OUYXAz46El6cQ==');
        
        } catch(Exception $e){
            dd($e);
        }
        return response('ok');    

    }
}
