<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use App\Provider;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Session;

class SocialiteController extends Controller
{
    public function redirectToProvider(Request $request, $provider)
    {
        
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, $provider)
    {
        $getInfo = Socialite::driver($provider)->stateless()->user();
        $provider = Provider::where('name', $provider)->first();
        /**
         * Modo Vincular Cuenta
         */
        if( $url_retorno = session('returnUrl') ){
            if( $user = Auth::user() ){
                $user->providers()->attach($provider->id, ['user_provider_id' => $getInfo->id]);
            }
            /**
             * Eliminar de la session -> returnUrl
             */
            $request->session()->forget('returnUrl');
            return redirect($url_retorno);
        }
        /**
         * Modo Login
         */
        $query = DB::table('user_provider')->where('user_provider_id', $getInfo->id)->where('provider_id', $provider->id)->select('user_id')->first();
        if( $query ){
            $user = \App\User::find($query->user_id);
            Auth::login($user);
            Auth::guard()->login($user);
            return redirect()->route('start');
        } else {
            if( isset($getInfo->email) ){
                $email = $getInfo->email;
            }else{
                $email = '';
            }
            try{
                $nombre = explode(' ', $getInfo->name)[0];
                $apellido = explode(' ', $getInfo->name)[1];
            } catch(\Exception $e){
                $nombre = '';
                $apellido = '';
            }
            return redirect()->route('start')->with('register', true)
                                            ->with('email', $email )
                                            ->with('firstname', $nombre )
                                            ->with('lastname', $apellido )
                                            ->with('provider', $provider->name)
                                            ->with('provider_id', $getInfo->id);
        }
    }
    public function toolkitFacebook(Request $request)
    {
        //dd($request);

        // Initialize variables
        $app_id = env('FACEBOOK_CLIENT_ID');
        $secret = env('TOOLKIT_SECRET');
        $version = 'v1.1'; // 'v1.1' for example

        // Exchange authorization code for access token
        $token_exchange_url = 'https://graph.accountkit.com/'.$version.'/access_token?'.
        'grant_type=authorization_code'.
        '&code='.$request->code.
        "&access_token=AA|$app_id|$secret";

        $data = $this->doCurl($token_exchange_url);
        $user_id = $data['id'];
        $user_access_token = $data['access_token'];
        $refresh_interval = $data['token_refresh_interval_sec'];

        /*
        $fb = new \Facebook\Facebook([
            'app_id' => $app_id,
            'app_secret' => $secret,
            'default_graph_version' => 'v2.10',
            //'default_access_token' => '{access-token}', // optional
          ]);
        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $fb->get('/me', $accessToken );
            dd($response);
          } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
        dd('nonon');
        */
        // Get Account Kit information
        $appsecret_proof= hash_hmac('sha256', $user_access_token, $secret); 
        $me_endpoint_url = 'https://graph.accountkit.com/'.$version.'/me?'.
        'access_token='.$user_access_token;
        //'access_token='.$user_access_token.
        //'appsecret_proof='.$appsecret_proof;
        $data_account = $this->doCurl($me_endpoint_url);
        $phone = isset($data_account['phone']) ? $data_account['phone']['number'] : '';
        $email = isset($data_account['email']) ? $data_account['email']['address'] : '';
        //dd($data_account);
        $user = User::where('phone', $data_account['phone']['national_number'])->where('phone_code', $data_account['phone']['country_prefix'])->first();
        if( $user ){
            Auth::login($user);
            Auth::guard()->login($user);
            return redirect()->route('start');
        }else{
            Session::flash('error_toolkit', "Numero ingresado no existe");
            return redirect()->route('start');
        }
    }
    // Method to send Get request to url
    public function doCurl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $data;
    }
}
