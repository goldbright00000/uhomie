<?php

namespace App\Http\Controllers;

use App\Conversation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
    	return Conversation::where('user_id', auth()->id())
    		->get([
    			'id',
				'contact_id',
				'has_blocked',
				'listen_notifications',
				'last_message',
				'last_time'
    		]);
		
		// contact_name
    }

	public function createChat(Request $request) {
    	$user_id = auth()->user()->id;
    	$contact_id = $request->contact_id;

        if($user_id != $contact_id){
    	   $url = '/users/profile/'.auth()->user()->getOnceRoleAttribute().'#/messages';
        	if(!Conversation::where('user_id', $user_id)->where('contact_id', $contact_id)->first()){
        		Conversation::create([
                    'user_id' => $user_id,
                    'contact_id' => $contact_id,
                    'last_message' => null,
                    'last_time' => Carbon::now()
                ]);
        	}
        	if(!Conversation::where('user_id', $contact_id)->where('contact_id', $user_id)->first()){
        		Conversation::create([
                    'user_id' => $contact_id,
                    'contact_id' => $user_id,
                    'last_message' => null,
                    'last_time' => Carbon::now()
                ]);
        	}

    		return redirect($url);
        }

    }
}
