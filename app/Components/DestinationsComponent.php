<?php 
    namespace App\Components;
	use App\DestinationInfo;

class DestinationsComponent{
	public static function getDescriptionByAffiliate($destination)
	{
		$text = "";
        $infoModel = $destination->destinationInfos()
        	->where('affiliateId',\SC::$affiliateId)
        	->first();
        if($infoModel) $text = $infoModel->description;
        return $text;
	}
	public static function setDescriptionByAffiliate($destination,$text)
	{
		$infoModel = DestinationInfo::where('destinationId',$destination->id)
		->where('affiliateId',\SC::$affiliateId)->first();
		if($infoModel==null){
			$infoModel = new DestinationInfo;
			$infoModel->destinationId = $destination->id;
			$infoModel->affiliateId = \SC::$affiliateId;
		}
		$infoModel->description = $text;
		$infoModel->save();
	}
}
