<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\House;

class HousesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $randomHouses = House::inRandomOrder()->take(6)->get();
        return view('houses.index',['houses'=>$randomHouses]);
    }
    public function autocomplete(Request $request)
    {
        $term = $request->input('term');
        $houses = House::where('name','like','%'.$term.'%')
        ->take(10)->get();

        $cities = City::where('name','like','%'.$term.'%')
        ->has('houses')
        ->take(10)->get();

        $data = [];
        foreach ($houses as $h) {
          $data[] = [
              'search_path'=>$h->getFullPath(),
              'label'=>$h->name.', '.$h->destination->fullName(),
              'category'=>'houses',
          ];
        }
        foreach ($cities as $c) {
          $data[] = [
              'search_path'=>'/vacational-rentals/'.$c->path,
              'label'=>$c->name.', '.$c->country->name,
              'category'=>'destination',
          ];
        }
        return $data;
    }
    public function houses(Request $req,$cityPath)
    {
      $city = City::where('path','=',$cityPath)->first();

      return view('houses.houses',[
          'city'=>$city,
          'houses'=>$city->houses,
      ]);
    }
    public function house(Request $req,$cityPath,$tourPath)
    {
        $house = House::where('path','=',$tourPath)->first();
        return view('houses.house',['house'=>$house]);
    }
}
