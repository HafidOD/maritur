<?php 
    namespace App\Components;

use Illuminate\Support\Facades\Log;

class StripeComponent{
	public static $apiKeyLive = "";
	public static $apiKeySandbox = "";
	public static function makeCharge($res,$cardToken,$amount,$currencyCode)
	{
		$success = false;
		$charge = null;
		$message = '';
		$amount = round($amount,2)*100;
		self::setApiKey();

		try {
			$charge = \Stripe\Charge::create(array(
			  "amount" => $amount,
			  "currency" => strtolower($currencyCode),
			  "source" => $cardToken, // obtained with Stripe.js
			  "description" => "Reservation charge of ".\SC::getAffiliate()->domain,
			  "metadata"=>['resId'=>$res->id],
			  "capture" => false,
			));
			$success = true;
		} catch(\Stripe\Error\Base $e) {
			$body = $e->getJsonBody();
			$errorData = $body['error'];
			// var_dump($errorData);
			if ($errorData['type']=='card_error') {
				$message = $errorData['message'];
			}else{
       			 Log::info('Stripe '.json_encode($body));
				$message = 'Error al realizar el cargo, por favor intente mas tarde.';
			}
			$success = false;
		}
		return [$charge,$message];
	}
	public static function doCharge($charge)
	{
		$charge->capture();
		return [];
	}
	public static function getCard($cardData)
	{
		self::setApiKey();

		$card = null;
		$message = '';
		try {
			$card = \Stripe\Token::create([
			  "card" => [
			    "name" => $cardData['cardHolder'],
			    "number" => $cardData['cardNumber'],
			    "exp_month" => $cardData['cardMonth'],
			    "exp_year" => $cardData['cardYear'],
			    "cvc" => $cardData['cardCvc'],
			    "address_zip" => $cardData['cardPostalCode'],
			  ]
			]);			
		} catch(\Stripe\Error\Base $e) {
			$body = $e->getJsonBody();
			$errorData = $body['error'];
			if ($errorData['type']=='card_error') {
				$message = $errorData['message'];
			}else{
				$message = 'Error en datos de tarjeta';
			}
			$success = false;
		}
		return [$card,$message];
	}
	public static function setApiKey()
	{
		if (self::$apiKeyLive=="" || self::$apiKeySandbox=="") {
			self::$apiKeyLive = \SettingsComponent::get('stripeApiKeyLive'); 
			self::$apiKeySandbox = \SettingsComponent::get('stripeApiKeySandbox'); 
		}
		\Stripe\Stripe::setApiKey(env('STRIPE_ENV')=='production'?self::$apiKeyLive:self::$apiKeySandbox);
	}
	public static function initApiKeys($affiliateId)
	{
		self::$apiKeyLive = SettingsComponent::get('stripeApiKeyLive',$affiliateId);
		self::$apiKeySandbox = SettingsComponent::get('stripeApiKeySandbox',$affiliateId);
	}
}