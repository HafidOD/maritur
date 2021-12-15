<section id="overview" class="gg-chk-item pull-right col-xs-12 col-sm-4 col-md-3 col-lg-3">
<div class="overview-title">Reservation</div>
<div>
  <div class="overview-item row">
  @if ($roomsSubtotal)
      <div class="col-xs-5 overview-item-name">Rooms</div>
      <div class="col-xs-7 overview-item-amount">
        <span class="gg-currency"><sup>$</sup></span>
        <span class="gg-price">{{number_format($roomsSubtotal,2)}}</span>
        <span class="gg-currency">{{$currencyCode}}</span>
      </div>
  @endif
  @if ($toursSubtotal)
    <div class="row">
      <div class="col-xs-5 overview-item-name">Tours</div>
      <div class="col-xs-7 overview-item-amount">
        <span class="gg-currency"><sup>$</sup></span>
        <span class="gg-price">{{number_format($toursSubtotal,2)}}</span>
        <span class="gg-currency">{{$currencyCode}}</span>
      </div>
    </div>
  @endif
  @if ($transfersSubtotal)
    <div class="row">
      <div class="col-xs-5 overview-item-name">Transportation</div>
      <div class="col-xs-7 overview-item-amount">
        <span class="gg-currency"><sup>$</sup></span>
        <span class="gg-price">{{number_format($transfersSubtotal,2)}}</span>
        <span class="gg-currency">{{$currencyCode}}</span>
      </div>
    </div>
  @endif
    </div>
  <div class="overview-item row">
    <div class="col-xs-5 overview-item-name">Taxes</div>
    <div class="col-xs-7 overview-item-amount">
      <span class="gg-currency"><sup>$</sup></span>
      <span class="gg-price">{{number_format($total-$subtotal,2)}}</span>
      <span class="gg-currency">{{$currencyCode}}</span>
    </div>
    <div class="col-xs-5 overview-item-name">Total</div>
    <div class="col-xs-7 overview-item-amount">
      <span class="gg-currency"><sup>$</sup></span>
      <span class="gg-price">{{number_format($total,2)}}</span>
      <span class="gg-currency">{{$currencyCode}}</span>
    </div>
  </div>
</div>
<div class="gg-item-btn">
@isset ($lastStep)
    <button type='submit' class='gg-btn-book'>Make Reservation </button>
@else
    <a class="gg-btn-book" href="/cart/info">Continue</a>
@endisset
</div>
</section>