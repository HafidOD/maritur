@foreach ($hotelsData as $hotel)
<li 
  class="gg-itemlist list-item row"
  data-price="{{round($hotel->subtotal,2)}}"
  data-stars='{{json_encode([$hotel->stars])}}'
  data-mealplans='{{json_encode($hotel->mealPlans)}}'
  >
  <div class="gg-thumb col-xs-12 col-sm-4 col-md-4 col-lg-3 ">
		<img src="{{$hotel->image}}" alt="" title="" width="700" height="500">
  </div>
  <div class="gg-info col-xs-12 col-sm-5 col-md-5 col-lg-6">
    <div class="gg-info-center">
      <a href="/hotels/{{$hotel->path}}" class="gg-item-name title" title="{{$hotel->name}}">{{$hotel->name}}</a>
      <div class="hotel-stars">
      	@for ($i = 0; $i < $hotel->stars; $i++)
			<i class="fa fa-star"></i>
      	@endfor
      </div>
      <div class="gg-item-city">
        <a href="#" title="{{$hotel->address}}"><i class="fa fa-map-marker"></i> {{$hotel->address}}</a>
      </div>
      <div>
      </div>
    </div>
  </div>
  <div class="gg-rate col-xs-12 col-sm-3 col-md-3 col-lg-3">
    <div class="gg-info-center">
          @if ($hotel->hasOffer)
            <div class="gg-item-price">
              <del>
                <span class="gg-currency"><sup>$</sup></span>
                <span class="gg-price">{{number_format($hotel->subtotalBeforeOffer,2)}}</span>
                <span class="gg-currency">{{$hotel->currencyCode}} </span>                
              </del>
            </div>
          @endif
      <div class="gg-item-price">
          <span class="gg-currency"><sup>$</sup></span>
         <span class="gg-price">{{number_format($hotel->subtotal,2)}}</span>
          <span class="gg-currency">{{$hotel->currencyCode}} </span>
      </div>
        <div class="gg-item-btn"> 
          <a class="gg-btn-book btn-loading" href="/hotels/{{$hotel->path}}" title="View More {{$hotel->name}}">View More</a>
        </div>
    </div>
  </div>
</li>
@endforeach
@if (count($hotelsData)==0)
<li>
  <h3 class="text-center">There are not enough hotels available for the requested parameters</h3>
</li>
@endif