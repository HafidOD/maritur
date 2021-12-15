<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\State;
use App\City;
use App\Tour;
use App\TourProvider;
use App\Image;
use App\Hotel;
use App\Reservation;
use Illuminate\Support\Facades\Storage;
use App\Components\DestinationsComponent;
use App\Components\OmnibeesApiComponent;
use App\Components\OmnibeesLoadDbComponent;
use App\Components\SessionComponent;
use App\Components\StripeComponent;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class HotelsController extends Controller
{
    public function test()
    {
        // OmnibeesLoadDbComponent::loadHotels();
        // OmnibeesLoadDbComponent::loadCountries();
        // OmnibeesLoadDbComponent::updateCitiesHotels();
        $res = Reservation::orderby('id','desc')->first();
        return ReservationsComponent::getConfirmationPdf($res);

        // return Mail::to('uicab2593@gmail.com')
        // ->send(new BookingConfirmation($res));
    }
    public function setFilters(Request $request)
    {
        $priceRange = explode(',',$request->input('priceRange'));
        $stars = $request->input('stars',0);
        SessionComponent::setHotelFilters($priceRange,$stars);
        var_dump(SessionComponent::getHotelFilters());
    }
    public function setcurrency(Request $request)
    {
        SessionComponent::setCurrenyCurrency($request->input('currency'));   
    }
    public function tryping()
    {
        OmnibeesApiComponent::tryPing();
    }
    public function autocomplete(Request $request)
    {
        $term = $request->input('term');
        $hotels = DB::table('Hotels')
        ->join('Cities', 'Cities.code', '=', 'Hotels.cityCode')
        ->join('Countries', 'Countries.code', '=', 'Cities.countryCode')
        ->select(DB::raw("CONCAT(Hotels.name,' - ',Cities.name,', ',Countries.name)  as label"),DB::raw('"hotel" as category'),DB::raw("CONCAT('/hotels/',Cities.path,'/',Hotels.path) as search_path"))
        ->where('Hotels.available',true)
        ->where(function($query) use ($term){
            $query->where('Hotels.name','like','%'.$term.'%')
            ->orWhere('Cities.name','like','%'.$term.'%');
        });

        $cities = DB::table('Cities')
        ->join('Countries', 'Countries.code', '=', 'Cities.countryCode')
        ->join('Hotels', 'Hotels.cityCode', '=', 'Cities.code')
        ->select(DB::raw("CONCAT(Cities.name,', ',Countries.name)  as label"),DB::raw('"destination" as category'),DB::raw("CONCAT('/hotels/',Cities.path) as search_path"))
        ->where('Cities.name','like','%'.$term.'%')
        ->where('Hotels.available',true)
        ->union($hotels);

        return $cities->take(10)->get();
        // $data = ;
        // foreach ($hotels as $h) {
        //   $data[] = [
        //       'search_path'=>'/hotels/'.$h->city->path.'/'.$h->path,
        //       'label'=>$h->name.' - '.$h->city->name.', '.$h->city->country->name,
        //       'category'=>'Hotels',
        //   ];
        // }
        // foreach ($cities as $c) {
        //   $data[] = [
        //       'search_path'=>'/hotels/'.$c->path,
        //       'label'=>$c->name.', '.$c->country->name.' hotels:'.$c->hotels()->count(),
        //       'category'=>'Destination',
        //   ];
        // }
        return $data;
    }
    public function list($cityPath)
    {
        // if($req->input('search',false)!==false) SessionComponent::setQuoteParams($req);
        $quoteParams = SessionComponent::getQuoteParams();
        $currencyCode = SessionComponent::getCurrentCurrencyCode();
        $city = City::where('path','=',$cityPath)->first();
        $hotelsData = OmnibeesApiComponent::getHotelsVar($city,$quoteParams,$currencyCode);

        return view('hotels.hotels-list',[
          'hotelsData'=>$hotelsData,
        ]);
    }
    public function hotels(Request $req,$cityPath)
    {
        if($req->input('search',false)!==false) SessionComponent::setQuoteParams($req);
        $quoteParams = SessionComponent::getQuoteParams();
        $currencyCode = SessionComponent::getCurrentCurrencyCode();
        $city = City::where('path','=',$cityPath)->first();
        if ($city==null) abort(404);
        
        list($hotelsData,$filters) = OmnibeesApiComponent::getHotelsVar($city,$quoteParams,$currencyCode);

        $quoteParams->destination = $city->name.', '.$city->country->name;
        $quoteParams->searchPath = '/hotels/'.$city->path;

        $desc = DestinationsComponent::getDescriptionByAffiliate($city);

        return view('hotels.hotels',[
          'city'=>$city,
          'hotelsData'=>$hotelsData,
          'filters'=>$filters,
          'quoteParams'=>$quoteParams,
          'destinationField'=>$city->name,
          'actionField'=>'/hotels/'.$city->path,
          'description'=>$desc,
        ]);
    }

    public function hotel(Request $req,$cityPath,$hotelPath)
    {
      if($req->input('search',false)!==false) SessionComponent::setQuoteParams($req);
      $quoteParams = SessionComponent::getQuoteParams();
      $roomsSelection = SessionComponent::getRoomsSelection();
      $hotel = Hotel::where('path','=',$hotelPath)->first();
      if ($hotel==null) abort(404);

      $currencyCode = SessionComponent::getCurrentCurrencyCode();
        
      list($roomRates,$infoData,$hotelImages) = OmnibeesApiComponent::getHotelVars($hotel,$quoteParams,$currencyCode);
      // dd($infoData);
      $this->setSelectionToRoomRates($roomRates,$roomsSelection);
      $quoteParams->destination = $hotel->name;
      $quoteParams->searchPath = '/hotels/'.$hotel->city->path.'/'.$hotel->path;

      list($hotelsData,$filters) = OmnibeesApiComponent::getHotelsVar($hotel->city,$quoteParams,$currencyCode);
      $betterHotels = $this->getBetterHotelsFormat($hotelsData);

      return view('hotels.hotel',[
          'hotel'=>$hotel,
          'infoData'=>$infoData,
          'infoDataAux'=>$this->generateInfoData($infoData),
          'roomRates'=>$roomRates,
          'hotelImages'=>$hotelImages,
          'quoteParams'=>$quoteParams,
          'destinationField'=>$hotel->name,
          'actionField'=>'/hotels/'.$hotel->city->path.'/'.$hotel->path,
          'betterHotels'=>$betterHotels,
        ]);
    }
    public function generateInfoData($infoData)
    {
        $adultsOnly = true;
        foreach ($infoData->FacilityInfo->GuestRoomsType->GuestRooms as $roomType) {
            if($roomType->MaxChildOccupancy > 0){
                $adultsOnly = false;
                break;
            }
        }
        return (object)[
            'adultsOnly'=>$adultsOnly,
        ];
    }
    public function getBetterHotelsFormat($hotelsData)
    {
        $betterHotels = [];
        foreach ($hotelsData as $hotel) {
            $betterHotels[] = (object)[
                'title'=>$hotel->name,
                'imageUrl'=>$hotel->image,
                'cityName'=>$hotel->cityName,
                'stars'=>$hotel->stars,
                'price'=>$hotel->subtotal,
                'currencyCode'=>$hotel->currencyCode,
                'seeMoreUrl'=>'/hotels/'.$hotel->path,
            ];
        }
        return $betterHotels;
    }
    public function setSelectionToRoomRates(&$roomRates,$roomsSelection)
    {
        // dd([$roomRates,$roomsSelection]);
      foreach ($roomsSelection->rooms as $rs) {
        $roomRate = null;
        foreach ($roomRates as &$rr) {
            if (
                $rr->rphRoom==$rs->rph
                && $rr->roomType->id==$rs->roomTypeId
                && $rr->ratePlan->id==$rs->ratePlanId
                ) {
                $rr->selected = true;
            }
        }
      }
    }
    public function loadTours()
    {
        $providers = \Illuminate\Support\Facades\DB::select('select * from goguytravel_retro.cat_tours_proveedores');
        foreach ($providers as $p) {
            $provider = new TourProvider;
            $provider->id = $p->id;
            $provider->name = $p->proveedor;
            $provider->save();
        }
        // \Illuminate\Support\Facades\DB::select('truncate table Tours');
        $tours = \Illuminate\Support\Facades\DB::select('select * from goguytravel_retro.cat_tours');
        foreach ($tours as $t) {
            $tour = new Tour;
            $tour->name = $t->nombre_en;
            $tour->path = $t->url_en;
            $tour->description = $t->descripcion_larga_en;
            $tour->shortDescription = $t->descripcion_corta_en;
            $tour->inclusions = $t->inclusiones_en;
            $tour->exclusions = $t->exclusiones_en;
            $tour->regulations = $t->regulaciones_en;
            $tour->recommendations = $t->recomendaciones_es;
            $tour->policies = $t->politicas_es;
            $tour->duration = $t->duracion;
            $tour->itinerary = $t->itinerario_en;
            $tour->address = $t->direccion_en;
            $tour->latitude = $t->latitud;
            $tour->longitude = $t->longitud;
            $tour->referenceId = $t->id;
            $tour->tourProviderId = $t->id_proveedor;
            $tour->childrenMinAge = $t->edad_min_menor?$t->edad_min_menor:5;
            $tour->childrenMaxAge = $t->edad_max_menor?$t->edad_max_menor:12;
            $tour->cityId = 101;
            echo $tour->save()?"SI {$tour->id}<br>":"NO {$t->id}<br>";
        }
    }
    public function sitemap()
    {
        $hotels = Hotels::findAll();
        $cities = Cities::has('hotels')->get();
        $tours = Tour::get();


    }
    public function getCurrencies()
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
