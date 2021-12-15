@extends('layouts.front')

@php
   SEO::setTitle($tour->name);
   SEO::setDescription($tour->shortDescription);
   SEO::addImages($tour->getPrimaryImageUrl());
   SEO::setCanonical(url($tour->getFullPath()));

   $quoteParams = SC::getToursQuoteParams();
@endphp

@section('content')
<div id="tour">
    <section id="bookingbox" class="with-back">
      <div class="container">
            @include('search-forms.tours',['style'=>'l'])
      </div>
    </section>
      <section class="gg-intback">
         <div id="detail-container" class="container">
            <div class="row">
              <div class="col-md-6">
                <ol class="breadcrumb">
                  <li><a href="{{url('/')}}" title="Home">Home</a></li>
                  <li><a href="{{url('/tours',[$tour->city->path])}}" title="{{$tour->city->name}}">{{$tour->city->name}}</a></li>
                  <li><a href="{{url('/tours',[$tour->city->path,$tour->path])}}" title="{{$tour->name}}">{{$tour->name}}</a></li>
                </ol>
              </div>
              <div class="col-md-6 text-right">
                <div id="shareicons">
                  <p>Share on:</p>
                  <div class="addthis_inline_share_toolbox"></div>
                </div>
              </div>
            </div>
            <div style="background: #fff">
              <div class="gg-datadetail">
                  <h1>
                    {{$tour->name}}
                  </h1>
                     @if ($tour->address)
                       <div id="gg-address">
                        <span><i class="fa fa-map-marker"></i> {!!$tour->address!!}</span>
                      </div>
                     @endif
               </div>
              <section id='single-gallery'>
                <h2 class='title'>{{$tour->name}} Gallery</h2>
                 <div class="row">
                    <div class="col-xs-12 col-md-8">
                       <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                             @foreach ($tour->getImages() as $i => $image)
                                @php
                                   if ($i==8) break;
                                @endphp
                                <div class="item {{$i==0?'active':''}}">
                                   <img src="{{$image->getFileUrl()}}" alt="">
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
                    </div>
                    <div class="col-xs-12 col-md-4">
                       @foreach ($tour->getImages() as $i => $image)
                          @php
                             if ($i==8) break;
                          @endphp
                          <div class="gg-img-gallery col-xs-4 col-sm-2 col-md-6">
                             <a 
                                href="#"
                                data-target="#carousel-example-generic" 
                                data-slide-to="{{$i}}" 
                                class="active"
                             >
                             <img src="{{$image->getFileUrl()}}" alt="" title=""  />   
                             </a>
                          </div>
                       @endforeach
                    </div>            
                 </div>
              </section>
            </div>
            <div class='title tabs'>
              {{-- {{$tour->name}} Overview --}}
              <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#description" role="tab" data-toggle="tab">Description</a></li>
                <li><a href="#whatsincluded" role="tab" data-toggle="tab">Whats included</a></li>
                <li><a href="#recommendations" role="tab" data-toggle="tab">Recomendations</a></li>
                <li><a href="#policies" role="tab" data-toggle="tab">Policies</a></li>
              </ul>
            </div>
             <div class="item-info">
              <div class="excerpt">
                <div class="tab-content">
                  <div class="tab-pane active" id="description">
                     @if ($tour->description)
                        {{-- <h3>Description:</h3> --}}
                        <p>{!!$tour->description!!}</p>                        
                     @endif
                     @if ($tour->duration)
                        <h3>Duration:</h3>
                        <p>{!!$tour->duration!!}</p>                        
                     @endif
                     @if ($tour->childrenMinAge)
                        <h3>Children Age:</h3>
                        <p>{!!$tour->childrenMinAge!!} - {!!$tour->childrenMaxAge!!}</p>                        
                     @endif
                  </div>
                  <div class="tab-pane" id="whatsincluded">
                     @if ($tour->inclusions)
                        {{-- <h3>What's Included?:</h3> --}}
                        <p>
                           {!!$tour->inclusions!!}
                        </p>
                     @endif
                     @if ($tour->exclusions)
                        <h3>What's not Included?:</h3>
                        <p>
                           {!!$tour->exclusions!!}
                        </p>
                     @endif
                  </div>
                  <div class="tab-pane" id="recommendations">
                     @if ($tour->recommendations)
                        {{-- <h3>Recommendations:</h3> --}}
                        <p>
                           {!!$tour->recommendations!!}
                        </p>
                     @endif
                  </div>
                  <div class="tab-pane" id="policies">
                     @if ($tour->policies)
                        {{-- <h3>Policies:</h3> --}}
                        <p>
                           {!!$tour->policies!!}
                        </p>
                     @endif
                  </div>
                </div>
              </div>
             </div>
            <h2 class='title'>{{$tour->name}} Prices</h2>
            @foreach ($tour->tourPrices as $tp)
            @php
              list($adultPrice,$childrenPrice) = \App\Components\ToursComponent::getTourPriceAmounts($tp,\SC::$affiliateId);
              $adultPrice*=$exchangeRate;
              $childrenPrice*=$exchangeRate;
            @endphp
              <article class="gg-itemlist row"
                  data-tp="{{$tp->id}}"
                  data-adult="{{$adultPrice}}"
                  data-children="{{$childrenPrice}}"
                 >
                <div class="gg-inforoom col-xs-12 col-sm-12 col-md-12">
                  <div class="gg-room-plan row">
                    <div class="gg-info col-xs-12 col-sm-6 col-md-6">
                      <div class="gg-room-name"><h2>{{$tp->name}}</h2></div>
                      <div>{!! $tp->description !!}</div>
                    </div>
                    @if (in_array(date('w',strtotime($quoteParams->arrival)), $tp->getWeekDaysArray()))
                      <div class="gg-info col-xs-12 col-sm-6 col-md-3">
                          <div class="gg-item-price">
                            <div class="gg-item-tax">
                              <span>Select Adults</span>
                            </div>
                            <select class="adults-selector check-prices">
                              @for ($i = 1; $i < 10; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                              @endfor
                            </select>
                            <span>X</span>
                            <span class="gg-currency"><sup>$</sup></span>
                            <span class="gg-price per-adult-price">{{number_format($adultPrice,2)}}</span>
                            <span class="gg-currency">{{$currencyCode}}</span>
                          </div>
                          <div class="gg-item-price {{$childrenPrice?'':'collapse'}}" >
                            <div class="gg-item-tax">
                              <span>Select Children <strong>({{$tour->childrenMinAge}} - {{$tour->childrenMaxAge}} years)</strong></span>
                            </div>
                            <select class="children-selector check-prices">
                              @for ($i = 0; $i < 10; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                              @endfor
                            </select>
                            <span class="gg-currency">X</span>
                            <span class="gg-currency"><sup>$</sup></span>
                            <span class="gg-price per-children-price">{{number_format($childrenPrice,2)}}</span>
                            <span class="gg-currency">{{$currencyCode}}</span>
                          </div>
                            <div class="gg-item-price {{count($tp->tourPriceTransportations)>0?'':'collapse'}}" >
                              <div class="gg-item-tax">
                                <span>Add transportation</span>
                              </div>
                              <select class="check-prices transportation-selector">
                                <option value="0">No transportation</option>
                                @foreach ($tp->tourPriceTransportations as $tpt)
                                  @php
                                    list($adultPrice2,$childrenPrice2) = \App\Components\ToursComponent::getTourPriceTransportationAmounts($tpt,\SC::$affiliateId);
                                  @endphp
                                  <option 
                                    value="{{$tpt->destinationId}}"
                                    data-adult="{{$adultPrice2}}"
                                    data-children="{{$childrenPrice2}}">From {{$tpt->destination->name}}</option>
                                @endforeach
                              </select>
                            </div>
                      </div>
                      <div class="gg-rate col-xs-12 col-sm-12 col-md-3">
                        <div class="gg-info-center">
                          <div class="gg-item-price">
                            <div class="gg-item-tax">
                              <span>TOTAL</span>
                            </div>
                            <span class="gg-currency"><sup>$</sup></span>
                            <span class="gg-price total">{{number_format($adultPrice,2)}}</span>
                            <span class="gg-currency">{{$currencyCode}}</span>
                          </div>
                          <div class="gg-item-tax">
                            <span>Taxes Included</span>
                          </div>
                          <div class="gg-item-btn">
                            <a class="gg-btn-book btn-loading add-tour" href="#" data-loading-text="<i class='fa fa-refresh fa-spin'></i>">Add to cart</a>
                          </div>
                        </div>
                      </div>
                    @else
                      <div class="gg-rate col-xs-12 col-sm-12 col-md-6">
                        <div class="gg-info-center">
                          <h3>This tour is not available on {{date('l',strtotime($quoteParams->arrival))}}</h3>
                          <div class="gg-item-btn">
                            <a class="gg-btn-book click-focus" href="#" data-selector="#tours-form .input-datepicker" >Change Tour Day</a>
                          </div>
                        </div>
                      </div>
                    @endif
                  </div>
                </div>
              </article>
            @endforeach
         </div>
          <hr>
          <div class="container">
              <p class="h1 text-center">Recommended tours in {{$tour->city->name}}</p>
              @include('templates.better-list',['models'=>$betterTours])
          </div>
      </section>
</div>
@endsection
@push('scripts')
      @if (!empty($tour->latitude))
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGvzzPQl6QC_0YVlumr4Tf_PeAjUgmr9U"></script>
        <script>
           // function initMap() {
              $(document).ready(function(){
            		var myLatlng = {lat: {{str_replace(',', '.', $tour->latitude)}}, lng: {{str_replace(',', '.',$tour->longitude)}} };
           
           	var map = new google.maps.Map(document.getElementById('gg-map'), {
              		zoom: 14,
              		scrollwheel: false,
              		// mapTypeId: google.maps.MapTypeId.HYBRID,
              		center: myLatlng
            		});
           
            		var marker = new google.maps.Marker({
              		position: myLatlng,
              		map: map,
            		});
           
            		map.addListener('center_changed', function() {
              		// 3 seconds after the center of the map has changed, pan back to the
              		// marker.
              		window.setTimeout(function() {
                			map.panTo(marker.getPosition());
              		}, 500);
            		});
           	
           	marker.addListener('click', function() {
              	map.setZoom(14);
              	map.setCenter(marker.getPosition());
            	});

              $('.added').each(function(i,o){
                 var $this = $(this);
                 var article = $this.closest('article');
                 var rph = $this.data('rph');
                 $("article[data-rph='"+rph+"']").hide();
                 article.show();
              });
           });
        </script>
      @endif
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
                "@id": "{{url('/tours',[$tour->city->path])}}",
                "name": "{{$tour->city->name}}"
              }
            },{
              "@type": "ListItem",
              "position": 3,
              "item": {
                "@id": "{{url('/tours',[$tour->city->path,$tour->path])}}",
                "name": "{{$tour->name}}"
              }
            }
            ]
          }
        </script>
        <script type="application/ld+json">
          {
            "@context": "http://schema.org/",
            "@type": "Product",
            "name": "{{$tour->name}}"
            @if ($tour->getImages())
            ,"image": [
              @foreach ($tour->getImages() as $i => $img)
              {{$i>0?',':''}}"{{$image->getFileUrl()}}"
              @endforeach
             ]
            @endif
            ,"description": "{{strip_tags($tour->shortDescription)}}"
            ,"mpn": "{{$tour->id}}"
            ,"brand": {
              "@type": "Thing",
              "name": "{{$tour->tourProvider->name}}"
            }
            @php
              list($adultPrice,$childrenPrice) = \App\Components\ToursComponent::getBestPrice($tour,SC::$affiliateId);
              $adultPrice *= $exchangeRate;
              $childrenPrice *= $exchangeRate;
            @endphp
              ,"offers": {
                "@type": "Offer",
                "priceCurrency": "{{SC::getCurrentCurrencyCode()}}",
                "price": "{{$adultPrice}}",
                "availability": "http://schema.org/InStock",
                "seller": {
                  "@type": "Organization",
                  "name": "Goguytravel"
                },
                "priceValidUntil": "{{date('Y-m-d',strtotime(' +1 month'))}}",
                "url" : "{{url($tour->getFullPath())}}"
              },
              "aggregateRating": {
                "@type" : "AggregateRating",
                "ratingValue" : "5",
                "reviewCount" : "5"
              },
              "sku": "{{$tour->path}}"
          }
          </script>
@endpush
