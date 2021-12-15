@foreach ($irs as $ir)
  @if ($ir->referenceModel)
      <li class="col-md-3">
        <article class="box-shadow-hover">
            <div class="image">
              <img data-src="{{$ir->referenceModel->getPrimaryImageUrl()}}">
              <span><i class="fa fa-smile"></i> Recomended</span>
            </div>
            <div class="desc">
              <p class="title">{{$ir->referenceModel->name}}</p>
              <p><i class="fa fa-map-marker"></i> {{$ir->referenceModel->city->name}}</p>
              <div class="hotel-stars">
                  @for ($i = 0; $i < $ir->referenceModel->stars; $i++)
                    <i class="fa fa-star"></i>
                  @endfor
              </div>
              <p class="price">From <strong>{{number_format($ir->fromPrice)}} USD</strong></p>
              <a href="{{$ir->referenceModel->getFullPath()}}" class="btn btn-block">SEE MORE <i class="fa fa-chevron-right"></i></a>
            </div>
        </article>  
      </li>
  @endif
@endforeach
