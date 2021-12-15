<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TourPrice extends Model
{
    protected $table = 'TourPrices';
    protected $guarded = [];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 3;

    public static $statusLabels = [
        self::STATUS_ACTIVE => 'Activo',
        self::STATUS_INACTIVE => 'Inactivo',
        self::STATUS_DELETED => 'Eliminado',
    ];

	public function getWeekDaysArray()
	{
		return $this->weekDays?str_split($this->weekDays):[];
	}
	public function getInvalidWeekDaysArray()
	{
		return str_split($this->getInvalidWeekDays());
	}
	public function getInvalidWeekDays()
	{
		$aux = '';
		$validDays = $this->weekDays;
		for ($i=0; $i < 7; $i++) { 
			$aux .= strpos($validDays,"$i")===false?$i:'';
		}
		return $aux;
	}
	public function tourPriceTransportations()
    {
        return $this->hasMany('App\TourPriceTransportation','tourPriceId','id');
    }
    public function tour()
    {
    	return $this->hasOne('App\Tour','id','tourId');
    }
    public function tourReservations()
    {
    	return $this->hasMany('App\TourReservation','tourPriceId','id');
    }
    public function isInList($listName)
    {
        return in_array($listName, $this->getLists());
    }
    public function getLists()
    {
        $aux = $this->lists;
        return $aux==""?[]:explode(',', $aux);
    }
    public function addToList($listName)
    {
        $aux = $this->getLists();
        if(!in_array($listName, $aux)){
            $aux[] = $listName;
            $this->lists = implode(',', $aux);
        }
        return $this;
    }
    public function removeFromList($listName)
    {
        $aux = $this->getLists();
        if(in_array($listName, $aux)){
            $key = array_search($listName, $aux);
            unset($aux[$key]);
            $this->lists = implode(',', $aux);
        }
        return $this;
    }
}
