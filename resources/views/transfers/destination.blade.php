@php
  use App\Components\TransportComponent;
@endphp
@extends('layouts.front')

@section('content')
@php
   SEO::setTitle('Transfers in '.$destName);
   SEO::setDescription('Transfers at '.SC::getAffiliate()->domain.' in '.$destName);
   SEO::setCanonical(url('transfers/'.$destPath));
@endphp
<section id="bookingbox" class='with-back'>
  <div class="container">
      @include('search-forms.transfers',['style'=>'l'])
  </div>
</section>

<section class="gg-intback">
  <div id="list-container" class="container">
    <div class="row">
       <section id="hotel-list" class="col-xs-12 col-sm-12 row">
        <section id="gg-hotel-list" class="col-xs-12 col-sm-12 col-md-12 ">
          <div class="gg-datasearch">
            <h1>Transfers from Airport to {{$destName}}</h1>
            @if ($destType!='destination')
              <h2>{{$dest->name}}</h2>
            @endif
            <div class="row">
              <h2><span class="col-xs-12 col-sm-4">1. Compare the best transfers.</span></h2>
              <h2><span class="col-xs-12 col-sm-4">2. Find the ideal transfer for your type of trip.</span></h2>
              <h2><span class="col-xs-12 col-sm-4">3. Book at the best price.</span></h2>
            </div>
          </div>

          @if (count($validTransports)>0)
            <div class="gg-datalist list">       
                @foreach ($validTransports as $ts)
                  @php
                    list($onewayPrice,$roundtripPrice) = TransportComponent::transportPrices($ts,$pax);
                  @endphp
                    <article class="gg-itemlist list-item row">
                      <div class="gg-thumb col-xs-12 col-sm-4 col-md-4 col-lg-3 ">
                      <img src="{{$ts->transportServiceType->getPrimaryImageUrl()}}" alt="{{$ts->transportServiceType->name}}" title="{{$ts->transportServiceType->name}}">
                      </div>
                      <div class="gg-info col-xs-12 col-sm-5 col-md-5 col-lg-6">
                        <div class="gg-info-center">
                          <p class="gg-item-name title">{{$ts->transportServiceType->name}} | <small>{{$ts->transportServiceType->priceType==1?'Shuttle':'Private'}} {{$ts->transportServiceType->maxPax}} Pax</small></p>
                          {{-- <div class="gg-item-category"></div> --}}
                          <div class="gg-item-city">
                            <a href="#"><i class="fa fa-map-marker"></i> {{$dest->fullName()}}</a>
                          </div>
                          <div class="gg-item-desc">
                            <p>{!!$ts->transportServiceType->description!!}</p>
                          </div>
                        </div>
                      </div>

                      <div class="gg-rate col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="row">
                          <div class="text-center col-md-6">
                            <p class="text-center">
                              One Way<br>
                              @if ($ts->transportServiceType->priceType==2)
                                <small>1 vehicle</small>
                              @else
                                <small>{{$pax}} people</small>
                              @endif
                            </p>
                            <span class="gg-price gg-item-price">${{number_format($onewayPrice*$exchangeRate,2)}} {{$currencyCode}}</span><br>
                            @php
                              $keyTypes = ['hotel'=>'h','tour'=>'t','destination'=>'d'];
                            @endphp
                            <a 
                              data-arrival="{{$arrival}}"
                              data-ts="{{$ts->id}}"
                              data-pax="{{$pax}}"
                              data-triptype="oneway"
                              data-destination="{{$keyTypes[$destType]}}.{{$destId}}"
                              class="gg-btn-book btn-loading add-transfer"
                              href="#"
                              >SELECT</a>
                          </div>
                          <div class="text-center  col-md-6">
                            <p class="text-center">
                            Roundtrip<br>
                              @if ($ts->transportServiceType->priceType==2)
                                <small>1 vehicle</small>
                              @else
                                <small>{{$pax}} people</small>
                              @endif
                            </p>
                            <span class="gg-price gg-item-price">${{number_format($roundtripPrice*$exchangeRate,2)}} {{$currencyCode}}</span><br>
                            <a 
                              data-arrival="{{$arrival}}"
                              data-ts="{{$ts->id}}"
                              data-pax="{{$pax}}"
                              data-triptype="roundtrip"
                              data-destination="{{$keyTypes[$destType]}}.{{$destId}}"
                              class="gg-btn-book btn-loading add-transfer" 
                              href="#">SELECT</a>
                          </div>
                        </div>
                      </div>
                    </article>
                @endforeach
              </div>
        @else
          <h2 class="text-center">No transfers</h2>
          @endif
        </section>
      </section>
    </div>
  </div>
</section>    
@endsection