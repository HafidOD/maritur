<?php 
	namespace App\Components;

use App\Hotel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class OmnibeesApiComponent
{
  public static $goguyMarkup = .35;
  public static $useCache = true;
  public static function tryPing()
  {
    $params = self::getBaseRequest();
    $params['EchoData'] = 'Hola mundo';
    $data = self::doResquestApi($params,'Ping');
    dd($data);
  }
  public static function getHotelInfo($hotelCode)
  {
      $params = self::getBaseRequest();
      $method = 'GetHotelDescriptiveInfo';
      $params['HotelDescriptiveInfosType']=[
        'HotelDescriptiveInfos'=>[
          [
            'HotelRef'=>[
              'HotelCode'=>$hotelCode,
            ],
            'HotelInfo'=>['SendData'=>true],
            'FacilityInfo'=>['SendGuestRooms'=>true],
            'Policies'=>['SendPolicies'=>true],
            'AreaInfo'=>['SendRefPoints'=>true,'SendAttractions'=>true],
            'AffiliationInfo'=>['SendAwards'=>true],
            'ContactInfo'=>['SendData'=>true],
            'MultimediaObjects'=>['SendData'=>true],
          ]
        ]
      ];
      // dd($params);
      $data = self::doResquestApi($params,$method);
      // dd($data);
      return isset($data->HotelDescriptiveContentsType->HotelDescriptiveContents[0])?$data->HotelDescriptiveContentsType->HotelDescriptiveContents[0]:null;
    }
  	public static function getHotelRatePlans($hotelCode)
    {
        $params = self::getBaseRequest();
        $method = 'GetHotelDescriptiveInfo';
        $params['HotelDescriptiveInfosType']=[
          'HotelDescriptiveInfos'=>[
            [
              'HotelRef'=>[
                'HotelCode'=>$hotelCode,
              ],
              'HotelInfo'=>['SendData'=>true],
              'FacilityInfo'=>['SendGuestRooms'=>true],
              'Policies'=>['SendPolicies'=>true],
              'AreaInfo'=>['SendRefPoints'=>true,'SendAttractions'=>true],
              'AffiliationInfo'=>['SendAwards'=>true],
              'ContactInfo'=>['SendData'=>true],
              'MultimediaObjects'=>['SendData'=>true],
            ]
          ]
        ];
        // dd($params);
        $data = self::doResquestApi($params,$method);
        // dd($data);
        return isset($data->HotelDescriptiveContentsType->HotelDescriptiveContents[0])?$data->HotelDescriptiveContentsType->HotelDescriptiveContents[0]:null;
      }
    public static function getRoomStayCandidates($adultsRooms,$childrenRooms,$agesRooms)
    {
      $roomStayCandidates = [];
      // dd($agesRooms);
      $currentGuests = 1;
      foreach ($adultsRooms as $i => $adults) {
        $children = $childrenRooms[$i];
        $ages = $children?$agesRooms[$i]:0;
        $roomStayCandidates[] = [
            'GuestCountsType'=>[
              'GuestCounts'=>self::getGuestCounts($adults,$children,$ages,$currentGuests),
            ],
            'Quantity'=>1,
            'RPH'=>$i,
            'RoomID'=>null,
        ];
      }
      return $roomStayCandidates;
    }
    public static function getGuestCounts($adults,$children,$ages,&$currentGuests)
    {
        $auxCurrentGuests = $currentGuests;
        $currentGuests+=$adults;
        $guestCounts = [
          [
            'Age'=>null,
            'AgeQualifyCode'=>10,
            'Count'=>$adults,
            'ResGuestRPH'=>range($auxCurrentGuests,$currentGuests-1),
          ],
        ];
        if ($children>0) {
          for ($j=0; $j < $children; $j++) { 
            $age = $ages[$j];
            $guestCounts[] = [
              'Age'=>$age,
              'AgeQualifyCode'=>8,
              'Count'=>1,
              'ResGuestRPH'=>[$currentGuests++],
            ];
          }
        }
        return $guestCounts;
    }
    public static function setHotelsData($hotelStays)
    {
      foreach ($hotelStays as $hotel) {
          $hotel->hotel = Hotel::where('code',$hotel->BasicPropertyInfo->HotelRef->HotelCode)->first();
          $hotel->localImage = ImagesComponent::getLocalImage($hotel->BasicPropertyInfo->ImageURL,$hotel);
      }
      return $hotelStays;
    }
    public static function getRoomTypesById($roomTypesData,$hotel=null,$idAttr='ID')
    {
      $roomTypes = [];
      foreach ($roomTypesData as $rt) {
         $roomTypes[$rt->$idAttr] = $rt;
         if($hotel!==null) $roomTypes[$rt->$idAttr]->ImageUrl = self::getImageFromRoomTypeData($rt,$hotel);
       } 
       return $roomTypes;
    }
    public static function getRatePlansById($ratePlansData)
    {
      $ratePlans = [];
      foreach ($ratePlansData as $rp) {
         $ratePlans[$rp->RatePlanID] = $rp;
       } 
       return $ratePlans;
    }
    public static function getImageFromRoomTypeData($data,$hotel)
    {
      $img = '/images/no-image.jpg';
      if (isset($data->MultimediaDescriptionsType->MultimediaDescriptions)) {
        foreach ($data->MultimediaDescriptionsType->MultimediaDescriptions as $mData) {
          if ($mData->ImageItemsType) {
            $img = ImagesComponent::getLocalImage($mData->ImageItemsType->ImageItems[0]->URL->Address,$hotel,2);
            break;
          }
        }
      }
      return $img;
    }
    public static function getApiUrl()
    {
      $prodUrl = "https://pullrest.omnibees.com";
      $sandboxUrl = "https://pullcertrest.omnibees.com";
      $apiEnv = env('API_ENV')=='production'?$prodUrl:$sandboxUrl;
      return $apiEnv;
    }
    public static function doResquestApi($params,$method)
    {
      $_doRequestApi = function() use ($params,$method){
        $client = new \GuzzleHttp\Client();
        $data = null;
        try {
          $curlParams = [
            'json'=>$params,
            'headers'  => ['UserName' => env('API_USER'),'Password'=>env('API_PASS')],
              // 'debug' => true,
          ];
          if (env('USE_PROXY')) $curlParams['proxy'] = "http://www.goguytravel.com:3333";
          
          $res = $client->request('POST',self::getApiUrl().'/api/Pull/'.$method,$curlParams);
          $data =  json_decode($res->getBody());
        } catch (\Exception $e) {
          echo $e->getMessage();  
        }
        if ($data!=null && in_array($method, ['SendHotelRes','SendHotelResCancel'])){
          Log::info('Omnibees '.$method.'RQ:'.json_encode($params));
          Log::info('Omnibees '.$method.'RS:'.json_encode($data));
        }
        return $data;
      };
      if(self::$useCache && !in_array($method, ['SendHotelRes','SendHotelResCancel','Ping'])){
        unset($params['EchoToken']);
        unset($params['Timestamp']);
        $data = Cache::remember('omnibeesApi'.base64_encode(json_encode($params)), 24*60 ,$_doRequestApi); 
      }else{
        $data = $_doRequestApi();
      }
      return $data;
    }
    public static function doRequestImage($url)
    {
      $client = new \GuzzleHttp\Client();
      $data = null;
      try {
        $curlParams = [
        ];
        if (env('USE_PROXY')) $curlParams['proxy'] = "http://www.goguytravel.com:3333";
        $res = $client->request('GET', $url,$curlParams);
        $data = (string)$res->getBody();
      } catch (\Exception $e) {
        echo $e->getMessage();
      }
      return $data;      
    }
    public static function getBaseRequest()
    {
      return [
        'EchoToken'=> str_random(25),
        'Timestamp'=> date('Y-m-d').'T'.date('H:i:s.u').'Z',//date('y-m-dth:i:sz'),
        // 'target' => '1',
        'Target' => env('API_ENV')=='production'?'1':'0', // 0 Test, 1 Production
        'Version' => '2.5',
        'PrimaryLangID' => '1', //en
      ];
    }
    public static function getHotelImages($multimediaDescriptions,$hotel)
    {
      $images = [];
      foreach ($multimediaDescriptions as $data) {
        if ($data->ImageItemsType && $data->ImageItemsType->ImageItems) {
          foreach ($data->ImageItemsType->ImageItems as $imageData) {
              $images[] = ImagesComponent::getLocalImage($imageData->URL->Address,$hotel);
          }
        }
      }
      return $images;
    }
    public static function getBaseAvRequest()
    {
      $params = self::getBaseRequest();

      $params['AvailRatesOnly']=true;
      $params['BestOnly']=false;
      $params['POS']=null;
      $params['PageNumber']=null;
      $params['MaxResponses']=null;
      $params['OnRequestInd']=false;
      $params['IsModify']=false;
      $params['RequestedCurrency']=null;
      $params['HotelSearchCriteria']=[
        'AvailableOnlyIndicator'=>null,
        'Criterion'=>[
          'Address'=>null,
          'Award'=>null,
          'GetPricesPerGuest'=>true,
          'HotelAmenity'=>null,
          'HotelRefs'=>null,
          'Location'=>null,
          'Position'=>null,
          'ProfilesType'=>null,
          'Radius'=>null,
          'RatePlanCandidatesType'=>null,
          'RateRanges'=>null,
          'RoomAmenity'=>null,
          'RoomStayCandidatesType'=>[
            'RoomStayCandidates'=>[
              [
                'BookingCode'=>null,
                'GuestCountsType'=>[
                  'GuestCounts'=>[
                    [
                      'Age'=>null,
                      'AgeQualifyCode'=>10,
                      'Count'=>2,
                      'ResGuestRPH'=>null,
                    ]
                  ]
                ],
                'Quantity'=>1,
                'RPH'=>0,
                'RoomID'=>null,
              ]
            ]
          ],
          'StayDateRange'=>null,
          'TPA_Extensions'=>[
            "AmountIncludingMarkup"=> null,
            "AmountIsPackageRates"=> null,
            "AmountNotIncludingMarkup"=> null,
            "IsForMobile"=> false,
            'MultimediaObjects'=>[
              'SendData'=>true,
            ]
          ]
        ]
      ];
      return $params;
    }
    public static function applyFilters(&$params)
    {
      $hotelFilters = SessionComponent::getHotelFilters();
      if (isset($hotelFilters->priceRange) && count($hotelFilters->priceRange)==2) {
        $minRate = min($hotelFilters->priceRange);
        $maxRate = max($hotelFilters->priceRange);
        $params['HotelSearchCriteria']['Criterion']['RateRanges'] =[
            [
              'RoomStayCandidateRPH'=>0,
              'MinRate'=>$minRate,
              'MaxRate'=>$maxRate,
              'CurrencyCode'=>SessionComponent::getCurrentCurrencyCode(),
            ]
        ]; 
      }
      if (isset($hotelFilters->stars) && $hotelFilters->stars>0) {
        $params['HotelSearchCriteria']['Criterion']['Award'] = [
          'Rating'=>$hotelFilters->stars,
        ];
      }
        // $params['HotelSearchCriteria']['Criterion']['HotelAmenity'] = [
        //   [
        //     'HotelAmenity'=>'Parking',
        //     'Code'=>68,
        //   ]
        // ];
    }
    public static function getHotelsVar($city,$quoteParams,$currencyCode)
    {
      $method = 'GetHotelAvail';
      $params = self::getBaseAvRequest();
      $params['BestOnly'] = true;
      $params['HotelSearchCriteria']['Criterion']['Address']=[
        'CityCode' => $city->code,
        'CountryCode' => $city->countryCode,
      ];
      $params['HotelSearchCriteria']['Criterion']['StayDateRange'] = [
            'Duration'=>null,
            'Start'=>$quoteParams->arrival.'T00:00:00',
            'End'=>$quoteParams->departure.'T00:00:00',
      ];
      $params['HotelSearchCriteria']['Criterion']['RoomStayCandidatesType']['RoomStayCandidates'] = self::getRoomStayCandidates($quoteParams->adults,$quoteParams->children,$quoteParams->ages);
      // self::applyFilters($params);
      $avData = self::doResquestApi($params,$method);
      // dd($avData);
      $hotels = [];
      $filters = [
        'price'=>[9999999,0],
        'mealPlans'=>[],
      ];
      $omnibeesCurrencies = self::getCurrencies();

      if (isset($avData->HotelStaysType->HotelStays)) {
        if(isset($_GET['debug']) && env('APP_ENV')!='production') dd($avData->RoomStaysType->RoomStays[3]);
        foreach ($avData->HotelStaysType->HotelStays as $key => $hotelData) {
            $localHotel = Hotel::where('code',$hotelData->BasicPropertyInfo->HotelRef->HotelCode)->first();
            if ($localHotel) {
              $hotelObj = new \stdClass();
              if($hotelData->BasicPropertyInfo->ImageURL==null){
                $hotelObj->image = '/images/no-image.jpg';
              }else{
                $hotelObj->image = ImagesComponent::getLocalImage($hotelData->BasicPropertyInfo->ImageURL,$localHotel);
              }
              $hotelObj->path = $localHotel->city->path.'/'.$localHotel->path;
              $hotelObj->name = $localHotel->name;
              $hotelObj->cityName = $localHotel->city->name;
              $hotelObj->address = isset($hotelData->BasicPropertyInfo->Address->AddressLine)?$hotelData->BasicPropertyInfo->Address->AddressLine:"";
              $hotelObj->stars = $hotelData->BasicPropertyInfo->Award->Rating;
              $hotelObj->subtotal = 0;
              $hotelObj->subtotalBeforeOffer = 0;
              $hotelObj->hasOffer = false;

              $mealPlans = [];
              $ratePlansById = self::getRatePlansById($avData->RoomStaysType->RoomStays[$key]->RatePlans);
              // itera los room rate, puede ser varios room rate para cada habitacion, como opciones de tarifas, para el listado, tomar la priemr tarifa que debia ser la mas baja
              $rphs=[];
              foreach ($avData->RoomStaysType->RoomStays[$key]->RoomRates as $key2 => $rr) {
                if (in_array($rr->RoomStayCandidateRPH, $rphs)) break;
                $rphs[] = $rr->RoomStayCandidateRPH;
                $rateCurrencyCode = $omnibeesCurrencies[$rr->Total->CurrencyCode];
                $exchangeRate = CurrenciesComponent::getExchangeRate($rateCurrencyCode,$currencyCode);
                $ratePlan = $ratePlansById[$rr->RatePlanID];
                $markup  = round($rr->Total->AmountAfterTax*self::$goguyMarkup,2);
                $refTotal = $rr->Total->AmountAfterTax + $markup;
                $total = round($refTotal * $exchangeRate,2);
                $subtotal = round($total/1.19,2);

                $subtotalBeforeOffer = $subtotal;
                if($ratePlan->Offers && count($ratePlan->Offers)>0 && isset($ratePlan->Offers[0]->Discount->Percent)){
                  $auxTotalOffer = $rr->Total->AmountAfterTax/(1-$ratePlan->Offers[0]->Discount->Percent/100);
                  $markupOffer  = round($auxTotalOffer*self::$goguyMarkup,2);
                  $refTotalBeforeOffer = ($auxTotalOffer + $markupOffer);
                  $totalBeforeOffer = round($refTotalBeforeOffer * $exchangeRate,2);
                  $subtotalBeforeOffer = round($totalBeforeOffer/1.19,2);
                  $hotelObj->hasOffer = true;
                }
                $hotelObj->subtotal += $subtotal;
                $hotelObj->subtotalBeforeOffer += $subtotalBeforeOffer;
                // dd($ratePlansById[$rr->RatePlanID]);
                $mealPlans[] = $ratePlan->MealsIncluded?$ratePlan->MealsIncluded->MealPlanCode:0;
                $filters['mealPlans'][] = $ratePlan->MealsIncluded?$ratePlan->MealsIncluded->MealPlanCode:0;
              }
              $hotelObj->mealPlans = $mealPlans;
              $hotelObj->currencyCode = $currencyCode;
              $hotels[] = $hotelObj;
              $filters['price'][0]=floor(min($filters['price'][0],$hotelObj->subtotal));
              $filters['price'][1]=floor(max($filters['price'][1],$hotelObj->subtotal));
            }
        }
      }
      // fix filters
      // dd($aux);
      $filters['price'][0] = $filters['price'][0] - $filters['price'][0]%100; 
      $filters['price'][1] = $filters['price'][1] + 100 - $filters['price'][1]%100; 
      $filters['mealPlans'] = array_unique($filters['mealPlans']);
      return [$hotels,$filters];
    }
    public static function getHotelRequestData($hotel,$quoteParams)
    {
      $params = self::getBaseAvRequest();
      $method = 'GetHotelAvail';
      $params['HotelSearchCriteria']['Criterion']['HotelRefs']=[
        [
          'HotelCode'=>$hotel->code,
        ]
      ];
      $params['HotelSearchCriteria']['Criterion']['StayDateRange'] = [
        'Duration'=>null,
        'Start'=>$quoteParams->arrival.'T00:00:00',
        'End'=>$quoteParams->departure.'T00:00:00',
      ];
      $params['HotelSearchCriteria']['Criterion']['RoomStayCandidatesType']['RoomStayCandidates'] = self::getRoomStayCandidates($quoteParams->adults,$quoteParams->children,$quoteParams->ages);
      return self::doResquestApi($params,$method);
    }
    public static function isValidPaymentCode($ratePlan)
    {
      $paymentCodes = [];
      $paymentCodes[] = 3; //Voucher
      $paymentCodes[] = 5; //CreditCard
      $valid = false;
      foreach ($ratePlan->PaymentPolicies->AcceptedPayments as $ap) {
          // echo $ap->GuaranteeTypeCode;echo "<br>";
        if (in_array(intval($ap->GuaranteeTypeCode), $paymentCodes)) {
          $valid = true;
          break;
        }
       }
       return $valid;
    }
    public static function getHotelVars($hotel,$quoteParams,$currencyCode)
    {
      $avData = self::getHotelRequestData($hotel,$quoteParams);
      $omnibeesCurrencies = self::getCurrencies();

      $hotelStay = isset($avData->RoomStaysType->RoomStays[0])?$avData->RoomStaysType->RoomStays[0]:null;

      $infoData = self::getHotelInfo($hotel->code);
      if(isset($_GET['infoDataDebug']) && env('APP_ENV')!='production') dd($infoData);

      $hotelImages = [];
      if (isset($avData->TPA_Extensions->MultimediaDescriptionsType->MultimediaDescriptions)) {
        $hotelImages = self::getHotelImages($avData->TPA_Extensions->MultimediaDescriptionsType->MultimediaDescriptions,$hotel);
      }

      $ratePlansById = $hotelStay?self::getRatePlansById($hotelStay->RatePlans):[];
      $roomTypesById = $hotelStay?self::getRoomTypesById($infoData->FacilityInfo->GuestRoomsType->GuestRooms,$hotel):[];
      $roomTypesById2 = $hotelStay?self::getRoomTypesById($hotelStay->RoomTypes,null,'RoomID'):[];

      // dd($ratePlansById);
      $roomRates = [];
      $rphRoom = false;
      if ($hotelStay) {
        if(isset($_GET['debug']) && env('APP_ENV')!='production') dd($hotelStay);
        $pricesByRoom = [];
        foreach ($hotelStay->RoomRates as $roomRate) {
          if (
              $roomRate->Availability[0]->AvailabilityStatus=='AvailableForSale'
              && self::isValidPaymentCode($ratePlansById[$roomRate->RatePlanID])
            ) {
            $roomRateKey = "{$roomRate->RoomStayCandidateRPH}.{$roomRate->RoomID}.{$roomRate->RatePlanID}";
            if(isset($pricesByRoom[$roomRateKey])) continue;
            $pricesByRoom[$roomRateKey] = true;
            
            $rateCurrencyCode = $omnibeesCurrencies[$roomRate->Total->CurrencyCode];
            $exchangeRate = CurrenciesComponent::getExchangeRate($rateCurrencyCode,$currencyCode);

            $roomType = $roomTypesById[$roomRate->RoomID];
            $roomType2 = $roomTypesById2[$roomRate->RoomID];
            $ratePlan = $ratePlansById[$roomRate->RatePlanID];

            $adults = $quoteParams->adults[$roomRate->RoomStayCandidateRPH];
            $children = $quoteParams->children[$roomRate->RoomStayCandidateRPH];

            $markup  = round($roomRate->Total->AmountAfterTax*self::$goguyMarkup,2);
            $refTotal = $roomRate->Total->AmountAfterTax + $markup;
            $refSubtotal = round($refTotal/1.19,2);
            $total = round($refTotal * $exchangeRate,2);
            $subtotal = round($total/1.19,2);

            $hasOffer = false;
            $subtotalBeforeOffer = $subtotal;

            if($ratePlan->Offers && count($ratePlan->Offers)>0 && isset($ratePlan->Offers[0]->Discount->Percent)){
              $auxTotalOffer = $roomRate->Total->AmountAfterTax/(1-$ratePlan->Offers[0]->Discount->Percent/100);
              $markupOffer  = round($auxTotalOffer*self::$goguyMarkup,2);
              $refTotalBeforeOffer = ($auxTotalOffer + $markupOffer);
              $totalBeforeOffer = round($refTotalBeforeOffer * $exchangeRate,2);
              $subtotalBeforeOffer = round($totalBeforeOffer/1.19,2);
              $hasOffer = true;
            }

            $hotelVar = (object) [
              'rphRoom' => $roomRate->RoomStayCandidateRPH,
              'roomType' => (object) [
                'id'=>$roomType->ID,
                'image'=>$roomType->ImageUrl,
                'name'=>$roomType2->RoomName,
                'description'=>$roomType2->RoomDescription->Description,
              ],
              'ratePlan'=> (object) [
                'id'=>$ratePlan->RatePlanID,
                'name' => $ratePlan->MealsIncluded?$ratePlan->MealsIncluded->Name:'Hotel only',
                'description' => $ratePlan->MealsIncluded?$ratePlan->MealsIncluded->Description:'',
                'politics' => $ratePlan->CancelPenalties[0]->PenaltyDescription->Description,
                'nonRefundable' => $ratePlan->CancelPenalties[0]->NonRefundable,
              ],
              'subtotal' => $subtotal,
              'subtotalBeforeOffer' => $subtotalBeforeOffer,
              'hasOffer' => $hasOffer,
              'total' => $total,
              'refTotal' => $refTotal,
              'refSubtotal' => $refSubtotal,
              'refCurrencyCode' => $rateCurrencyCode,
              'currencyCode' => $currencyCode,
              'adults' => $adults,
              'children' => $children,
              'selected' => false,
            ];

            $roomRates[] = $hotelVar;
          }
        }
      }
      // dd($roomRates);
      return [$roomRates,$infoData,$hotelImages];
    }
    public static function findRoomRateData($rph,$roomTypeId,$ratePlanId,$avData)
    {
      $roomRate = null;
      foreach ($avData->RoomStaysType->RoomStays[0]->RoomRates as $rr) {
          if (
            $rr->RoomStayCandidateRPH == $rph
            && $rr->RoomID == $roomTypeId
            && $rr->RatePlanID == $ratePlanId
          ) {
            $roomRate = $rr;
            break;
          }
      }
      return $roomRate;
    }
    public static function needCreditCard($ratePlans)
    {
      $r = false;
      foreach ($ratePlans as $rpData) {
        $totalOptions = 0;
        $paymentsVoucher = 0;
        $paymentsCreditCard = 0;
        foreach ($rpData['RatePlans'][0]->PaymentPolicies->AcceptedPayments as $ap) {
          $totalOptions++;
          if (intval($ap->GuaranteeTypeCode) == 5) { //creditcard
            $paymentsCreditCard++;
          }
          if (intval($ap->GuaranteeTypeCode) == 3) { //voucher
            $paymentsVoucher++;
          }
         }
         if($paymentsCreditCard>0 && $paymentsVoucher<$totalOptions){
            $r = true;
            break;
         }
      }
      return $r;
    }
    public static function getDoReservationRequest($reservation,$avData,$cardData=[])
    {
      $hotel = $reservation->hotel;

      $hotelStay = $avData->RoomStaysType->RoomStays[0];
      $ratePlansById = self::getRatePlansById($hotelStay->RatePlans);
      $roomTypesById = self::getRoomTypesById($hotelStay->RoomTypes,null,'RoomID');
      // dd($roomTypesById);
      $AmountBeforeTax = 0;
      $AmountAfterTax = 0;

      $roomStays = [];
      $resGuests = [];
      $currentGuests = 1;

      foreach ($reservation->rooms as $room) {
        $rph = $room->jsonRefExtraData['rph'];
        $rr =  self::findRoomRateData($rph,$room->refRoomTypeId,$room->refRatePlanId,$avData);
        $currencyCode = $rr->Total->CurrencyCode;

        self::setResGuests($resGuests,$room->adults,$room->children,$room->jsonAges,$currentGuests,$room->jsonRefExtraData['guestsData']);
        $auxRoomStay = [
          'AvailabilityStatus'=>null,
          'BasicPropertyInfo'=>$hotelStay->BasicPropertyInfo,
          'CommentsType'=>null,
          // 'CommentsType'=>[
          //   'Comments'=>[
          //     [
          //       'Description'=>'N/A',
          //       'Language'=>null,
          //       'Name'=>null,
          //     ]
          //   ]
          // ],
          'Guarantees'=>null,
          'GuestCountsType'=>[
            'GuestCounts'=>self::getGuestCounts($room->adults,$room->children,$room->jsonAges,$currentGuests),
          ],
          'IndexNumber'=>null,
          'RPH'=>$rph,
          'RatePlans'=>[$ratePlansById[$room->refRatePlanId]],
          'Reference'=>null,
          'RoomRates'=>[$rr],
          'RoomStayCandidateRPH'=>$rph,
          'RoomStayStatus'=>null,
          'RoomTypes'=>[$roomTypesById[$room->refRoomTypeId]],
          // 'SpecialRequestsType'=>[
          //   'SpecialRequests'=>[
          //     ['Description'=>$reservation->specialRequests?$reservation->specialRequests:'N/A']
          //   ]
          // ],
          'TPA_Extensions'=>null,
          'Total'=>clone $rr->Total,
          'RoomStayLanguage'=>1,
          'WarningRPH'=>null,
        ];
        $auxRoomStay['Total']->ChargeType = 12; //PerStay
        $auxRoomStay['RoomRates'][0]->Total->ChargeType = 18; //PerRoomPerStay

        $roomStays[] = $auxRoomStay;
        $AmountBeforeTax+=$rr->Total->AmountBeforeTax;
        $AmountAfterTax+=$rr->Total->AmountAfterTax;
      }

        // resto del request
        $params = self::getBaseRequest();
        $params['HotelReservationsType']=[
          'HotelReservations'=>[
            [
              'CreateDateTime'=>'0001-01-01T00:00:00',
              'LastModifyDateTime'=>'0001-01-01T00:00:00',
              'POS'=>null,
              'ResGlobalInfo'=>[
                'Total'=>[
                  'AmountBeforeTax'=>round($AmountBeforeTax,2),
                  'AmountAfterTax'=>round($AmountAfterTax,2),
                  'AmountIncludingMarkup'=>false,
                  'AmountIsPackage'=>false,
                  'ChargeType'=>12,
                  'CurrencyCode'=>$currencyCode,
                  'TPA_Extensions'=>null,
                ],
                'TimeSpan'=>null,
                'HotelReservationsIDsType'=>null,
                'Guarantee'=>[
                  'GuaranteesAcceptedType'=>[
                    'GuaranteesAccepted'=>[
                      [
                        'GuaranteeTypeCode'=>3,
                        // 'PaymentCard'=>[
                        // ],
                        'RPH'=>0,
                      ]
                    ]
                  ]
                ],
                'CommentsType'=>null,
              ],
              'ResGuestsType'=>[
                'ResGuests'=>$resGuests,
              ],
              'ResStatus'=>[
                'PMS_ResStatusType'=>null,
                'TransactionActionType'=>1,
              ],
              'RoomStaysType'=>[
                'MoreIndicator'=>false,
                'RoomStays'=>$roomStays
              ],
              'Services'=>null,
              'UniqueID'=>null,
            ]
          ]
        ];

        if (self::needCreditCard($roomStays)) {
          $params['HotelReservationsType']['HotelReservations'][0]['ResGlobalInfo']['Guarantee']['GuaranteesAcceptedType']['GuaranteesAccepted'][0]['GuaranteeTypeCode'] = 5; //CreditCard
          $params['HotelReservationsType']['HotelReservations'][0]['ResGlobalInfo']['Guarantee']['GuaranteesAcceptedType']['GuaranteesAccepted'][0]['PaymentCard'] = [
            'CardCode' => self::getBrandsCodes()['Visa'],
            'CardHolderName'=> "Go Guy Travel",
            'CardNumber'=>"4259 0900 0848 7663",
            // 'EffectiveDate'=>'2014-01-01T00:00:00',
            'ExpireDate'=>'2020-08-28T00:00:00',
            'SeriesCode'=>'129',
          ];
        }
      
        if ($reservation->reservation->specialRequests) {
          $params['HotelReservationsType']['HotelReservations'][0]['CommentsType']=[
            'Comments'=>[
              [
                'Description'=>$reservation->reservation->specialRequests,
              ]
            ]
          ];
          // $auxRoomStay['SpecialRequestsType'] = [
          //   'SpecialRequests'=>[
          //     ['Description'=>$reservation->specialRequests]
          //   ]
          // ];
        }
        // dd($params);
        // exit;
      $data = self::doResquestApi($params,'SendHotelRes');
      if(isset($data->HotelReservationsType->HotelReservations[0]->UniqueID->ID)){
        return [true,$data];
      }
      return [false,$data];
    }
    public static function setResGuests(&$resGuests,$adults,$children,$ages,$currentGuests,$guestsData)
    {
      for($i=0;$i<$adults+$children;$i++){
        $guestData = $guestsData[$i]; 
        $resGuests[] = [
          'Age'=>$i>=$adults?intval($ages[$i-$adults]):0, //;) uiki eres una vrg
          'AgeQualifyingCode'=>$i<$adults?10:8, //;) ## ##
          'PrimaryIndicator'=>$guestData['isPrimary'],
          'Profiles'=>[
            'ProfileInfos'=>[
              [
                'Profile'=>[
                  'Customer'=>[
                    'PersonName'=>[
                      'NamePrefix'=>'',
                      'GivenName'=>$guestData['givenName'],
                      'MiddleName'=>$guestData['middleName'],
                      'Email'=>$guestData['email'],
                    ],
                    'Address'=>[
                      'AddressLine'=>$guestData['addressLine'],
                      'CityName'=>$guestData['cityName'],
                      'CountryCode'=>$guestData['countryCode'],
                      'PostalCode'=>$guestData['postalCode'],
                    ],
                  ]
                ]
              ]
            ]
          ],
          'ResGuestRPH'=>$currentGuests++,
        ];
      }
    }
    public static function doCancelRooms($res,$rooms=[])
    {
      $params = self::getBaseRequest();
      $params['UniqueID'] = [
        [
          'ID'=>$res->refCode,
          'Type'=>14,
          'Reason'=>null,
        ]
      ];
      $params['Verification']=[
        'Email'=>null,
        'HotelRef'=>[
          'HotelCode'=>$res->hotel->code,
        ],
        'ReservationTimeSpan'=>[
          'Start'=>$res->arrival.'T00:00:00',
          'End'=>$res->departure.'T00:00:00',
        ],
      ];
      $params['CancelType'] = $rooms?98:null;
      if ($rooms) $params['Segment'] = [];
      foreach ($rooms as $room) {
        $params['Segment'][] = [
          'ItinSegNbr'=>$room->refCode,
          'Status'=>0,
        ];
      }
      $data = self::doResquestApi($params,'SendHotelResCancel');
      if (isset($data->Success)) {
        return [true,$data->CancelInfoRS->CancelRules[0]->Amount];
      }
      return [false,0];
    }
    public static function setReferenceData($res,$resData)
    {
      $res->refCode = $resData->HotelReservationsType->HotelReservations[0]->UniqueID->ID;
      $res->save();
      foreach ($res->rooms as $room) {
        foreach ($resData->HotelReservationsType->HotelReservations[0]->RoomStaysType->RoomStays as $rs) {
          if ($room->jsonRefExtraData['rph']==$rs->RPH) {
            $room->refCode = $rs->IndexNumber;
            $room->save();
            break;
          }
        }
      }
    }
    public static function getBrandsCodes()
    {
      return [
        'Visa'=>1,
        'MasterCard'=>2,
        'American Express'=>3,
        'Discover'=>4,
        'Diners Club'=>6,
        'JCB'=>7,
        'Unknown'=>null,
      ];
    }
    public static function getCurrencies()
    {
        return [
            1 => 'ALL',
            2 => 'AFN',
            3 => 'ARS',
            4 => 'AWG',
            5 => 'AUD',
            6 => 'AZN',
            7 => 'BSD',
            8 => 'BBD',
            9 => 'BYR',
            10 => 'BZD',
            11 => 'BMD',
            12 => 'BOB',
            13 => 'BAM',
            14 => 'BWP',
            15 => 'BGN',
            16 => 'BRL',
            17 => 'BND',
            18 => 'KHR',
            19 => 'CAD',
            20 => 'KYD',
            21 => 'CLP',
            22 => 'CNY',
            23 => 'COP',
            24 => 'CRC',
            25 => 'HRK',
            26 => 'CUP',
            27 => 'CZK',
            28 => 'DKK',
            29 => 'DOP',
            30 => 'XCD',
            31 => 'EGP',
            32 => 'SVC',
            33 => 'EEK',
            34 => 'EUR',
            43 => 'HKD',
            44 => 'HUF',
            46 => 'INR',
            47 => 'IDR',
            50 => 'ILS',
            52 => 'JPY',
            56 => 'KRW',
            59 => 'LVL',
            62 => 'LTL',
            64 => 'MYR',
            66 => 'MXN',
            72 => 'NZD',
            76 => 'NOK',
            82 => 'PHP',
            83 => 'PLN',
            85 => 'RON',
            86 => 'RUB',
            91 => 'SGD',
            94 => 'ZAR',
            97 => 'SEK',
            98 => 'CHF',
            102 => 'THB',
            104 => 'TRY',
            108 => 'GBP',
            109 => 'USD',
            117 => 'MAD',
            118 => 'MZN',
            119 => 'VEF',
            120 => 'PEN',
            121 => 'AED',
            122 => 'AMD',
            123 => 'ANG',
            124 => 'AOA',
            125 => 'BDT',
            126 => 'BHD',
            127 => 'BIF',
            128 => 'BOV',
            129 => 'BTN',
            130 => 'CDF',
            131 => 'CHE',
            132 => 'CHW',
            133 => 'CLF',
            134 => 'COU',
            135 => 'CUC',
            136 => 'CVE',
            137 => 'DJF',
            138 => 'DZD',
            139 => 'ERN',
            140 => 'ETB',
            141 => 'FJD',
            142 => 'FKP',
            143 => 'GEL',
            144 => 'GHS',
            145 => 'GIP',
            146 => 'GMD',
            147 => 'GNF',
            148 => 'GTQ',
            149 => 'GYD',
            150 => 'HNL',
            151 => 'HTG',
            152 => 'IQD',
            153 => 'IRR',
            154 => 'ISK',
            155 => 'JMD',
            156 => 'JOD',
            157 => 'KES',
            158 => 'KGS',
            159 => 'KMF',
            160 => 'KPW',
            161 => 'KWD',
            162 => 'KZT',
            163 => 'LAK',
            164 => 'LBP',
            165 => 'LKR',
            166 => 'LRD',
            167 => 'LSL',
            168 => 'LYD',
            169 => 'MDL',
            170 => 'MGA',
            171 => 'MKD',
            172 => 'MMK',
            173 => 'MNT',
            174 => 'MOP',
            175 => 'MRO',
            176 => 'MUR',
            177 => 'MVR',
            178 => 'MWK',
            179 => 'MXV',
            180 => 'NAD',
            181 => 'NGN',
            182 => 'NIO',
            183 => 'NPR',
            184 => 'OMR',
            185 => 'PAB',
            186 => 'PGK',
            187 => 'PKR',
            188 => 'PYG',
            189 => 'QAR',
            190 => 'RSD',
            191 => 'RWF',
            192 => 'SAR',
            193 => 'SBD',
            194 => 'SCR',
            195 => 'SDG',
            196 => 'SHP',
            197 => 'SLL',
            198 => 'SOS',
            199 => 'SRD',
            200 => 'SSP',
            201 => 'STD',
            202 => 'SYP',
            203 => 'SZL',
            204 => 'TJS',
            205 => 'TMT',
            206 => 'TND',
            207 => 'TOP',
            208 => 'TTD',
            209 => 'TWD',
            210 => 'TZS',
            211 => 'UAH',
            212 => 'UGX',
            213 => 'USN',
            214 => 'UYI',
            215 => 'UYU',
            216 => 'UZS',
            217 => 'VND',
            218 => 'VUV',
            219 => 'WST',
            220 => 'XAF',
            221 => 'XAG',
            222 => 'XAU',
            223 => 'XBA',
            224 => 'XBB',
            225 => 'XBC',
            226 => 'XBD',
            227 => 'XDR',
            228 => 'XOF',
            229 => 'XPD',
            230 => 'XPF',
            231 => 'XPT',
            232 => 'XSU',
            233 => 'XTS',
            234 => 'XUA',
            235 => 'XXX',
            236 => 'YER',
            237 => 'ZMW',
            238 => 'ZWL',
          ];
    }
}
 ?>