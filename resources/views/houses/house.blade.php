@extends('layouts.front')

@section('content')
@php
   SEO::setTitle($house->name);
   SEO::setDescription($house->shortDescription);
   SEO::addImages(asset($house->getPrimaryImageUrl()));
   SEO::setCanonical(url($house->getFullPath()));
@endphp
<div id="tour">
      <section class="gg-intback">
      <div class="container">
            <div class="gg-datadetail">
               <h1>
               	{{$house->name}}
               </h1>
                  @if ($house->destination->name)
                  <div id="gg-address">
                  	<span><i class="fa fa-map-marker"></i> {{$house->destination->fullName()}}</span>
                  </div>
                  @endif
            </div>         
      </div>
      </section>
            <section class="gg-intback">
         <div id="detail-container" class="container">
            <section id='single-gallery'>
               <p class='title'>Gallery</p>
               <div class="row">
                  <div class="col-xs-12 col-md-8">
                     <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                      <div class="carousel-inner" role="listbox">
                           @foreach ($house->getImages() as $i => $image)
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
                     @foreach ($house->getImages() as $i => $image)
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
      </section>
      <section class="gg-intback">
         <div id="detail-container" class="container">
            {{-- <div class="book-container"> --}}
               {{-- @include('hotels.search-form') --}}
            {{-- </div> --}}
            @if ($house->description)
               <br>
               <p class="title-box">Description</p>
               <div class="row">
                  <div class="item-info col-md-12 excerpt">
                     {!!$house->description!!}
                  </div>
               </div>
            @endif
         </div>
      </section>
</div>
@endsection
