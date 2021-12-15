@extends('layouts.front')

@section('content')

@php
   SEO::setTitle('Vacational rentals');
   SEO::setDescription('Vacational rentals at '.SC::getAffiliate()->domain);
   SEO::setCanonical(url('vacational-rentals'));
@endphp

<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="{{asset('images/tours/goguytravel-tours.jpg')}}" alt="">
    </div>
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

<div id="bookingbox" class="with-back">
  <div class="container">
    @include('search-forms.houses',['style'=>'l'])
  </div>
</div>

<div id="tours">
  <section class="main">
  	<section class="container">
      <h1 class="text-center">Top vacational rentals</h1>
      <div class="row tours-list">
      @foreach ($houses as $h)
          <div class="col-sm-4">
            <a href="{{$h->getFullPath()}}">
              <article >
                <img src="{{$h->getPrimaryImageUrl(750,550)}}">
                <h2>{{$h->name}}</h2>
              </article>
            </a>
          </div>
      @endforeach
      </div>
  	</section>
  </section>     
</div>
@endsection