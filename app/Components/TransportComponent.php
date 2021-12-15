<?php 
    namespace App\Components;
	use App\AffiliateTourPrice;
	use App\AffiliateTourPriceTrans;
	use App\Tour;
	use App\TourPrice;
	use App\InactiveTour;
	use App\TransportService;
	class TransportComponent{
		public static function getValidTransport($destination,$pax)
		{
			return $destination->validTransportServices()
			->where([
				['onewayPrice','>',0],
				['roundtripPrice','>',0],
			])
			->whereHas('transportServiceType' , function ($query) use ($pax){
	            $query->where([
	                ['priceType','=',2],
	                ['maxPax','>=',$pax]
	            ])->orWhere('priceType',1);
	        })->get();
		}
		public static function transportPrices($transportService,$pax)
		{
			$onewayPrice = $transportService->onewayPrice;
			$roundtripPrice = $transportService->roundtripPrice;
			if($transportService->transportServiceType->priceType==1){
				$onewayPrice*=$pax;
				$roundtripPrice*=$pax;
			}
			return [$onewayPrice,$roundtripPrice];
		}
		public static function getValidTransportService($id,$pax)
		{
    		$transportService = TransportService::find($id);
    		if($transportService == null) return null;
    		if($transportService->onewayPrice<=0 || $transportService->roundtripPrice<=0) return null;
    		if($transportService->transportServiceType->priceType == 2 && $transportService->transportServiceType->maxPax < $pax) return null;
    		return $transportService;
		}
	}