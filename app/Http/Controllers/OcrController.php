<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class OcrController extends Controller
{
    public function consumirServicioDesdeForm(Request $request)
    {
        if( $user = \Auth::user() ){
            $filePath = 'storage/ocr/' . uniqid() .'_'.$_POST['filename'];
            if(!$filePath) return 'error en filePath';
            // path to ~/tmp directory
            $tempName = $_FILES['archivo']['tmp_name'];
            setlocale(LC_ALL,'en_US.UTF-8');
            $extension_file = pathinfo($filePath, PATHINFO_EXTENSION);
            $ruta_publica_archivo = env('APP_URL_NGROK', url('')).'/'.$filePath;
            
            // move file from ~/tmp to "uploads" directory
            if (!move_uploaded_file($tempName, $filePath)) {
                // failure report
                echo 'Problem saving file: '.$tempName;
                die();
            }
            
            switch($_POST['tipo']){
                case 'id_front':
                    $client = new Client();
                    $flag = true;
                    $nombre_a_buscar_cliente = strtoupper($user->firstname).' '.strtoupper($user->lastname);
                    $arreglo_nombres = explode(" ", strtoupper($user->firstname));
                    $arreglo_apellidos = explode(" ", strtoupper($user->lastname));
                    $arreglo_fecha_nacimiento = array();
                    $arreglo_fecha_nacimiento[] =  explode('-',$user->birthdate)[2];
                    $arreglo_fecha_nacimiento[] = explode('-',$user->birthdate)[0];
                    $pre_mes = explode('-',$user->birthdate)[1];
                    switch( $pre_mes ){
                        case '01':
                            $arreglo_fecha_nacimiento[] = 'ENE';
                            break;
                        case '02':
                            $arreglo_fecha_nacimiento[] = 'FEB';
                            break;
                        case '03':
                            $arreglo_fecha_nacimiento[] = 'MAR';
                            break;
                        case '04':
                            $arreglo_fecha_nacimiento[] = 'ABR';
                            break;
                        case '05':
                            $arreglo_fecha_nacimiento[] = 'MAY';
                            break;
                        case '06':
                            $arreglo_fecha_nacimiento[] = 'JUN';
                            break;
                        case '07':
                            $arreglo_fecha_nacimiento[] = 'JUL';
                            break;
                        case '08':
                            $arreglo_fecha_nacimiento[] = 'AGO';
                            break;
                        case '09':
                            $arreglo_fecha_nacimiento[] = 'SEP';
                            break;
                        case '10':
                            $arreglo_fecha_nacimiento[] = 'OCT';
                            break;
                        case '11':
                            $arreglo_fecha_nacimiento[] = 'NOV';
                            break;
                        case '12':
                            $arreglo_fecha_nacimiento[] = 'DIC';
                            break;
                    }
                    $nacionalidad = array();
                    $nacionalidad[] = substr(strtoupper($user->country()->first()->nationality), 0, 2  );
                    $arreglo_busqueda = array_merge($arreglo_nombres, $arreglo_apellidos, $arreglo_fecha_nacimiento, $nacionalidad);
                    
                    foreach( $arreglo_busqueda as $elemento ){
                        $res = $client->get("https://cbrsfaas.azurewebsites.net/api/cbrs-getcbrsotrodoc?code=YMxLOVMYt3ABteywTVZnFYDZOVak88Xm1nE5mVH5ItKltAmm2IFtkg==&url={$ruta_publica_archivo}&nombre={$elemento}");
                        if ($res->getBody()) {
                            $json = json_decode($res->getBody());
                            if ( isset($json->BUSQUEDA) ){
                                if( $json->BUSQUEDA == 'OK' ){
                                    
                                } else {
                                    $flag = false;
                                    return response()->json(['respuesta' => $flag, 'nombre_no_encontrado' => $nombre_a_buscar_cliente, 'error_code' => 1]);
                                }
                            } else {
                                $flag = false;
                                return response()->json(['respuesta' => $flag, 'nombre_no_encontrado' => $nombre_a_buscar_cliente, 'error_code' => 2]);
                            }
                        } else {
                            $flag = false;
                            return response()->json(['respuesta' => $flag, 'nombre_no_encontrado' => $nombre_a_buscar_cliente, 'error_code' => 3]);
                        }
                    }

                    $res = $client->get("https://cbrsfaas.azurewebsites.net/api/cbrs-getdatoscarnet?code=1qTtadci307jZlMK0trooml/Keho9MvgmoQYYBLPWTiS/tfqTSWaXA==&url=".$ruta_publica_archivo);
                    if ($res->getBody()) {
                        $json = json_decode($res->getBody());
                        if ( isset($json->ndoc) ){
                            $numero_documento = $json->ndoc;
                            if( strlen($numero_documento) == 9 && is_numeric($numero_documento) ){
                                $user->document_serie = $numero_documento;
                                $user->save();
                                return response()->json(['respuesta' => $flag]);
                            }else{
                                $flag = false;
                                return response()->json(['respuesta' => $flag, 'nombre_no_encontrado' => 'NUMERO DE DOCUMENTO', 'error_code' => 1]);
                            }
                        } else {
                            $flag = false;
                            return response()->json(['respuesta' => $flag, 'nombre_no_encontrado' => 'NUMERO DE DOCUMENTO', 'error_code' => 2]);
                        } 
                    } else {
                        $flag = false;
                        return response()->json(['respuesta' => $flag, 'nombre_no_encontrado' => 'NUMERO DE DOCUMENTO', 'error_code' => 3]);
                    }
                    break;
                case 'id_back':
                    if(!$user->document_serie){
                        /**
                         * TODO: modificar front end para que cuando reciba error_code = 4 , diga que primer debe subir y validar carnet frontal
                         */
                        return response()->json(['respuesta' => false,  'error_code' => 4]);
                    }
                    $client = new Client();
                    $rut_a_buscar = $user->document_serie;
                    $res = $client->get("https://cbrsfaas.azurewebsites.net/api/cbrs-getcbrsotrodoc?code=YMxLOVMYt3ABteywTVZnFYDZOVak88Xm1nE5mVH5ItKltAmm2IFtkg==&url={$ruta_publica_archivo}&nombre={$rut_a_buscar}");
                    if ($res->getBody()) {
                        $json = json_decode($res->getBody());
                        if ( isset($json->BUSQUEDA) ){
                            if( $json->BUSQUEDA == 'OK' ){
                                
                            } else {
                                return response()->json(['respuesta' => false, 'rut_no_encontrado' => $rut_a_buscar, 'error_code' => 1]);
                            }
                        } else {
                            return response()->json(['respuesta' => false, 'rut_no_encontrado' => $rut_a_buscar, 'error_code' => 2]);
                        }
                    } else {
                        return response()->json(['respuesta' => false, 'rut_no_encontrado' => $rut_a_buscar, 'error_code' => 3]);
                    }

                    $rut = str_replace('.', '', $user->document_number);
                    switch( $user->document_type ){
                        case 'RUT':
                            $res = $client->get("https://utilsfaas.azurewebsites.net/api/utils-getvigenciacedula?code=BRLTXeGjXIN9Xb170dIWgkog5Vkqz9yHGmva1m3tb0J78s9Y7Fo7qg==&rut={$rut}&tipo=CEDULA&serie={$user->document_serie}");
                            if ($res->getBody()) {
                                $json = json_decode($res->getBody());
                                if ( isset($json->resultado) ){
                                    if( $json->resultado == 'VIGENTE'){

                                    } else {
                                        return response()->json(['respuesta' => false, 'error_code' => 5]);
                                    }
                                }
                            }
                            break;
                        case 'RUT_PROVISIONAL':
                            $res = $client->get("https://utilsfaas.azurewebsites.net/api/utils-getvigenciacedula?code=BRLTXeGjXIN9Xb170dIWgkog5Vkqz9yHGmva1m3tb0J78s9Y7Fo7qg==&rut={$rut}&tipo=CEDULA_EXT&serie={$user->document_serie}");
                            if ($res->getBody()) {
                                $json = json_decode($res->getBody());
                                if ( isset($json->resultado) ){
                                    if( $json->resultado == 'VIGENTE'){

                                    } else {
                                        
                                        return response()->json(['respuesta' => false, 'error_code' => 5]);
                                    }
                                }
                            }
                            break;
                        case 'PASSPORT':
                            return response()->json(['respuesta' => false, 'error_code' => 6]);
                            break;
                        default:
                            return response()->json(['respuesta' => false, 'error_code' => 6]);
                            break;
                    }
                    return response()->json(['respuesta' => true]);
                    break;
                /** OCRS Edgardo */
                // Liquidaciones 
                case 'first_settlement':
                    $client = new Client();
                    $flag = true;
                    $nombre_a_buscar_cliente = strtoupper($user->firstname).' '.strtoupper($user->lastname);
                    $arreglo_nombres = explode(" ", strtoupper($user->firstname));
                    $arreglo_apellidos = explode(" ", strtoupper($user->lastname));
                    $arreglo_busqueda = array_merge($arreglo_nombres, $arreglo_apellidos);
                    $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
                    if($res_s3->getBody()){
                        $json_s3 = json_decode($res_s3->getBody());
                        if(isset($json_s3->archivo)){
                            $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                            if($res_read->getBody()){
                                $stream = $res_read->getBody();
                                $contents = $stream->getContents(); 
                                if(!empty($contents)){
                                    foreach( $arreglo_busqueda as $elemento ){
                                        if(stripos($contents, $elemento)){
                                            $found = true;
                                        } else {
                                            $no_found = true;
                                        }
                                    }
                                    if(!isset($no_found)){
                                        $res_liquid = $client->get("https://liquidacionfaas.azurewebsites.net/api/liquidacion-getliquidopdf?code=L5WN6bXXtYaAK5Y0dqC9wY6E4Z0AVIin6vftMGUNon9ygpir4FIGIA==&url={$ruta_publica_archivo}&pag=1");
                                        if($res_liquid->getBody()){
                                            $json_liquid = json_decode($res_liquid->getBody());
                                            if(isset($json_liquid->liquido)){
                                                $db_file = $user->files()->where('name', $_POST['tipo'])->first();
                                                $db_file->factor = $json_liquid->liquido;
                                                $db_file->verified_ocr = 1;
                                                $db_file->save();
                                                return response()->json(['respuesta' => $flag]);
                                            } else {
                                                return response()->json(['respuesta' => $flag, 'liquido' => 'No de encontro sueldo liquido']);
                                            }
                                        } else {
                                            return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro sueldo liquido en el documento']);
                                        }
                                    } else {
                                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro los nombres en el documento']);
                                    }
                                } else {
                                    return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                                }
                            } else {
                                return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                            }
                        } else {
                            return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                        }
                    } else {
                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                    }
                    break;
                case 'second_settlement':
                    $client = new Client();
                    $flag = true;
                    $nombre_a_buscar_cliente = strtoupper($user->firstname).' '.strtoupper($user->lastname);
                    $arreglo_nombres = explode(" ", strtoupper($user->firstname));
                    $arreglo_apellidos = explode(" ", strtoupper($user->lastname));
                    $arreglo_busqueda = array_merge($arreglo_nombres, $arreglo_apellidos);
                    $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
                    if($res_s3->getBody()){
                        $json_s3 = json_decode($res_s3->getBody());
                        if(isset($json_s3->archivo)){
                            $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                            if($res_read->getBody()){
                                $stream = $res_read->getBody();
                                $contents = $stream->getContents(); 
                                if(!empty($contents)){
                                    foreach( $arreglo_busqueda as $elemento ){
                                        if(stripos($contents, $elemento)){
                                            $found = true;
                                        } else {
                                            $no_found = true;
                                        }
                                    }
                                    if(!isset($no_found)){
                                        $res_liquid = $client->get("https://liquidacionfaas.azurewebsites.net/api/liquidacion-getliquidopdf?code=L5WN6bXXtYaAK5Y0dqC9wY6E4Z0AVIin6vftMGUNon9ygpir4FIGIA==&url={$ruta_publica_archivo}&pag=1");
                                        if($res_liquid->getBody()){
                                            $json_liquid = json_decode($res_liquid->getBody());
                                            if(isset($json_liquid->liquido)){
                                                $db_file = $user->files()->where('name', $_POST['tipo'])->first();
                                                $db_file->factor = $json_liquid->liquido;
                                                $db_file->verified_ocr = 1;
                                                $db_file->save();
                                                return response()->json(['respuesta' => $flag]);
                                            } else {
                                                return response()->json(['respuesta' => $flag, 'liquido' => 'No de encontro sueldo liquido']);
                                            }
                                        } else {
                                            return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro sueldo liquido en el documento']);
                                        }
                                    } else {
                                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro los nombres en el documento']);
                                    }
                                } else {
                                    return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                                }
                            } else {
                                return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                            }
                        } else {
                            return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                        }
                    } else {
                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                    }
                    break;
                case 'third_settlement':
                    $client = new Client();
                    $flag = true;
                    $nombre_a_buscar_cliente = strtoupper($user->firstname).' '.strtoupper($user->lastname);
                    $arreglo_nombres = explode(" ", strtoupper($user->firstname));
                    $arreglo_apellidos = explode(" ", strtoupper($user->lastname));
                    $arreglo_busqueda = array_merge($arreglo_nombres, $arreglo_apellidos);
                    $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
                    if($res_s3->getBody()){
                        $json_s3 = json_decode($res_s3->getBody());
                        if(isset($json_s3->archivo)){
                            $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                            if($res_read->getBody()){
                                $stream = $res_read->getBody();
                                $contents = $stream->getContents(); 
                                if(!empty($contents)){
                                    foreach( $arreglo_busqueda as $elemento ){
                                        if(stripos($contents, $elemento)){
                                            $found = true;
                                        } else {
                                            $no_found = true;
                                        }
                                    }
                                    if(!isset($no_found)){
                                        $res_liquid = $client->get("https://liquidacionfaas.azurewebsites.net/api/liquidacion-getliquidopdf?code=L5WN6bXXtYaAK5Y0dqC9wY6E4Z0AVIin6vftMGUNon9ygpir4FIGIA==&url={$ruta_publica_archivo}&pag=1");
                                        if($res_liquid->getBody()){
                                            $json_liquid = json_decode($res_liquid->getBody());
                                            if(isset($json_liquid->liquido)){
                                                $db_file = $user->files()->where('name', $_POST['tipo'])->first();
                                                $db_file->factor = $json_liquid->liquido;
                                                $db_file->verified_ocr = 1;
                                                $db_file->save();
                                                return response()->json(['respuesta' => $flag]);
                                            } else {
                                                return response()->json(['respuesta' => $flag, 'liquido' => 'No de encontro sueldo liquido']);
                                            }
                                        } else {
                                            return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro sueldo liquido en el documento']);
                                        }
                                    } else {
                                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro los nombres en el documento']);
                                    }
                                } else {
                                    return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                                }
                            } else {
                                return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                            }
                        } else {
                            return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                        }
                    } else {
                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                    }
                    break;
                
                // Constancia de trabajo 

                case 'work_constancy':

                    $client = new Client();
                    $flag = true;
                    $nombre_a_buscar_cliente = strtoupper($user->firstname).' '.strtoupper($user->lastname);
                    $arreglo_nombres = explode(" ", strtoupper($user->firstname));
                    $arreglo_apellidos = explode(" ", strtoupper($user->lastname));
                    $arreglo_busqueda = array_merge($arreglo_nombres, $arreglo_apellidos);
                    $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
                    if($res_s3->getBody()){
                        $json_s3 = json_decode($res_s3->getBody());
                        if(isset($json_s3->archivo)){
                            $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                            if($res_read->getBody()){
                                $stream = $res_read->getBody();
                                $contents = $stream->getContents(); 
                                if(!empty($contents)){
                                    foreach( $arreglo_busqueda as $elemento ){
                                        if(stripos($contents, $elemento)){
                                            $found = true;
                                        } else {
                                            $no_found = true;
                                        }
                                    }
                                    if(!isset($no_found)){
                                        if(stripos($contents, $user->company)){
                                            $db_file = $user->files()->where('name', $_POST['tipo'])->first();
                                            $db_file->verified_ocr = 1;
                                            $db_file->save();
                                            return response()->json(['respuesta' => $flag]);
                                        } else {
                                            return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro el nombre de la compañia en el documento']);
                                        }
                                    } else {
                                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro los nombres en el documento']);
                                    }
                                } else {
                                    return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                                }
                            } else {
                                return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                            }
                        } else {
                            return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                        }
                    } else {
                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                    }
                    
                    break;

                // AFP 

                case 'afp':

                    $client = new Client();
                    $flag = true;
                    $nombre_a_buscar_cliente = strtoupper($user->firstname).' '.strtoupper($user->lastname);
                    $arreglo_nombres = explode(" ", strtoupper($user->firstname));
                    $arreglo_apellidos = explode(" ", strtoupper($user->lastname));
                    $arreglo_busqueda = array_merge($arreglo_nombres, $arreglo_apellidos);
                    $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
                    if($res_s3->getBody()){
                        $json_s3 = json_decode($res_s3->getBody());
                        if(isset($json_s3->archivo)){
                            $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                            if($res_read->getBody()){
                                $stream = $res_read->getBody();
                                $contents = $stream->getContents(); 
                                if(!empty($contents)){
                                    foreach( $arreglo_busqueda as $elemento ){
                                        if(stripos($contents, $elemento)){
                                            $found = true;
                                        } else {
                                            $no_found = true;
                                        }
                                    }
                                    if(!isset($no_found)){
                                        return response()->json(['respuesta' => $flag]);
                                    } else {
                                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro los nombres en el documento']);
                                    }
                                } else {
                                    return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                                }
                            } else {
                                return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                            }
                        } else {
                            return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                        }
                    } else {
                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                    }
                    
                    break;

                // Boletas 
                /*case 'saves':
                    break;
                */
                /* Dicom */
                case 'dicom':
                    $client = new Client();
                    $flag = true;
                    $nombre_a_buscar_cliente = strtoupper($user->firstname).' '.strtoupper($user->lastname);
                    $arreglo_nombres = explode(" ", strtoupper($user->firstname));
                    $arreglo_apellidos = explode(" ", strtoupper($user->lastname));
                    $arreglo_busqueda = array_merge($arreglo_nombres, $arreglo_apellidos);
                    $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
                    if($res_s3->getBody()){
                        $json_s3 = json_decode($res_s3->getBody());
                        if(isset($json_s3->archivo)){
                            $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                            if($res_read->getBody()){
                                $stream = $res_read->getBody();
                                $contents = $stream->getContents(); 
                                if(!empty($contents)){
                                    foreach( $arreglo_busqueda as $elemento ){
                                        if(stripos($contents, $elemento)){
                                            $found = true;
                                        } else {
                                            $no_found = true;
                                        }
                                    }
                                    if(!isset($no_found)){
                                        $res_dicom = $client->get("https://dicomfaas.azurewebsites.net/api/dicom-getdicomocrpuntaje?code=2tiXaQyLYs2ZQ/2r0TwVYakAy9dDIqsz3fwlgQ13a7pyAcdL2eHOyA==&url={$ruta_publica_archivo}");
                                        if($res_dicom->getBody()){
                                            $json_dicom = json_decode($res_dicom->getBody());
                                            if($json_dicom->puntaje != ""){
                                                $db_file = $user->files()->where('name', $_POST['tipo'])->first();
                                                $db_file->factor = $json_dicom->puntaje;
                                                $db_file->verified_ocr = 1;
                                                $db_file->save();
                                                return response()->json(['respuesta' => $flag]);
                                            } else {
                                                return response()->json(['respuesta' => $flag, 'dicom' => 'No se encontro puntaje de dicom']);
                                            }
                                        } else {
                                            return response()->json(['respuesta' => $flag, 'dicom' => 'No se encontro dicom']);
                                        }
                                    } else {
                                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro los nombres en el documento']);
                                    }
                                } else {
                                    return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                                }
                            } else {
                                return response()->json(['respuesta' => $flag, 'liquido' => 'No se encontro contenido en el documento']);
                            }
                        } else {
                            return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                        }
                    } else {
                        return response()->json(['respuesta' => $flag, 'liquido' => 'No se logro subir el documento']);
                    }
                    break;
                default:
                    return response()->json(['respuesta' => true]);
            }
        } else {
            return abort(401, 'No estas autenticado');
        }
        
    }
    public function consumirServicioDesdePerfil(Request $request)
    {
        
        //dd($request);
        if( $user = \Auth::user() ){
            // Obtengo los archivos del request
            $files = $request->files->all();
            Log::info($request);
            // Recorro los archivos
            foreach ($files as $fileName => $file) {
                Log::info('Se está subiendo el siguiente archivo: '.$file);
                // Verifico si el usuario tiene en sus files un archivo de tipo $filename ( puede ser: id_front, id_back, afp, dicom, etc..)
                if( $db_file = $user->files()->where('name', $fileName)->first() ){
                    // obtengo la extension del archivo que subió el usuario en el input type="file"( puede ser: jpg, pdf, etc)
                    $extension_file = $request->ext;
                    $name_file = uniqid() .'_'.$fileName.'.'.$extension_file;
                    // creo una ruta publica para mover el archivo subido
                    $filePath = 'storage/ocr/' . $name_file;
                    if(!$filePath) return 'error en filePath';
                    // path to ~/tmp directory
                    //obtengo la ruta (temporal) del archivo en el request
                    $tempName = $_FILES[$fileName]['tmp_name'];
                    setlocale(LC_ALL,'en_US.UTF-8');
                    
                    // guardo el archivo en storage porque al mover el archivo desde ~/tmp to "uploads" directory pierdo el puntero
                    \Storage::disk('local')->putFileAs('users/'.$user->id.'/files/',$file,$fileName);
                    // if (!$request->verified) { $db_file->verified = $request->verified; }
                    $db_file->original_name = $name_file;
                    $db_file->path = 'users/'.$user->id.'/files/'.$fileName;
                    $db_file->save();
                    // move file from ~/tmp to "uploads" directory
                    if (!move_uploaded_file($tempName, $filePath)) {
                        // failure report
                        echo 'Problem saving file: '.$tempName;
                        die();
                    }
                    switch( $fileName ){
                        case 'id_front':
                            if( $user->files()->where('name', 'id_back')->first()->path ){
                                $resp = $this->validarRutAnverso($user, $db_file);
                                if( !$resp['success'] ){
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->save();
                                    return response([ 'id_front' => $resp]);
                                }
                                //dd('paseo esto');
                                $db_file_contrario = $user->files()->where('name', 'id_back')->first();
                                $resp2 = $this->validarRutReverso($user, $db_file_contrario);
                                if( !$resp2['success'] ){
                                    //$db_file_contrario->path = null;
                                    $db_file_contrario->verified_ocr = 0;
                                    $db_file_contrario->save();
                                    return response( [ 'id_back' => $resp2 ] );
                                } 
                                return response( [ 'id_back' => $resp2, 'id_front' => $resp ] );
                            }else{
                                $resp = $this->validarRutAnverso($user, $db_file);
                                if( !$resp['success'] ){
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->save();
                                    return response([
                                        'success' => true,
                                        'file' => $db_file,
                                        'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                                    ]);
                                }
                                return response( [ 'id_front' => $resp ] );  
                            }
                            
                            break;
                        case 'id_back':
                            
                            if( $user->files()->where('name', 'id_front')->first()->path ){
                                $resp = $this->validarRutReverso($user, $db_file);
                                if( !$resp['success'] ){
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->save();
                                    return response( [ 'id_back' => $resp ] );
                                }
                                
                                $db_file_contrario = $user->files()->where('name', 'id_front')->first();
                                $resp2 = $this->validarRutAnverso($user, $db_file_contrario);
                                if( !$resp2['success'] ){
                                    //$db_file_contrario->path = null;
                                    $db_file_contrario->verified_ocr = 0;
                                    $db_file_contrario->save();
                                    return response([ 'id_front' => $resp2]);
                                }
                                return response( [ 'id_back' => $resp, 'id_front' => $resp2 ] );
                            }else{
                                $resp = $this->validarRutReverso($user, $db_file);
                                if( !$resp['success'] ){
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->save();
                                    return response([
                                        'success' => true,
                                        'file' => $db_file,
                                        'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                                    ]);
                                }
                                return response( [ 'id_back' => $resp ] );
                                
                            }
                                 
                            break;

                        case 'dicom':

                            if( $user->files()->where('name', 'dicom')->first()->path ){
                                $resp = $this->validarDicom($user, $db_file);
                                if($resp['success']){
                                    $db_file->verified_ocr = 1;
                                    $db_file->verified = 1;
                                    $db_file->val_date = 1;
                                    $db_file->save();
                                    return response($resp);
                                } else {
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->verified = 0;
                                    $db_file->val_date = 0;
                                    $db_file->save();
                                    return response($resp);
                                }
                            }else{
                                return response([
                                    'success' => true,
                                    'file' => $db_file,
                                    'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                                ]);
                            }
                            
                            break;
                        case 'afp':
                            if( $user->files()->where('name', 'afp')->first()->path ){
                                $resp = $this->validarAfp($user, $db_file);
                                if($resp['success']){
                                    $db_file->verified_ocr = 1;
                                    $db_file->verified = 1;
                                    $db_file->val_date = 1;
                                    $db_file->save();
                                    return response($resp);
                                } else {
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->verified = 0;
                                    $db_file->val_date = 0;
                                    $db_file->save();
                                    return response($resp);
                                }
                            }else{
                                return response([
                                    'success' => true,
                                    'file' => $db_file,
                                    'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                                ]);
                            }
                            break;
                        case 'first_settlement':
                            if( $user->files()->where('name', 'first_settlement')->first()->path ){
                                $resp = $this->validarLiquidaciones($user, $db_file);
                                if($resp['success']){
                                    $db_file->verified_ocr = 1;
                                    $db_file->verified = 1;
                                    $db_file->val_date = 1;
                                    $db_file->save();
                                    return response($resp);
                                } else {
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->verified = 0;
                                    $db_file->val_date = 0;
                                    $db_file->save();
                                    return response($resp);
                                }
                            }else{
                                return response([
                                    'success' => true,
                                    'file' => $db_file,
                                    'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                                ]);
                            }
                            break;
                        case 'second_settlement':
                            if( $user->files()->where('name', 'second_settlement')->first()->path ){
                                $resp = $this->validarLiquidaciones($user, $db_file);
                                if($resp['success']){
                                    $db_file->verified_ocr = 1;
                                    $db_file->verified = 1;
                                    $db_file->val_date = 1;
                                    $db_file->save();
                                    return response($resp);
                                } else {
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->verified = 0;
                                    $db_file->val_date = 0;
                                    $db_file->save();
                                    return response($resp);
                                }
                            }else{
                                return response([
                                    'success' => true,
                                    'file' => $db_file,
                                    'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                                ]);
                            }
                            break;
                        case 'third_settlement':
                            if( $user->files()->where('name', 'third_settlement')->first()->path ){
                                $resp = $this->validarLiquidaciones($user, $db_file);
                                if($resp['success']){
                                    $db_file->verified_ocr = 1;
                                    $db_file->verified = 1;
                                    $db_file->val_date = 1;
                                    $db_file->save();
                                    return response($resp);
                                } else {
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->verified = 0;
                                    $db_file->val_date = 0;
                                    $db_file->save();
                                    return response($resp);
                                }
                            }else{
                                return response([
                                    'success' => true,
                                    'file' => $db_file,
                                    'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                                ]);
                            }
                            break;
                        case 'work_constancy':
                            if( $user->files()->where('name', 'work_constancy')->first()->path ){
                                $resp = $this->validarEmpleo($user, $db_file);
                                if($resp['success']){
                                    $db_file->verified_ocr = 1;
                                    $db_file->verified = 1;
                                    $db_file->val_date = 1;
                                    $db_file->save();
                                    return response($resp);
                                } else {
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->verified = 0;
                                    $db_file->val_date = 0;
                                    $db_file->save();
                                    return response($resp);
                                }
                            }else{
                                return response([
                                    'success' => true,
                                    'file' => $db_file,
                                    'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                                ]);
                            }
                            break;
                        case 'last_invoice':
                            if( $user->files()->where('name', 'last_invoice')->first()->path ){
                                $resp = $this->validarDoc($user, $db_file);
                                if($resp['success']){
                                    $db_file->verified_ocr = 1;
                                    $db_file->verified = 1;
                                    $db_file->val_date = 1;
                                    $db_file->save();
                                    return response($resp);
                                } else {
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->verified = 0;
                                    $db_file->val_date = 0;
                                    $db_file->save();
                                    return response($resp);
                                }
                            }else{
                                return response([
                                    'success' => true,
                                    'file' => $db_file,
                                    'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                                ]);
                            }
                            break;
                        case 'other_income':
                            if( $user->files()->where('name', 'other_income')->first()->path ){
                                $resp = $this->validarDoc($user, $db_file);
                                if($resp['success']){
                                    $db_file->verified_ocr = 1;
                                    $db_file->verified = 1;
                                    $db_file->val_date = 1;
                                    $db_file->save();
                                    return response($resp);
                                } else {
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->verified = 0;
                                    $db_file->val_date = 0;
                                    $db_file->save();
                                    return response($resp);
                                }
                            }else{
                                return response([
                                    'success' => true,
                                    'file' => $db_file,
                                    'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                                ]);
                            }
                            break;
                        case 'saves':
                            if( $user->files()->where('name', 'saves')->first()->path ){
                                $resp = $this->validarDoc($user, $db_file);
                                if($resp['success']){
                                    $db_file->verified_ocr = 1;
                                    $db_file->verified = 1;
                                    $db_file->val_date = 1;
                                    $db_file->save();
                                    return response($resp);
                                } else {
                                    //$db_file->path = null;
                                    $db_file->verified_ocr = 0;
                                    $db_file->verified = 0;
                                    $db_file->val_date = 0;
                                    $db_file->save();
                                    return response($resp);
                                }
                            }else{
                                return response([
                                    'success' => true,
                                    'file' => $db_file,
                                    'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                                ]);
                            }
                            break;
                        default:
                            $db_file->original_name = $file->getClientOriginalName();
                            $db_file->path = 'users/'.$user->id.'/files/'.$fileName;
                            $db_file->verified_ocr = 0;
                            $db_file->save();
                            return response([
                                'success' => true,
                                'file' => $db_file,
                                'mensaje' => 'El archivo se ha salvado satisfactoriamente.'
                            ]);
                    }
                }
            }
        } else {
            return abort(401, 'No estas autenticado');
        }
        
    }
    private function validarRutAnverso( $user, $db_file ){
        if (Storage::exists( 'public/ocr/'.$db_file->original_name)) {
            Storage::delete( 'public/ocr/'.$db_file->original_name);
        }
        Storage::copy($db_file->path, 'public/ocr/'.$db_file->original_name);
        // guardo la ruta relativa publica del archivo que necesitaré mas adelante
        $ruta_publica_archivo = env('APP_URL_NGROK', url('')).'/storage/ocr/'.$db_file->original_name;
        $client = new Client();
        $flag = true;
        $nombre_a_buscar_cliente = strtoupper($user->firstname).' '.strtoupper($user->lastname);
        $arreglo_nombres = explode(" ", strtoupper($user->firstname));
        $arreglo_apellidos = explode(" ", strtoupper($user->lastname));
        $arreglo_busqueda = array_merge($arreglo_nombres, $arreglo_apellidos);
        $arreglo_fecha_nacimiento = array();
        /*$arreglo_fecha_nacimiento[] =  explode('-',$user->birthdate)[2];
        $arreglo_fecha_nacimiento[] = explode('-',$user->birthdate)[0];
        $pre_mes = explode('-',$user->birthdate)[1];
        switch( $pre_mes ){
            case '01':
                $arreglo_fecha_nacimiento[] = 'ENE';
                break;
            case '02':
                $arreglo_fecha_nacimiento[] = 'FEB';
                break;
            case '03':
                $arreglo_fecha_nacimiento[] = 'MAR';
                break;
            case '04':
                $arreglo_fecha_nacimiento[] = 'ABR';
                break;
            case '05':
                $arreglo_fecha_nacimiento[] = 'MAY';
                break;
            case '06':
                $arreglo_fecha_nacimiento[] = 'JUN';
                break;
            case '07':
                $arreglo_fecha_nacimiento[] = 'JUL';
                break;
            case '08':
                $arreglo_fecha_nacimiento[] = 'AGO';
                break;
            case '09':
                $arreglo_fecha_nacimiento[] = 'SEP';
                break;
            case '10':
                $arreglo_fecha_nacimiento[] = 'OCT';
                break;
            case '11':
                $arreglo_fecha_nacimiento[] = 'NOV';
                break;
            case '12':
                $arreglo_fecha_nacimiento[] = 'DIC';
                break;
        }
        */
        $nacionalidad = array();
        $nacionalidad[] = substr(strtoupper($user->country()->first()->nationality), 0, 2  );
        $arreglo_busqueda = array_merge($arreglo_nombres, $arreglo_apellidos, $arreglo_fecha_nacimiento, $nacionalidad);
        
        foreach( $arreglo_busqueda as $elemento ){
            $res = $client->get("https://cbrsfaas.azurewebsites.net/api/cbrs-getcbrsotrodoc?code=YMxLOVMYt3ABteywTVZnFYDZOVak88Xm1nE5mVH5ItKltAmm2IFtkg==&url={$ruta_publica_archivo}&nombre={$elemento}");
            if ($res->getBody()) {
                $json = json_decode($res->getBody());
                if ( isset($json->BUSQUEDA) ){
                    //if( true ){
                    if( $json->BUSQUEDA == 'OK' ){
                        // no hacer nada, continuar con el foreach
                    } else {
                        //$db_file->path = null;
                        $db_file->verified_ocr = 0;
                        $db_file->save();
                        return [
                            'success' => false,
                            'file' => $db_file,
                            'razon' => 'no se encuentra el siguiente texto en documento (carnet anverso): '.$elemento
                        ];
                    }
                } else {
                    //$db_file->path = null;
                    $db_file->verified_ocr = 0;
                    $db_file->save();
                    return [
                        'success' => false,
                        'file' => $db_file,
                        'razon' => 'servicio no retorno contenido deseado'
                    ];
                }
            } else {
                $db_file->path = null;
                $db_file->verified_ocr = 0;
                $db_file->save();
                return [
                    'success' => false,
                    'file' => $db_file,
                    'razon' => 'error cliente'
                ];
            }
        }
        
        $res = $client->get("https://cbrsfaas.azurewebsites.net/api/cbrs-getdatoscarnet?code=1qTtadci307jZlMK0trooml/Keho9MvgmoQYYBLPWTiS/tfqTSWaXA==&url=".$ruta_publica_archivo);
        if ($res->getBody()) {
            $json = json_decode($res->getBody());
            if ( isset($json->ndoc) ){
                $numero_documento = $json->ndoc;
                if( strlen($numero_documento) == 9 && is_numeric($numero_documento) ){
                    $user->document_serie = $numero_documento;
                    $user->save();
                }else{
                    //$db_file->path = null;
                    if( $carnet_trasero = $user->files()->where('name', 'id_back')->first() ){
                        if (Storage::exists( 'public/ocr/'.$carnet_trasero->original_name)) {
                            Storage::delete( 'public/ocr/'.$carnet_trasero->original_name);
                        }
                        Storage::copy($carnet_trasero->path, 'public/ocr/'.$carnet_trasero->original_name);
                        // guardo la ruta relativa publica del archivo que necesitaré mas adelante
                        $ruta_publica_archivo = env('APP_URL_NGROK', url('')).'/storage/ocr/'.$carnet_trasero->original_name;
                        $respuesta2 = $client->get("https://cbrsfaas.azurewebsites.net/api/cbrs-getdatoscarnet?code=1qTtadci307jZlMK0trooml/Keho9MvgmoQYYBLPWTiS/tfqTSWaXA==&url=".$ruta_publica_archivo);
                        //dd("https://cbrsfaas.azurewebsites.net/api/cbrs-getdatoscarnet?code=1qTtadci307jZlMK0trooml/Keho9MvgmoQYYBLPWTiS/tfqTSWaXA==&url=".$ruta_publica_archivo);
                        $json2 = json_decode($respuesta2->getBody());
                        //dd( isset($json2->texto) );
                        if( isset($json2->texto) ){
                            //dd(strpos($json2->texto, 'NO DE SERIE: '));
                            if( ($pos_start = strpos($json2->texto, 'NO DE SERIE: ')) !== false ){
                                $pos_start+= 13;
                                $nro_serie = substr($json2->texto, $pos_start, 10);
                                $user->document_serie = $nro_serie;
                                $user->save();
                            }else{
                                $carnet_trasero->verified_ocr = 0;
                                $carnet_trasero->save();
                                return [
                                    'success' => false,
                                    'file' => $db_file,
                                    'razon' => 'Número de serie no encontrado - Code:NSNE1'
                                ];
                            }
                        }else{
                            $carnet_trasero->verified_ocr = 0;
                            $carnet_trasero->save();
                            return [
                                'success' => false,
                                'file' => $db_file,
                                'razon' => 'Número de serie no encontrado - Code:NSNE2'
                            ];
                        }
                        
                    }else{
                        $db_file->verified_ocr = 0;
                        $db_file->save();
                        return [
                            'success' => false,
                            'file' => $db_file,
                            'razon' => 'Carnet trasero no se ha cargado!'
                        ];
                    }
                }
            } else {
                $db_file->path = null;
                $db_file->verified_ocr = 0;
                $db_file->save();
                return [
                    'success' => false,
                    'file' => $db_file,
                    'razon' => 'Servicio no retorno contenido deseado'
                ];
            } 
        } else {
            $db_file->path = null;
            $db_file->verified_ocr = 0;
            $db_file->save();
            return [
                'success' => false,
                'file' => $db_file,
                'razon' => 'Error cliente'
            ];
        }
        $db_file->verified_ocr = 1;
        $db_file->save();
        //dd('paso esto');
        return [
            'success' => true,
            'file' => $db_file,
            'exito' => 'Carnet de identidad verificado correctamente'
        ];
    }

    private function validarRutReverso( $user, $db_file ){
        if(!$user->document_serie){
            return [
                'success' => false,
                'file' => $db_file,
                'razon' => 'Debes validar tu cédula de identidad por la parte frontal antes de validar la trasera'
            ];
        }

        if (Storage::exists( 'public/ocr/'.$db_file->original_name)) {
            Storage::delete( 'public/ocr/'.$db_file->original_name);
        }
        Storage::copy($db_file->path, 'public/ocr/'.$db_file->original_name);
        // guardo la ruta relativa publica del archivo que necesitaré mas adelante
        $ruta_publica_archivo = env('APP_URL_NGROK', url('')).'/storage/ocr/'.$db_file->original_name;
        $client = new Client();
        //$nombre_a_buscar = explode('-',str_replace('.','',$user->document_number) )[0];
        $nombre_a_buscar = $user->document_serie;
        $res = $client->get("https://cbrsfaas.azurewebsites.net/api/cbrs-getcbrsotrodoc?code=YMxLOVMYt3ABteywTVZnFYDZOVak88Xm1nE5mVH5ItKltAmm2IFtkg==&url={$ruta_publica_archivo}&nombre={$nombre_a_buscar}");
        if ($res->getBody()) {
            $json = json_decode($res->getBody());
            if ( isset($json->BUSQUEDA) ){
                //if (true){
                if( $json->BUSQUEDA == 'OK' ){
                    /**
                     * 
                     */
                } else {
                    /* TO_DO
                    if (Storage::exists( 'public/ocr/'.$db_file_rut_->original_name)) {
                        Storage::delete( 'public/ocr/'.$db_file->original_name);
                    }
                    Storage::copy($db_file->path, 'public/ocr/'.$db_file->original_name);
                    */
                    //dd("https://cbrsfaas.azurewebsites.net/api/cbrs-getcbrsotrodoc?code=YMxLOVMYt3ABteywTVZnFYDZOVak88Xm1nE5mVH5ItKltAmm2IFtkg==&url={$ruta_publica_archivo}&nombre={$nombre_a_buscar}");
                    //$db_file->path = null;
                    $db_file->verified_ocr = 0;
                    $db_file->save();
                    return [
                        'success' => false,
                        'file' => $db_file,
                        'razon' => 'no se encuentra el siguiente texto en el documento (carnet reverso): '.$nombre_a_buscar
                    ];
                }
            } else {
                //$db_file->path = null;
                $db_file->verified_ocr = 0;
                $db_file->save();
                return [
                    'success' => false,
                    'file' => $db_file,
                    'razon' => 'servicio no retorno contenido deseado'
                ];
            }
        } else {
            //$db_file->path = null;
            $db_file->verified_ocr = 0;
            $db_file->save();
            return [
                'success' => false,
                'file' => $db_file,
                'razon' => 'error cliente'
            ];
        }
        $rut = str_replace('.', '', $user->document_number);
        switch( $user->document_type ){
            case 'RUT':
                $res = $client->get("https://utilsfaas.azurewebsites.net/api/utils-getvigenciacedula?code=BRLTXeGjXIN9Xb170dIWgkog5Vkqz9yHGmva1m3tb0J78s9Y7Fo7qg==&rut={$rut}&tipo=CEDULA&serie={$user->document_serie}");
                if ($res->getBody()) {
                    $json = json_decode($res->getBody());
                    if ( isset($json->resultado) ){
                        if( $json->resultado == 'VIGENTE'){

                        } else {
                            //$db_file->path = null;
                            $db_file->verified_ocr = 0;
                            $db_file->save();
                            return [
                                'success' => false,
                                'file' => $db_file,
                                'razon' => 'Documento no vigente'
                            ];
                        }
                    }
                }
                break;
            case 'RUT_PROVISIONAL':
                $res = $client->get("https://utilsfaas.azurewebsites.net/api/utils-getvigenciacedula?code=BRLTXeGjXIN9Xb170dIWgkog5Vkqz9yHGmva1m3tb0J78s9Y7Fo7qg==&rut={$rut}&tipo=CEDULA_EXT&serie={$user->document_serie}");
                if ($res->getBody()) {
                    $json = json_decode($res->getBody());
                    if ( isset($json->resultado) ){
                        if( $json->resultado == 'VIGENTE'){

                        } else {
                            //$db_file->path = null;
                            $db_file->verified_ocr = 0;
                            $db_file->save();
                            return [
                                'success' => false,
                                'file' => $db_file,
                                'razon' => 'Documento no vigente'
                            ];
                        }
                    }
                }
                break;
            case 'PASSPORT':
                //$db_file->path = null;
                $db_file->verified_ocr = 0;
                $db_file->save();
                return [
                    'success' => false,
                    'file' => $db_file,
                    'razon' => 'Aún no validamos pasaportes :(. Porfavor comunicate con nosotros en contacto para darte una solución'
                ];
                break;
            default:
                //$db_file->path = null;
                $db_file->verified_ocr = 0;
                $db_file->save();
                return [
                    'success' => false,
                    'file' => $db_file,
                    'razon' => 'Aún no validamos este tipo de documento :(. Porfavor comunicate con nosotros en contacto para darte una solución'
                ];
                break;
        }
        
        $db_file->verified_ocr = 1;
        $db_file->verified = 1;
        $db_file->save();
        return [
            'success' => true,
            'file' => $db_file,
            'exito' => 'Carnet de identidad verificado correctamente'
        ];
    }
    public function validarDicom( $user, $db_file ){
        if (Storage::exists( 'public/ocr/'.$db_file->original_name)) {
            Storage::delete( 'public/ocr/'.$db_file->original_name);
        }
        Storage::copy($db_file->path, 'public/ocr/'.$db_file->original_name);
        $ruta_publica_archivo = env('APP_URL_NGROK', url('')).'/storage/ocr/'.$db_file->original_name;

        $client = new Client();

        $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
        if($res_s3->getBody()){
            $json_s3 = json_decode($res_s3->getBody());
            if(isset($json_s3->archivo)){
                $db_file->s3 = $json_s3->archivo;
                $db_file->save();
                $tmp_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/videoindexer-bkt2bkttmp?code=9IEU4626Il/neWw/nxt0Ou9mNtk2w3PErUoBD97YROOCa8P7gPIu7Q==&bucket=pdf&archivo={$json_s3->archivo}");
                $json_tmp_s3 = json_decode($tmp_s3->getBody());
                $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                if($res_read->getBody()){
                    $stream = $res_read->getBody();
                    $contents = $stream->getContents(); 
                    if(!empty($contents)){
                        if(stripos($contents, $user->document_number)){
                            if(stripos($contents, strtoupper($user->firstname . ' ' . $user->lastname))){
                                $res_year = $client->get("https://dicomfaas.azurewebsites.net/api/dicom-getdicomocrfechadoc?code=8KF6PpkmwgT0tzvQq7yPAqt9/lzXDLqemzxcAJzWZqgt8OI1xxm5SA==&url={$json_tmp_s3->s3tmp}");
                                if($res_year->getBody()){
                                    $json_year = json_decode($res_year->getBody());
                                    if(isset($json_year->fechadoc)){
                                        if(stripos($json_year->fechadoc, date('Y'))){
                                            $res_dicom = $client->get("https://dicomfaas.azurewebsites.net/api/dicom-getdicomocrpuntaje?code=2tiXaQyLYs2ZQ/2r0TwVYakAy9dDIqsz3fwlgQ13a7pyAcdL2eHOyA==&url={$json_tmp_s3->s3tmp}");
                                            if($res_dicom->getBody()){
                                                $json_dicom = json_decode($res_dicom->getBody());
                                                if(isset($json_dicom->puntaje)){
                                                    $db_file->factor = $json_dicom->puntaje;
                                                    $db_file->verified_ocr = 1;
                                                    $db_file->verified = 1;
                                                    $db_file->val_date = 1;
                                                    $db_file->s3 = $json_s3->archivo;
                                                    $db_file->save();
                                                    return [
                                                        'success' => true,
                                                        'file' => $db_file,
                                                        'exito' => 'Se ha cargado y validado el documento exitosamente'
                                                    ];
                                                } else {
                                                    return [
                                                        'success' => false,
                                                        'file' => $db_file,
                                                        'razon' => 'No se encontro puntaje de dicom'
                                                    ];
                                                }
                                            } else {
                                                return [
                                                    'success' => false,
                                                    'file' => $db_file,
                                                    'razon' => 'error cliente'
                                                ];
                                            }
                                        } else {
                                            return [
                                                'success' => false,
                                                'file' => $db_file,
                                                'razon' => 'El archivo dicom no esta vigente'
                                            ];
                                        }
                                    } else {
                                        return [
                                            'success' => false,
                                            'file' => $db_file,
                                            'razon' => 'No se encontro fecha de vigencia del dicom'
                                        ];
                                    }
                                } else {
                                    return [
                                        'success' => false,
                                        'file' => $db_file,
                                        'razon' => 'error cliente'
                                    ];
                                }
                            } else {
                                return [
                                    'success' => false,
                                    'file' => $db_file,
                                    'razon' => 'No se encontro el nombre el titular en el documento'
                                ];
                            }
                        } else {
                            return [
                                'success' => false,
                                'file' => $db_file,
                                'razon' => 'No encontro identificacion'
                            ];
                        }
                    } else {
                        return [
                            'success' => false,
                            'file' => $db_file,
                            'razon' => 'No hay texto'
                        ];
                    }
                } else {
                    return [
                        'success' => false,
                        'file' => $db_file,
                        'razon' => 'No hay elementos'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'file' => $db_file,
                    'razon' => 'No se pudo cargar el archivo vuelve intentarlo'
                ];
            }
        } else {
            return [
                'success' => false,
                'file' => $db_file,
                'razon' => 'No ingreso al S3'
            ];
        }
    }

    public function validarAfp( $user, $db_file ){
        if (Storage::exists( 'public/ocr/'.$db_file->original_name)) {
            Storage::delete( 'public/ocr/'.$db_file->original_name);
        }
        Storage::copy($db_file->path, 'public/ocr/'.$db_file->original_name);
        $ruta_publica_archivo = env('APP_URL_NGROK', url('')).'/storage/ocr/'.$db_file->original_name;

        $client = new Client();
        $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
        if($res_s3->getBody()){
            $json_s3 = json_decode($res_s3->getBody());
            if(isset($json_s3->archivo)){
                $db_file->s3 = $json_s3->archivo;
                $db_file->save();
                $tmp_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/videoindexer-bkt2bkttmp?code=9IEU4626Il/neWw/nxt0Ou9mNtk2w3PErUoBD97YROOCa8P7gPIu7Q==&bucket=pdf&archivo={$json_s3->archivo}");
                $json_tmp_s3 = json_decode($tmp_s3->getBody());
                $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                if($res_read->getBody()){
                    $stream = $res_read->getBody();
                    $contents = $stream->getContents(); 
                    if(!empty($contents)){
                        if(stripos($contents, $user->document_number)){
                            if(stripos($contents, strtoupper($user->firstname . ' ' . $user->lastname))){
                                $res_dir_file_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Bkt2Bkttmp?code=9IEU4626Il/neWw/nxt0Ou9mNtk2w3PErUoBD97YROOCa8P7gPIu7Q==&bucket=pdf&archivo={$json_s3->archivo}");
                                if($res_dir_file_s3->getBody()){
                                    $dir_s3 = json_decode($res_dir_file_s3->getBody());
                                    if(isset($dir_s3->s3tmp)){
                                        $res_quotes = $client->get("https://previsionfaas.azurewebsites.net/api/PREVISION-getdata?code=6h526NBMurZFImg7yyZBa57dwKKpZldPB6mZoABN5XJamaVov257QA==&url={$dir_s3->s3tmp}");
                                        if($res_quotes->getBody()){
                                            $json_quotes = json_decode($res_quotes->getBody());
                                            $monto = 0;
                                            if($json_quotes->datos){
                                                foreach ($json_quotes->datos as $quote) {
                                                    $monto += $quote->monto;
                                                }
                                                $db_file->factor = $monto;
                                                $db_file->verified_ocr = 1;
                                                $db_file->verified = 1;
                                                $db_file->val_date = 1;
                                                $db_file->save();
                                                return [
                                                    'success' => true,
                                                    'file' => $db_file,
                                                    'exito' => 'Se ha cargado y validado el documento exitosamente'
                                                ];
                                            } else {
                                                return [
                                                    'success' => false,
                                                    'file' => $db_file,
                                                    'razon' => 'No se encontro montos de cotizacion en el documento'
                                                ];
                                            }
                                        } else {
                                            return [
                                                'success' => false,
                                                'file' => $db_file,
                                                'razon' => 'No se encontro montos de cotizacion en el documento'
                                            ];
                                        }
                                    } else {
                                        return [
                                            'success' => false,
                                            'file' => $db_file,
                                            'razon' => 'No se encontro el archivo en el s3'
                                        ];
                                    }
                                } else {
                                    return [
                                        'success' => false,
                                        'file' => $db_file,
                                        'razon' => 'No se encontro el archivo en el s3'
                                    ];
                                }
                            } else {
                                return [
                                    'success' => false,
                                    'file' => $db_file,
                                    'razon' => 'No se encontro el nombre el titular en el documento'
                                ];
                            }
                        } else {
                            return [
                                'success' => false,
                                'file' => $db_file,
                                'razon' => 'No encontro identificacion'
                            ];
                        }
                    } else {
                        return [
                            'success' => false,
                            'file' => $db_file,
                            'razon' => 'No hay texto'
                        ];
                    }
                } else {
                    return [
                        'success' => false,
                        'file' => $db_file,
                        'razon' => 'No hay elementos q leer'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'file' => $db_file,
                    'razon' => 'No se pudo cargar el archivo vuelve intentarlo'
                ];
            }
        } else {
            return [
                'success' => false,
                'file' => $db_file,
                'razon' => 'No ingreso al S3'
            ];
        }
    }
    public function validarLiquidaciones( $user, $db_file ){
        if (Storage::exists( 'public/ocr/'.$db_file->original_name)) {
            Storage::delete( 'public/ocr/'.$db_file->original_name);
        }
        Storage::copy($db_file->path, 'public/ocr/'.$db_file->original_name);
        $ruta_publica_archivo = env('APP_URL_NGROK', url('')).'/storage/ocr/'.$db_file->original_name;

        $client = new Client();

        $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
        if($res_s3->getBody()){
            $json_s3 = json_decode($res_s3->getBody());
            if(isset($json_s3->archivo)){
                $db_file->s3 = $json_s3->archivo;
                $db_file->save();
                $tmp_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/videoindexer-bkt2bkttmp?code=9IEU4626Il/neWw/nxt0Ou9mNtk2w3PErUoBD97YROOCa8P7gPIu7Q==&bucket=pdf&archivo={$json_s3->archivo}");
                $json_tmp_s3 = json_decode($tmp_s3->getBody());
                $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                if($res_read->getBody()){
                    $stream = $res_read->getBody();
                    $contents = $stream->getContents();
                    if(!empty($contents)){
                        $arreglo_identificacion = explode("-", strtoupper($user->document_number));
                        if(stripos($contents, $user->document_number) || $arreglo_identificacion[0]){
                            $arreglo_nombres = explode(" ", $user->firstname);
                            $arreglo_apellidos = explode(" ", $user->lastname);
                            $arreglo_busqueda = array_merge($arreglo_nombres, $arreglo_apellidos);
                            foreach( $arreglo_busqueda as $elemento ){
                                if(stripos($contents, $elemento)){
                                    $found = true;
                                } else {
                                    $no_found = true;
                                }
                            }
                            if(!isset($no_found)){
                                // Liquido anterior $res_liquid = $client->get("https://liquidacionfaas.azurewebsites.net/api/liquidacion-getliquidopdf?code=L5WN6bXXtYaAK5Y0dqC9wY6E4Z0AVIin6vftMGUNon9ygpir4FIGIA==&url={$json_tmp_s3->s3tmp}&pag=1");
                                $res_liquid= $client->post("https://liquidacionfaas.azurewebsites.net/api/liquidacion-getliquidotext?code=TthKr4HLo3nhZJKFCOAlvaGRxQ9UGdBRD7o3kcaKSsHsKyIF5IIVDw==", ["body" => $contents]);
                                if($res_liquid->getBody()){
                                    $json_liquid = json_decode($res_liquid->getBody());
                                    if(isset($json_liquid->liquido)){
                                        $db_file->factor = $json_liquid->liquido;
                                        $db_file->verified_ocr = 1;
                                        $db_file->verified = 1;
                                        $db_file->val_date = 1;
                                        $db_file->s3 = $json_s3->archivo;
                                        $db_file->save();
                                        return [
                                            'success' => true,
                                            'file' => $db_file,
                                            'exito' => 'Se ha cargado y validado el documento exitosamente'
                                        ];
                                    } else {
                                        return [
                                            'success' => false,
                                            'file' => $db_file,
                                            'razon' => 'No de encontro sueldo liquido'
                                        ];
                                    }
                                } else {
                                    return [
                                        'success' => false,
                                        'file' => $db_file,
                                        'razon' => 'No se encontro sueldo liquido en el documento'
                                    ];
                                }
                            } else {
                                return [
                                    'success' => false,
                                    'file' => $db_file,
                                    'razon' => 'No se encontro el nombre el titular en el documento'
                                ];
                            }
                        } else {
                            return [
                                'success' => false,
                                'file' => $db_file,
                                'razon' => 'No encontro identificacion'
                            ];
                        }
                    } else {
                        return [
                            'success' => false,
                            'file' => $db_file,
                            'razon' => 'No hay texto'
                        ];
                    }
                } else {
                    return [
                        'success' => false,
                        'file' => $db_file,
                        'razon' => 'No hay elementos q leer'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'file' => $db_file,
                    'razon' => 'No se pudo cargar el archivo vuelve intentarlo'
                ];
            }
        } else {
            return [
                'success' => false,
                'file' => $db_file,
                'razon' => 'No ingreso al S3'
            ];
        }
    }
    public function validarEmpleo( $user, $db_file ){
        if (Storage::exists( 'public/ocr/'.$db_file->original_name)) {
            Storage::delete( 'public/ocr/'.$db_file->original_name);
        }
        Storage::copy($db_file->path, 'public/ocr/'.$db_file->original_name);
        $ruta_publica_archivo = env('APP_URL_NGROK', url('')).'/storage/ocr/'.$db_file->original_name;

        $client = new Client();

        $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
        if($res_s3->getBody()){
            $json_s3 = json_decode($res_s3->getBody());
            if(isset($json_s3->archivo)){
                $db_file->s3 = $json_s3->archivo;
                $db_file->save();
                $tmp_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/videoindexer-bkt2bkttmp?code=9IEU4626Il/neWw/nxt0Ou9mNtk2w3PErUoBD97YROOCa8P7gPIu7Q==&bucket=pdf&archivo={$json_s3->archivo}");
                $json_tmp_s3 = json_decode($tmp_s3->getBody());
                $db_file->s3 = $json_s3->archivo;
                $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                if($res_read->getBody()){
                    $stream = $res_read->getBody();
                    $contents = $stream->getContents(); 
                    if(!empty($contents)){
                        $document_number = str_replace(".", "", $user->document_number);
                        $document_explode = explode('-',$document_number);
                        if(stripos($contents, $user->document_number) || stripos($contents,$document_number) || stripos($contents,$document_explode[0])){
                            if(stripos($contents, strtoupper($user->firstname . ' ' . $user->lastname))){
                                $company = explode(' ',$user->company);
                                if(stripos($contents, $company[0])){
                                    $db_file->verified_ocr = 1;
                                    $db_file->verified = 1;
                                    $db_file->val_date = 1;
                                    $db_file->s3 = $json_s3->archivo;
                                    $db_file->save();
                                    return [
                                        'success' => true,
                                        'file' => $db_file,
                                        'exito' => 'Se ha cargado y validado el documento exitosamente'
                                    ];
                                } else {
                                    return [
                                        'success' => false,
                                        'file' => $db_file,
                                        'razon' => 'No se encontro el nombre del empleo en el archivo'
                                    ];
                                }
                                
                            } else {
                                return [
                                    'success' => false,
                                    'file' => $db_file,
                                    'razon' => 'No se encontro el nombre el titular en el documento'
                                ];
                            }
                        } else {
                            return [
                                'success' => false,
                                'file' => $db_file,
                                'razon' => 'No encontro identificacion'
                            ];
                        }
                    } else {
                        return [
                            'success' => false,
                            'file' => $db_file,
                            'razon' => 'No hay texto'
                        ];
                    }
                } else {
                    return [
                        'success' => false,
                        'file' => $db_file,
                        'razon' => 'No hay elementos q leer'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'file' => $db_file,
                    'razon' => 'No se pudo cargar el archivo vuelve intentarlo'
                ];
            }
        } else {
            return [
                'success' => false,
                'file' => $db_file,
                'razon' => 'No ingreso al S3'
            ];
        }
    }

    public function validarDoc( $user, $db_file ){
        if (Storage::exists( 'public/ocr/'.$db_file->original_name)) {
            Storage::delete( 'public/ocr/'.$db_file->original_name);
        }
        Storage::copy($db_file->path, 'public/ocr/'.$db_file->original_name);
        $ruta_publica_archivo = env('APP_URL_NGROK', url('')).'/storage/ocr/'.$db_file->original_name;

        $client = new Client();

        switch ($db_file->name) {
            case 'saves':
                $amount_search = number_format($user->save_amount, 0, ',', '.');
                break;

            case 'other_income':
                $amount_search = number_format($user->other_income_amount, 0, ',', '.');
                break;

            case 'last_invoice':
                $amount_search = number_format($user->last_invoice_amount, 0, ',', '.');
                break;
            
            default:
                
                break;
        }

        $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
        if($res_s3->getBody()){
            $json_s3 = json_decode($res_s3->getBody());
            if(isset($json_s3->archivo)){
                $db_file->s3 = $json_s3->archivo;
                $db_file->save();
                $tmp_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/videoindexer-bkt2bkttmp?code=9IEU4626Il/neWw/nxt0Ou9mNtk2w3PErUoBD97YROOCa8P7gPIu7Q==&bucket=pdf&archivo={$json_s3->archivo}");
                $json_tmp_s3 = json_decode($tmp_s3->getBody());
                $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                if($res_read->getBody()){
                    $stream = $res_read->getBody();
                    $contents = $stream->getContents(); 
                    if(!empty($contents)){
                        if(stripos($contents, $user->document_number)){
                            $arreglo_nombres = explode(" ", strtoupper($user->firstname));
                            $arreglo_apellidos = explode(" ", strtoupper($user->lastname));
                            $arreglo_busqueda = array_merge($arreglo_nombres, $arreglo_apellidos, $amount_search);
                            foreach( $arreglo_busqueda as $elemento ){
                                if(stripos($contents, $elemento)){
                                    $found = true;
                                } else {
                                    $no_found = true;
                                }
                            }
                            if(!isset($no_found)){
                                
                                $db_file->verified_ocr = 1;
                                $db_file->verified = 1;
                                $db_file->val_date = 1;
                                $db_file->s3 = $json_s3->archivo;
                                $db_file->factor = intval(str_replace(".", "", $amount_search));
                                $db_file->save();
                                return [
                                    'success' => true,
                                    'file' => $db_file,
                                    'exito' => 'Se ha cargado y validado el documento exitosamente'
                                ];
                            } else {
                                return [
                                    'success' => false,
                                    'file' => $db_file,
                                    'razon' => 'No se encontro el nombre el titular en el documento'
                                ];
                            }
                        } else {
                            return [
                                'success' => false,
                                'file' => $db_file,
                                'razon' => 'No encontro identificacion'
                            ];
                        }
                    } else {
                        return [
                            'success' => false,
                            'file' => $db_file,
                            'razon' => 'No hay texto'
                        ];
                    }
                } else {
                    return [
                        'success' => false,
                        'file' => $db_file,
                        'razon' => 'No hay elementos q leer'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'file' => $db_file,
                    'razon' => 'No se pudo cargar el archivo vuelve intentarlo'
                ];
            }
        } else {
            return [
                'success' => false,
                'file' => $db_file,
                'razon' => 'No ingreso al S3'
            ];
        }
    }
    public function test(){
        $client = new Client();
        $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9eyJmb28iOiJ1aG9taWUiLCJpYXQiOjE1Njc3MjMxMDl9xHGb3Vu-ZvFca2WYoogFW9Lu0PH-Ve8jVde12e7W6rE.pdf");
        if($res_read->getBody()){
            $stream = $res_read->getBody();
            $contents = $stream->getContents();
            $res_liquid= $client->post("https://liquidacionfaas.azurewebsites.net/api/liquidacion-getliquidotext?code=TthKr4HLo3nhZJKFCOAlvaGRxQ9UGdBRD7o3kcaKSsHsKyIF5IIVDw==", ["body" => $contents]);
            $json_liquid = json_decode($res_liquid->getBody());
            if(isset($json_liquid->liquido)){
                return $json_liquid->liquido;
            } else {
                return 'No encontro liquido';
            }
        } else {
            return 'No encontro cuerpo';
        }
    }
}
