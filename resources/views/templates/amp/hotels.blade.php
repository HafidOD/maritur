@foreach ($irs as $ir)
  @if ($ir->referenceModel)
  <div>
    <amp-img lightbox
      {{-- src="{{$ir->referenceModel->getPrimaryImageUrl()}}" --}}
      src="http://192.168.0.12:8000/storage/hotels/fiesta-americana-villas-cancun-cancun.jpg"
      width="300"
      height="200"
      layout="responsive"></amp-img>
    <p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>    
  </div>

{{--       <li class="col-md-3">
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
      </li> --}}
  @endif
@endforeach
