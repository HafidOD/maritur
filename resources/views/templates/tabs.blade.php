@php
	$rStr = str_random(4);
@endphp
<div class="row dinamic-tab">
<div class="col-xs-3 col-menu">
	<ul class='nav nav-pills nav-stacked'>
	@php
		$i = 0;
	@endphp
	@foreach ($tabs as $tabTitle => $viewName)
		<li class="{{$i==0?'active':''}}">
			<a href="#{{$rStr}}{{++$i}}" data-toggle='tab'>{{$tabTitle}}</a>
		</li>
	@endforeach
	</ul>
</div>
<div class="col-xs-9 col-content">
	<div class="tab-content">
		@php
			$i = 0;
		@endphp
		@foreach ($tabs as $tabTitle => $viewName)
			<div id='{{$rStr}}{{++$i}}' class="tab-pane {{$i==1?'active':''}}">@include($viewName)</div>
		@endforeach
	</div>
</div>
</div>