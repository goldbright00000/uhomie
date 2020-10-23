<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Freshwork\Transbank\CertificationBagFactory;
//use Freshwork\Transbank\TransbankServiceFactory;
use App\RedirectHelper;
use Transbank\Webpay\Configuration;
use Transbank\Webpay\Webpay;
use Illuminate\Support\Facades\Storage;
use App\Membership;
use Carbon\Carbon;
/** All Paypal Details class **/
use PayPal\Api\{Amount, Details, Item, ItemList, Payer, Payment as PPayment, PaymentExecution, RedirectUrls, Transaction};
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;

class Payment extends Model
{
	protected $fillable = [
		"order","user_id","coupon","status","amount","iva","total","method","currency","exchange_rate","details", "contract_id", "property_id", "tenanting_insurance","token_ws", "service_amount", "token"
	];
	/**
		Class Constants
	*/

	/*
		Payment's Statuses
	*/
	const STATUS_CREATED = 0;
	const STATUS_APPROVED = 1;
	const STATUS_REJECTED = 2;
	const STATUS_REFUNDED = 3;
	const STATUS_CANCELED = 4;

	const STATUS_TEXT = [
		self::STATUS_CREATED => "CREADO",
		self::STATUS_APPROVED => "APROBADO",
		self::STATUS_REJECTED => "RECHAZADO",
		self::STATUS_REFUNDED => "REEMBOLSADO",
		self::STATUS_CANCELED => "CANCELADO"
	];

	/* Transbank Result Statuses */
	const TB_APPROVED = 0;
	const TB_REJECTED = -1;
	const TB_REJECTED_RETRY = -2;
	const TB_REJECTED_ERROR = -3;
	const TB_REJECTED_OTHER = -4;
	const TB_REJECTED_FEE = -5;
	const TB_REJECTED_LIMIT_MONTH = -6;
	const TB_REJECTED_LIMIT_DAY = -7;
	const TB_REJECTED_NOT_AUTHORIZED = -8;

	const TB_STATUS_TEXT = [
		self::TB_APPROVED => "Aprobado.",
		self::TB_REJECTED => "Rechazo de transacción.",
		self::TB_REJECTED_RETRY => "Transacción debe reintentarse.",
		self::TB_REJECTED_ERROR => "Error en transacción.",
		self::TB_REJECTED_OTHER => "Rechazo de transacción.",
		self::TB_REJECTED_FEE => "Rechazo por error de tasa.",
		self::TB_REJECTED_LIMIT_MONTH => "Excede cupo máximo mensual.",
		self::TB_REJECTED_LIMIT_DAY => "Excede límite diario por transacción.",
		self::TB_REJECTED_NOT_AUTHORIZED => "Rubro no autorizado.",
	];

	public function user()
	{
		return $this->belongsTo('App\User');
	}
	public function membership()
	{
		return $this->belongsTo('App\Membership');
	}
	public function contract()
	{
		return $this->belongsTo('App\Contract', 'contract_id', 'id');
	}
 	#	private $_api_context;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	/*public function __construct()
	{
		$paypal_conf = \Config::get('paypal');
		$this->_api_context = new ApiContext(new OAuthTokenCredential(
			$paypal_conf['client_id'],
			$paypal_conf['secret'])
		);
		$this->_api_context->setConfig($paypal_conf['settings']);
	}*/
	public static function findByOrder($order){
		return self::where('order', $order)->first();
	}
	
	public function processToCheckoutMembershipTB($membership_id, $success_redir ,$back_url, $csrf_token){
		/*$transaction = (new Webpay(Configuration::forTestingWebpayPlusNormal()))
			   ->getNormalTransaction();*/
		$transaction = $this->getTransaction();
		
		$amount = $this->total;
		//$sessionId = Auth::user()->id; // Podría ser tambien ->activation_token (?)
		$sessionId = $csrf_token;
		$buyOrder = $this->order;
		$returnUrl = route('payments.intermediate-callback', ['payment_order' => $this->order, 'membership_id' => $membership_id]);
		$finalUrl = route('payments.end', ['payment_order' => $this->order, 'membership_id' => $membership_id,
		'success_redir' => $success_redir. $this->order, 'back_url' => $back_url . $this->order]);
		
		$initResult = $transaction->initTransaction(
				$amount, $buyOrder, $sessionId, $returnUrl, $finalUrl);
		
		$formAction = $initResult->url;
		$tokenWs = $initResult->token;
		$this->token_ws = $tokenWs;
		$this->save();

		return RedirectHelper::redirectHTML($formAction, $tokenWs);
	}
	private function getTransaction(){
		if( env('TB_ENVIRONMENT', 'DEV') == 'PRODUCCION'){
			$configuration = new Configuration();
			$configuration->setEnvironment("PRODUCCION");	
			$configuration->setCommerceCode((int)'597034426139');
			$configuration->setPrivateKey( file_get_contents(Storage::disk('certs')->getDriver()->getAdapter()->getPathPrefix().'597034426139.key' ) );
			$configuration->setPublicCert( file_get_contents(Storage::disk('certs')->getDriver()->getAdapter()->getPathPrefix().'597034426139.crt' ) );
			$configuration->setWebpayCert( file_get_contents(Storage::disk('certs')->getDriver()->getAdapter()->getPathPrefix().'serverTBK.crt' ) );
			
		} else {
			$configuration = Configuration::forTestingWebpayPlusNormal();
		}
		return (new Webpay($configuration))
			   ->getNormalTransaction();
	}
	public function processToCheckoutRentTBOficial($apply_property_id, $success_redir ,$back_url){

		/*$transaction = (new Webpay(Configuration::forTestingWebpayPlusNormal()))
			   ->getNormalTransaction();*/
		$transaction = $this->getTransaction();
			   
		$amount = $this->total;
		//$sessionId = Auth::user()->id; // Podría ser tambien ->activation_token (?)
		$sessionId = $apply_property_id;
		$buyOrder = $this->order;
		$returnUrl = route('rents.intermediate-callback', ['payment_order' => $this->order, 'apply_property_id' => $apply_property_id]);
		$finalUrl = route('rents.payment.end', ['payment_order' => $this->order, 'apply_property_id' => $apply_property_id,
		'success_redir' => $success_redir, 'back_url' => $back_url ]);
		$initResult = $transaction->initTransaction(
				$amount, $buyOrder, $sessionId, $returnUrl, $finalUrl);

		$formAction = $initResult->url;
		$tokenWs = $initResult->token;
		$this->token_ws = $tokenWs;
		$this->save();

		return RedirectHelper::redirectHTML($formAction, $tokenWs);
	}
	

	public static function generateOrder(){
		return 'o_' . Carbon::now()->format('ymd'). '_' .  \Auth::user()->id . '_' . self::where('user_id', \Auth::user()->id)->whereDate('created_at', Carbon::today())->count();
	}
	

	public function getTbResponse($token_ws){
		/*$transaction = (new Webpay(Configuration::forTestingWebpayPlusNormal()))
			   ->getNormalTransaction();*/
		$transaction = $this->getTransaction();
		$result = $transaction->getTransactionResult($token_ws);
		
		return $result;
	}

	public function redirectToVoucherTB($url){
		return RedirectHelper::redirectBackNormal($url);
	}
}
