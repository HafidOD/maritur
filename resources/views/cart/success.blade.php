@extends('layouts.front')

@section('content')
<br>
<br>
<br>
<h2 class="text-center">Your reservation has been created successfully, you will receive an email with more information.</h2>
<br>
<br>
<br>
@endsection

@if ($res && SC::$affiliateId == 1)
	@section('gtagScripts')
	<script>
		var transaction = {
		  transaction_id: '{{$res->id}}',
		  affiliation: 'Goguytravel Reservation',
		  value: {{round($res->total,2)}},
		  currency: '{{$res->currencyCode}}',
		  tax: {{round($res->total-$res->subtotal,2)}},
		  items:[]
		};
	@if ($res->hotelReservation)
		@foreach ($res->hotelReservation->rooms as $i => $room)
			transaction.items.push({
				'name': '{{$room->refRoomTypeName}}',
		      	'variant': '{{$room->refRatePlanName}}',
		      	'category': 'Accommodation',
		      	'quantity': 1,
		      	'price': '{{round($room->total,2)}}'
			});
		@endforeach
	@endif
	@if ($res->toursReservations()->count())
		@foreach ($res->toursReservations as $i => $t)
			transaction.items.push({
				'name': '{{$t->tourPrice->tour->name}}',
		      	'variant': '{{$t->tourPrice->name}}',
		      	'category': 'Tour',
		      	'quantity': {{$t->adults+$t->children}},
		      	'price': '{{round($t->total,2)}}'
			});
		@endforeach
	@endif
	@if ($res->transportReservations()->count())
		@foreach ($res->transportReservations as $transport)
			transaction.items.push({
				'name': '{{$transport->transportServiceType->name}}',
		      	'category': 'Transportation',
		      	'quantity': {{$transport->pax}},
		      	'price': '{{round($transport->total,2)}}'
			});
		@endforeach
	@endif
		gtag('event', 'purchase', transaction);
	</script>
	@endsection
@endif
