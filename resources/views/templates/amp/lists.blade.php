<section class="topitems">
  @foreach ($lists as $list)
  <h2>{{$list->name}}</h2>
  <amp-carousel
    width="1200"
    height="300"
    layout="responsive"
    type="slides">
          @php
            $templates = [
              1=>'templates.amp.hotels',
              2=>'templates.amp.tours',
              3=>'templates.amp.destinations',
            ];
          @endphp
          @include($templates[$list->itemType],['irs'=>$list->itemRelations])
    </amp-carousel>
      <hr>
@endforeach
</section>