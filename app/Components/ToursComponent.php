<?php 
    namespace App\Components;
	use App\AffiliateTourPrice;
	use App\AffiliateTourPriceTrans;
	use App\Tour;
	use App\TourPrice;
	use App\InactiveTour;
class ToursComponent{
	public static function calculateTotal($tourPrice,$affiliateId,$adults,$children,$fromDestination=null)
	{
		list($adultPriceAux,$childrenPriceAux) = self::getTourPriceAmounts($tourPrice,$affiliateId);
		$total = $adultPriceAux * $adults + $childrenPriceAux * $children;
		$adultPrice = $adultPriceAux * $adults;
		$childrenPrice = $childrenPriceAux * $children;
		if($fromDestination !== null){
			$tpt = $tourPrice->tourPriceTransportations()->where('destinationId',$fromDestination->id)->first();
			if ($tpt) {
				list($adultPrice2,$childrenPrice2) = self::getTourPriceTransportationAmounts($tpt,$affiliateId);
				$total += $adultPrice2 * $adults + $childrenPrice2 * $children;
				$adultPrice += $adultPrice2 * $adults;
				$childrenPrice += $childrenPrice2 * $children;
			}else{
				$total = -1;
			}
		}
		return [$total,$adultPrice,$childrenPrice];
	}
	public static function getBestPrice($tour,$affiliateId)
	{
		$bestAdultPrice = 0;
		$bestChildrenPrice = 0;
		foreach ($tour->tourPrices as $tp) {
			list($adultPrice,$childrenPrice) = self::getTourPriceAmounts($tp,$affiliateId);
			if($bestAdultPrice == 0 || $adultPrice < $bestAdultPrice) $bestAdultPrice = $adultPrice;
		}
		return [$bestAdultPrice,$bestChildrenPrice];
	}
	public static function getTourPriceAmounts($tourPrice,$affiliateId)
	{
		$adultPrice = $tourPrice->adultPrice;
		$childrenPrice = $tourPrice->childrenPrice;
		// busca la carga de un precio sobreescrito por un afiliado
		$affiliatePrice = AffiliateTourPrice::where(['tourPriceId'=>$tourPrice->id,'affiliateId'=>$affiliateId])->first();
		if ($affiliatePrice) {
			$adultPrice = $affiliatePrice->adultPrice;
			$childrenPrice = $affiliatePrice->childrenPrice;
		}
		return [$adultPrice,$childrenPrice];
	}
	public static function getTourPriceTransportationAmounts($tourPriceTrans,$affiliateId)
	{
		$adultPrice = $tourPriceTrans->adultPrice;
		$childrenPrice = $tourPriceTrans->childrenPrice;
		// busca la carga de un precio sobreescrito por un afiliado
		$affiliatePrice = AffiliateTourPriceTrans::where(['tourPriceTransportationId'=>$tourPriceTrans->id,'affiliateId'=>$affiliateId])->first();
		if ($affiliatePrice) {
			$adultPrice = $affiliatePrice->adultPrice;
			$childrenPrice = $affiliatePrice->childrenPrice;
		}
		return [$adultPrice,$childrenPrice];
	}
	public static function deactivateTour($tourId,$affiliateId)
	{
		// busca modelo de desactivacion
		InactiveTour::firstOrCreate(['tourId'=>$tourId,'affiliateId'=>$affiliateId]);
	}
	public static function activateTour($tourId,$affiliateId)
	{
		// busca modelo de desactivacion
		$inactiveTour = InactiveTour::where(['tourId'=>$tourId,'affiliateId'=>$affiliateId])->first();
		if($inactiveTour) $inactiveTour->delete();
	}
	public static function isActiveForAffiliate($tourId,$affiliateId)
	{
		$active = Tour::find($tourId)->status == Tour::STATUS_ACTIVE;
		// if(($aux = ->affiliateId) == $affiliateId) return $aux->status == Tour::STATUS_ACTIVE;
		return $active && InactiveTour::where(['tourId'=>$tourId,'affiliateId'=>$affiliateId])->count()==0;
	}
}