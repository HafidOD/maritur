@extends('layouts.front')

@section('content')

<section class="gg-intback">
  <div class="container">

  <div class="gg-chk-steps row">
    <div class="col-xs-4"><a href='/cart'><div class="chevron active">1. Reservation</div></a></div>
    <div class="col-xs-4"><a href='/cart/info'><div class="chevron">2. Information</div></a></div>
    <div class="col-xs-4"><div class="chevron">3. Payment</div></div>
  </div>
    @if ($roomsCart || $toursCart || $transfersCart)
      <div class="row">
        <section class="col-xs-12 col-sm-8 col-md-8 col-lg-8">                                  
          @if ($roomsCart)
            @include('cart.rooms')
          @endif
          @if ($toursCart)
            <h2>Tours</h2>
            @include('cart.tours')
          @endif   
          @if ($transfersCart)
            <h2>Transportation</h2>
            @include('cart.transfers')
          @endif   
        </section>
        @include('cart.summary')
      </div>
    @else
      <h4 class="text-center">No se han seleccionado habitaciones</h4>
    @endif
  </div>
  </section>
@endsection
