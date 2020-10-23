<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Membership;
use Carbon\Carbon;
use App\Property;
use App\ApplyProperty;
use App\PayPalClient;
use App\Coupon;
use App\UserCoupon;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use Illuminate\Support\Facades\Response;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use GuzzleHttp\Client;
use App\Contract;
use App\Notifications\PayRentOwner as PayRentOwner;
use App\Notifications\PayRentTenant as PayRentTenant;
use App\Notifications\PayRentStayOwner as PayRentStayOwner;
use App\Notifications\PayRentStayTenant as PayRentStayTenant;
use App\Notifications\PayRentCollateral as PayRentCollateral;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    private function getUf(){
        $client = new Client();
        $res = $client->get("https://api.sbif.cl/api-sbifv3/recursos_api/uf?apikey=dc6586648e523399e25aa87059f0de7e4c5eac29&formato=json");
        $json = json_decode($res->getBody());
        $valor = $json->UFs[0];
        $valor = $valor->Valor;
        $valor = str_replace(".", "",$valor);
        $valor = str_replace(",", ".",$valor);
        return $valor;
    }
    public function handleMembershipPayment(Request $request){
        //dd($request->cupon);
        $user = \Auth::user();
		$membership = Membership::find($request->membership);
        if (!$membership) abort(404, 'no membership found');

        $amount = str_replace(".", "",$membership->getFeatures()->package_amount);
        $amount = str_replace(",", ".",$amount);
        /**
         * Validar el cupon
         */
        if( isset($request->cupon) ){
            //Servicio de cupon de rodrigo
            //$client = new Client();
            //$res = $client->get("https://utilsfaas.azurewebsites.net/api/utils-getstatuscoupon?code=XuO94XtMz3Du4f6kmNBfHRh/COSkv3R8aV3mnriSBlg7EMbjYwcKkA==&coupon={$request->cupon}");
            /*if( $res->getBody() ){
                if( $json = json_decode($res->getBody())){
                    if( $json->OK === "true" ){
                        //Gastar el cupon
                        $client->get("https://utilsfaas.azurewebsites.net/api/utils-killcoupon?code=ZMpaNHYh/Np3pjxgDF209Sl2htlGf7O8Uk2njpn2IdDZG08Pijo6fw==&coupon={$request->cupon}");
                    }
                }

            */
            
            $user = \Auth::user();
            $coupon = Coupon::where('code', $request->cupon)->where('membership_id', $request->membership)->first();
            $usage = UserCoupon::where('user_id', $user->id)->where('coupon_id', $coupon->id)->first();
            
            if(is_null($usage)){
                $ticket = new UserCoupon;
                $ticket->user_id = $user->id;
                $ticket->coupon_id = $coupon->id;
                $ticket->save();
            }
            $amount = 0;
            $payment = Payment::create([
                "order" => Payment::generateOrder(),
                "user_id" => \Auth::user()->id,
                "status" => Payment::STATUS_APPROVED,
                "amount" => $amount,
                "iva" => env("IVA"),
                "total" => 0,
                "method" => 'cupon',
                "currency" => env("CURRENCY_ID"),
                "exchange_rate" => 1,
                "details" => "Pago de Membresia uHomie tipo: ". $membership->name . " para " . $membership->role->name.' con cupon: '.$request->cupon,
                "token" => $request->_token,
                "cupon" => $request->cupon
            ]);
            Membership::attachMembership($membership->id, \Auth::user());
            return redirect($request->success_redir.$payment->order);
        }
        if ($amount <= 0) { // Para el caso cuando el usuario selecciona una membresia basic o con el cupon quedo en 0
            Membership::attachMembership($membership->id, \Auth::user());
            return redirect($request->success_redir);
        }
        $membership = Membership::where(['id' => $request->membership])->first();
        switch((int)$membership->role_id){
            case 3:
                $total = (float)(($amount * $this->getUf()) * env("IVA")/100 + ($amount * $this->getUf()));
                break;
            default:
                $total = (float)($amount * env("IVA")/100 + $amount);
                break;
        }
        $order = Payment::generateOrder();
        $details = "Pago de Membresia uHomie tipo: ". $membership->name . " para " . $membership->role->name;

		if ($request->payment_method == 'paypal') {
            return back();
		}elseif($request->payment_method == "transbank") {
            
            $payment = Payment::create([
                "order" => $order,
                "user_id" => \Auth::user()->id,
                "status" => Payment::STATUS_CREATED,
                "amount" => $amount,
                "iva" => env("IVA"),
                "total" => (int)$total,
                "method" => $request->payment_method,
                "currency" => env("CURRENCY_ID"),
                "exchange_rate" => 1,
                "details" => $details,
                "token" => $request->_token
            ]);
            return $payment->processToCheckoutMembershipTB($membership->id, $request->success_redir, $request->back_url, $request->input('_token'));
		}else {
			abort(500);
		}
	}

    public function membershipsTransbankCallBack(Request $request, $payment_order, $membership_id){
        $payment = Payment::findByOrder($payment_order);
        
        $response = $payment->getTbResponse($request->input("token_ws"));
        /**
         * $token_csrf = $response->sessionId; // Usar si molesta el VerifyCsrfToken, nota: desactivandolo en la url correspodiente en Middleware\VerifyCsrfToken
         */
        if ( $response->detailOutput->responseCode == 0) {
            Membership::attachMembership($membership_id, $payment->user);
            $payment->status = Payment::STATUS_APPROVED;
            $payment->token_ws = $request->token_ws;
        }
        else {
            $payment->status = Payment::STATUS_REJECTED;
            $payment->token_ws = $request->token_ws;
            // dd("Pago NO PROCESADO! <<<< TODO: Handlers <<<<");
            // abort(404);
        }
        $payment->save();
        return $payment->redirectToVoucherTB($response->urlRedirection);
    }
    public function rentsTransbankCallBack(Request $request, $payment_order, $apply_property_id){
        $payment = Payment::findByOrder($payment_order);

        $response = $payment->getTbResponse($request->input("token_ws"));
        /**
         * $token_csrf = $response->sessionId; // Usar si molesta el VerifyCsrfToken, nota: desactivandolo en la url correspodiente en Middleware\VerifyCsrfToken
         */
        if ( $response->detailOutput->responseCode== 0) {
            //Cambio de estado en la postulacion
            $postulacion = ApplyProperty::find($apply_property_id);
            $postulacion->state = 3;
            $postulacion->save();
            //Cambio de estado en el Pago
            $payment->status = Payment::STATUS_APPROVED;
            $payment->save();
            // Asociando Propiedad con Contrato
            $property = Property::find($postulacion->property_id);
            //Generar Contrato si la propiedad es larga temporada
            if($property->type_stay == 'LONG_STAY'){
                $contract = $property->contract()->orderBy('created_at','desc')->first();
                $contract->property_id = $property->id;
                $contract->save();
                $payment->contract()->associate($contract);
                $contract->save();
            }
            
            // Obteniendo arrendatario y arrendador
            $tenant = $postulacion->postulant();
            $owner = $property->getOwner();
            // Asociando contrato con usuarios
            //$contract->users()->attach($tenant, ['signer_role' => 'tenant']);
            //$contract->users()->attach($owner, ['signer_role' => 'owner']);
            //if($tenant->confirmed_collateral) $contract->users()->attach($tenant->getCollateralUser(), ['signer_role' => 'collateral']);
            // Asociando Pago con Contrato
            $payment->token_ws = $request->input("token_ws");
            
            $payment->save();
            /*
            // Generacion de contrato en HelloSign y en Disco
            if($tenant->confirmed_collateral) {
                $contract->generarContratoConAval();
            } else {
                $contract->generarContratoSinAval();
            }
            */
            // Envio de notificaciones (arrendador - arrendatario)

            if($property->type_stay == 'LONG_STAY'){
                $owner->notify(new PayRentOwner($property->getOwner(),$payment->created_at,$property->id, $property, $payment));
                $tenant->notify(new PayRentTenant($postulacion->postulant(),$payment->created_at,$property->id, $property, $payment ));
            } elseif($property->type_stay == 'SHORT_STAY') {
                $dates = json_decode(DB::table('users_has_postulates_days')->where('id', $postulacion->id)->first()->days);
                $fechaEmision = Carbon::parse($dates->start);
                $fechaExpiracion = Carbon::parse($dates->end);
                $owner->notify(new PayRentStayOwner($property->getOwner(),$postulacion->postulant(),$payment->created_at,$property->id, $property, $payment, $fechaEmision, $fechaExpiracion));
                $tenant->notify(new PayRentStayTenant($postulacion->postulant(),$property->getOwner(),$payment->created_at,$property->id, $property, $payment, $fechaEmision, $fechaExpiracion ));
            }
        }
        else {
            $payment->token_ws = $request->input("token_ws");
            $payment->status = Payment::STATUS_REJECTED;
            // dd("Pago NO PROCESADO! <<<< TODO: Handlers <<<<");
            // abort(404);
        }
        $payment->save();
        return $payment->redirectToVoucherTB($response->urlRedirection);
    }
    public function endTransbankPayment(Request $request, $payment_order, $membership_id){
        #dd("END");
        $payment = Payment::findByOrder($payment_order);

        if ($payment->status != Payment::STATUS_APPROVED) {
            return redirect($request->back_url);
        }

        /*
        TODO: Validaciones para redirecciones dependiendo de lo ocurrido con el pago
        */

        //$membership = Membership::find($membership_id);
        //Membership::attachMembership($membership_id, $payment->user);
        return redirect($request->success_redir);
    }

    public function endTransbankPaymentRent(Request $request, $payment_order, $apply_property_id){
        #dd("END");
        $payment = Payment::findByOrder($payment_order);

        if ($payment->status != Payment::STATUS_APPROVED) {
            
            return redirect($request->back_url);
        }

        /*
        TODO: Validaciones para redirecciones dependiendo de lo ocurrido con el pago
        */

        //$membership = Membership::find($membership_id);
        //Membership::attachMembership($membership_id, $payment->user);
        return redirect($request->success_redir);
    }

    public function processPayPalPayment(Request $request)
    {
        dd($request);
        $membership = Membership::find($request->membership);
        if (!$membership) abort(404, 'no membership found');
        $amount = str_replace(".", "",$membership->getFeatures()->package_amount);
        $request->payment_details = [
            'total' =>  (float)($amount * env("IVA")/100 + $amount),
            'quantity' => 1,
            'description' => "Pago de Membresia uHomie tipo: ".$membership->name
        ];
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Item 1') /** item name **/
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($request->get('amount')); /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
                ->setTotal($request->get('amount'));
        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setItemList($item_list)
                    ->setDescription('Your transaction description');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('profile.tenant')) /** Specify return URL **/
                        ->setCancelUrl(URL::route('profile.tenant'));
        $payment = new Payment();
        $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; **/
        try {
            $payment->create($this->_api_context);
        } catch (\Exception $ex) {
            dd(json_decode($ex->getData()));
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                return Redirect::route('/');
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::route('/');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        \Session::put('error', 'Unknown error occurred');
        return Redirect::route('paywithpaypal');
    }

    public function processPayPalPayment2(Request $request)
    {
        //dd($request);
        //$membership = Membership::find($request->membership);
        //if (!$membership) abort(404, 'no membership found');
        //$amount = str_replace(".", "",$membership->getFeatures()->package_amount);
        $amount = 100;
        $request->payment_details = [
            'total' =>  (float)($amount * env("IVA")/100 + $amount),
            'quantity' => 1,
            'description' => "Pago de Membresia uHomie tipo: "
        ];
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Item 1') /** item name **/
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($request->get('amount')); /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
                ->setTotal($request->get('amount'));
        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setItemList($item_list)
                    ->setDescription('Your transaction description');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('profile.tenant')) /** Specify return URL **/
                        ->setCancelUrl(URL::route('profile.tenant'));
        $payment = new Payment();
        $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; **/
        try {
            $payment->create($this->_api_context);
        } catch (\Exception $ex) {
            dd(json_decode($ex->getData()));
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                return Redirect::route('/');
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::route('/');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        \Session::put('error', 'Unknown error occurred');
        return Redirect::route('paywithpaypal');
    }

    public function getPaymentStatus(){
            /** Get the payment ID before session clear **/
            $payment_id = Session::get('paypal_payment_id');
                /** clear the session payment ID **/
                Session::forget('paypal_payment_id');
                if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
        \Session::put('error', 'Payment failed');
                    return Redirect::route('/');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
                $execution = new PaymentExecution();
                $execution->setPayerId(Input::get('PayerID'));
        /**Execute the payment **/
                $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {
        \Session::put('success', 'Payment success');
                    return Redirect::route('/');
        }
        \Session::put('error', 'Payment failed');
                return Redirect::route('/');
    }

    public function showPropertyPayment(Request $request, $property_id)
    {
        $property = Property::findOrFail($property_id);
        if(!($user = \Auth::user())) return abort(403);
        
        if($property->getAppliedAttribute()){ // corroborando que el usuario de la sesión ha postulado a esa propiedad
            $state = $property->applications()->where('user_id', $user->id)->get()->first()->state; // corroborando que la postulación (del usuario) sólo y ssi está en estado aprobada
            if($state == 2){
                return view('payments.step-one')->with(['user' => $user,
                                                        'property' => $property,
                                                        ]);
            } else {
                return abort(403);
            }
        } else {
            return abort(403);
        }
    }

    public function showPropertyShortStayPayment(Request $request, $property_id)
    {
        //dd($request);
        $property = Property::findOrFail($property_id);
        if(!($user = \Auth::user())) return abort(403);
        if($property->getAppliedAttribute()){ // corroborando que el usuario de la sesión ha postulado a esa propiedad
            $postulacion = $property->applications()->where('user_id', $user->id)->orderBy('id', 'desc')->first();
            $state = $postulacion->state; // corroborando que la postulación (del usuario) sólo y ssi está en estado aprobada
            if($state == 6){
                //dd($postulacion);

                $dates = json_decode(DB::table('users_has_postulates_days')->where('id', $postulacion->id)->first()->days);

                $fechaEmision = Carbon::parse($dates->start);
                $fechaExpiracion = Carbon::parse($dates->end);

                $diasDiferencia = $fechaExpiracion->diffInDays($fechaEmision) + 1;

                return view('payments.short_stay')->with(['user' => $user,
                                                        'property' => $property,
                                                        'days' => $diasDiferencia,
                                                        'cantidad_arriendos' => DB::table('users_has_properties')->where('property_id', $property->id)->count() - 1
                                                        ]);
            } else {
                return abort(403);
            }
        } else {
            return abort(403);
        }
    }

    public function showPaymentCongratulationsShortStay(Request $request){

        $user = \Auth::user();

        if( $request->query('token') !== null && $user && $request->query('ap') !== null){
            $client = PayPalClient::client();
            $response = $client->execute(new OrdersGetRequest($request->query('token'))); // Le pregunto a PayPal el estado de la transaccion
            $postulacion = ApplyProperty::findOrFail($request->query('ap'));
            if($response->result->status == 'APPROVED'){
                $query = Payment::where('token_ws', $request->query('token'));
                if($query->count()){
                    $pago = $query->first();
                    
                    $pago->status = Payment::STATUS_APPROVED;
                    $pago->save();
                    //dd(Contract::findOrFail($pago->contract_id));
                    $property = Property::find($postulacion->property_id);

                    
                    if($property->getAppliedAttribute()){ // Comprobando que el user actual (de sesion) haya postulado a la propiedad
                        $apply_property_id = $property->applications()->where('user_id', $user->id)->get()->first()->id;
                        $postulacion = ApplyProperty::find($apply_property_id);
                        $postulacion->state = 3;
                        $postulacion->save();
                        //Cambio de estado en el Pago
                        $pago->status = Payment::STATUS_APPROVED;
                        $pago->save();
                        // Asociando Propiedad con Contrato
                        $property = Property::find($postulacion->property_id);
                        //$contract = $property->contract()->orderBy('created_at','desc')->first();
                        //$contract->property_id = $property->id;
                        //$contract->save();
                        // Obteniendo arrendatario y arrendador
                        $tenant = $postulacion->postulant();
                        $owner = $property->getOwner();
                        // Asociando contrato con usuarios
                        //$contract->users()->attach($tenant, ['signer_role' => 'tenant']);
                        //$contract->users()->attach($owner, ['signer_role' => 'owner']);
                        //if( $collateral_flag = $tenant->confirmed_collateral){
                        //    $contract->users()->attach($tenant->getCollateralUser(), ['signer_role' => 'collateral']);
                        //} 
                        // Asociando Pago con Contrato
                        //$pago->contract()->associate($contract);
                        $pago->save();
                        // Generacion de contrato en HelloSign y en Disco
                        //if($tenant->confirmed_collateral) {
                            //$contract->generarContratoConAval();
                        //} else {
                           // $contract->generarContratoSinAval();
                        //}
                        //$contract->save();
                        // Envio de notificaciones (arrendador - arrendatario)
                        if($property->type_stay == "LONG_STAY"){
                            $owner->notify(new PayRentOwner($owner,$pago->created_at,$property->id, $property, $pago));
                            $tenant->notify(new PayRentTenant($tenant,$pago->created_at,$property->id, $property, $pago ));
                        } elseif($property->type_stay == "SHORT_STAY") {
                            $dates = json_decode(DB::table('users_has_postulates_days')->where('id', $postulacion->id)->first()->days);
                            $fechaEmision = Carbon::parse($dates->start);
                            $fechaExpiracion = Carbon::parse($dates->end);
                            $owner->notify(new PayRentStayOwner($owner,$tenant,$pago->created_at,$property->id, $property, $pago, $fechaEmision, $fechaExpiracion));
                            $tenant->notify(new PayRentStayTenant($tenant,$owner,$pago->created_at,$property->id, $property, $pago, $fechaEmision, $fechaExpiracion ));
                        }
                        
                        //if($collateral_flag){
                            //$tenant->getCollateralUser()->notify( new PayRentCollateral($tenant->getCollateralUser(),$pago->created_at,$property->id, $property, $pago) );
                        //}
                        
                        return view('payments.congratulations_short_stay', ['property' => $property])->with(['user' => $user,
                                                'property' => $property,
                                                ]);
                    }
                    else {
                        return error(403);
                        // TO-DO: programar una vista de pago con error: (el usuario no se ha postulado a la propiedad pagada)
                    }
                } else {
                    return error(401);
                    //TO-DO: programar una vista de pago con error: token perdido
                }
            } else {
                return error(401);
                    //TO-DO: programar una vista de pago con error 
            }
        } elseif($user) {
            $payment = Payment::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
            //dd($property);
            $property = Property::find($payment->property_id);
            $postulation = \App\ApplyProperty::where('property_id',$property->id)->where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
            //dd($postulation);
            $postulation->state = 3;
            $postulation->save();
            return view('payments.congratulations_short_stay')->with(['user' => $user,
                                                'property' => $property,
                                                ]);
        }
    }

    public function showPaymentCongratulations(Request $request)
    {
        $user = \Auth::user();
        //dd($request);
        //$property = \App\Property::find(1);
        if( $request->query('token') !== null && $user && $request->query('ap') !== null){
            $client = PayPalClient::client();
            $response = $client->execute(new OrdersGetRequest($request->query('token'))); // Le pregunto a PayPal el estado de la transaccion
            $postulacion = ApplyProperty::findOrFail($request->query('ap'));
            if($response->result->status == 'APPROVED'){
                $query = Payment::where('token_ws', $request->query('token'));
                if($query->count()){
                    $pago = $query->first();
                    
                    $pago->status = Payment::STATUS_APPROVED;
                    $pago->save();
                    //dd(Contract::findOrFail($pago->contract_id));
                    $property = Property::find($postulacion->property_id);

                    
                    if($property->getAppliedAttribute()){ // Comprobando que el user actual (de sesion) haya postulado a la propiedad
                        $apply_property_id = $property->applications()->where('user_id', $user->id)->get()->first()->id;
                        $postulacion = ApplyProperty::find($apply_property_id);
                        $postulacion->state = 3;
                        $postulacion->save();
                        //Cambio de estado en el Pago
                        $pago->status = Payment::STATUS_APPROVED;
                        $pago->save();
                        // Asociando Propiedad con Contrato
                        $property = Property::find($postulacion->property_id);
                        $contract = $property->contract()->orderBy('created_at','desc')->first();
                        $contract->property_id = $property->id;
                        $contract->save();
                        // Obteniendo arrendatario y arrendador
                        $tenant = $postulacion->postulant();
                        $owner = $property->getOwner();
                        // Asociando contrato con usuarios
                        $contract->users()->attach($tenant, ['signer_role' => 'tenant']);
                        $contract->users()->attach($owner, ['signer_role' => 'owner']);
                        if( $collateral_flag = $tenant->confirmed_collateral){
                            $contract->users()->attach($tenant->getCollateralUser(), ['signer_role' => 'collateral']);
                        } 
                        // Asociando Pago con Contrato
                        $pago->contract()->associate($contract);
                        $pago->save();
                        // Generacion de contrato en HelloSign y en Disco
                        if($tenant->confirmed_collateral) {
                            $contract->generarContratoConAval();
                        } else {
                            $contract->generarContratoSinAval();
                        }
                        $contract->save();
                        // Envio de notificaciones (arrendador - arrendatario)
                        $owner->notify(new PayRentOwner($owner,$pago->created_at,$property->id, $property, $pago));
                        $tenant->notify(new PayRentTenant($tenant,$pago->created_at,$property->id, $property, $pago ));
                        if($collateral_flag){
                            $tenant->getCollateralUser()->notify( new PayRentCollateral($tenant->getCollateralUser(),$pago->created_at,$property->id, $property, $pago) );
                        }
                        
                        return view('payments.congratulations', ['property' => $property])->with(['user' => $user,
                                                'property' => $property,
                                                ]);
                    }
                    else {
                        return error(403);
                        // TO-DO: programar una vista de pago con error: (el usuario no se ha postulado a la propiedad pagada)
                    }
                } else {
                    return error(401);
                    //TO-DO: programar una vista de pago con error: token perdido
                }
            } else {
                return error(401);
                    //TO-DO: programar una vista de pago con error 
            }
        } elseif($user) {
            $property = Payment::where('user_id', $user->id)->orderBy('created_at', 'desc')->first()->contract()->first()->property()->first();
            //dd($property);
            return view('payments.congratulations')->with(['user' => $user,
                                                'property' => $property,
                                                ]);
        }
        
    }


    public function handleRentPayment(Request $request)
    {
        
        $request->validate([
            'property' => 'required|numeric|exists:properties,id',
            'seguro_check' => 'nullable|in:on',
            'payment_method' => 'required|string|in:debit,credit_clp,credit_usd'
        ]);
        
        $property = Property::findOrFail($request->property);
        if(!($user = \Auth::user())) return abort(403);
        
        if($property->getAppliedAttribute()){ // corroborando que el usuario de la sesión ha postulado a esa propiedad
            $state = $property->applications()->where('user_id', $user->id)->get()->first()->state; // corroborando que la postulación (del usuario) sólo y ssi está en estado aprobada
            if($state == 2){ // 2 = postulación estado aprobada
                //dd($property);
                $advance_mount = floatval($property->rent)*floatval($property->months_advance_quantity);
                $warranty_mount = floatval($property->rent)*floatval($property->warranty_months_quantity);
                $amount = $advance_mount; // Agregando el monto de meses de adelanto
                $amount += $warranty_mount; // Agregando el monto de meses de garantía

                $tenanting_insurance_from_tenant = false;
                if(isset($request->seguro_check)){ // validando si el usuario aceptó el seguro HDI
                    $tenanting_insurance_from_tenant = true;
                    if($user->confirmed_collateral){ // Agregando el monto del seguro HDI
                        if($property->tenanting_insurance){
                            $amount += floatval($property->rent)*0.25*1.19;
                        } else {
                            $amount += floatval($property->rent)*0.5*1.19;
                        }
                    } else {
                        if($property->tenanting_insurance){
                            $amount += floatval($property->rent)*0.4*1.19;
                        } else {
                            $amount += floatval($property->rent)*0.8*1.19;
                        }
                    }
                }
                    
                $pre_service_mount = $amount;
                if(isset($request->payment_method)){ // Agregando el monto de servicio digital uHomie
                    switch($request->payment_method){
                        /*
                        case 'debit':
                            
                            $amount *= 1.09;
                            break;
                        */
                        case 'credit_clp':
                            
                            $amount *= 1.11;
                            break;
                        case 'credit_usd':
                            $amount *= 1.3;
                            break;
                        default:
                            return abort(403);
                            break;
                    }
                } else {
                    return abort(403);
                }
                $service_mount = $amount - $pre_service_mount;
                //$amount = intval($property->rent);
                $total = (float)($amount);
                //dd($total);
                $order = Payment::generateOrder();
                $details = "Pago de Servicio uHomie para la propiedad: ". $property->name . " para " . $user->firstname. ' '.$user->lastname;
                
                if ($request->payment_method == 'credit_usd') {
                    // CONVERTIR PESOS EN DOLARES AQUI
                    Carbon::setLocale('es');
                    $fecha = Carbon::now('America/Santiago');
                    $diaSemana = $fecha->format('l'); // Se busca obtener el nombre del dia en la semana, si es sabado o domingo, la api no funciona bien para esos dias.
                    if($diaSemana == 'Saturday') { $fecha->subDay(); }
                    if($diaSemana == 'Sunday') { $fecha->subDays(2); }
                    $ano = $fecha->format('Y');
                    $mes = $fecha->format('m');
                    $dia = $fecha->format('d');
                    $client = new Client();
                    try{
                        $res = $client->request('GET', 'https://api.sbif.cl/api-sbifv3/recursos_api/dolar/'.$ano.'/'.$mes.'/dias/'.$dia.'?apikey=243ee93523145ebdd7f6f2d9c1a3320401bbee5d&formato=json');
                        $string_dolar = str_replace(',','.',json_decode($res->getBody()->getContents())->Dolares[0]->Valor);
                        $valor_dolar= floatval($string_dolar);
                    } catch(Exception $e){
                        dd('NO esta conectado a internet');
                    };
                    $total = $amount; // Cuando se crea un payment con method paypal, la columna total guardara el valor en pesos chilenos y la columna amount guardara el monto en dolares
                    $amount = intval($total/$valor_dolar);
                    
                    $response = $this->createOrderPaypal(false, $property, $amount, $property->applications()->where('user_id', $user->id)->get()->first()->id);
                    //dd($response);
                    
                    $redireccion = null;
                    foreach($response->result->links as $link)
                    {
                        if($link->rel == 'approve') $redireccion = $link->href;
                    }
                    if(!$redireccion) return back();
                    $payment = Payment::create([
                        "order" => $order,
                        "user_id" => \Auth::user()->id,
                        "status" => Payment::STATUS_CREATED,
                        "amount" => $amount,
                        "iva" => env("IVA"),
                        "total" => $total,
                        "method" => 'paypal', // TO DO: Colocar que pague solo con UN SOLO medio de pago
                        "currency" => env("CURRENCY_ID"),
                        "exchange_rate" => 1,
                        "details" => $details,
                        'tenanting_insurance' => $tenanting_insurance_from_tenant,
                        'token_ws' => $response->result->id, // token que nos envia PayPal
                        'service_amount' => $service_mount
                    ]);
                    

                    return redirect($redireccion);

                }elseif($request->payment_method == "debit" || $request->payment_method == "credit_clp" ) {
                    $payment = Payment::create([
                        "order" => $order,
                        "user_id" => \Auth::user()->id,
                        "status" => Payment::STATUS_CREATED,
                        "amount" => $amount,
                        "iva" => env("IVA"),
                        "total" => $total,
                        "method" => 'transbank', // TO DO: Colocar que pague solo con UN SOLO medio de pago
                        "currency" => env("CURRENCY_ID"),
                        "exchange_rate" => 1,
                        "details" => $details, 
                        'tenanting_insurance' => $tenanting_insurance_from_tenant
                    ]);
                    /*
                    $contract = new Contract();
                    $contract->property_id = $property->id;
                    $contract->save();
                    $payment->contract()->associate($contract);
                    */
                    $payment->save();
                    return $payment->processToCheckoutRentTBOficial($property->applications()->where('user_id', $user->id)->get()->first()->id, $request->success_redir, $request->back_url);
                }else {
                    abort(500);
                }
            } else {
                return abort(401);
            }
        } else {
            return abort(403);
        }
        dd($property->applications()->get());
        dd($property->getAppliedUsers()->contains($user));
    }
    public function handleShortStayPayment(Request $request)
    {

        //dd($request);
        $request->validate([
            'property' => 'required|numeric|exists:properties,id',
            'seguro_check' => 'nullable|in:on',
            'payment_method' => 'required|string|in:debit,credit_clp,credit_usd'
        ]);
        
        $property = Property::findOrFail($request->property);
        if(!($user = \Auth::user())) return abort(403);
        
        if($property->getAppliedAttribute()){ // corroborando que el usuario de la sesión ha postulado a esa propiedad
            $postulacion = $property->applications()->where('user_id', $user->id)->orderBy('id', 'desc')->first();
            $state = $postulacion->state; // corroborando que la postulación (del usuario) sólo y ssi está en estado aprobada
            if($state == 6){ // 2 = postulación estado aprobada
                //dd($property);

                $dates = json_decode(DB::table('users_has_postulates_days')->where('id', $postulacion->id)->first()->days);

                $fechaEmision = Carbon::parse($dates->start);
                $fechaExpiracion = Carbon::parse($dates->end);

                $cantidad_dias = $fechaExpiracion->diffInDays($fechaEmision) + 1;
                $rent_subtotal = $property->rent * $cantidad_dias;
                //$pre_service_mount = ( $property->rent * $cantidad_dias ) + $property->cleaning_rate;
                
                if( (DB::table('users_has_properties')->where('property_id', $property->id)->count() -1 ) <= 10 && $property->special_sale == 1  ){
                    $pre_service_mount = $rent_subtotal * 0.1;
                }
                if( $property->week_sale > 0 && $cantidad_dias >= 7 && $cantidad_dias < 30 ){
                    $pre_service_mount = $rent_subtotal*($property->week_sale/100);
                }elseif( $property->month_sale > 0 && $cantidad_dias >= 30 ){
                    $pre_service_mount = $rent_subtotal*($property->month_sale/100);
                } else {
                    $pre_service_mount = 0;
                }
                $amount = $rent_subtotal + $property->cleaning_rate - $pre_service_mount;
                if(isset($request->payment_method)){ // Agregando el monto de servicio digital uHomie
                    switch($request->payment_method){
                        
                        case 'debit':
                            
                            $service_mount = $amount * 0.07;
                            break;
                        
                        case 'credit_clp':
                            
                            $service_mount = $amount * 0.11;
                            break;
                        case 'credit_usd':
                            $service_mount = $amount * 0.3;
                            break;
                        default:
                            return abort(403);
                            break;
                    }
                } else {
                    return abort(403);
                }
                
                //dd($service_mount);
                $total = (int)($amount + $service_mount);
                $order = Payment::generateOrder();
                $details = "Pago de Servicio uHomie para la propiedad de corta temporada: ". $property->name . " para " . $user->firstname. ' '.$user->lastname . " en los dias desde " . $fechaEmision->format('d-m-Y') . ' hasta '. $fechaExpiracion->format('d-m-Y') .'.';
                
                if ($request->payment_method == 'credit_usd') {
                    // CONVERTIR PESOS EN DOLARES AQUI
                    Carbon::setLocale('es');
                    $fecha = Carbon::now('America/Santiago');
                    $diaSemana = $fecha->format('l'); // Se busca obtener el nombre del dia en la semana, si es sabado o domingo, la api no funciona bien para esos dias.
                    if($diaSemana == 'Saturday') { $fecha->subDay(); }
                    if($diaSemana == 'Sunday') { $fecha->subDays(2); }
                    $ano = $fecha->format('Y');
                    $mes = $fecha->format('m');
                    $dia = $fecha->format('d');
                    $client = new Client();
                    try{
                        $res = $client->request('GET', 'https://api.sbif.cl/api-sbifv3/recursos_api/dolar/'.$ano.'/'.$mes.'/dias/'.$dia.'?apikey=243ee93523145ebdd7f6f2d9c1a3320401bbee5d&formato=json');
                        $string_dolar = str_replace(',','.',json_decode($res->getBody()->getContents())->Dolares[0]->Valor);
                        $valor_dolar= intval($string_dolar);
                    } catch(Exception $e){
                        dd('NO esta conectado a internet');
                    };
                    $total = $amount; // Cuando se crea un payment con method paypal, la columna total guardara el valor en pesos chilenos y la columna amount guardara el monto en dolares
                    $amount = intval($total/$valor_dolar);
                    
                    $response = $this->createOrderPaypal(false, $property, $amount, $property->applications()->where('user_id', $user->id)->get()->first()->id);
                    //dd($response);
                    
                    $redireccion = null;
                    foreach($response->result->links as $link)
                    {
                        if($link->rel == 'approve') $redireccion = $link->href;
                    }
                    if(!$redireccion) return back();
                    $payment = Payment::create([
                        "order" => $order,
                        "user_id" => \Auth::user()->id,
                        "status" => Payment::STATUS_CREATED,
                        "amount" => $amount,
                        "iva" => env("IVA"),
                        "total" => $total,
                        "method" => 'paypal', // TO DO: Colocar que pague solo con UN SOLO medio de pago
                        "currency" => env("CURRENCY_ID"),
                        "exchange_rate" => 1,
                        "details" => $details,
                        'token_ws' => $response->result->id, // token que nos envia PayPal
                        'service_amount' => $service_mount,
                        'property_id' => $property->id
                    ]);
                    

                    return redirect($redireccion);

                }elseif($request->payment_method == "debit" || $request->payment_method == "credit_clp" ) {
                    $payment = Payment::create([
                        "order" => $order,
                        "user_id" => \Auth::user()->id,
                        "status" => Payment::STATUS_CREATED,
                        "amount" => $amount,
                        "iva" => env("IVA"),
                        "total" => $total,
                        "method" => 'transbank', // TO DO: Colocar que pague solo con UN SOLO medio de pago
                        "currency" => env("CURRENCY_ID"),
                        "exchange_rate" => 1,
                        "details" => $details, 
                        'service_amount' => $service_mount,
                        'property_id' => $property->id
                    ]);
                    /*
                    $contract = new Contract();
                    $contract->property_id = $property->id;
                    $contract->save();
                    $payment->contract()->associate($contract);
                    */
                    $payment->save();
                    return $payment->processToCheckoutRentTBOficial($property->applications()->where('user_id', $user->id)->get()->first()->id, $request->success_redir, $request->back_url);
                }else {
                    abort(500);
                }
            } else {
                return abort(401);
            }
        } else {
            return abort(403);
        }
        dd($property->applications()->get());
        //dd($property->getAppliedUsers()->contains($user));
    }
    public function createOrderPaypal($debug=false, $property, $amount, $apply_property_id)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = array(
            'intent' => 'CAPTURE',
            'application_context' =>
                array(
                    'return_url' => route('users.payments.congratulations', ['ap' => $apply_property_id]),
                    'cancel_url' => route('users.payments.step-one', ['property_id' => $property->id])
                ),
            'purchase_units' =>
                array(
                    0 =>
                        array(
                            'amount' =>
                                array(
                                    'currency_code' => 'USD',
                                    'value' => $amount
                                )
                        )
                )
        );
        // 3. Call PayPal to set up a transaction
        $client = \App\PayPalClient::client();
        $response = $client->execute($request);
        if ($debug)
        {
        print "Status Code: {$response->statusCode}\n";
        print "Status: {$response->result->status}\n";
        print "Order ID: {$response->result->id}\n";
        print "Intent: {$response->result->intent}\n";
        print "Links:\n";
        foreach($response->result->links as $link)
        {
            print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
        }

        // To print the whole response body, uncomment the following line
        // echo json_encode($response->result, JSON_PRETTY_PRINT);
        }

        // 4. Return a successful response to the client.
        return $response;
        
    }

    
    /*public function handleRentPayment(Request $request)
    {
        
        $request->validate([
            'property' => 'required|numeric|exists:properties,id',
            'seguro_check' => 'nullable|in:on',
            'payment_method' => 'required|string|in:debit,credit_clp,credit_usd'
        ]);
        
        $property = Property::findOrFail($request->property);
        if(!($user = \Auth::user())) return abort(403);
        
        if($property->getAppliedAttribute()){ // corroborando que el usuario de la sesión ha postulado a esa propiedad
            $state = $property->applications()->where('user_id', $user->id)->get()->first()->state; // corroborando que la postulación (del usuario) sólo y ssi está en estado aprobada
            if($state == 2){ // 2 = postulación estado aprobada
                //dd($property);
                $advance_mount = floatval($property->rent)*floatval($property->months_advance_quantity);
                $warranty_mount = floatval($property->rent)*floatval($property->warranty_months_quantity);
                $amount = $advance_mount; // Agregando el monto de meses de adelanto
                $amount += $warranty_mount; // Agregando el monto de meses de garantía

                $tenanting_insurance_from_tenant = false;
                if(isset($request->seguro_check)){ // validando si el usuario aceptó el seguro HDI
                    $tenanting_insurance_from_tenant = true;
                    if($user->confirmed_collateral){ // Agregando el monto del seguro HDI
                        if($property->tenanting_insurance){
                            $amount += floatval($property->rent)*0.25*1.19;
                        } else {
                            $amount += floatval($property->rent)*0.5*1.19;
                        }
                    } else {
                        if($property->tenanting_insurance){
                            $amount += floatval($property->rent)*0.4*1.19;
                        } else {
                            $amount += floatval($property->rent)*0.8*1.19;
                        }
                    }
                }
                    
                
                if(isset($request->payment_method)){ // Agregando el monto de servicio digital uHomie
                    switch($request->payment_method){
                        
                        //case 'debit':
                            
                            //$amount *= 1.09;
                            //break;

                        case 'credit_clp':
                            $amount *= 1.11;
                            break;
                        case 'credit_usd':
                            $amount *= 1.3;
                            break;
                        default:
                            return abort(403);
                            break;
                    }
                } else {
                    return abort(403);
                }
                //$amount = intval($property->rent);
                $total = (float)($amount);
                //dd($total);
                $order = Payment::generateOrder();
                $details = "Pago de Servicio uHomie para la propiedad: ". $property->name . " para " . $user->firstname. ' '.$user->lastname;
                
                if ($request->payment_method == 'credit_usd') {
                    return back();
                }elseif($request->payment_method == "debit" || $request->payment_method == "credit_clp" ) {
                    $payment = Payment::create([
                        "order" => $order,
                        "user_id" => \Auth::user()->id,
                        "status" => Payment::STATUS_CREATED,
                        "amount" => $amount,
                        "iva" => env("IVA"),
                        "total" => $total,
                        "method" => 'transbank', // TO DO: Colocar que pague solo con UN SOLO medio de pago
                        "currency" => env("CURRENCY_ID"),
                        "exchange_rate" => 1,
                        "details" => $details,
                        'tenanting_insurance' => $tenanting_insurance_from_tenant
                    ]);
                    return $payment->processToCheckoutRentTB($property->applications()->where('user_id', $user->id)->get()->first()->id, $request->success_redir, $request->back_url);
                }else {
                    abort(500);
                }
            } else {
                return abort(401);
            }
        } else {
            return abort(403);
        }
        dd($property->applications()->get());
        dd($property->getAppliedUsers()->contains($user));
    }*/
    public function checkCoupon(Request $request)
    {
        $user = \Auth::user();

        $coupon = Coupon::where('code', $request->code)->where('membership_id', $request->membership)->where('enabled', 1)->first();

        if(!is_null($coupon)){
            $usages = UserCoupon::where('coupon_id', $coupon->id)->get();
            if(count($usages) <= $coupon->quantity){
                $usage = UserCoupon::where('user_id', $user->id)->where('coupon_id', $coupon->id)->first();
                $membership = Membership::find($coupon->membership_id);
                $amount = str_replace(".", "",$membership->getFeatures()->package_amount);
                if(is_null($usage)){
                    return response(['respuesta' => true, 'monto_dscto' => str_replace(",", ".",$amount)]);
                } else {
                    return response()->json(['respuesta' => false, 'razon' => 'Lo sentimos el cupon ya lo has utilizado.']);
                }
            } else {
                return response()->json(['respuesta' => false, 'razon' => 'Lo sentimos el cupon a superado el limite de usos.']);
            }
        } else {
            return response()->json(['respuesta' => false, 'razon' => 'Lo sentimos el cupon ingresado no es valido.']);
        }

        return response(['success' => true, 'request' => $coupon]);



        /*Servicio de verificacion de cupon de rodrigo
        
        if( $user = \Auth::user() ){
            if (isset($request->code)){
                $cupon = $request->code;
                if( $user->getOwnerMerbershipOnce() ){
                    $client = new Client();
                    $res = $client->get("https://utilsfaas.azurewebsites.net/api/utils-getstatuscoupon?code=XuO94XtMz3Du4f6kmNBfHRh/COSkv3R8aV3mnriSBlg7EMbjYwcKkA==&coupon={$request->code}");
                    if( $res->getBody() ){
                        if( $json = json_decode($res->getBody())){
                            if( $json->OK === "true" ){
                                return response()->json(['respuesta' => true, 'monto_dscto' => 22550]);
                            }else{
                                return response()->json(['respuesta' => false, 'razon' => 'Lo sentimos, el código ingresado ha expirado o no existe.']);
                            }
                        }else{
                            return response()->json(['respuesta' => false, 'razon' => 'Lo sentimos, servicio de cupones no esta disponible']);
                        }
                    }else{
                        return response()->json(['respuesta' => false, 'razon' => 'Lo sentimos, servicio de cupones no esta disponible']);
                    }
                }else{
                    return response()->json(['respuesta' => false, 'razon' => 'Lo sentimos, este cupon es solo válido para nuevos usuarios propietario.']);
                }
            }else{
                abort(401, 'Error formato');
            }
        } else {
            abort(401, 'No estas autenticado');
        }*/
        
    }
}
