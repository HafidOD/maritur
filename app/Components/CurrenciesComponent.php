<?php 
    namespace App\Components;
    use App\ExchangeRate;

class CurrenciesComponent{
    public static $cache = [];
    public static function getExchangeRate($fromCurrencyCode,$toCurrencyCode)
    {
        $date = date('Y-m-d');
        if(isset(self::$cache["$fromCurrencyCode-$toCurrencyCode"])) return self::$cache["$fromCurrencyCode-$toCurrencyCode"]; 
    	if($fromCurrencyCode==$toCurrencyCode) return 1;
        else if($fromCurrencyCode=='PEN' && $toCurrencyCode=='USD') return .306378;

    	// USD to MXN
        $ex = ExchangeRate::where(['date'=>$date,'fromCurrencyCode'=>$fromCurrencyCode,'toCurrencyCode'=>$toCurrencyCode])->first();
        if($ex){
            self::$cache["$fromCurrencyCode-$toCurrencyCode"] = $ex->amount;
            return $ex->amount;
        }else{
            self::updateExchangeRateByApi($date,$fromCurrencyCode,$toCurrencyCode);
            // segundo intento
            $ex = ExchangeRate::where(['date'=>$date,'fromCurrencyCode'=>$fromCurrencyCode,'toCurrencyCode'=>$toCurrencyCode])->first();
            if($ex){
                self::$cache["$fromCurrencyCode-$toCurrencyCode"] = $ex->amount;
                return $ex->amount;
            }
            else return -1;
        }
    }
    // siempre es MXN a USD
    public static function updateExchangeRateByApi($date,$fromCurrencyCode,$toCurrencyCode)
    {
    	$client  = new \GuzzleHttp\Client();
        $res = $client->request('GET',"http://openexchangerates.org/api/historical/{$date}.json?app_id=ae15052cfd8c4dcf8ef1c6dc334fa2ea&base=".$fromCurrencyCode);
    	// $res = $client->request('GET',"http://api.fixer.io/$date?base=".$fromCurrencyCode);
    	$data = json_decode($res->getBody());
    	if(isset($data->rates->$toCurrencyCode)){
    		$ex = new ExchangeRate;
    		$ex->date = $date;
            $ex->fromCurrencyCode = $fromCurrencyCode;
            $ex->toCurrencyCode = $toCurrencyCode;
    		$ex->amount = $data->rates->$toCurrencyCode;
    		$ex->save();
    	}
    }
}