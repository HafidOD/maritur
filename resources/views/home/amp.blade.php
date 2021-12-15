@extends('layouts.amp')
@section('content')
<amp-carousel
    width="1350"
    height="500"
    layout="responsive"
    type="slides">
	@foreach ($sliders as $i => $slider)
	  <amp-img 
	  	src="{{$slider->getPrimaryImageUrl(1350,500)}}"
	    alt="{{$slider->altText}}"
	    width="1350"
	    height="500"	    
	    layout="responsive"></amp-img>
    @endforeach
 </amp-carousel>
 <br>
    @include('templates.amp.lists',['lists'=>$itemLists])

@endsection