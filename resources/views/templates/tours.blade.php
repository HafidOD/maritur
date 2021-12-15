{{-- <div class="row"> --}}
@foreach ($irs as $ir)
  @if ($ir->referenceModel)
  <li class="col-md-3">
    <article class="box-shadow-hover">
        <div class="image">
          <img data-src="{{$ir->referencemodel->getPrimaryImageUrl(750,550)}}">
          <span><i class="fa fa-smile"></i> recomended</span>
        </div>
        <div class="desc">
          <p class="title">{{$ir->referencemodel->name}}</p>
          <p><i class="fa fa-map-marker"></i> {{$ir->referencemodel->city->name}}</p>
          <p class="price">from <strong>{{number_format($ir->fromPrice)}} usd</strong></p>
          <a href="{{$ir->referencemodel->getfullpath()}}" class="btn btn-block">see more <i class="fa fa-chevron-right"></i></a>
        </div>
    </article>      
  </li>
  @endif
@endforeach
{{-- </div> --}}