  @foreach ($toursCart as $tour)
      <article class="gg-chk-item row">
        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
          <img src="{{$tour->image}}" alt="" title="" width="700" height="500">
        </div>
        <div class="col-xs-12 col-sm-7 col-md-8 col-lg-9">
          <div>
            <a class="gg-delete remove-tour" href="#" data-index='{{$tour->index}}'>
              <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </a>
            <div class="gg-item-name">{{$tour->name}}</div>
            <div class="gg-item-detail">                    
              {{$tour->variant}}
            </div>
          </div>
          <div class="gg-item-data col-xs-12">
            <div>
              <div>
                <span>Day: {{$tour->day->format('m/d/Y')}}</span><br>
                <span>
                  {{$tour->adults}} Adults
                  @if ($tour->children>0)
                    ,{{$tour->children}} Children
                  @endif
                </span><br>
                @if ($tour->fromDestination)
                  <span>With transportation from {{$tour->fromDestination}}</span>
                @endif
              </div>
              <div>
                <div class="price-box right">
                  <div class="info-price-box">
                    <span class="price">${{number_format($tour->subtotal,2)}} {{$currencyCode}}</span>
                  </div>
                </div>
              </div>
            </div>                  
          </div>
        </div>
      </article>
      @php
        $subtotal+=$tour->subtotal;
        $total+=$tour->total;
      @endphp
  @endforeach