<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\Tour;
use App\TourCategory;
use App\TourPrice;
use App\ItemList;
use App\Components\CurrenciesComponent;
use App\Components\DestinationsComponent;

class ToursController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $listTours = ItemList::where([
            'section'=>ItemList::SECTION_TOURS,
            'affiliateId'=>\SC::$affiliateId
        ])
        ->has('itemRelations')
        ->get();
        $sliders = \App\SliderImage::where([
            'section'=>1,
            'affiliateId'=>\SC::$affiliateId
        ])
        ->orderBy('orderx','ASC')
        ->get();
        return view('tours.index',['listTours'=>$listTours,'sliders'=>$sliders]);
    }
    public function autocomplete(Request $request)
    {
        $term = $request->input('term');
        $tours = Tour::where('name','like','%'.$term.'%')
        ->where('status',1)
        ->whereIn('affiliateId',[1,\SC::$affiliateId])
        ->whereDoesntHave('inactiveTours',function($query){
          $query->where('affiliateId',\SC::$affiliateId);
        })
        ->take(10)
        ->get();

        $cities = City::where('name','like','%'.$term.'%')
        ->has('tours')
        ->take(10)->get();

        $data = [];
        foreach ($tours as $t) {
          $data[] = [
              'search_path'=>$t->getFullPath(),
              'label'=>$t->name,
              'category'=>'tour',
          ];
        }
        foreach ($cities as $c) {
          $data[] = [
              'search_path'=>'/tours/'.$c->path,
              'label'=>$c->name.', '.$c->country->name,
              'category'=>'destination',
          ];
        }
        return $data;
    }
    public function tours(Request $req,$cityPath)
    {
      $city = City::where('path','=',$cityPath)->first();
      if ($city==null) abort(404);

      $quoteParams = \SC::getToursQuoteParams();
      $quoteParams->destination = $city->fullName();
      $quoteParams->searchPath = '/tours/'.$cityPath;
      $tours = $city
        ->tours()
        ->where('status',Tour::STATUS_ACTIVE)
        ->whereIn('affiliateId',[1,\SC::$affiliateId])
        ->whereDoesntHave('inactiveTours',function($query){
          $query->where('affiliateId',\SC::$affiliateId);
        })
        ->has('tourPrices')
        ->get();
      $tourCategories = TourCategory::all();
      $maxValue = $this->getMax($tours);
      $filters = [
        'price'=>[0,$maxValue+50-$maxValue%50],
      ];
      $currencyCode = \SC::getCurrentCurrencyCode();
      $desc = DestinationsComponent::getDescriptionByAffiliate($city);

      return view('tours.tours',[
          'filters'=>$filters,
          'city'=>$city,
          'tours'=>$tours,
          'tourCategories'=>$tourCategories,
          'exchangeRate'=>CurrenciesComponent::getExchangeRate('USD',$currencyCode),
          'currencyCode'=>$currencyCode,
          'description'=>$desc,
      ]);
    }
    public function getMax($tours)
    {
      $max = 0;
      foreach ($tours as $t) {
        $max = max($max,$t->getBestTourPrice()->adultPrice);
      }
      return $max;
    }
    public function tour(Request $req,$cityPath,$tourPath)
    {
        $quoteParams = \SC::getToursQuoteParams();
        $tour = Tour::where('path','=',$tourPath)->first();
        if ($tour==null) abort(404);

        $quoteParams->searchPath = '/tours/'.$cityPath.'/'.$tourPath;
        $quoteParams->destination = $tour->name;
        $currencyCode = \SC::getCurrentCurrencyCode();

        $betterTours = $this->getBetterTours($tour);
        return view('tours.tour',[
          'tour'=>$tour,
          'exchangeRate'=>CurrenciesComponent::getExchangeRate('USD',$currencyCode),
          'currencyCode'=>$currencyCode,
          'betterTours'=>$betterTours,
        ]);
    }
    public function getBetterTours($tour)
    {
          $tours = Tour::where('cityId',$tour->cityId)
            ->where('status',Tour::STATUS_ACTIVE)
            ->inRandomOrder()
            ->take(10)
            ->get();
          $betterTours = [];
          foreach ($tours as $tour) {
              $exchangeRate = CurrenciesComponent::getExchangeRate('USD',\SC::getCurrentCurrencyCode());
              list($adultPrice,$childrenPrice) = \App\Components\ToursComponent::getBestPrice($tour,\SC::$affiliateId);
              $adultPrice *= $exchangeRate;
              $childrenPrice *= $exchangeRate;

              $betterTours[] = (object)[
                  'title'=>$tour->name,
                  'imageUrl'=>$tour->getPrimaryImageUrl(),
                  'cityName'=>$tour->city->name,
                  'stars'=>$tour->stars,
                  'price'=>$adultPrice,
                  'currencyCode'=>\SC::getCurrentCurrencyCode(),
                  'seeMoreUrl'=>$tour->getFullPath(),
              ];
          }
          return $betterTours;
    }
}
