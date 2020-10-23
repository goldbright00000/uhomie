<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;

class PhotoController extends Controller
{
    public function getPropertyPhotos(Request $request){
		return response(['cover' => Photo::where(['property_id' => $request->property_id, 'cover' => true])->first(),
						'photos' => Photo::where(['property_id' => $request->property_id, 'cover' => false])->get()]);
	}
}
