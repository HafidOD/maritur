@extends('layouts.front')

@section('content')
@php
   SEO::setTitle($hotel->name);
   SEO::setDescription($infoData->HotelInfo->Descriptions->DescriptiveText);
   SEO::addImages(asset($hotelImages?$hotelImages[0]:'/images/no-image.jpg'));
   SEO::setCanonical(url($hotel->getFullPath()));
@endphp
<div id="hotel">
    <div id="bookingbox" class="with-back">
      <div class="container">
          @include('search-forms.hotels',['showTittle'=>false,'style'=>'l','adultsOnly'=>$infoDataAux->adultsOnly])
      </div>
    </div>
      <section class="gg-intback">
        <div id="detail-container" class="container">
          <div class="row">
            <div class="col-md-6">
              <ol class="breadcrumb">
                <li><a href="{{url('/')}}" title="Home">Home</a></li>
                <li><a href="{{url('/hotels',[$hotel->city->path])}}" title="{{$hotel->city->name}}">{{$hotel->city->name}}</a></li>
                <li><a href="{{url('/hotels',[$hotel->city->path,$hotel->path])}}" title="{{$hotel->name}}">{{$hotel->name}}</a></li>
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
                  {{$hotel->name}}
                </h1>
                  @isset ($infoData->AffiliationInfo->AwardsType->Awards[0])
                      <span class="hotel-stars">
                        @for ($i = 0; $i < $infoData->AffiliationInfo->AwardsType->Awards[0]->Rating; $i++)
                          <i class="fa fa-star"></i>
                        @endfor
                      </span>
                  @endisset
                  @if ($infoData->HotelRef->ChainName)
                  <p>
                    {{$infoData->HotelRef->ChainName}}
                  </p>
                  @endif
                   @if ($infoData->ContactInfosType->ContactInfos[0]->AddressesType->Addresses[0]->AddressLine)
                   <div id="gg-address">
                    <span><i class="fa fa-map-marker"></i> {!! $infoData->ContactInfosType->ContactInfos[0]->AddressesType->Addresses[0]->AddressLine !!}</span>
                    </div>

                   @endif
             </div>
              @if ($hotelImages)
              <section id='single-gallery'>
               <h2 class='title'>{{$hotel->name}} Gallery</h2>
               <div class="row">
                  <div class="col-xs-12 col-md-8">
                     <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                      <div class="carousel-inner" role="listbox">
                           @foreach ($hotelImages as $i => $img)
                              @php
                                 if ($i==8) break;
                              @endphp
                              <div class="item {{$i==0?'active':''}}">
                                 <img src="{{$img}}" alt="">
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
                     @foreach ($hotelImages as $i => $img)
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
                           <img src="{{$img}}" alt="" title="" width="700" height="500" />   
                           </a>
                        </div>
                     @endforeach
                  </div>            
               </div>
              </section>
              @endif
          </div>
         <h2 class='title'>{{$hotel->name}} Overview</h2>
          <div class='hotelinfo row'>
           @if ($infoData->HotelInfo->TotalRooms)
           	<div class="col-xs-12 "><b>Rooms:</b> {{$infoData->HotelInfo->TotalRooms}}</div>
           @endif
           @if ($infoData->HotelInfo->CheckInHours)
  	         <div class="col-sm-6 col-md-6 "><b>Check-in:</b> {{date('g:00 A',strtotime($infoData->HotelInfo->CheckInHours->Start))}}</div>
  	         <div class="col-sm-6 col-md-6 "><b>Check-out:</b> {{date('g:00 A',strtotime($infoData->HotelInfo->CheckInHours->End))}}</div>
           @endif
             <div class="col-sm-12 col-md-12 ">
               <div class="hotel-descripcion">
                  {{$infoData->HotelInfo->Descriptions->DescriptiveText}}
               </div>
               @if ($infoData->HotelInfo->HotelAmenities)
      	         <div class="tab-pane fade in" id="facilities">
                  <br>
        	         <h2>Hotel Amenities</h2>
        	         <ul class="list-items row">
        		         @foreach ($infoData->HotelInfo->HotelAmenities as $a)
        			         <li class="col-xs-6 col-sm-5 col-md-4 col-lg-4">
        			         	<span>{{$a->HotelAmenity}}</span>
        			         </li>         	
        		         @endforeach
        	         </ul>
      	         </div>
             @endif
             </div>
          </div>
          <div id="gg-map"></div>
          <br>
          <div>
             @php
                $rphRoom = false;
             @endphp
             @if ($roomRates)
                @foreach ($roomRates as $roomRate)
                @if ($rphRoom!==$roomRate->rphRoom)
                   @php
                      $rphRoom = $roomRate->rphRoom; 
                   @endphp
                  <p class='title'>Options for room {{$rphRoom+1}}</p>
                @endif
                 <article class="gg-itemlist row" data-rph='{{$rphRoom}}'>
                    <div class="gg-thumb col-xs-12 col-sm-5 col-md-3 ">
                       <img src="{{$roomRate->roomType->image}}" alt="" title="" width="700" height="500">
                    </div>
                    <div class="gg-inforoom col-xs-12 col-sm-7 col-md-9">
                       <div class="gg-room-name">
                          <h3>{{$roomRate->roomType->name}}</h3>
                       </div>
                       <div class="gg-room-plan row">
                          <div class="col-xs-12 col-sm-6 col-md-8">
                               <h3>
                                {{$roomRate->ratePlan->name}}
                                  @if ($roomRate->ratePlan->description)
                                      <i 
                                        class="fa fa-info-circle"
                                        data-toggle="tooltip"
                                        title="{{$roomRate->ratePlan->description}}"
                                     ></i>
                                  @endif
                                 @if($roomRate->ratePlan->nonRefundable)
                                  <small><strong>Non Refundable</strong></small>
                                 @endif
                               </h3>
                               <div class="plan-nombre">
                                  {!!nl2br($roomRate->roomType->description)!!}         
                              </div>
                          </div>
                          <div class="gg-rate col-xs-12 col-sm-6 col-md-4">
                             <div>
                                @if ($roomRate->hasOffer)
                                  <div class="gg-item-price">
                                    <del>
                                     <span class="gg-currency"><sup>$</sup></span>
                                     <span class="gg-price">{{number_format($roomRate->subtotalBeforeOffer,2)}}</span>
                                     <span class="gg-currency">{{$roomRate->currencyCode}} </span>                                      
                                    </del>
                                  </div>
                                @endif
                                <div class="gg-item-price">
                                   <span class="gg-currency"><sup>$</sup></span>
                                   <span class="gg-price">{{number_format($roomRate->subtotal,2)}}</span>
                                   <span class="gg-currency">{{$roomRate->currencyCode}} </span>
                                </div>
                                  <a 
                                    href="#politicr{{$roomRate->roomType->id}}{{$roomRate->ratePlan->id}}"
                                    data-toggle="modal"
                                    class="btn btn-link text-right no-margin"
                                    title="POLITICS" 
                                    ><i class="fa fa-eye"></i> <strong>POLITICS</strong></a>
                                  <div id='politicr{{$roomRate->roomType->id}}{{$roomRate->ratePlan->id}}' class="modal fade" tabindex="-1" role="dialog">
                                   <div class="modal-dialog" role="document">
                                     <div class="modal-content">
                                        <div class="modal-header">
                                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                         <p class="h2 text-left">POLITICS</p>
                                        </div>
                                       <div class="modal-body">
                                        @if($roomRate->ratePlan->nonRefundable)
                                          <p class='text-left'>{{$roomRate->ratePlan->politics}}</p>
                                        @else
                                          <p class='text-left'>{!!SettingsComponent::get('hotelPolicy')!!}</p>
                                        @endif
                                       </div>
                                     </div>
                                   </div>
                                 </div>

                                <div class="gg-item-btn">
                                      <a 
                                           data-hotelcode="{{$hotel->code}}"
                                           data-roomtypeid="{{$roomRate->roomType->id}}"
                                           data-roomtypename="{{$roomRate->roomType->name}}"
                                           data-rateplanid="{{$roomRate->ratePlan->id}}"
                                           data-rateplanname="{{$roomRate->ratePlan->name}}"
                                           data-total="{{$roomRate->refTotal}}"
                                           data-subtotal="{{$roomRate->refSubtotal}}"
                                           data-currencycode="{{$roomRate->refCurrencyCode}}"
                                           data-rph="{{$rphRoom}}"
                                           data-image="{{$roomRate->roomType->image}}"
                                           href='#' 
                                           class="gg-btn-book add-room btn-block {{$roomRate->selected?'added':'add'}}" 
                                           data-loading="<i class='fa fa-refresh fa-spin'></i>"
                                           data-added-text="<i class='fa fa-check'></i> DESELECT ROOM"
                                           title="{{$roomRate->selected?'DESELECT ROOM':'SELECT ROOM'}}"
                                           >
                                           @if ($roomRate->selected)
                                           <i class='fa fa-check'></i> DESELECT ROOM
                                           @else
                                           SELECT ROOM
                                           @endif
                                           </a>
                                </div>
                             </div>
                          </div>
                       </div>
                      </div>
                 </article>
                @endforeach
               <div class="text-right">
                  <a href="/cart" style="border-radius: 0;margin-bottom: 10px" class="btn btn-primary btn-lg">CONTINUE <i class="fa fa-arrow-right"></i></a>
               </div>
             @else
                <h2 class="text-center">No se encontró disponibilidad</h2>
             @endif
           </div>
         </div>
         <hr>
          <div class="container">
              <p class="h1 text-center">Recommended hotels in {{$hotel->city->name}}</p>
              @include('templates.better-list',['models'=>$betterHotels])
          </div>
      </section>
      <br>
      <br>
      <br>
