@php
	$quoteParams2 = \App\Components\SessionComponent::getQuoteParams();
	$showFirstRoom = $quoteParams2->rooms>1 || $quoteParams2->children[0]>0;
	$actionField = isset($actionField)?$actionField:'#';
	$adultsOnly = isset($adultsOnly)?$adultsOnly:false;
	$style = isset($style)?$style:'p';
	$classStyles = [
		'p'=>[
			'col-xs-12',
			'col-xs-12',
			'col-xs-12',
			'col-xs-4',
			'col-xs-12 text-right',
		],
		'l'=>[
			'col-xs-12 col-sm-4',
			'col-xs-12 col-sm-3',
			'col-xs-12 col-sm-2',
			'col-xs-4 col-sm-1',
			'col-xs-12 col-sm-2 text-center',
			// 'col-xs-12',
		],
	];
	$iaux=0;
@endphp
<form id='hotels-form' action="{{$quoteParams2->searchPath}}" class="style{{$style}}">
	<input type="hidden" name="search" value="1">
	<input type="hidden" name="searchPath" value="{{$quoteParams2->searchPath}}">
	<div class="row">
		@if (!isset($showTittle) || (isset($showTittle) && $showTittle==true))
			<div class="col-xs-12">
				<h2>Meet the best destinations of mexico</h2>
			</div>
		@endif
		<div class="{{$classStyles[$style][$iaux++]}}">
			<label class="label-control">Transfers / Tours / Destination</label>
			<input type="text" class="form-control destination-field" value="{{$quoteParams2->destination}}" data-url='/transfers/autocomplete' required/>
		</div>
		<div class="{{$classStyles[$style][$iaux++]}}">
			<label class="label-control">Checkin & Checkout</label>
			<span id='hotels-daterange' class="input-daterange form-control">{{date('M d, Y',strtotime($quoteParams2->arrival))}} - {{date('M d, Y',strtotime($quoteParams2->departure))}}</span>
			<input type="hidden" name="arrival" id="hotels-arrival" value="{{$quoteParams2->arrival}}"/>
			<input type="hidden" name="departure" id="hotels-departure" value="{{$quoteParams2->departure}}" />
		</div>
		
		<div class="{{$classStyles[$style][$iaux++]}}" id="adultsFake" style="{{$showFirstRoom?"display:none":''}}">
			<label class="fake label-control">.</label>
			<select class="form-control" required >
				@for ($i = 1; $i <=10; $i++)
					<option {{$quoteParams2->adults[0]==$i?'selected':''}} value="{{$i}}" >{{$i}} {{$i>1?'Adults':'Adult'}}</option>
				@endfor
			</select>
		</div>
		<div class="{{$classStyles[$style][$iaux++]}}">
			<button type="submit" class="btn btn-success">GO</button>
		</div>
		<div id="rooms-container" class="col-xs-12">
	@foreach ($quoteParams2->adults as $i => $adults)
	@php
		$children = $quoteParams2->children[$i];
		$ages = $children>0?$quoteParams2->ages[$i]:[];
	@endphp
		<div class="room-pax" style="{{$i==0 && !$showFirstRoom?'display:none':''}}">
			<div class="row">
				<div class="room-number col-sm-2">
					<p>Room <span>1</span></p>
				</div>
				<div class="col-sm-2">
					<label class="label-control">Pax</label>
					<select name='adults[]' class="form-control">
						@for ($j = 0; $j <=10; $j++)
							<option {{$adults==$j?'selected':''}} value="{{$j}}" >{{$j}}</option>
						@endfor
					</select>
				</div>
				<div class="col-sm-2">
					<label class="label-control">Children</label>
					<select name='children[]' class="form-control">
						@for ($j = 0; $j <=10; $j++)
							<option {{$children==$j?'selected':''}} value="{{$j}}" >{{$j}}</option>
						@endfor
					</select>
				</div>
				<div class="ages-container col-sm-6">
					<div class="row">
						@if ($children>0)
							@foreach ($ages as $j => $age)
							    <div class="col-sm-4" >
							    	<label class="label-control">Age child <span>{{$j+1}}</span></label>
							        <select name="ages[{{$i}}][]" class="form-control">
										@for ($k = 0; $k <= 17; $k++)
											<option {{$age==$k?'selected':''}} value="{{$k}}" >{{$k}}</option>
										@endfor
							        </select>
							    </div>														
							@endforeach
						@endif									
					</div>
				</div>						
			</div>
		</div>
	@endforeach
	</div>
	</div>
</form>
<div id='children-age-example' class="col-sm-4" style="display: none;">
	<label class="label-control">Age <span>1</span></label>
    <select name="" class="form-control">
		@for ($j = 0; $j <= 17; $j++)
			<option value="{{$j}}" >{{$j}}</option>
		@endfor
    </select>
</div>