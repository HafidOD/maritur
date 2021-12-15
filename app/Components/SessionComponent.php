<?php 
	namespace App\Components;

use Illuminate\Http\Request;

class SessionComponent
{
    public static $affiliateId = -1;
    public static $affiliateModel = null;
    public static function getCurrentCurrencyCode()
    {
        return session('currentCurrency','USD');
    }
    public static function setCurrentCurrency($currencyCode)
    {
        if (in_array($currencyCode, ['USD','MXN'])) {
            return session(['currentCurrency'=>$currencyCode]);
        }
    }
    public static function getRoomsSelection()
    {
        $roomsSelection = session('roomsSelection',new \stdClass);
        if (isset($roomsSelection->hotelCode) && isset($roomsSelection->rooms)) {
            if (count($roomsSelection->rooms)==0) {
                $roomsSelection->hotelCode = -1;
            }
            return $roomsSelection;
        }
		return self::getInitRoomsSelection();
    }
    public static function getInitRoomsSelection()
    {
        $roomsSelection = new \stdClass;
        $roomsSelection->hotelCode = -1;
        $roomsSelection->rooms = [];
        return $roomsSelection;
    }
    public static function setHotelSelection($hotelCode)
    {
    }
    public static function getQuoteParams()
    {
        $quoteParams = session('quoteParams',null);
        if ($quoteParams==null) $quoteParams = self::initQuoteParams();
        $quoteParams->rooms = count($quoteParams->adults);
        return $quoteParams;
    }
    public static function getHotelFilters()
    {
        $hotelFilters = session('hotelFilters',null);
        if ($hotelFilters==null){
            $hotelFilters = new \stdClass;
            $hotelFilters->priceRange = [1000,5000];
            $hotelFilters->stars = 0;
        }
        return $hotelFilters;
    }
    public static function setHotelFilters($priceRange,$stars)
    {
        $hotelFilters = self::getHotelFilters();
        $hotelFilters->priceRange = $priceRange;
        $hotelFilters->stars = $stars;
        session(['hotelFilters'=>$hotelFilters]);
    }
    public static function removeData()
    {
        $quoteParams = self::initQuoteParams();
        session(['quoteParams'=>$quoteParams]);
        $roomsSelection = self::getInitRoomsSelection();
        session(['roomsSelection'=>$roomsSelection]);
        session(['toursSelection'=>[]]);
    }
    public static function addRoom($adults,$children,$ages,$hotelCode,$roomTypeId,$roomTypeName,$ratePlanId,$ratePlanName,$rph,$subtotal,$total,$currencyCode,$image)
    {
        $roomsSelection = self::getRoomsSelection();

        if ($roomsSelection->hotelCode!=$hotelCode) {
            $roomsSelection->hotelCode = $hotelCode;
            $roomsSelection->rooms = [];
        }

        $room = new \stdClass;
        $room->adults = $adults;
        $room->children = $children;
        $room->ages = $ages;
        $room->roomTypeId = $roomTypeId; 
        $room->ratePlanId = $ratePlanId;
        $room->roomTypeName = $roomTypeName;
        $room->ratePlanName = $ratePlanName;
        $room->total = $total;
        $room->subtotal = $subtotal;
        $room->currencyCode = $currencyCode;
        $room->rph = $rph;
        $room->image = $image;

        $roomsSelection->rooms[] = $room;
        session(['roomsSelection'=>$roomsSelection]);
    }
    public static function removeRoomByRph($rph)
    {
        $roomsSelection = self::getRoomsSelection();
        $index = 0;
        foreach ($roomsSelection->rooms as $i => $r) {
            if ($r->rph==$rph) {
                $index = $i;
            }
        }
        if (isset($roomsSelection->rooms[$index])) {
            unset($roomsSelection->rooms[$index]);
        }
        session(['roomsSelection'=>$roomsSelection]);
    }
    public static function setQuoteParams($request)
    {
        $prevQuoteParams = self::getQuoteParams();

        $destination = $request->input('destination','');
        $searchPath = $request->input('searchPath','/');

        $arrival = $request->input('arrival',date('Y-m-d',strtotime('+1 week')));
        if($arrival<date('Y-m-d')) $arrival = date('Y-m-d');

        $departure = $request->input('departure',date('Y-m-d',strtotime('+1 week +1 day')));
        if($departure <= $arrival) $departure = date('Y-m-d',strtotime($arrival.' +1 day'));
        $endDate = date('Y-m-d',strtotime($departure.' -1 day'));

        $rooms = $request->input('rooms',1);
        $adults = $request->input('adults',[2]);
        $children = $request->input('children',[0]);
        $ages = $request->input('ages',[[]]);

        $quoteParams = new \stdClass;
        $quoteParams->destination = $destination; //hotel | destination
        $quoteParams->searchPath = $searchPath;
        $quoteParams->arrival = $arrival;
        $quoteParams->departure = $departure;
        $quoteParams->endDate = $endDate;
        $quoteParams->adults = $adults;
        $quoteParams->children = $children;
        $quoteParams->ages = $ages;

        unset($prevQuoteParams->rooms);
        if (json_encode($prevQuoteParams)!=json_encode($quoteParams)) session(['roomsSelection'=>self::getInitRoomsSelection()]);

        session(['quoteParams'=>$quoteParams]);
        return $quoteParams;
    }
    public static function initQuoteParams()
    {
        $arrival = date('Y-m-d',strtotime('+1 week'));
        $departure = date('Y-m-d',strtotime('+1 week +1 day'));
        $endDate = date('Y-m-d',strtotime($departure.' -1 day'));

        $rooms = 1;
        $adults = [2];
        $children = [0];
        $ages = [[]];

        $quoteParams = new \stdClass;
        $quoteParams->destination = ''; //hotel | destination
        $quoteParams->searchPath = '/';
        $quoteParams->arrival = $arrival;
        $quoteParams->departure = $departure;
        $quoteParams->endDate = $endDate;
        $quoteParams->adults = $adults;
        $quoteParams->children = $children;
        $quoteParams->ages = $ages;

        session(['quoteParams'=>$quoteParams]);
        return $quoteParams;
    }
    public static function getToursQuoteParams()
    {
        $quoteParams = session('toursQuoteParams',null);
        if ($quoteParams == null) $quoteParams = self::initToursQuoteParams();
        self::setToursQuoteParams($quoteParams);
        session(['toursQuoteParams'=>$quoteParams]);
        return $quoteParams;
    }
    public static function initToursQuoteParams()
    {
        $arrival = date('Y-m-d',strtotime('+2 day'));

        $adults = 2;
        $children = 0;

        $quoteParams = new \stdClass;
        $quoteParams->destination = ''; //hotel | destination
        $quoteParams->searchPath = '/tours';
        $quoteParams->arrival = $arrival;
        $quoteParams->adults = $adults;
        $quoteParams->children = $children;

        return $quoteParams;
    }
    public static function setToursQuoteParams(&$quoteParams)
    {
        $req = request();
        if($req->has('arrival')){
            $arrival = $req->input('arrival');
            if($arrival<date('Y-m-d',strtotime('+2 day'))) $arrival = date('Y-m-d',strtotime('+2 day'));            
            $quoteParams->arrival = $arrival;
        }

        if ($req->has('adults')) {
            $adults = $req->input('adults');
            if (is_array($adults)) $adults = $adults[0];
            $quoteParams->adults = $adults;
        }
        if ($req->has('children')) {
            $children = $req->input('children');
            if (is_array($children)) $children = $children[0];
            $quoteParams->children = $children;
        }
    }
    public static function getToursSelection()
    {
        $toursSelection = session('toursSelection',[]);
        return $toursSelection;
    }
    public static function getTransfersSelection()
    {
        $transfersSelection = session('transfersSelection',[]);
        return $transfersSelection;
    }
    public static function removeTour($index)
    {
        $selection = self::getToursSelection();
        if(isset($selection[$index])) unset($selection[$index]);
        session(['toursSelection'=>$selection]);
    }
    public static function removeTransfer($index)
    {
        $selection = self::getTransfersSelection();
        if(isset($selection[$index])) unset($selection[$index]);
        session(['transfersSelection'=>$selection]);
    }
    public static function addTourSelection($tourPriceId,$day,$adults,$children,$fromDestinationId=false)
    {
        $toursSelection = self::getToursSelection();
        // busca si ya hay tours seleccionados con los mismos parametros
        $index = false;
        foreach ($toursSelection as $i => $ts) {
            if ($ts->tourPriceId == $tourPriceId && $ts->day == $day) {
                $index = $i;
                break;
            }
        }
        // if  $tour = $toursSelection[$index];
        $tour = new \stdClass;

        $tour->tourPriceId = $tourPriceId;
        $tour->day = $day;
        $tour->adults = $adults;
        $tour->children = $children;
        $tour->destinationTransportId = $fromDestinationId;
        if ($index!==false) $toursSelection[$index] = $tour;
        else $toursSelection[] = $tour;
        session(['toursSelection'=>$toursSelection]);
    }
    public static function addTransferSelection($transportServiceId,$arrival,$pax,$triptype,$destinationHash)
    {
        // solo debe haber una transportacion
        $transfer = new \stdClass;
        $transfer->transportServiceId = $transportServiceId;
        $transfer->arrival = $arrival;
        $transfer->pax = $pax;
        $transfer->triptype = $triptype;
        $transfer->destinationHash = $destinationHash;
        session(['transfersSelection'=>[$transfer]]);
    }
    public static function getAffiliate()
    {
        if(self::$affiliateModel==null) self::$affiliateModel = \App\Affiliate::find(self::$affiliateId);
        return self::$affiliateModel;
    }
}