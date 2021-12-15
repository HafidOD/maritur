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

<div id="bookingbox">
    @include('search-forms.bookingbox',['style'=>'l','showTittle'=>false])
</div>  

<div id="home">
  	<div class="container">
        <!-- @include('templates.lists',['lists'=>$itemLists]) -->
        <div class="mt10h">
          <h3 class="text-center h2">Welcome to Maritur Events!</h3>
          <div class="d-flex-between">
            <div class="column-half">
              <h4 class="eslogan">IMAGINATION, EXPERIENCE, RELIABILITY, AND SERVICE</h4>
              
              <p>We have more than 40 years of experience, offering the best DMC expertise throughout Mexico.</p>
              <p>We have the experience and organization needed to give your groups outstanding service in the destination of your choice.</p>
              <p>Our staff is highly trained to take care of even the smallest details regarding group logistics.</p>
              <p>We specialize in VIP travel services of the highest quality.</p>
              <p>All our services are covered under a $5,000,000 USD insurance policy.</p>
              <p>We have been selected as the Official DMC for the best hotel chains in Mexico, which include The Fairmont Mayakoba, Banyan Tree Mayakoba, Hilton Hotels Puerto Vallarta and The Nizuc Resort &amp; Spa Cancun.</p>
              <p>Since the foundation of our company we have offered activities to our clients that promote social responsibility and aid to the local communities where we operate.</p>
              <p>We have the largest DMC transportation fleet in Mexico</p>

            </div>

            <div class="column-half woo">
              <h4 class="text-center h4-h">WHY CHOOSE MARITUR?</h4>
            </div>
          </div>            
        </div>
    		<section class="row text-center mb-60-H">
    			<article class="gg-bft col-xs-6 col-sm-3">
            <img src="/images/icon-money.png"><br>
    				<span>The best rates</span>
    			</article>
    			<article class="gg-bft col-xs-6 col-sm-3">
            <img src="/images/icon-security.png"><br>
    				<span>Security and trust</span>
    			</article>
    			<article class="gg-bft col-xs-6 col-sm-3">
            <img src="/images/icon-service.png"><br>
    				<span>Quality service</span>
    			</article>
    			<article class="gg-bft col-xs-6 col-sm-3">
            <img src="/images/icon-contact.png"><br>
    				<span>Expert Advisors</span>
    			</article>
    		</section>
    		<hr>
        <!-- <h3 class="text-center">If you are looking for options for your next vacation, these are our recommendations</h3>
        <section class="destinations">
          <div class="row">
            @for ($i = 0; $i < 20; $i++)
              @isset ($allDests[$i])
                @php
                  $dest = $allDests[$i];
                @endphp
                <div class="col-xs-6 col-sm-3 col-md-3">
                  <a href="/hotels/{{$dest->path}}">
                    <h4><strong>{{$dest->name}}</strong></h4>
                    <p><strong>{{$dest->available_hotels_count}}</strong></p>                  
                  </a>
                </div>
              @endisset
            @endfor
              <div class="text-center col-xs-12">
                <button id='seemore' type='button' class="btn btn-primay collapsed" data-toggle='collapse' data-target="#alldests">SEE MORE <i class="fa fa-caret-down"></i><i class="fa fa-caret-up"></i></button>
              </div>
          </div>          
          <div id="alldests" class="row collapse">
              @foreach ($allDests2 as $dest)
                <div class="col-xs-6 col-sm-3 col-md-3">
                  <a href="/hotels/{{$dest->path}}">
                    <h4><strong>{{$dest->name}}</strong></h4>
                    <p><strong>{{$dest->available_hotels_count}}</strong></p>                  
                  </a>
                </div>
              @endforeach              
          </div>
        </section> -->
  	</div>
</div>
  <section id="gg-support" class="main text-center">
  	<section class="container">
  		<h3 class="text-center">Need Help?</h3>
  		<section class="top-40 row">
  			<article class="gg-spt col-sm-4">
          <i class="fa fa-question-circle fa-4x"></i><br>
          <span>FAQs</span>
        </article>
        <article class="gg-spt col-sm-4">
          <i class="fa fa-comments-o fa-4x"></i><br>
          <span>Live Chat</span>
        </article>
        <article class="gg-spt col-sm-4">
          <i class="fa fa-thumbs-o-up fa-4x"></i><br>
  				<span>Your opinion is important</span>
  			</article>
  		</section>
  	</section>
  </section>    
@endsection