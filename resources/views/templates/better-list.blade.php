<section class="topitems">
    <div class="flexslider">
      <ul class="row slides">
        @foreach ($models as $model)
          <li class="col-md-3">
            <article class="box-shadow-hover">
                <div class="image">
                  <img src="{{$model->imageUrl}}">
                  <span><i class="fa fa-smile"></i> Recomended</span>
                </div>
                <div class="desc">
                  <p class="title">{{$model->title}}</p>
                  <p><i class="fa fa-map-marker"></i> {{$model->cityName}}</p>
                  @if($model->stars)
                  <div class="hotel-stars">
                      @for ($i = 0; $i < $model->stars; $i++)
                        <i class="fa fa-star"></i>
                      @endfor
                  </div>
                  @endif
                  @if($model->price)
                    <p class="price">From <strong>${{number_format($model->price,2)}} {{$model->currencyCode}}</strong></p>
                  @endif
                  <a href="{{$model->seeMoreUrl}}" class="btn btn-block">SEE MORE <i class="fa fa-chevron-right"></i></a>
                </div>
            </article>  
          </li>          
        @endforeach
      </ul>              
    </div>
    <hr>
</section>

