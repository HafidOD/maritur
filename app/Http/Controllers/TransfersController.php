<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\Tour;
use App\Hotel;
use Illuminate\Support\Facades\DB;
use App\Components\CurrenciesComponent;
use App\Components\TransportComponent;

class TransfersController extends Controller
{
    public function actionIndex()
    {
        $destinations = City::inRandomOrder()->has('images')->has('transportServices')->take(6)->get();
        $sliders = \App\SliderImage::where(['section'=>2])
                ->where('affiliateId',\SC::$affiliateId)
                ->orderBy('orderx','ASC')->get();
        return view('transfers.index',['destinations'=>$destinations,'sliders'=>$sliders]);
    }
    public function actionHotel(Request $request, $hotelPath)
    {
        $hotel = Hotel::where('path',$hotelPath)->first();
        if ($hotel==null) abort(404);
        $dest = $hotel->city;
        $pax = $request->input('pax',1);
        // dd($validTransports);
        $validTransports = TransportComponent::getValidTransport($dest,$pax);
        $currencyCode = \SC::getCurrentCurrencyCode();
        return view('transfers.destination',[
            'destName'=>$hotel->name,
            'destPath'=>'/hotel/'.$hotel->path,
            'destType'=>'hotel',
            'destId'=>$hotel->id,
            'dest'=>$dest,
            'validTransports'=>$validTransports,

            'pax'=>$pax,
            'arrival'=>$request->input('arrival',date('Y-m-d',strtotime('+1 day'))),
            'actionField'=>'/transfers/hotel/'.$hotel->path,

            'exchangeRate'=>CurrenciesComponent::getExchangeRate('USD',$currencyCode),
            'currencyCode'=>$currencyCode,
        ]);
    }
    public function actionTour(Request $request, $tourPath)
    {
        $tour = Tour::where('path',$tourPath)->first();
        if ($tour==null) abort(404);
        $dest = $tour->city;
        $pax = $request->input('pax',1);
        $validTransports = TransportComponent::getValidTransport($dest,$pax);

        $currencyCode = \SC::getCurrentCurrencyCode();
        return view('transfers.destination',[
            'destName'=>$tour->name,
            'destPath'=>'/tour/'.$tour->path,
            'destType'=>'tour',
            'destId'=>$tour->id,
            'dest'=>$dest,
            'validTransports'=>$validTransports,

            'pax'=>$request->input('pax',1),
            'arrival'=>$request->input('arrival',date('Y-m-d',strtotime('+1 day'))),
            'actionField'=>'/transfers/tour/'.$tour->path,

            'exchangeRate'=>CurrenciesComponent::getExchangeRate('USD',$currencyCode),
            'currencyCode'=>$currencyCode,
        ]);
    }
    public function actionDestination(Request $request, $destPath)
    {
        $dest = City::where('path','=',$destPath)->first();
        if ($dest==null) abort(404);
        $pax = $request->input('pax',1);
        $validTransports = TransportComponent::getValidTransport($dest,$pax);
        $currencyCode = \SC::getCurrentCurrencyCode();
        return view('transfers.destination',[
            'destName'=>$dest->name,
            'destPath'=>$dest->path,
            'destType'=>'destination',
            'destId'=>$dest->id,
            'dest'=>$dest,
            'validTransports'=>$validTransports,

            'pax'=>$pax,
            'arrival'=>$request->input('arrival',date('Y-m-d',strtotime('+1 day'))),
            'actionField'=>'/transfers/'.$dest->path,

            'exchangeRate'=>CurrenciesComponent::getExchangeRate('USD',$currencyCode),
            'currencyCode'=>$currencyCode,
        ]);
    }
    public function autocomplete(Request $request)
    {
        $term = $request->input('term');

        $hotels = DB::table('Hotels')
        ->join('Cities', 'Cities.code', '=', 'Hotels.cityCode')
        ->join('Countries', 'Countries.code', '=', 'Cities.countryCode')
        ->select(DB::raw("CONCAT(Hotels.name,' - ',Cities.name,', ',Countries.name)  as label"),DB::raw('"hotel" as category'),DB::raw("CONCAT('/transfers/hotel/',Hotels.path) as search_path"))
        ->where(function($query) use ($term){
            $query->where('Hotels.name','like','%'.$term.'%');
            // ->orWhere('Cities.name','like','%'.$term.'%');
        })
        ->take(5);

        $cities = DB::table('Cities')
        ->join('Countries', 'Countries.code', '=', 'Cities.countryCode')
        ->select(DB::raw("CONCAT(Cities.name,', ',Countries.name)  as label"),DB::raw('"destination" as category'),DB::raw("CONCAT('/transfers/',Cities.path) as search_path"))
        ->where('Cities.name','like','%'.$term.'%');
        // ->union($hotels);

        $tours = DB::table('Tours')
        ->join('Cities', 'Cities.id', '=', 'Tours.cityId')
        ->join('Countries', 'Countries.code', '=', 'Cities.countryCode')
        ->select(DB::raw("CONCAT(Tours.name,' - ',Cities.name,', ',Countries.name)  as label"),DB::raw('"tour" as category'),DB::raw("CONCAT('/transfers/tour/',Tours.path) as search_path"))
        ->where(function($query) use ($term){
            $query->where('Tours.name','like','%'.$term.'%');
            // ->orWhere('Cities.name','like','%'.$term.'%');
        })
        ->take(5);

        return $tours->union($cities)->take(20)->get();
    }
}
