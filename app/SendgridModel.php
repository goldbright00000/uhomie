<?php

namespace App;

use GuzzleHttp\Client;
use App\Role;
use App\User;

class SendgridModel
{
	private $sg;
	private $client;

	public function  __construct(){
		//$this->sg = new \SendGrid(env('SENDGRID_API_KEY'));
		$this->client = new Client(['headers' => ['Authorization' => 'Bearer '.env('SENDGRID_API_KEY', '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'), 'Content-Type' => 'application/json']]);
	}





	public static function test1(){

		$email = new \SendGrid\Mail\Mail(); 
		$email->setFrom("info@uhomie.cl", "uHomie Test");
		$email->setSubject("Sending with SendGrid is Fun");
		$email->addTo("alejandro.arancibia@uhomie.cl", "Alejandro");
		$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
		$email->addContent(
			"text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
		);
		$sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));
		try {
			$response = $sendgrid->send($email);
			print $response->statusCode() . "\n";
			print_r($response->headers());
			print $response->body() . "\n";
		} catch (Exception $e) {
			echo 'Caught exception: '. $e->getMessage() ."\n";
		}
	}
	public function testAddRecipient()
	{
		$request_body = json_decode('[
			{
			  "age": 25,
			  "email": "example@example.com",
			  "first_name": "Juanito Test",
			  "last_name": "User"
			},
			{
			  "age": 25,
			  "email": "example2@example.com",
			  "first_name": "Example",
			  "last_name": "User"
			}
		  ]');
		$response = $this->sg->client->contactdb()->recipients()->post($request_body);
		dd($response);
	}

	public function testAddContact() // Esto es lo unico que SI funciona
	{
		//dd();
		$res = $this->client->request('PUT', 'https://api.sendgrid.com/v3/marketing/contacts', [
			//'headers' => ['Authorization' => 'Bearer '.env('SENDGRID_API_KEY'), 'Content-Type' => 'application/json'],
			'body' => json_encode([
					'list_ids' => ['ba2f6785-6307-41f6-8aeb-024bafbbd710'],
					'contacts' => [
							['email' => 'test3@uhomie.cl', 'firstname' => 'Juan Test 3', 'lastname' => 'Perez Test 3'],
							['email' => 'test4@uhomie.cl', 'firstname' => 'Juan Test 4', 'lastname' => 'Perez Test 4'],
							['email' => 'test5@uhomie.cl', 'firstname' => 'Juan Test 5', 'lastname' => 'Perez Test 5'],
						] 
				])
		]);
		dd($res);
	}
	public function getIdLists() // No quiere funcionar ninguno de estos metodos
	{
		/*
			$res = $this->client->request('GET', 'https://api.sendgrid.com/v3/marketing/lists?page_size=100');
			dd($res);
			$objeto = $res->getBody();
			$listaOutput = [];
			foreach($objeto->results as $lista){
				//if($lista->name == "ListaTest")
				$listaOutput[] = ['nombre' => $lista->name, 'id' => $lista->id];
			}
			dd($listaOutput);
		*/
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/lists?page_size=100",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_POSTFIELDS => "{}",
		CURLOPT_HTTPHEADER => array(
				"authorization: Bearer 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"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}
	}


	/*
	*	Updated incompleted: memberships = Default
	*/
	public function updateIncompleteTenantsList()
	{
		try{
			$rol_arrendatario = Role::where('name', 'Arrendatario')->first();
			$membresia_default = $rol_arrendatario->memberships()->where('name', 'Default')->first();
			$users = $membresia_default->users()->get();
			$arregloUsuarios = [];
			foreach( $users as $user ){
				$arregloUsuarios[] = [  'first_name' => $user->firstname,
										'last_name' => $user->lastname,
										'email' => $user->email
									 ];
			}
			$res = $this->client->request('PUT', 'https://api.sendgrid.com/v3/marketing/contacts', [
				//'headers' => ['Authorization' => 'Bearer '.env('SENDGRID_API_KEY'), 'Content-Type' => 'application/json'],
				'body' => json_encode([
						'list_ids' => [env('SENDGRID_ID_TENANTS')],
						'contacts' => $arregloUsuarios
					])
			]);
		}catch( \Exception $e )
		{
			dd($e);
		}
	}


	public function updateIncompleteOwnersList()
	{
		try{
			$rol_arrendador = Role::where('name', 'Propietario')->first();
			$membresia_default = $rol_arrendador->memberships()->where('name', 'Default')->first();
			$users = $membresia_default->users()->get();
			$arregloUsuarios = [];
			foreach( $users as $user ){
				$arregloUsuarios[] = [  'first_name' => $user->firstname,
										'last_name' => $user->lastname,
										'email' => $user->email
									 ];
			}
			$res = $this->client->request('PUT', 'https://api.sendgrid.com/v3/marketing/contacts', [
				//'headers' => ['Authorization' => 'Bearer '.env('SENDGRID_API_KEY'), 'Content-Type' => 'application/json'],
				'body' => json_encode([
						'list_ids' => [env('SENDGRID_ID_OWNERS')],
						'contacts' => $arregloUsuarios
					])
			]);
		}catch( \Exception $e )
		{
			dd($e);
		}
	}
	public function updateIncompleteAgentsList()
	{
		try{
			$rol_agente = Role::where('name', 'Agente')->first();
			$membresia_default = $rol_agente->memberships()->where('name', 'Default')->first();
			$users = $membresia_default->users()->get();
			$arregloUsuarios = [];
			foreach( $users as $user ){
				$arregloUsuarios[] = [  'first_name' => $user->firstname,
										'last_name' => $user->lastname,
										'email' => $user->email
									 ];
			}
			$res = $this->client->request('PUT', 'https://api.sendgrid.com/v3/marketing/contacts', [
				//'headers' => ['Authorization' => 'Bearer '.env('SENDGRID_API_KEY'), 'Content-Type' => 'application/json'],
				'body' => json_encode([
						'list_ids' => [env('SENDGRID_ID_AGENTS')],
						'contacts' => $arregloUsuarios
					])
			]);
		}catch( \Exception $e )
		{
			dd($e);
		}
	}
	public function updateIncompleteCollateralsList()
	{
		try{
			$rol_aval = Role::where('name', 'Aval')->first();
			$membresia_default = $rol_aval->memberships()->where('name', 'Default')->first();
			$users = $membresia_default->users()->get();
			$arregloUsuarios = [];
			foreach( $users as $user ){
				$arregloUsuarios[] = [  'first_name' => $user->firstname,
										'last_name' => $user->lastname,
										'email' => $user->email
									 ];
			}
			$res = $this->client->request('PUT', 'https://api.sendgrid.com/v3/marketing/contacts', [
				//'headers' => ['Authorization' => 'Bearer '.env('SENDGRID_API_KEY'), 'Content-Type' => 'application/json'],
				'body' => json_encode([
						'list_ids' => [env('SENDGRID_ID_COLLATERALS')],
						'contacts' => $arregloUsuarios
					])
			]);
		}catch( \Exception $e )
		{
			dd($e);
		}
	}



	// UPDATE COMPLETES:

	/*
	* Updated complete list
	*/
	public function updateCompleteTenantsList()
	{
		$role = Role::where('name', 'Arrendatario')->first();

		$arregloUsuarios = $this->usersCompletedByRole($role);

		try{
			$res = $this->client->request('PUT', 'https://api.sendgrid.com/v3/marketing/contacts', [
				//'headers' => ['Authorization' => 'Bearer '.env('SENDGRID_API_KEY'), 'Content-Type' => 'application/json'],
				'body' => json_encode([
						'list_ids' => [env('SENDGRID_ID_TENANTS')],
						'contacts' => $arregloUsuarios
					])
			]);
		}catch( \Exception $e )
		{
			dd($e);
		}
	}


	/*
	* Updated complete owners list
	*/
	public function updateCompleteOwnersList()
	{
		$role = Role::where('name', 'Propietario')->first();

		$arregloUsuarios = $this->usersCompletedByRole($role);

		

		try{
			$res = $this->client->request('PUT', 'https://api.sendgrid.com/v3/marketing/contacts', [
				//'headers' => ['Authorization' => 'Bearer '.env('SENDGRID_API_KEY'), 'Content-Type' => 'application/json'],
				'body' => json_encode([
						'list_ids' => [env('SENDGRID_ID_OWNERS')],
						'contacts' => $arregloUsuarios
					])
			]);
		}catch( \Exception $e )
		{
			dd($e);
		}
	}

	/*
	* Updated complete Agents list
	*/
	public function updateCompleteAgentsList()
	{
		$role = Role::where('name', 'Agente')->first();

		$arregloUsuarios = $this->usersCompletedByRole($role);
		

		try{
			$res = $this->client->request('PUT', 'https://api.sendgrid.com/v3/marketing/contacts', [
				//'headers' => ['Authorization' => 'Bearer '.env('SENDGRID_API_KEY'), 'Content-Type' => 'application/json'],
				'body' => json_encode([
						'list_ids' => [env('SENDGRID_ID_AGENTS')],
						'contacts' => $arregloUsuarios
					])
			]);
		}catch( \Exception $e )
		{
			dd($e);
		}
	}

	/*
	* Updated complete Agents list
	*/
	public function updateCompleteCollateralsList()
	{
		$role = Role::where('name', 'Aval')->first();

		$arregloUsuarios = $this->usersCompletedByRole($role);
		
		print_r($arregloUsuarios);die("h");

		try{
			$res = $this->client->request('PUT', 'https://api.sendgrid.com/v3/marketing/contacts', [
				//'headers' => ['Authorization' => 'Bearer '.env('SENDGRID_API_KEY'), 'Content-Type' => 'application/json'],
				'body' => json_encode([
						'list_ids' => [env('SENDGRID_ID_COLLATERALS')],
						'contacts' => $arregloUsuarios
					])
			]);
		}catch( \Exception $e )
		{
			dd($e);
		}
	}



	/* Get users array */
	private function usersCompletedByRole($role)
	{
		$memberships = $role->memberships;

		$arregloUsuarios = [];

		// e5_T -> id: status custom_fields
		foreach ($memberships as $m) {
			if($m->name != 'Default') {
				$users = $m->users()->get();
				foreach ($users as $user) {
					$arregloUsuarios[]	= [  
						'first_name' => $user->firstname,
						'last_name' => $user->lastname,
						'email' => $user->email
					];
				}
			}			
		}

		return $arregloUsuarios;
	}


	/*
	* TEST
	*/
  public function testJavi()
	{
		$list_id = env('SENDGRID_ID_TENANTS', '2681b41c-cf01-4c78-9b3a-d06a1f741728');

		//$list_id = 'aad52c3d-62e5-4c79-8a3e-dc295288e9b1';

		try{
			$role = Role::where('name', 'Propietario')->first();
			$memberships = $role->memberships;

			$arregloUsuarios = [];

			// e5_T -> id: status custom_fields
			foreach ($memberships as $m) {
				$users = $m->users()->get();
				foreach ($users as $user) {
					$arregloUsuarios[]	= [  
						'first_name' => $user->firstname,
						'last_name' => $user->lastname,
						'email' => $user->email,
						'custom_fields' => [
							'e5_T' => $m->name == 'Default' ? 'incomplete' : 'complete'
						]
					];
				}
			}

			$res = $this->client->request('PUT', 'https://api.sendgrid.com/v3/marketing/contacts', [
				//'headers' => ['Authorization' => 'Bearer '.env('SENDGRID_API_KEY'), 'Content-Type' => 'application/json'],
				'body' => json_encode([
						'list_ids' => ['5ef5fe0b-1a31-4154-a79b-159e726b9453'],
						'contacts' => $arregloUsuarios
					])
			]);
		} catch( \Exception $e )
		{
			dd($e);
		}

		die(".---");
		
	}


	/*
	Javi:
	Create custom_fields para que retorne el ID y usarlo.
	Desde el GUI es imposible

	$res = $this->client->request('POST', 'https://api.sendgrid.com/v3/marketing/field_definitions', [
			'body' => json_encode([
				'name' => 'status',
				'field_type' => 'Text'
			])
		]);

		echo $res->getBody();
		die("hola");

	*/
}