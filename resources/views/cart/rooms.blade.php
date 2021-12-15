<h2>{{$hotelName}}</h2>
<p>{{date('M d, Y',strtotime($quoteParams->arrival))}} - {{date('M d, Y',strtotime($quoteParams->departure))}}</p>
@foreach ($roomsCart as $i => $rs)
<article class="gg-chk-item row">
  <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
    <img src="{{$rs->image}}" alt="" title="" width="700" height="500">
  </div>
  <div class="col-xs-12 col-sm-7 col-md-8 col-lg-9">
    <div>
      <a class="gg-delete remove-room" href="#" data-rph='{{$rs->rph}}'>
        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
      </a>
      <div class="gg-item-name">{{$rs->roomTypeName}}</div>
      <div class="gg-item-detail">                    
        {{$rs->ratePlanName}}
      </div>
    </div>
    <div class="gg-item-data col-xs-12">
      <div>
        <div>
          <span>1 Room: </span> 
          <span>{{$rs->adults}} Adults, {{$rs->children}} Children</span>
        </div>
        <div>
          <div class="price-box right">
            <div class="info-price-box">
              <span class="price">${{number_format($rs->subtotal,2)}} {{$currencyCode}}</span>
            </div>
          </div>
        </div>
      </div>                  
    </div>
  </div>
</article>
@php
  $subtotal+=$rs->subtotal;
  $total+=$rs->total;
@endphp
@endforeach