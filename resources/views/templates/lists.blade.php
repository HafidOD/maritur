<section class="topitems">
  @foreach ($lists as $list)
      <h2>{{$list->name}}</h2>
      <div class="flexslider">
        <ul class="row slides">
          @php
            $templates = [
              1=>'templates.hotels',
              2=>'templates.tours',
              3=>'templates.destinations',
            ];
          @endphp
          @include($templates[$list->itemType],['irs'=>$list->itemRelations])
        </ul>              
      </div>
      <hr>
@endforeach
</section>