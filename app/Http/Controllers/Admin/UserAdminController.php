<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\{Useradmin, File};
use Authy\AuthyApi;
use App\Notifications\{MailVerification, PhoneVerification};
use \Exception;
use App\Http\Controllers\Controller;
use App\Library\Services\OneTouch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Image;

class UserAdminController extends Controller
{


	public function getUsers( Request $request ){
    $db_users = Useradmin::select('id', 'name', 'email', 'role')
                      ->get();
    return response([ 'records' => $db_users ]);
	}

	public function create( Request $request ){


		if(!Useradmin::where('email', '=', $request->email)->first()) {
      $user = new Useradmin();
      $user->name = $request->name;
      $user->email = $request->email;
      $user->password = bcrypt($request->password);
      $user->role = $request->role ? $request->role : 2;
      $user->save();
      
  		return response( [ 'operation' => true ]);
  	} else {
  		return response( [ 'operation' => false, 'message' => 'El mail ya existe' ]);
  	}
  }

  public function update( Request $request ) {
		$user = Useradmin::find($request->userId);
    $user->name = $request->name;
    if( $user->email != $request->email ) {
    	//If different mail
    	if(!Useradmin::where('email', '=', $request->email)->first()) {
	      $user->email = $request->email;
	  	} else {
	  		return response( [ 'operation' => false, 'message' => 'El mail ya existe' ]);
	  	}
    }

    $user->address = $request->address;
    $user->phone = $request->phone;

    if(isset($request->password)) {
    	$user->password = bcrypt($request->password);
		}
    $user->role = $request->role;
    $user->save();

    return response([ "operation" => true ]);
	}

  public function getUser( Request $request ){
		$user = Useradmin::where([ "id" => $request->userId ])
                  ->first();
    return response([ "user" => $user ]);
	}
  
  public function delete( Request $request ){
		$user = Useradmin::find($request->userId);
    $user->delete();
    return response([ "operation" => true ]);
  }
  
  public function saveAvatarPhoto(Request $request){
    //dd($request->all());
    if ($request->hasFile('file')) {
        $user = Useradmin::find($request->userId);
        if (Storage::exists(str_replace('storage', 'public',$user->photo))){
            Storage::delete(str_replace('storage', 'public',$user->photo));
        }
        $originalImage= $request->file('file');
        $thumbnailImage = Image::make($originalImage);
        $originalPath = public_path().'/storage/avatars/';
        $width = $thumbnailImage->width();
        $height = $thumbnailImage->height();
        if($width > $height){
            $thumbnailImage->resize(null,500, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $thumbnailImage->resize(500,null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $thumbnailImage->crop(500,500);
        $thumbnailImage->save($originalPath.time().$thumbnailImage->filename.'.jpg');
        $photo_dir = '/storage/avatars/'.$thumbnailImage->basename;
        $user->photo = $photo_dir;
        $user->save();
        return response()->json(['code' => 200, 'url' => $user->photo, 'description' => 'Image Updated']);
    } else {
        return response()->json(['code'=>400, 'description' => 'File Not Found']);
    }
  }
}
