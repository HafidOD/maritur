@extends('layouts.front')

@section('content')
@php
   SEO::setTitle('Transfers');
   SEO::setDescription('Transfers at '.SC::getAffiliate()->domain);
   SEO::setCanonical(url('transfers'));
@endphp

<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
{{--   <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
  </ol> --}}
  <div class="carousel-inner" role="listbox">
     @foreach ($sliders as $i => $slider)
        <div class="item {{$i==0?'active':''}}">
          <a href="{{$slider->url}}" title="{{$slider->altText}}">
            <img src="{{$slider->getPrimaryImageUrl(1350,500)}}" alt='{{$slider->altText}}'>
          </a>
        </div>
      @endforeach
  </div>
  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

<!-- <div id="bookingbox" class="with-back">
  <div class="container">
    @include('search-forms.transfers',['style'=>'l'])
  </div>
</div>  -->
	<section class="main" id="top">
		<div class="container">
			<h3 class="gg-stitle-center">Top Transfers Destinations</b></h3>
			<div id="gg-top" class="row">
				@foreach ($destinations as $dest)
					<article class="gg-topitem col-xs-12 col-sm-6 col-md-4">
						<div class="gg-img-filter">
						<a href="/transfers/{{$dest->path}}">
							<img src="{{$dest->getPrimaryImageUrl(750,550)}}" width="700" height="500" alt="">
							<span>{{$dest->name}}</span>
						</a>
						</div>
					</article>
				@endforeach
			</div>
		</div>
	</section>
@endsection