<?php

namespace App\Http\Controllers;

use \Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Notification;
use App\{ContactMessage, Newsletter, Membership, Commune, City};

class HomeController extends Controller
{

	public function getCommunes(Request $request){
		$city = City::find($request->city);
		return response([
			'villages' => $city ? $city->communes()->select('communes.id as id', 'communes.name as text')->orderBy('communes.name')->get() : null,
		]);
	}

	public function getMemberships(Request $request){
		$memberships = [];
		$db_memberships = false;
		if ( $request->type_membership == Membership::TYPE_TENANT ) {
		  $db_memberships = Membership::getTenantMemberships();
		}elseif ( $request->type_membership == Membership::TYPE_OWNER ) {
		  $db_memberships = Membership::getOwnerMemberships();
		}elseif ( $request->type_membership == Membership::TYPE_AGENT ) {
		  $db_memberships = Membership::getAgentMemberships();
		}elseif ( $request->type_membership == Membership::TYPE_SERVICE ) {
		  $db_memberships = Membership::getServiceMemberships();
		}elseif ( $request->type_membership == Membership::TYPE_COLLATERAL ) {
		  $db_memberships = Membership::getCollateralMemberships();
		}
		foreach ($db_memberships as $m) {
			$membership = (object)[
				'id' => $m->id,
				'name' => $m->name,
				'features' => json_decode($m->features,true),
				'enabled' => $m->enabled,
				'role_id' => $m->role_id
			];
			$memberships[] = $membership;
		}
		
		return response([
			'memberships' => $memberships
		]);
	}

	public function newsletterRegisterForm(Request $request){

		$newsletter = Newsletter::where('email',$request->other_email)->first();
		if (is_null($newsletter)) {
		  $newsletter = new Newsletter();
		}
		$commune = Commune::where('name',$request->city)->first();
		$newsletter->bathrooms = $request->bathrooms;
		$newsletter->bedrooms = $request->bedrooms;
		$newsletter->price = str_replace(".","",$request->price);
		$newsletter->firstname = $request->firstname;
		$newsletter->lastname = $request->lastname;
		$newsletter->email = $request->other_email;
		$newsletter->cell_phone = str_replace('-','',$request->cell_phone);
		if (!is_null($commune)) { $newsletter->commune_id = $commune->id; };
		$furnished_date = str_replace('/', '-', $request->furnished_date);
		$newsletter->furnished_date = date('Y-m-d', strtotime($furnished_date));
		$newsletter->save();
		return json_encode(['data' => $newsletter]);
	}

	public function contactForm(Request $request) {
		if($request->isMethod('post')) {
			$this->validate($request, [
				'name'  => 'required|string|regex:/([a-zA-Z ]+)/i|max:30',
				'phone' => 'required|string|regex:/[0-9]{8,10}/i',
				'reason_contact' => [
					'required',
					Rule::in(ContactMessage::getReasonsList())
				],
				'message' => 'nullable|string|max:500',
				// 'recaptcha' => [new \App\Rules\ReCaptcha] Comentado para pruebas
			]);

			$contact_message = new ContactMessage($request->all());

			if($contact_message->save()) {
				$admin_email = env('MAIL_USERNAME', false);

				#-- Send mail to admin
				Notification::route('mail', $admin_email)
					->notify(new \App\Notifications\Contact($contact_message));

				#-- Send mail to user
				Notification::route('mail', $contact_message->email)
					->notify(new \App\Notifications\Contact($contact_message, \App\Notifications\Contact::TO_USER));
			}

			return 'OK';
		}

		if($request->isMethod('get')) {
			return view('pages.contact');
		}
	}

	public function getTerms(){
		//return response()->download(storage_path("app/terms/terminos_condiciones.pdf"));
		return redirect('https://uhomiehelp.zendesk.com/hc/es-419/categories/360001842192-Pol%C3%ADticas-de-UHOMIE');
	}
}
