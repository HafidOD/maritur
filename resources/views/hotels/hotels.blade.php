@extends('layouts.front')

@section('content')

@php
   SEO::setTitle('Hotels in '.$city->name);
   SEO::setDescription('Hotels in '.$city->name);
   SEO::setCanonical(url('hotels/'.$city->path));
@endphp

<div id="bookingbox" class="with-back">
	<div class="container">
  		@include('search-forms.hotels',['showTittle'=>false,'style'=>'l'])
	</div>
</div>

<section class="gg-intback">
  <div id="list-container" class="container">
		<div class="row">
        <div class="col-md-6">
          <ol class="breadcrumb">
            <li><a href="{{url('/')}}" title="Home">Home</a></li>
            <li><a href="{{url('/hotels',[$city->path])}}" title="{{$city->name}}">{{$city->name}}</a></li>
          </ol>
        </div>
        <div class="col-md-6 text-right">
          <div id="shareicons">
            <p>Share on:</p>
            <div class="addthis_inline_share_toolbox"></div>
          </div>
        </div>
      </div>

       <section id="hotel-list" class="row">
	        <section id="section-filters" class="col-xs-12 col-sm-3">
		       	<form method="post" action="/hotels/setfilters">
					{{ csrf_field() }}
		        	<h4 class="text-center">Filter your results</h4>
		        	<p class="filter-title">Price</p>
		        	<div 
		        		class="filter-range"
		        		data-filter-name="price"
		        		>
		        		<p class="range-values"><span id='minOfRange'>{{$filters['price'][0]}}</span> to <span id="maxOfRange">{{$filters['price'][1]}}</span></p>
			        	<input 
			        		type="text" 
			        		class="span2" 
			        		value="" 
			        		name="priceRange"
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
		        	<p class="filter-title">Stars ratings</p>
		        	<div 
		        		id='hotel-stars-filter'
		        		class="btn-group filter-keys" 
		        		data-toggle="buttons"
		        		data-filter-name='stars'
		        		>
					  <label class="btn btn-default btn-xs ">
					    <input type="radio" name="stars" value="1"  >
					    <i class="fa fa-star"></i>
					  </label>
					  <label class="btn btn-default btn-xs ">
					    <input type="radio" name="stars" value="2"  >
					    <i class="fa fa-star"></i>
					    <i class="fa fa-star"></i>
					  </label>
					  <label class="btn btn-default btn-xs ">
					    <input type="radio" name="stars" value="3"  >
					    <i class="fa fa-star"></i>
					    <i class="fa fa-star"></i>
					    <i class="fa fa-star"></i>
					  </label>
					  <label class="btn btn-default btn-xs ">
					    <input type="radio" name="stars" value="4"  >
					    <i class="fa fa-star"></i>
					    <i class="fa fa-star"></i>
					    <i class="fa fa-star"></i>
					    <i class="fa fa-star"></i>
					  </label>
					  <label class="btn btn-default btn-xs ">
					    <input type="radio" name="stars" value="5"  >
					    <i class="fa fa-star"></i>
					    <i class="fa fa-star"></i>
					    <i class="fa fa-star"></i>
					    <i class="fa fa-star"></i>
					    <i class="fa fa-star"></i>
					  </label>
					</div>
					<br>
					<br>
					<hr>
		        	<p class="filter-title">Meal Plan</p>
					@php
						$ratePlansNames = [
							0 => 'Room Only',
							1 => 'All Inclusive',
							3 => 'Bed Breakfast',
							10 => 'Full Board',
							12 => 'Half Board',
						];
					@endphp
					<div 
						class="filter-keys"
		        		data-filter-name='mealplans'
						>
						@foreach ($filters['mealPlans'] as $key => $value)
						  <label class="btn btn-default btn-block">
						    <input type="checkbox" name="mealplans[]" value="{{$value}}">
						    {{$ratePlansNames[$value]}}
						  </label>
						@endforeach
					</div>
					<br>
					<br>
					<button type="button" class="reset-btn btn-default btn btn-block"><strong>RESET FILTERS</strong></button>
		       	</form>
	        </section>
	        <section class="col-xs-12 col-sm-9">
	          <div class="gg-datasearch">
	            <h1 class="text-center">Hotels in {{$city->name}}</h1>
	            <div class="row">
	              <h2><span class="col-xs-12 col-sm-4">1. Compare the best hotels in {{$city->name}}.</span></h2>
	              <h2><span class="col-xs-12 col-sm-4">2. Find the ideal hotel for your type of trip.</span></h2>
	              <h2><span class="col-xs-12 col-sm-4">3. Book at the best price.</span></h2>
	            </div>
	          </div>
		        <div id='items-list' class="gg-datalist list">
		        	<ul class="list">
		        		@include("hotels.hotels-list",['hotelsData'=>$hotelsData]);
		        	</ul>
		        </div>
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
		    { data: ['stars','price','mealplans'] },
		  ]
		};
		var currentFilterList = new List('items-list', options);
	</script>
    <script type="application/ld+json">
      {
        "@context": "http://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [{
          "@type": "ListItem",
          "position": 1,
          "item": {
            "@id": "{{url('/')}}",
            "name": "Home"
          }
        },{
          "@type": "ListItem",
          "position": 2,
          "item": {
            "@id": "{{url('/hotels',[$city->path])}}",
            "name": "{{$city->name}}"
          }
        }]
      }
    </script>
@endpush