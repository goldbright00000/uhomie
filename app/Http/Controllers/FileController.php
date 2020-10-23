<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{File, User};
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function downloadFile(Request $request){
        $file = File::find($request->path);
	   	return response()->download(storage_path("app/".$file->path), $file->original_name);
    }
    public function viewFileS3(Request $request){
        $file = File::find($request->id);
        $client = new Client();

        if($file->s3 == null){

            if (Storage::exists( 'public/ocr/'.$file->original_name)) {
                Storage::delete( 'public/ocr/'.$file->original_name);
            }
            Storage::copy($file->path, 'public/ocr/'.$file->original_name);

            $ruta_publica_archivo = env('APP_URL_NGROK', url('')).'/storage/ocr/'.$file->original_name;

            $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$ruta_publica_archivo}&bucket=pdf&extension=pdf");
            $json_s3 = json_decode($res_s3->getBody());
            $file->s3 = $json_s3->archivo;
            $file->save();
        }
        $tmp_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/videoindexer-bkt2bkttmp?code=9IEU4626Il/neWw/nxt0Ou9mNtk2w3PErUoBD97YROOCa8P7gPIu7Q==&bucket=pdf&archivo={$file->s3}");
        $json_tmp_s3 = json_decode($tmp_s3->getBody());
        return response(['s3' => $json_tmp_s3->s3tmp], 200);

    }
}
