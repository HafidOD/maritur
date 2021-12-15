<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\State;
use App\City;
use App\Image;
// use Carbon\Carbon;
use App\Hotel;
use App\Tour;
use App\TourPrice;
use App\Reservation;
use App\RoomReservation;
use App\TourReservation;
use App\HotelReservation;
use App\TransportReservation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Components\OmnibeesApiComponent;
use App\Components\CurrenciesComponent;
use App\Components\StripeComponent;
use App\Components\PaypalComponent;
use App\Components\ToursComponent;
use App\Components\TransportComponent;
use App\Components\SettingsComponent;
use App\Mail\BookingConfirmation;
use App\Mail\TryBookingNotification;
use Illuminate\Support\Facades\Mail;


class CartController extends Controller
{
	public function actionIndex(Request $request)
	{
    $roomsSelection = \SC::getRoomsSelection();
    $toursSelection = \SC::getToursSelection();
    $transfersSelection = \SC::getTransfersSelection();
    $quoteParams = \SC::getQuoteParams();
    $currencyCode = \SC::getCurrentCurrencyCode();
    $hotelName = '';
    if ($roomsSelection->hotelCode>0) {
      $hotelName = Hotel::where('code',$roomsSelection->hotelCode)->first()->name;
    }
    $roomsCart = $this->getRoomsCart($roomsSelection->rooms,$currencyCode);
    $toursCart = $this->getToursCart($toursSelection,$currencyCode);
    $transfersCart = $this->getTransfersCart($transfersSelection,$currencyCode);
    // dd($transfersCart);
    list($roomsSubtotal,$toursSubtotal,$transfersSubtotal,$subtotal,$total) = $this->getTotals($roomsCart,$toursCart,$transfersCart);
    return view('cart.index',[
      'quoteParams' => $quoteParams,
      'hotelName' => $hotelName,
      'currencyCode' => $currencyCode,
      'roomsCart' => $roomsCart,
      'toursCart' => $toursCart,
      'transfersCart' => $transfersCart,
      'roomsSubtotal'=>$roomsSubtotal,
      'toursSubtotal'=>$toursSubtotal,
      'transfersSubtotal'=>$transfersSubtotal,
      'subtotal'=>$subtotal,
      'total'=>$total,
    ]);
  }
  public function getTotals($roomsCart,$toursCart,$transfersCart)
  {
    $total = 0;
    $subtotal = 0;
    $roomsSubtotal = 0;
    foreach ($roomsCart as $room){
      $roomsSubtotal+=$room->subtotal;
      $total+=$room->total;
    }
    $toursSubtotal = 0;
    foreach ($toursCart as $tour){
      $toursSubtotal+=$tour->subtotal;
      $total+=$tour->total;
    } 
    $transfersSubtotal = 0;
    foreach ($transfersCart as $transfer){
      $transfersSubtotal+=$transfer->subtotal;
      $total+=$transfer->total;
    } 
    $subtotal = $roomsSubtotal + $toursSubtotal + $transfersSubtotal;
    return [$roomsSubtotal,$toursSubtotal,$transfersSubtotal,$subtotal,$total];
  }
  public function getTransfersCart($transfersSelection,$currencyCode)
  {
    $transfers = [];
    $exchangeRate = CurrenciesComponent::getExchangeRate('USD',$currencyCode);
    foreach ($transfersSelection as $index => $t) {
      $transportService = TransportComponent::getValidTransportService($t->transportServiceId,$t->pax);
      list($onewayPrice,$roundtripPrice) = TransportComponent::transportPrices($transportService,$t->pax);
      $total = $t->triptype == 'oneway'?$onewayPrice:$roundtripPrice;
      $transfers[] = (object)[
        'index'=>$index,
        'image'=>$transportService->transportServiceType->getPrimaryImageUrl(750,550),
        'serviceName'=>$transportService->transportServiceType->name,
        'destinationName'=>$this->getTransferDestinationName($t),
        'triptype'=>$t->triptype,
        'arrival'=>new \Carbon($t->arrival),
        'pax'=>$t->pax,
        'subtotal'=>round($total * $exchangeRate/1.16,2),
        'total'=>round($total * $exchangeRate,2),
      ];
    }
    return $transfers;
  }
  public function getTransferDestinationName($transfer)
  {
    $aux = explode('.', $transfer->destinationHash);
    if($aux[0]=='h'){
      return Hotel::find($aux[1])->name;
    }else if($aux[0]=='t'){
      return Tour::find($aux[1])->name;
    }else if($aux[0]=='d'){
      return City::find($aux[1])->name;
    }
    return "";
  }
  public function onlyAffiliateTours($toursSelection)
  {
    $allToursAffiliate = true;
    foreach ($toursSelection as $t) {
      $tourPrice = TourPrice::find($t->tourPriceId);
      if($tourPrice->tour->affiliateId != \SC::$affiliateId){
        $allToursAffiliate = false;
        break;
      }
    }
    return $allToursAffiliate;
  }
  public function getToursCart($toursSelection,$currencyCode)
  {
    $tours = [];
    $exchangeRate = CurrenciesComponent::getExchangeRate('USD',$currencyCode);
    // dd($toursSelection);
    foreach ($toursSelection as $index => $t) {
      $tourPrice = TourPrice::find($t->tourPriceId);
      $fromDestination =  $t->destinationTransportId?City::find($t->destinationTransportId):null;
      list($total) = ToursComponent::calculateTotal($tourPrice,\SC::$affiliateId,$t->adults,$t->children,$fromDestination);
      $tours[] = (object)[
        'index'=>$index,
        'image'=>$tourPrice->tour->getPrimaryImageUrl(750,550),
        'name'=>$tourPrice->tour->name,
        'variant'=>$tourPrice->name,
        'day'=>new \Carbon($t->day),
        'adults'=>$t->adults,
        'children'=>$t->children,
        'fromDestination'=>$fromDestination?$fromDestination->name:false,
        'subtotal'=>round($total * $exchangeRate/1.16,2),
        'total'=>round($total * $exchangeRate,2),
      ];
    }
    return $tours;
  }
  public function getRoomsCart($roomsSelection,$currencyCode)
  {
    $rooms = [];
    foreach ($roomsSelection as $r) {
      $exchangeRate = CurrenciesComponent::getExchangeRate($r->currencyCode,$currencyCode);
      $rooms[] = (object)[
        'image'=>$r->image,
        'rph'=>$r->rph,
        'roomTypeName'=>$r->roomTypeName,
        'ratePlanName'=>$r->ratePlanName,
        'adults'=>$r->adults,
        'ages'=>$r->ages,
        'children'=>$r->children,
        'subtotal'=>round($r->total * $exchangeRate/1.19,2),
        'total'=>round($r->total * $exchangeRate,2),
      ];
    }
    return $rooms;
  }
  public function actionInfo()
  {
    $roomsSelection = \SC::getRoomsSelection();
    $toursSelection = \SC::getToursSelection();
    $transfersSelection = \SC::getTransfersSelection();
    $quoteParams = \SC::getQuoteParams();
    $currencyCode = \SC::getCurrentCurrencyCode();
    $hotelName = '';
    if ($roomsSelection->hotelCode>0) {
      $hotelName = Hotel::where('code',$roomsSelection->hotelCode)->first()->name;
    }
    $roomsCart = $this->getRoomsCart($roomsSelection->rooms,$currencyCode);
    $toursCart = $this->getToursCart($toursSelection,$currencyCode);
    $transfersCart = $this->getTransfersCart($transfersSelection,$currencyCode);
    list($roomsSubtotal,$toursSubtotal,$transfersSubtotal,$subtotal,$total) = $this->getTotals($roomsCart,$toursCart,$transfersCart);

    $countries = Country::all();
    list($validPaymentMethods,$affiliateId) = $this->getValidPaymentMethods($roomsSelection,$toursSelection,$transfersSelection);
    return view('cart.info',[
      'quoteParams' => $quoteParams,
      'hotelName' => $hotelName,
      'currencyCode' => $currencyCode,
      'roomsCart' => $roomsCart,
      'toursCart' => $toursCart,
      'transfersCart' => $transfersCart,
      'roomsSubtotal'=>$roomsSubtotal,
      'toursSubtotal'=>$toursSubtotal,
      'transfersSubtotal'=>$transfersSubtotal,
      'subtotal'=>$subtotal,
      'total'=>$total,
      'countries' => $countries,
      'paymentMethods' => $validPaymentMethods,
    ]);
  }
  public function actionCheckout(Request $request)
  {
      OmnibeesApiComponent::$useCache = false;
      $currencyCode = \SC::getCurrentCurrencyCode();
      $roomsSelection = \SC::getRoomsSelection();
      $transfersSelection = \SC::getTransfersSelection();
      $toursSelection = \SC::getToursSelection();
      $hotelQuoteParams = \SC::getQuoteParams();

      $hasHotelRooms = $roomsSelection->hotelCode > 0 && count($roomsSelection->rooms)>0;
      $hasTours = count($toursSelection)>0;
      $hasTransfers = count($transfersSelection)>0;

      list($paymentMethods,$paymentMethodAffiliateId) = $this->getValidPaymentMethods($roomsSelection,$toursSelection,$transfersSelection);

      DB::beginTransaction();
      $success = false;
      $message = '';
      $redirect = '/cart/success';

      $cardData = $this->getCardData($request);
      $adminRes = $this->isAdminRes($cardData);
      try {
        // Global Reservation
        $res = new Reservation;
        $res->fill($request->input());
        $res->currencyCode = $currencyCode;      
        $res->token = str_random(30);
        $res->paymentToken = str_random(30);
        $res->status = Reservation::STATUS_RESERVED;
        $res->paymentStatus = Reservation::PAYMENT_STATUS_PENDING;
        $res->paymentMethod = $this->getPaymentMethod()=='stripe'?Reservation::PAYMENT_METHOD_STRIPECARD:Reservation::PAYMENT_METHOD_PAYPAL;
        $res->affiliateId = \SC::$affiliateId;
        if(!$res->save()) throw new \Exception("Error save reservation",10);

        if ($hasHotelRooms) {
          $hotel = Hotel::where('code',$roomsSelection->hotelCode)->first();
          $res->hotelname = $hotel->name;
          
          $avData = OmnibeesApiComponent::getHotelRequestData($hotel,$hotelQuoteParams);
          $guestNames = $request->input('guestNames');
          $primaryGuest = $request->input('primaryGuest');
          list($primaryGuestRph,$primaryGuestIndex) = explode('.',$primaryGuest);

          $hotelRes = new HotelReservation;
          $hotelRes->status = 1; //pendiente usar estados
          // $hotelRes->reservationId = $res->id;
          $hotelRes->arrival = $hotelQuoteParams->arrival;
          $hotelRes->departure = $hotelQuoteParams->departure;
          if((new \DateTime($hotelRes->arrival))->diff(new \DateTime(date('Y-m-d')))->days <= 2 && !$adminRes) {
            throw new \Exception("Error min arrival date",10);
          }
          $guestsData = self::generateJsonGuestNames($guestNames,$primaryGuestRph,$primaryGuestIndex,$res);
          $hotelRes->hotelId = $hotel->id;
          if(!$res->hotelReservation()->save($hotelRes)) throw new \Exception("Error save hotel reservation",10);
          // obtiene el objeto relacionado por referencia para agregar las habitaciones
          $hotelRes = $res->hotelReservation;
          
          // habitacions
          $rooms = [];
          $exchangeRate = 1;
          foreach ($roomsSelection->rooms as $room) {
            // obtener la cotización original
            $roomRate = OmnibeesApiComponent::findRoomRateData($room->rph,$room->roomTypeId,$room->ratePlanId,$avData);
            if($roomRate==null) throw new \Exception("No Room Rate", 1);
            
            $exchangeRate = CurrenciesComponent::getExchangeRate(OmnibeesApiComponent::getCurrencies()[$avData->RoomStaysType->RoomStays[0]->RoomRates[0]->Total->CurrencyCode],$currencyCode);

            $newRoom = new RoomReservation;
            $newRoom->status = RoomReservation::STATUS_RESERVED;
            $newRoom->refRoomTypeId = $room->roomTypeId;
            $newRoom->refRoomTypeName = $room->roomTypeName;
            $newRoom->refRatePlanId = $room->ratePlanId;
            $newRoom->refRatePlanName = $room->ratePlanName;
            $newRoom->adults = $room->adults;
            $newRoom->children = $room->children;
            $newRoom->jsonAges = $room->ages;
            $newRoom->jsonRefExtraData = ['rph'=>$room->rph,'guestsData'=>$guestsData[$room->rph]];

            $newRoom->refSubtotal = $roomRate->Total->AmountBeforeTax;
            $newRoom->refTotal = $roomRate->Total->AmountAfterTax;
            $newRoom->markup = round($newRoom->refTotal * OmnibeesApiComponent::$goguyMarkup,2);

            // totales en moneda de cotización
            $quoteCurrencyCode = OmnibeesApiComponent::getCurrencies()[$roomRate->Total->CurrencyCode];
            $newRoom->total = round(($newRoom->refTotal + $newRoom->markup) * $exchangeRate,2);
            $newRoom->subtotal = round($newRoom->total/1.19,2); //IVA
            if( abs($newRoom->total - $room->total*$exchangeRate) > 5 ) throw new \Exception("Prices have changed, please perform your search again", 60);
            
            $rooms[] = $newRoom;
            $refCurrencyCode = $quoteCurrencyCode;
          }
          $hotelRes->refCurrencyCode = $refCurrencyCode;      
          $hotelRes->exchangeRate = $exchangeRate;
          // termina habitaciones
          // guarda habitaciones
          if(!$hotelRes->rooms()->saveMany($rooms)) throw new \Exception("Error saving rooms", 30);
          $hotelRes->updateTotals();
        }

        if ($hasTours) {
          $exchangeRate = CurrenciesComponent::getExchangeRate('USD',$currencyCode);
          foreach ($toursSelection as $index => $t) {
            $tourPrice = TourPrice::find($t->tourPriceId);
            $fromDestination =  $t->destinationTransportId?City::find($t->destinationTransportId):null;
            list($total, $adultPrice, $childrenPrice) = ToursComponent::calculateTotal($tourPrice,\SC::$affiliateId,$t->adults,$t->children,$fromDestination);

            $tourRes = new TourReservation;
            $tourRes->day = $t->day;
            $tourRes->tourPriceId = $tourPrice->id; 
            $tourRes->adults = $t->adults;
            $tourRes->children = $t->children;
            $tourRes->adultPrice = $adultPrice;
            $tourRes->childrenPrice = $childrenPrice;
            $tourRes->subtotal = round($total * $exchangeRate/1.16,2);
            $tourRes->total = round($total * $exchangeRate,2);
            $tourRes->fromDestinationId = $fromDestination?$fromDestination->id:-1;
            $tours[] = $tourRes;
          }
          if(!$res->toursReservations()->saveMany($tours)) throw new \Exception("Error saving tours", 30);
        }
        if ($hasTransfers) {
          $exchangeRate = CurrenciesComponent::getExchangeRate('USD',$currencyCode);
          $transports = [];
          foreach ($transfersSelection as $index => $t) {
            $transportService = TransportComponent::getValidTransportService($t->transportServiceId,$t->pax);
            list($onewayPrice,$roundtripPrice) = TransportComponent::transportPrices($transportService,$t->pax);
            $total = $t->triptype == 'oneway'?$onewayPrice:$roundtripPrice;

            $newTransport = new TransportReservation;
            $newTransport->status = TransportReservation::STATUS_RESERVED;
            $newTransport->triptype = $t->triptype=='oneway'?TransportReservation::TRIPTYPE_ONEWAY:TransportReservation::TRIPTYPE_ROUNDTRIP;
            $newTransport->transportServiceTypeId = $transportService->transportServiceTypeId;
            $newTransport->arrivalDatetime = $request->input('transport.arrivalDate').' '.$request->input('transport.arrivalHour').':'.$request->input('transport.arrivalMinut').':00';
            $newTransport->arrivalFlight = $request->input('transport.arrivalFlight');
            $newTransport->pax = $t->pax;
            if ($t->triptype=='roundtrip') {
              $newTransport->departureDatetime = $request->input('transport.departureDate').' '.$request->input('transport.departureHour').':'.$request->input('transport.departureMinut').':00';
              $newTransport->departureFlight = $request->input('transport.departureFlight');
            }
            $newTransport->subtotal = round($total * $exchangeRate/1.16,2);
            $newTransport->total = round($total * $exchangeRate,2);

            $aux = explode('.', $t->destinationHash);
            if($aux[0] == 'h'){
              $newTransport->hotelId = $aux[1];
            }else if($aux[0] == 't'){
              $newTransport->tourId = $aux[1];
            }else if($aux[0] == 'd'){
              $newTransport->destinationId = $aux[1];
            }

            $transports[] = $newTransport;
          }
          if(!$res->transportReservations()->saveMany($transports)) throw new \Exception("Error saving transfers", 60);

        }
        // actualiza totales
        $res->updateTotals();
        // var_dump($res->subtotal);
        // var_dump($res->total);

        // intenta tokenizar tarjeta
        $stripeCharge = null;
        if ($res->paymentMethod == Reservation::PAYMENT_METHOD_STRIPECARD) {
          // $stripeCharge = true;
          if (!$adminRes && !$this->onDebug()) {
            // inicializa stripe
            StripeComponent::initApiKeys($paymentMethodAffiliateId);
            list($card,$message) = StripeComponent::getCard($cardData);
            if ($card==null) throw new \Exception($message, 20);
            $cardData['cardBrand'] = $card->card->brand;

            // intenta generar el cargo stripe, aun no hace el cargo real solo lo genera
            list($stripeCharge,$message) = StripeComponent::makeCharge($res,$card->id,$res->total,$res->currencyCode);
            if ($stripeCharge==null) throw new \Exception($message, 40);
          }
        }else if($res->paymentMethod == Reservation::PAYMENT_METHOD_PAYPAL){
          PaypalComponent::initApiKeys($paymentMethodAffiliateId);
          list($successPaypal,$redirect,$referenceToken) = PaypalComponent::getUrl($res);
          if($successPaypal){
            $res->paymentReferenceCode = $referenceToken;
            $success = true;
            $res->save();
          }else{
            throw new \Exception("Error paypal", 50);
          }
        }

        
          // ahora intenta subir la reserva omnibees si su llegada es de mas de 30 dias.
        if ($hasHotelRooms && ((new \Datetime($hotelRes->arrival))->diff(new \Datetime('now'))->days > 30 || $adminRes)) {

            list($reqSuccess,$resData) = OmnibeesApiComponent::getDoReservationRequest($hotelRes,$avData,$cardData);
            if(!$reqSuccess) throw new \Exception("Error omnibees response", 50);
            else OmnibeesApiComponent::setReferenceData($hotelRes,$resData);
        }
        // ejecuta el pago de stripe y obtiene datos de referencia
        if($stripeCharge!=null){
          $paymentRefData = StripeComponent::doCharge($stripeCharge);
          $res->paymentStatus = Reservation::PAYMENT_STATUS_PAYED;
          $res->paymentReference = $paymentRefData;
        }
        // guardar referencia de reserva y habitaciones
        $res->save();
        $redirect = "/cart/success?token=".$res->token;
        $success = true;
        \SC::removeData();
        DB::commit();
      } catch (\Exception $e) {
        // echo $e->getMessage();
        // var_dump($e);
        if (in_array($e->getCode(), [10,20,40,50,60])) $message = $e->getMessage();
        if($e->getCode() == 60) \SC::removeData();
        if($e->getCode() == 40) {
          // fallo el pago con tarjeta de stripe, enviar correo para seguimiento del intento
          Mail::send(new TryBookingNotification($res));
        }
        DB::rollBack();
      }
      // envia correo con confirmación
      if ($success) Mail::send(new BookingConfirmation($res));

      return response()->json([
        'success'=>$success,
        'redirect'=>$redirect,
        'message'=>$message,
      ]);
  }
  public function onDebug()
  {
    // return false;
    return env('APP_ENV')!='production';
  }
  public function getAffiliatePayment($paymentMethod)
  {
    $paymentMethods = SettingsComponent::get('paymentMethods');
    if(in_array($paymentMethod, $paymentMethods))
    if($hasHotels || count($paymentMethods)==0 || (count($paymentMethods)==1 && $paymentMethods[0]=='paypal')){
      // usa los metodos de pago del afiliado principal
      $paymentMethods = SettingsComponent::get('paymentMethods',1);
    }
    $validPaymentMethods = [];
    if(!$hasHotels && in_array('paypal', $paymentMethods)) $validPaymentMethods[] = 'paypal';
    if(in_array('stripe', $paymentMethods)) $validPaymentMethods[] = 'directcard';
    return $validPaymentMethods;

  }
  public function getCardData(Request $request)
  {
    return [
      'cardHolder'=>$request->input('cardHolder',''),
      'cardNumber'=>$request->input('cardNumber',''),
      'cardMonth'=>$request->input('cardMonth',''),
      'cardYear'=>$request->input('cardYear',''),
      'cardCvc'=>$request->input('cardCvc',''),
      'cardPostalCode'=>$request->input('cardPostalCode',''),
    ];
  }
  public function getValidPaymentMethods($roomsSelection,$toursSelection,$transfersSelection)
  {
    // usa los metodos de pago del afiliado principal
    $paymentMethods = SettingsComponent::get('paymentMethods',1);
    $affiliateId = 1;
    $affiliatePaymentMethods = SettingsComponent::get('paymentMethods');
    $hasRooms = count($roomsSelection->rooms);
    $onlyAffiliateItems = $hasRooms==0;
    if (count($affiliatePaymentMethods)>0 && $onlyAffiliateItems) {
      foreach ($toursSelection as $index => $t) {
        $tourPrice = TourPrice::find($t->tourPriceId);
        if($tourPrice->tour->affiliateId != \SC::$affiliateId){
          $onlyAffiliateItems = false;
          break;
        }
      }
      if ($onlyAffiliateItems) {
        foreach ($transfersSelection as $index => $t) {
          $transportService = TransportComponent::getValidTransportService($t->transportServiceId,$t->pax);
          if($transportService->transportServiceType->affiliateId != \SC::$affiliateId){
            $onlyAffiliateItems = false;
            break;
          }
        }
      }
      if($onlyAffiliateItems){
        $paymentMethods =$affiliatePaymentMethods;
        $affiliateId = \SC::$affiliateId;
      }
    }

    $validPaymentMethods = [];
    if(!$hasRooms && in_array('paypal', $paymentMethods)) $validPaymentMethods[] = 'paypal';
    if(in_array('stripe', $paymentMethods)) $validPaymentMethods[] = 'directcard';
    // echo $affiliateId;
    return [$validPaymentMethods,$affiliateId];
  }
  public function getPaymentMethod()
  {
    $paymentMethod = request()->input('paymentMethod',false);
    if($paymentMethod === false) throw new Exception("no payment methods", 1);
    if($paymentMethod=='directcard'){
      // busca el tipo de servicio utilizado
      // list($validPaymentMethods,$affiliateId) = $this->getValidPaymentMethods();
      // if(in_array('stripe', $validPaymentMethods)) $paymentMethod = 'stripe';
      $paymentMethod = 'stripe';
    }
    return $paymentMethod; 
  }
  public function isAdminRes($cardDatas)
  {
    if($cardDatas['cardHolder']!='mauro.perez@goguytravel.com') return false;
    if($cardDatas['cardCvc']!='0101') return false;
    return true;
  }
  public function generateJsonGuestNames($guestNames,$primaryGuestRph,$primaryGuestIndex,$reservation)
  {
    $newGuestNames = [];
    foreach ($guestNames as $rph => $roomGuests) {
      $newGuestNames[$rph] = [];
      foreach ($roomGuests as $i => $age) {
          $isPrimary = $primaryGuestRph==$rph && $i==$primaryGuestIndex;
          $newGuestNames[$rph][]=[
            'isPrimary'=>$isPrimary,
            'givenName'=>$isPrimary?$reservation->clientFirstName:$guestNames[$rph][$i],
            'middleName'=>$isPrimary?$reservation->clientLastName:'',
            'email'=>$isPrimary?$reservation->clientEmail:'',
            'addressLine'=>$isPrimary?$reservation->clientAddress:'',
            'cityName'=>$isPrimary?$reservation->clientCity.', '.$reservation->clientState:'',
            'countryCode'=>$isPrimary?$reservation->clientCountryId:'',
            'postalCode'=>$isPrimary?$reservation->clientZipcode:'',
          ];
      }
    }
    return $newGuestNames;
  }
	public function getRoomsSelectionData($roomRates,$rph,$roomTypeId,$ratePlanId)
	{
		$roomRate = null;
		foreach ($roomRates as $rr) {
			if ($rr->roomType->id==$roomTypeId && $rr->ratePlan->id==$ratePlanId && $rr->rphRoom==$rph) {
				$roomRate = $rr;
				break;
			}
		}
		return $roomRate;
	}
  public function actionPaypalIpn()
  {
    return "paypal ipn";
  }
  public function actionPaypalPayment(Request $request)
  {
    $token = $request->input('token');
    $PayerID = $request->input('PayerID');
    $res = Reservation::where('paymentReferenceCode',$token)->first();
    if ($res) {
      if ($res->paymentStatus!=Reservation::PAYMENT_STATUS_PAYED) {
        list($success,$reference) = PaypalComponent::doCheckout($res,$token,$PayerID);
        if ($success) {
          $res->paymentStatus = Reservation::PAYMENT_STATUS_PAYED;
          $res->paymentReference = $reference;
          $res->save();
          return redirect('cart/success');
          // return "success";
        }else{
          // return "error";
          return redirect('cart/error');
        }
      }else{
        return "Reserva ya pagada";
      }
    }
  }
  public function removeTransfer(Request $request)
  {
    $index = intval($request->input('index'));
    \SC::removeTransfer($index);
  }
  public function removeTour(Request $request)
  {
    $index = intval($request->input('index'));
    \SC::removeTour($index);
  }
  public function addTour(Request $request)
  {
    $success = true;
    $tp = $request->input('tp');
    $adults = $request->input('adults');
    $children = $request->input('children');
    $transportation = $request->input('transportation');
    $tourPrice = TourPrice::find($tp);
    $quoteParams = \SC::getToursQuoteParams();
    if ($tourPrice && $tourPrice->adultPrice>0) {
      if ($transportation>0) {
        if($tourPrice->tourPriceTransportations()->where('destinationId',$transportation)->count()==0){
          $success = false;
        }
      }
      if ($success) {
        \SC::addTourSelection($tourPrice->id,$quoteParams->arrival,$adults,$children,$transportation);
      }
    }
    return ['success'=>$success];
  }
  public function addTransfer(Request $request)
  {
    $success = true;
    $ts = $request->input('ts');
    $pax = $request->input('pax');
    $arrival = $request->input('arrival');
    $triptype = $request->input('triptype');
    $destination = $request->input('destination');
    $transportService = TransportComponent::getValidTransportService($ts,$pax);
    if ($transportService) {
        \SC::addTransferSelection($transportService->id,$arrival,$pax,$triptype,$destination);
    }
    return ['success'=>$success];
  }
  public function actionConfirm($token)
  {
    $res = Reservation::where('token',$token)->first();
    return \App\Components\ReservationsComponent::getConfirmationPdf($res);
  }
  public function actionAddRoom(Request $request)
    {
      $roomTypeId = $request->input('roomTypeId');
      $roomTypeName = $request->input('roomTypeName');
      $ratePlanId = $request->input('ratePlanId');
      $ratePlanName = $request->input('ratePlanName');
      $hotelCode = $request->input('hotelCode');
      $hotelName = $request->input('hotelName');
      $rph = $request->input('rph');
      $subtotal = $request->input('subtotal');
      $total = $request->input('total');
      $currencyCode = $request->input('currencyCode');
      $image = $request->input('image');
      $quoteParams = \SC::getQuoteParams();

      $adults = $quoteParams->adults[$rph];
      $children = $quoteParams->children[$rph];
      $ages = $children>0?$quoteParams->ages[$rph]:[];

      \SC::addRoom(
          $adults,
          $children,
          $ages,
          $hotelCode,
          $roomTypeId,
          $roomTypeName,
          $ratePlanId,
          $ratePlanName,
          $rph,
          $subtotal,
          $total,
          $currencyCode,
          $image
      );
      return response()->json([
        'success'=>true,
      ]);
    }
    public function actionRemove($i)
    {
      \SC::removeRoomByRph($i);
    }
    public function success(Request $request)
    {
        $token = $request->input('token');
        $res = Reservation::where('token',$token)->first();
        return view('cart.success',['res'=>$res]); 
    }
}