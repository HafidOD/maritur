@extends('layouts.front')

@section('content')
@php
   SEO::setTitle('Tours in '.$city->name);
   SEO::setDescription('Tours in '.$city->name);
   SEO::setCanonical(url('tours/'.$city->path));
@endphp

<section id="bookingbox" class="with-back">
  <div class="container">
        @include('search-forms.tours',['style'=>'l'])
	</div>
</section>

<section class="gg-intback">
  <div id="list-container" class="container">
  		<div class="row">
        <div class="col-md-6">
          <ol class="breadcrumb">
            <li><a href="{{url('/')}}" title="Home">Home</a></li>
            <li><a href="{{url('/tours',[$city->path])}}" title="{{$city->name}}">{{$city->name}}</a></li>
          </ol>
        </div>
        <div class="col-md-6 text-right">
          <div id="shareicons">
            <p>Share on:</p>
            <div class="addthis_inline_share_toolbox"></div>
          </div>
        </div>
      </div>
       <section id="tour-list" class="row">
          <section id="section-filters" class="col-xs-12 col-sm-3">
            <form method="post" action="/tours/setfilters">
              {{ csrf_field() }}
              <h4 class="text-center">Filter your results</h4>
              <p class="filter-title">Price</p>
              <div 
                class="filter-range"
                data-filter-name='price'
                >
                <p class="range-prices"><span id='minOfRange'>{{$filters['price'][0]}}</span> to <span id="maxOfRange">{{$filters['price'][1]}}</span></p>
                <input 
                  type="text" 
                  class="span2" 
                  data-slider-min="{{$filters['price'][0]}}" 
                  data-slider-max="{{$filters['price'][1]}}" 
                  data-slider-value="[{{$filters['price'][0]}},{{$filters['price'][1]}}]"
                />
                <div class="row">
                  <div class="col-xs-4">
                    <strong>{{$filters['price'][0]}}</strong> 
                  </div>
                  <div class="col-xs-4 text-center">
                    <strong>{{SC::getCurrentCurrencyCode()}}</strong> 
                  </div>
                  <div class="col-xs-4 text-right">
                    <strong>{{$filters['price'][1]}}</strong> 
                  </div>
                </div>                
              </div>
              <hr>
              <p class="filter-title">Categories</p>
              <div 
                class="filter-keys"
                data-filter-name='categories'
                >
                @foreach ($tourCategories as $tc)
                  <label class="btn btn-default btn-block">
                    <input type="checkbox" name="categories[]" value="{{$tc->id}}">
                    {{$tc->name}}
                  </label>
                @endforeach
              </div>
          <br>
          <br>
          <button type="button" class="reset-btn btn-default btn btn-block"><strong>RESET FILTERS</strong></button>
            </form>
          </section>
        <section id="gg-tour-list" class="col-xs-12 col-sm-9">
          <div class="gg-datasearch">
            <h1 class="text-center">Tours in {{$city->name}}</h1>
            <div class="row">
              <h2><span class="col-xs-12 col-sm-4">1. Compare the best tours in Cancun.</span></h2>
              <h2><span class="col-xs-12 col-sm-4">2. Find the ideal tour for your type of trip.</span></h2>
              <h2><span class="col-xs-12 col-sm-4">3. Book at the best price.</span></h2>
            </div>
          </div>

          @if ($tours)
	          <div id='items-list' class="gg-datalist list">       
              <ul class="list">
	          		@foreach ($tours as $tour)
                    @php
                      list($adultPrice,$childrenPrice) = \App\Components\ToursComponent::getBestPrice($tour,SC::$affiliateId);
                      $adultPrice *= $exchangeRate;
                      $childrenPrice *= $exchangeRate;
                    @endphp
                    <li 
                      class="gg-itemlist list-item row"
                      data-price="{{$adultPrice}}"
                      data-categories='{{json_encode($tour->getTourCategoriesIds())}}'
                      >
                      <div class="gg-thumb col-xs-12 col-sm-4 col-md-4 col-lg-3 ">
                        <img src="{{$tour->getPrimaryImageUrl(750,550)}}" alt="{{$tour->name}}" title="{{$tour->name}}">
                      </div>
                      <div class="gg-info col-xs-12 col-sm-5 col-md-5 col-lg-6">
                        <div class="gg-info-center">
                          <a href="{{$tour->getFullPath()}}" class="gg-item-name title">{{$tour->name}}</a>
                          <div class="gg-item-category">{{$tour->tourProvider->name}}</div>
                          <div class="gg-item-city">
                            <a href="#"><i class="fa fa-map-marker"></i> {{$city->name}}</a>
                          </div>
                          <div class="gg-item-desc">
                            <p>{!!$tour->shortDescription!!}</p>
                          </div>
                        </div>
                      </div>
                          @if ($adultPrice)
                          <div class="gg-rate col-xs-12 col-sm-3 col-md-3 col-lg-3">
                            <div class="gg-info-center">
                                <div class="gg-item-price">
                                  <div class="gg-item-tax">
                                    <span>Adults From</span>
                                  </div>
                                  <span class="gg-currency"><sup>$</sup></span>
                                  <span class="gg-price">{{number_format($adultPrice,2)}}</span>
                                  <span class="gg-currency">{{$currencyCode}}</span>
                                </div>
                              @if ($childrenPrice)
                                <div class="gg-item-price">
                                  <div class="gg-item-tax">
                                    <span>Childs From</span>
                                  </div>
                                  <span class="gg-currency"><sup>$</sup></span>
                                  <span class="gg-price">{{number_format($childrenPrice,2)}}</span>
                                  <span class="gg-currency">{{$currencyCode}}</span>
                                </div>
                              @endif
                                <div class="gg-item-tax">
                                  <span>Taxes Included</span>
                                </div>
                                <div class="gg-item-btn">
                                  <a class="gg-btn-book btn-loading" href="{{$tour->getFullPath()}}">View More</a>
                                </div>
                            </div>
    		                  </div>
                          @endif
		                </li>
	          		@endforeach
              </ul>
	         </div>
	        @else
	      	<h2 class="text-center">No se encontraron tours</h2>
          @endif
           @if ($description)
              <section class="gg-datasearch">
            <p style="color:black" >{{$description}}</p>
              </section>
            @endif
        </section>
      </section>
	</div>
</section>    
@endsection
@push('scripts')
  <script type="text/javascript">
    var options = {
      valueNames: [
        { data: ['price','categories'] },
      ]
    };
    var currentFilterList = new List('items-list', options);
  </script>
@endpush