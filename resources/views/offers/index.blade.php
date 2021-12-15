@extends('layouts.front')

@section('content')
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

<div id="bookingbox" class="with-back">
  <div class="container">
    @include('search-forms.hotels',['style'=>'l','showTittle'=>false])
  </div>
</div>  

<div id="tours">
  <section class="main">
  	<section class="container">
        @include('templates.lists',['lists'=>$lists])
      <hr>
      <h1 class="text-center">Top Hotels</h1>
      <div class="row">
          <div class="col-md-6">
          	<iframe width="100%" height="315" src="https://www.youtube.com/embed/xdMrH56eYOY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
          </div>
          <div class="col-md-6">
          	<iframe width="100%" height="315" src="https://www.youtube.com/embed/Oo0huWL4Dn4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
          	<iframe width="100%" height="315" src="https://www.youtube.com/embed/rkO0hB7x2qc" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
          </div>
          <div class="col-md-6">
          	<iframe width="100%" height="315" src="https://www.youtube.com/embed/1wHTGyhJOmw" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
          </div>
      </div>
  	</section>
  </section>     
</div>
@endsection