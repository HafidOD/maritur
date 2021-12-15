@extends('layouts.front')

@section('content')
<div id="carousel-example-generic" class="carousel slide tours-slide" data-ride="carousel">
  <div class="carousel-inner" role="listbox">
      @foreach ($sliders as $i => $slider)
        <div class="item {{$i==0?'active':''}}">
          <a href="{{$slider->url}}" title="{{$slider->altText}}">
            <img src="{{$slider->getPrimaryImageUrl(1350,500)}}" alt='{{$slider->altText}}'>
          </a>
        </div>
      @endforeach

{{--     <div class="item active">
      <a href="/tours/cancun/whale-shark-adventure">
        <img src="{{asset('images/tours/whale-shark-adventure.jpg')}}" alt="">
      </a>
    </div>
    <div class="item">
          <a href="/tours/cancun/atv-ziplines-cenotes">
        <img src="{{asset('images/tours/3x1-atv.jpg')}}" alt="">
      </a>
    </div>
    <div class="item">
      <a href="/tours/cancun/isla-mujeres-on-catamaran">
        <img src="{{asset('images/tours/banners/goguy1.jpg')}}" alt="">
      </a>
    </div>
    <div class="item">
      <a href="/tours/cancun/tulum-coba-cenote-playadelcarmen-tour-cancun">
      <img src="{{asset('images/tours/banners/goguy2.jpg')}}" alt="">
      </a>
    </div>
    <div class="item">
      <a href="">
        <img src="{{asset('images/tours/xcaret-parks.jpg')}}" alt="">
      </a>
    </div> --}}
  </div>
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
    @include('search-forms.tours',['style'=>'l'])
  </div>
</div>  -->

<div id="tours">
  <section class="main">
    <div class="container">
        @include('templates.lists',['lists'=>$listTours])
    </div>
  </section>     
</div>
@endsection