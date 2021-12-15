@php
	$tourQuoteParams = SC::getToursQuoteParams();
	$style = isset($style)?$style:'p';
	$classStyles = [
		'p'=>[
			'col-xs-6 col-md-12',
			'col-xs-6 col-md-6',
			'col-xs-6 col-md-3',
			'col-xs-6 col-md-3',
			'col-xs-12',
		],
		'l'=>[
			'col-xs-6 col-md-5',
			'col-xs-6 col-md-3',
			'col-xs-6 col-md-1',
			'col-xs-6 col-md-1',
			'col-xs-12 col-md-2',
		],
	];
	$iaux=0;
@endphp
<form id='tours-form' class="style{{$style}}" action='{{$tourQuoteParams->searchPath}}' method="get" >
	<div class="row">
		<div class="{{$classStyles[$style][$iaux++]}}">
			<label class="label-control">Tour / Destination</label>
			<input type="text" class="form-control destination-field" value="{{$tourQuoteParams->destination}}" data-url='/tours/autocomplete' required/>
		</div>
		<div class="{{$classStyles[$style][$iaux++]}}">
			<label class="label-control">Day</label>
			<span class="input-datepicker form-control"></span>
			<input type="hidden" name="arrival" value="{{$tourQuoteParams->arrival}}" />
		</div>
		<div class="{{$classStyles[$style][$iaux++]}}" >
			<label class="label-control">Adults</label>
			<select name="adults" class="form-control" required >
				@for ($i = 1; $i <=10; $i++)
					<option {{$tourQuoteParams->adults==$i?'selected':''}} value="{{$i}}" >{{$i}}</option>
				@endfor
			</select>
		</div>
		<div class="{{$classStyles[$style][$iaux++]}}" >
			<label class="label-control">Children</label>
			<select name="children" class="form-control" required >
				@for ($i = 0; $i <=10; $i++)
					<option {{$tourQuoteParams->children==$i?'selected':''}} value="{{$i}}" >{{$i}}</option>
				@endfor
			</select>
		</div>
		<div class="{{$classStyles[$style][$iaux++]}} {{$style=='p'?'text-right':'text-center'}}">
			<button type="submit" class="btn btn-success">GO</button>
		</div>
	</div>
</form>