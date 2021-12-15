<?php 
  namespace App\Components;
set_time_limit (30*30);
use App\Hotel;
use App\City;
use App\State;
use App\Country;
use App\Components\OmnibeesApiComponent;
/**
* 
*/
class OmnibeesLoadDbComponent
{
	public static function loadHotels()
    {
      $params = OmnibeesApiComponent::getBaseRequest();
      $method = 'HotelSearch';
      $params['MaxResponses'] = 4000;
      $params['PageNumber'] = 1;
      $params['POS'] = null;
      $params['Criteria'] = [
          'AvailableOnlyIndicator'=>true,
          'Criterion'=>[
            'Address'=>null,
            'HotelRefs'=>null,
            'Radius'=>null,
            'Award'=>null,
            'ProfilesType'=>null,
            'RateRanges'=>null,
            'StayDateRange'=>null,
            'RoomStayCandidatesType'=>null,
            'RatePlanCandidatesType'=>null,
            'Location'=>null,
          ]
      ]; 
      $data = OmnibeesApiComponent::doResquestApi($params,$method);
      // dd($data->PropertiesType->Properties[10]);
      if (isset($data->PropertiesType->Properties)) {
        // echo count($data->PropertiesType->Properties);
        foreach ($data->PropertiesType->Properties as $h) {
          // dd($h);
            if (Hotel::where(['code'=>$h->HotelRef->HotelCode])->count()==0) {
                $hotel = new Hotel;
                $hotel->name = $h->HotelRef->HotelName;
                echo "NEW HOTEL: ".$hotel->name.", ".$h->Address->CityName."\n";
                $number = 1;
                $path = str_slug($h->HotelRef->HotelName,'-');
                $aux = $path;
                while (Hotel::where('path','=',$aux)->count()>0) {
                  $aux = $path.'-'.$number;
                  $number++;
                }
                $hotel->path = $aux;
                $hotel->available = 1;
                $hotel->code = $h->HotelRef->HotelCode;
                $hotel->cityCode = $h->Address->CityCode;
                $hotel->stateCode = $h->Address->StateProvCode;
                $hotel->zoneCode = $h->Address->ZoneCode;
                self::addCity($h->Address->CityName,$h->Address->CityCode,$h->Address->CountryCode,$h->Address->StateProvCode);
                self::addState($h->Address->StateProv,$h->Address->StateProvCode,$h->Address->CountryCode);
                $hotel->save();
            }
        }
      }
    }
    public static function setHotelsAvailable()
    {
      $params = OmnibeesApiComponent::getBaseRequest();
      $method = 'HotelSearch';
      $params['MaxResponses'] = 5000;
      $params['PageNumber'] = 1;
      $params['POS'] = null;
      $params['Criteria'] = [
          'AvailableOnlyIndicator'=>true,
          'Criterion'=>[
            'Address'=>null,
            'HotelRefs'=>null,
            'Radius'=>null,
            'Award'=>null,
            'ProfilesType'=>null,
            'RateRanges'=>null,
            'StayDateRange'=>null,
            'RoomStayCandidatesType'=>null,
            'RatePlanCandidatesType'=>null,
            'Location'=>null,
          ]
      ]; 
      $data = OmnibeesApiComponent::doResquestApi($params,$method);
      // dd($data->PropertiesType->Properties[0]);
      if (isset($data->PropertiesType->Properties)) {
        echo count($data->PropertiesType->Properties);
        $codes = [];
        foreach ($data->PropertiesType->Properties as $h) $codes[] = $h->HotelRef->HotelCode;
        Hotel::whereIn('code',$codes)->update(['available'=>true]);
      }
    }
    public static function addCity($cityName,$cityCode,$countryCode,$stateProvCode)
    {
      if ($cityName=='') $cityName = 'noname';
      if(City::where(['code'=>$cityCode])->count()==0){
        $city = new City;
        $city->name = $cityName;
        $number = 1;
        $path = str_slug($cityName,'-');
        $aux = $path;
        while (City::where('path','=',$aux)->count()>0) {
          $aux = $path.'-'.$number;
          $number++;
        }
        $city->path = $aux;
        $city->code = $cityCode;
        // $city->searchCode = $pos->SearchCode;
        // $city->searchZoneCode = $pos->SearchZoneCode;
        $city->countryCode = $countryCode;
        $city->hasHotels = 0;
        $city->save();
      }
    }
    public static function addState($stateName,$stateCode,$countryCode)
    {
      if ($stateName=='') $stateName = 'noname';
      if(State::where(['code'=>$stateCode])->count()==0){
        $state = new State;
        $state->name = $stateName;
        $state->code = $stateCode;
        $number = 1;
        $path = str_slug($stateName,'-');
        $aux = $path;
        while (State::where('path','=',$aux)->count()>0) {
          $aux = $path.'-'.$number;
          $number++;
        }
        $state->path = $aux;
        // $state->searchCode = $st->SearchCode;
        // $state->searchZoneCode = $st->SearchZoneCode;
        $state->countryCode = $countryCode;
        $state->save();
      }
    }
    public static function loadCities()
    {
      $paramsBase = OmnibeesApiComponent::getBaseRequest();
      $states = State::all();
      $method = 'GetCityList';
      foreach ($states as $s) {
          $params = $paramsBase;
          $params['Country_UID'] = $s->countryCode;
          $params['StateSearchCode'] = $s->searchCode;
          $data = OmnibeesApiComponent::doResquestApi($params,$method);
          if (isset($data->PositionsType->Positions)) {
            foreach ($data->PositionsType->Positions as $pos) {
              if(City::where(['code'=>$pos->Code])->count()==0){
                $city = new City;
                $city->name = $pos->Name;
                $number = 1;
                $path = str_slug($pos->Name,'-');
                $aux = $path;
                while (City::where('path','=',$aux)->count()>0) {
                  $aux = $path.'-'.$number;
                  $number++;
                }
                $city->path = $aux;
                $city->code = $pos->Code;
                $city->searchCode = $pos->SearchCode;
                $city->searchZoneCode = $pos->SearchZoneCode;
                $city->countryCode = $pos->CountryUID;
                $city->hasHotels = 0;
                $city->save();
                echo "new City: ".$pos->Name;
              }
            }
          }
      }
    }
   	public static function loadCountries()
    {
      $params = OmnibeesApiComponent::getBaseRequest();
      $method = 'GetCountryList';
      $data = OmnibeesApiComponent::doResquestApi($params,$method);
      foreach ($data->CountriesType->Countries as $c) {
          if(Country::where(['code'=>$c->Code])->count()==0){
            $country =  new Country;
            $country->name = $c->Name;
            $country->code = $c->Code;
            $country->path = str_slug($c->Name,'-');
            $country->save();
          }
      }
    }
    public static function loadStates()
    {
      $paramsBase = self::getBaseRequest();
      $countries = Country::all();
      $method = 'GetStateList';
      foreach ($countries as $c) {
        $params = $paramsBase;
        $params['Country_UID'] = $c->code;
        $data = self::doResquestApi($params,$method);
        if (isset($data->PositionsType->Positions) && $data->PositionsType->Positions) {
          foreach ($data->PositionsType->Positions as $st) {
              $state = new State;
              $state->name = $st->Name;
              $state->code = $st->Code;
              $number = 1;
              $path = str_slug($st->Name,'-');
              $aux = $path;
              while (State::where('path','=',$aux)->count()>0) {
                $aux = $path.'-'.$number;
                $number++;
              }
              $state->path = $auxa;
              $state->searchCode = $st->SearchCode;
              $state->searchZoneCode = $st->SearchZoneCode;
              $state->countryCode = $c->code;
              $state->save();
          }
        }
      }
    }
    public static function loadZones()
    {
      $paramsBase = self::getBaseRequest();
      $states = State::all();
      $method = 'GetZonesList';
      foreach ($states as $s) {
          $params = $paramsBase;
          $params['Country_UID'] = $s->countryCode;
          $params['StateSearchCode'] = $s->searchCode;
          $data = self::doResquestApi($params,$method);
          if (isset($data->PositionsType->Positions)) {
            dd($data);
            // foreach ($data->PositionsType->Positions as $pos) {
              // dd($pos);exit;
              // if(Zone::where(['code'=>$pos->Code])->count()==0){
              //   $zone = new zone;
              //   $zone->name = $pos->Name;
              //   $number = 1;
              //   $path = str_slug($pos->Name,'-');
              //   $aux = $path;
              //   while (zone::where('path','=',$aux)->count()>0) {
              //     $aux = $path.'-'.$number;
              //     $number++;
              //   }
              //   $zone->path = $aux;
              //   $zone->code = $pos->Code;
              //   $zone->searchCode = $pos->SearchCode;
              //   $zone->searchZoneCode = $pos->SearchZoneCode;
              //   $zone->countryCode = $pos->CountryUID;
              //   $zone->save();
              // }
            // }
          }
      }
    }
    public static function updateCitiesHotels()
    {
      $hotels = Hotel::all();
      foreach ($hotels as $h) {
        if ($h->cityCode) {
          City::where('code', $h->cityCode)
          ->update(['hasHotels' => 1]);       
        }
      }
    }
}
 ?>