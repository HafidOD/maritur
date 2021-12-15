@extends('layouts.front')

@section('content')

@php
   SEO::setTitle('Vacational rentals in '.$city->name);
   SEO::setDescription('Vacational rentals in '.$city->name);
   SEO::setCanonical(url('vacational-rentals/'.$city->path));
@endphp
<section id="bookingbox" class="with-back">
  <div class="container">
        @include('search-forms.houses',['style'=>'l'])
	</div>
</section>

<section class="gg-intback">
  <div id="list-container" class="container">
    <div class="row">
       <section id="hotel-list" class="col-xs-12 col-sm-12 row">
        <section id="gg-hotel-list" class="col-xs-12 col-sm-12 col-md-12 ">
          <div class="gg-datasearch">
            <h1>Vacational rentals in {{$city->name}}</h1>
            <div class="row">
              <h2><span class="col-xs-12 col-sm-4">1. Compare the best tours in Cancun.</span></h2>
              <h2><span class="col-xs-12 col-sm-4">2. Find the ideal tour for your type of trip.</span></h2>
              <h2><span class="col-xs-12 col-sm-4">3. Book at the best price.</span></h2>
            </div>
          </div>

          @if ($houses)
	          <div class="gg-datalist list">       
	          		@foreach ($houses as $house)
		                <article class="gg-itemlist list-item row">
		                  <div class="gg-thumb col-xs-12 col-sm-4 col-md-4 col-lg-3 ">
								<img src="{{$house->getPrimaryImageUrl()}}" alt="{{$house->name}}" title="{{$house->name}}">
		                  </div>
		                  <div class="gg-info col-xs-12 col-sm-5 col-md-5 col-lg-6">
		                    <div class="gg-info-center">
		                      <a href="{{$house->getFullPath()}}" class="gg-item-name title">{{$house->name}}</a>
		                      {{-- <div class="gg-item-category">{{$house->tourProvider->name}}</div> --}}
		                      <div class="gg-item-city">
		                        <a href="#"><i class="fa fa-map-marker"></i> {{$city->fullName()}}</a>
		                      </div>
		                      <div class="gg-item-desc">
		                      	<p>{{$house->shortDescription}}</p>
		                      </div>
		                    </div>
		                  </div>

		                  <div class="gg-rate col-xs-12 col-sm-3 col-md-3 col-lg-3">
		                    <div class="gg-info-center">
		                        <div class="gg-item-btn"> 
		                          <a class="gg-btn-book btn-loading" href="{{$house->getFullPath()}}">View More</a>
		                        </div>
		                    </div>
		                  </div>
		                </article>
	          		@endforeach
	            </div>
	      @else
	      	<h2 class="text-center">No se encontraron hoteles</h2>
          @endif
        </section>
      </section>
    </div>
	</div>
</section>    
@endsection