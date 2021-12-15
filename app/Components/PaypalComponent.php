<?php 
    namespace App\Components;

use Illuminate\Support\Facades\Log;

// paypal
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\AddressType;
use PayPal\EBLBaseComponents\BillingAgreementDetailsType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;

use PayPal\PayPalAPI\GetExpressCheckoutDetailsReq;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsRequestType;

use PayPal\EBLBaseComponents\DoExpressCheckoutPaymentRequestDetailsType;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentReq;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentRequestType;

class PaypalComponent{
    public static $apiUsername="";
    public static $apiPassword="";
    public static $apiSignature="";
	public static function getUrl($res)
    {
        $success = false;
        $redirect = false;
        $referenceToken = '';
        $sandboxRedirect = "https://www.sandbox.paypal.com/checkoutnow?token=";
        $liveRedirect = "https://www.paypal.com/checkoutnow?token=";
		
        $config = self::getConfig();
        // var_dump($config);
        $amountToPay = $res->total;
        $currencyCode = $res->currencyCode;

        $cancelUrl = url('cart/error');
        $returnUrl = url('cart/paypalpayment');

        $itemAmount = new BasicAmountType($currencyCode, $amountToPay);
        $itemDetails = new PaymentDetailsItemType();
        $itemDetails->Name = 'Reservation';
        $itemDetails->Amount = $itemAmount;
        $itemDetails->Quantity = 1;
        // $itemDetails->ItemCategory = 'Digital';
        $itemDetails->ItemCategory = 'Physical';

        $paymentDetails = new PaymentDetailsType();
        $paymentDetails->PaymentDetailsItem[] = $itemDetails;
        $paymentDetails->OrderTotal = new BasicAmountType($currencyCode,$amountToPay);
        $paymentDetails->NotifyURL = url('cart/paypalipn');

        $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
        $setECReqDetails->PaymentDetails[] = $paymentDetails;
        $setECReqDetails->CancelURL = $cancelUrl;
        $setECReqDetails->ReturnURL = $returnUrl;
        $setECReqDetails->NoShipping = 1;

        // Display options
        // $setECReqDetails->cppheaderimage = $_REQUEST['cppheaderimage'];
        // $setECReqDetails->cppheaderbordercolor = $_REQUEST['cppheaderbordercolor'];
        // $setECReqDetails->cppheaderbackcolor = $_REQUEST['cppheaderbackcolor'];
        // $setECReqDetails->cpppayflowcolor = $_REQUEST['cpppayflowcolor'];
        // $setECReqDetails->cppcartbordercolor = $_REQUEST['cppcartbordercolor'];
        // $setECReqDetails->cpplogoimage = $_REQUEST['cpplogoimage'];
        // $setECReqDetails->PageStyle = $_REQUEST['pageStyle'];
        // $setECReqDetails->BrandName = $_REQUEST['brandName'];

        $setECReqType = new SetExpressCheckoutRequestType();
        $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
        $setECReq = new SetExpressCheckoutReq();
        $setECReq->SetExpressCheckoutRequest = $setECReqType;

        $paypalService = new PayPalAPIInterfaceServiceService($config);

        try {
            /* wrap API method calls on the service object with a try catch */
            $setECResponse = $paypalService->SetExpressCheckout($setECReq);
            Log::info('Paypal setEC: '.json_encode($setECResponse));
            // Yii::info($setECResponse,'paypalApi');
            if (isset($setECResponse->Ack) && $setECResponse->Ack=='Success') {
                $referenceToken = $setECResponse->Token;
                $redirect = $config['mode']=='sandbox'?$sandboxRedirect:$liveRedirect;
                $redirect.=$setECResponse->Token;
                $success = true;
            }else{
                // var_dump($setECResponse);
            }
        } catch (\Exception $ex) {
            Log::info('Paypal setEC error: '.json_encode($ex));
        }
        return [$success,$redirect,$referenceToken];
    }
	public static function doCheckout($res,$token,$payerId)
    {
    	$success = false;
    	$reference = '';
    	$config = self::getConfig();

        $amountToPay = $res->total;
        $currencyCode = $res->currencyCode;

		$orderTotal = new BasicAmountType();
		$orderTotal->currencyID = $currencyCode;
		$orderTotal->value = $amountToPay;
		$paymentDetails= new PaymentDetailsType();
		$paymentDetails->OrderTotal = $orderTotal;
        $paymentDetails->NotifyURL = url('cart/paypalipn');

    	$DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
		$DoECRequestDetails->PayerID = $payerId;
		$DoECRequestDetails->Token = $token;
		$DoECRequestDetails->PaymentAction = 'Sale';
		$DoECRequestDetails->PaymentDetails[0] = $paymentDetails;

		$DoECRequest = new DoExpressCheckoutPaymentRequestType();
		$DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;

		$DoECReq = new DoExpressCheckoutPaymentReq();
		$DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

        $paypalService = new PayPalAPIInterfaceServiceService($config);
		try {
			/* wrap API method calls on the service object with a try catch */
			$DoECResponse = $paypalService->DoExpressCheckoutPayment($DoECReq);
            Log::info('Paypal DoEC: '.json_encode($DoECResponse));
            
            if ($DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo) {            
                $success = true;
                $reference = []; //Se obtienen la información de pago directamente de la respuesta de paypal.
                $reference["transactionId"] = $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->TransactionID;
                $reference["amount"] = $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->GrossAmount->value;
                $reference["currencyCode"] = $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->GrossAmount->currencyID;
            }
        } catch (\Exception $ex) {
            Log::info('Paypal DoEC error: '.json_encode($ex));
        }
		return [$success,$reference];
    }
    public static function getConfig()
    {
        if(self::$apiUsername=="" || self::$apiPassword==""){
            return [
               'mode' => env('APP_ENV')=='production'?'live':'sandbox',
               'acct1.UserName' => SettingsComponent::get('paypalUser'),
               'acct1.Password' => SettingsComponent::get('paypalPass'),           
               "acct1.Signature" => SettingsComponent::get('paypalSign'),
            ];            
        }else{
            return [
               'mode' => env('APP_ENV')=='production'?'live':'sandbox',
               'acct1.UserName' => self::$apiUsername,
               'acct1.Password' => self::$apiPassword,           
               "acct1.Signature" => self::$apiSignature,
            ];
        }
        return $config;
    }
    public static function initApiKeys($affiliateId)
    {
        self::$apiUsername = SettingsComponent::get('paypalUser',$affiliateId);
        self::$apiPassword = SettingsComponent::get('paypalPass',$affiliateId);
        self::$apiSignature = SettingsComponent::get('paypalSign',$affiliateId);
    }
}