</div>
@endsection
@push('scripts')
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGvzzPQl6QC_0YVlumr4Tf_PeAjUgmr9U"></script>
      <script>
         // function initMap() {
            $(document).ready(function(){
          		var myLatlng = {lat: {{str_replace(',', '.', $infoData->HotelInfo->Position->Latitude)}}, lng: {{str_replace(',', '.',$infoData->HotelInfo->Position->Longitude)}} };
         
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
                "@id": "{{url('/hotels',[$hotel->city->path])}}",
                "name": "{{$hotel->city->name}}"
              }
            },{
              "@type": "ListItem",
              "position": 3,
              "item": {
                "@id": "{{url('/hotels',[$hotel->city->path,$hotel->path])}}",
                "name": "{{$hotel->name}}"
              }
            }
            ]
          }
        </script>
        <script type="application/ld+json">
          {
            "@context": "http://schema.org/",
            "@type": "Product",
            "name": "{{$hotel->name}}"
            @if ($hotelImages)
            ,"image": [
              @foreach ($hotelImages as $i => $img)
              {{$i>0?',':''}}"{{url($img)}}"
              @endforeach
             ]
            @endif
            ,"description": {!!json_encode($infoData->HotelInfo->Descriptions->DescriptiveText)!!}
            ,"mpn": "{{$hotel->id}}"
            @isset ($infoData->HotelRef->ChainName)
              ,"brand": {
                "@type": "Thing",
                "name": "{{$infoData->HotelRef->ChainName}}"
              } 
            @endisset
            @if ($roomRates)
            @php
              $roomRate = $roomRates[0];
            @endphp
              ,"offers": {
                "@type": "Offer",
                "priceCurrency": "{{SC::getCurrentCurrencyCode()}}",
                "price": "{{$roomRate->subtotal}}",
                "availability": "http://schema.org/InStock",
                "seller": {
                  "@type": "Organization",
                  "name": "Goguytravel"
                },
                "priceValidUntil": "{{date('Y-m-d',strtotime($quoteParams->arrival.' +1 week'))}}",
                "url" : "{{url($hotel->getFullPath())}}"
              },
              "aggregateRating": {
                "@type" : "AggregateRating",
                "ratingValue" : "5",
                "reviewCount" : "5"
              },
              "sku": "{{$hotel->path}}"
            @endif
          }
          </script>
@endpush
