  @foreach ($transfersCart as $transfer)
      <article class="gg-chk-item row">
        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
          <img src="{{$transfer->image}}" alt="" title="" width="700" height="500">
        </div>
        <div class="col-xs-12 col-sm-7 col-md-8 col-lg-9">
          <div>
            <a class="gg-delete remove-transfer" href="#" data-index='{{$transfer->index}}'>
              <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </a>
            <div class="gg-item-name">{{$transfer->serviceName}}</div>
            <div class="gg-item-detail">                    
              Transport to {{$transfer->destinationName}}
            </div>
          </div>
          <div class="gg-item-data col-xs-12">
            <div>
              <div>
                <span>Arrival: {{$transfer->arrival->format('m/d/Y')}}</span><br>
                <span>
                  {{$transfer->pax}} passengers <strong>{{$transfer->triptype=='oneway'?'One Way':'Roundtrip'}}</strong>
                </span><br>
              </div>
              <div>
                <div class="price-box right">
                  <div class="info-price-box">
                    <span class="price">${{number_format($transfer->subtotal,2)}} {{$currencyCode}}</span>
                  </div>
                </div>
              </div>
            </div>                  
          </div>
        </div>
      </article>
      @php
        $subtotal+=$transfer->subtotal;
        $total+=$transfer->total;
      @endphp
  @endforeach