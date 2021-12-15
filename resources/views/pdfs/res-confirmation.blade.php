@php
	$c1 = SettingsComponent::get('color1');
	$c2 = SettingsComponent::get('color2');
@endphp
<style type="text/css">
	th{background-color: {{$c1}};color:#ffffff;}
	table.bordered td{border: .5px solid {{$c2}};}
	.c1{color:#2F9DD7;}
</style>
<table>
	<tr>
		<td>
			@if (!env('APP_DEBUG'))
				<img src="{{SettingsComponent::getLogoUrl()}}" style="width: 200px">
			@endif
		</td>
		<td></td>
		<td>
			<p style="text-align: center;color:{{$c2}};font-size: 20px">
			<strong>
				Confirmation <br/>
				#{{sprintf('%05d', $res->id)}}				
			</strong>
			</p>
		</td>
	</tr>
</table>
<br/>
<br/>
<h2>Thank you for your purchase, please print your itinerary/confirmation letter, present it
at the time of service with a valid ID.</h2>
<br/>
<table cellpadding="4" class="bordered">
	<thead>
		<tr>
			<th colspan="4">Client info</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<strong>First Name</strong><br/>{{$res->clientFirstName}}
			</td>
			<td>
				<strong>Last Name</strong><br/>{{$res->clientLastName}}
			</td>
			<td>
				<strong>Email</strong><br/>{{$res->clientEmail}}
			</td>
			<td>
				<strong>Phone</strong><br/>{{$res->clientPhone}}
			</td>
		</tr>
		<tr>
			<td>
				<strong>City</strong><br/>{{$res->clientCity}}
			</td>
			<td>
				<strong>State</strong><br/>{{$res->clientState}}
			</td>
			<td>
				<strong>Country</strong><br/>{{$res->country->name}}
			</td>
			<td>
				<strong>Hotel</strong><br/>{{$res->hotelname?$res->hotelname:'N/A'}}
			</td>
		</tr>
		<tr>
			<td colspan="4"><strong>Holder name</strong><br/>{{$res->holdername?$res->holdername:'N/A'}}</td>
		</tr>		
	</tbody>
</table>

@if ($res->hotelReservation)
	@php
		$hotelRes = $res->hotelReservation;
	@endphp
	<p>Hotel Name: <strong>{{$hotelRes->hotel->name}}</strong></p>
	<p>Arrival: <strong>{{date('d M, Y',strtotime($hotelRes->arrival))}}</strong></p>
	<p>Departure: <strong>{{date('d M, Y',strtotime($hotelRes->departure))}}</strong></p>
	<h3>Rooms reserved</h3>
	<table cellpadding="4" class="bordered">
		<thead >
			<tr>
				<th>#</th>
				<th>Room type</th>
				<th>Rate plan</th>
				<th>Adults</th>
				<th>Children</th>
			</tr>		
		</thead>
		<tbody>
			@foreach ($hotelRes->rooms as $i => $room)
				<tr>
					<td>{{$i+1}}</td>
					<td>{{$room->refRoomTypeName}}</td>
					<td>{{$room->refRatePlanName}}</td>
					<td>{{$room->adults}}</td>
					<td>{{$room->children}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<br/>
	<br/>
	<h3>Guests</h3>
	<table cellpadding="4" class="bordered">
		<thead>
			<tr>
				<th># Room</th>
				<th>Type</th>
				<th>Name</th>
				<th>Age</th>
			</tr>		
		</thead>
		<tbody>
			@foreach ($hotelRes->rooms as $i => $room)
			@php
				$auxPerson = 1;
			@endphp
				@foreach ($room->jsonRefExtraData['guestsData'] as $j => $person)
					@php
						$isAdult = $auxPerson<=$room->adults;
						$auxPerson++;
					@endphp
					<tr>
						<td>{{$i+1}}</td>
						<td>{{$isAdult?'Adult':'Kid'}}</td>
						<td>{{$person['givenName']}} {{$person['middleName']}}</td>
						<td>{{$isAdult?'--':$room->jsonAges[$j-$room->adults]}}</td>
					</tr>
				@endforeach
			@endforeach
		</tbody>
	</table>
@endif
@if ($res->toursReservations()->count())
	<h3>Tours Reserved</h3>
	<table cellpadding="4" class="bordered">
		<thead >
			<tr>
				<th>#</th>
				<th>Tour Name</th>
				<th>Tour Day</th>
				<th>Adults</th>
				<th>Children</th>
				<th>Transportation</th>
			</tr>		
		</thead>
		<tbody>
			@foreach ($res->toursReservations as $i => $t)
				<tr>
					<td>{{$i+1}}</td>
					<td>{{$t->tourPrice->tour->name}} / {{$t->tourPrice->name}}</td>
					<td>{{$t->day->format('m/d/Y')}}</td>
					<td>{{$t->adults}}</td>
					<td>{{$t->children}}</td>
					<td>{{$t->fromDestinationId>0?'From '.$t->fromDestination->name:'N/A'}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endif
<br/>
@if ($res->transportReservations()->count())
	@foreach ($res->transportReservations as $i => $transport)
		<h3>Reserved Transportation</h3>
		<table cellpadding="4" class="bordered">
			<thead >
				<tr>
					<th colspan="4">Transportation</th>
				</tr>		
			</thead>
			<tbody>
				<tr>
					<td>
						<strong>Destination</strong><br/>{{$transport->destName()}}
					</td>
					<td>
						<strong>Passengers</strong><br/>{{$transport->pax}}
					</td>
					<td>
						<strong>Service type</strong><br/>{{$transport->transportServiceType->name}}
					</td>
					<td>
						<strong>Trip type</strong><br/>{{$transport::$triptypeLabels[$transport->triptype]}}
					</td>
				</tr>
				<tr>
					<td>
						<strong>Arrival Date</strong><br/>{{$transport->arrivalDatetime->format('m/d/Y')}}
					</td>
					<td>
						<strong>Arrival time</strong><br/>{{$transport->arrivalDatetime->format('H:i')}} hrs
					</td>
					<td colspan="2">
						<strong>Arrival Flight</strong><br/>{{$transport->arrivalFlight}}
					</td>
				</tr>
				@if ($transport->triptype==2)
					<tr>
						<td>
							<strong>Departure Date</strong><br/>{{$transport->departureDatetime->format('m/d/Y')}}
						</td>
						<td>
							<strong>Departure time</strong><br/>{{$transport->departureDatetime->format('H:i')}} hrs
						</td>
						<td colspan="2">
							<strong>Departure Flight</strong><br/>{{$transport->departureFlight}}
						</td>
					</tr>
				@endif
			</tbody>
		</table>
	@endforeach
@endif
<br/>
<h3>Special requests:</h3>
<p>{{$res->specialRequests}}</p>
<br/>
@if ($res->toursReservations()->count())
	<table cellpadding="4" class="bordered">
		<thead>
			<tr>
				<th>Tours Policies</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
	                {!!nl2br(strip_tags(SettingsComponent::get('toursPolicy')))!!}
				</td>
			</tr>
		</tbody>
	</table>
@endif
@if ($res->hotelReservation)
	<table cellpadding="4" class="bordered">
		<thead>
			<tr>
				<th>Hotel Policies</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
	                {!!nl2br(strip_tags(SettingsComponent::get('hotelPolicy')))!!}
				</td>
			</tr>
		</tbody>
	</table>
@endif
@if ($res->transportReservations->count())
	<table cellpadding="4" class="bordered">
		<thead>
			<tr>
				<th>Transport Policies</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
	                {!!nl2br(strip_tags(SettingsComponent::get('transferPolicy')))!!}
				</td>
			</tr>
		</tbody>
	</table>
@endif
<br/>
<h3>Please confirm your pick up time at:</h3>
<p><strong>Email:</strong> <strong class="c1">{{SettingsComponent::get('emailNotifications')}}</strong></p>
<p><strong>Contact Phone:</strong> {{SettingsComponent::get('contactPhone')}}</p>
<p><strong>From US or Canada:</strong> +1 888 222 0906</p>
<p><strong>México:</strong> +52 1 998 252 2209 / +52 1 998 252 1915 8</p>
<p><strong>Office hours:</strong><br/>
8:30 am to 6:00 pm Monday to Friday <br/>
9:00 am to 5:00 pm Saturday<br/>
Closed on Sunday</p>
<p><strong>Only emergencies / Whatsapp:</strong> +52 1 998 128 8784</p>
