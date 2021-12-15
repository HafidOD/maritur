@extends('layouts.front')

@section('content')

	<section class="gg-intback">
  	<div class="container">

  	<div class="gg-chk-steps row">
    	<div class="col-xs-4"><div class="chevron">1. Reservation</div></div>
    	<div class="col-xs-4"><div class="chevron active">2. Information</div></div>
    	<div class="col-xs-4"><div class="chevron">3. Payment</div></div>
  	</div>

	<div class="row">
		<form id="clientInfo" class="row no-margin" method="post" action="/cart/checkout">
			{{ csrf_field() }}
			<article class="content col-xs-12 col-sm-8 col-md-9">
				<div class="information-content">
					<div class="information-title">Personal Information</div>
					<div class="information-form">
						<div class="col-xs-12 col-sm-6 col-md-3">
							<label class="label-control">First Name: <span class="required">*</span></label>
							<input type="text" name="clientFirstName" id="first_name" class="form-control" placeholder="First Name" required />
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-3">
							<label class="label-control">Last Name: <span class="required">*</span></label>
							<input type="text" name="clientLastName" id="last_name" class="form-control" placeholder="Last Name"  required />	
						</div>

						<div class="col-xs-12 col-sm-12 col-md-6">
							<label class="label-control">Address: <span class="required">*</span></label>
							<input type="text" class="form-control" placeholder="Address" name="clientAddress" id="Direccion_Cliente" required />
						</div>

						<div class="col-xs-12 col-sm-3 col-md-3">
							<label class="label-control">Country <span class="required">*</span></label>
							<select name="clientCountryId" id="countryId" class="form-control" required >
							@foreach ($countries as $c)
								<option {{$c->code==130?'selected':''}} value="{{$c->id}}">{{$c->name}}</option>
							@endforeach
							</select>
						</div>

						<div class="col-xs-12 col-sm-3 col-md-3">
							<label class="label-control">State: <span class="required">*</span></label>
							<input type="text" class="form-control" placeholder="State" name="clientState" id="State_Cliente"  required>
						</div>

						<div class="col-xs-12 col-sm-3 col-md-3">
							<label class="label-control">City: <span class="required">*</span></label>
							<input type="text" class="form-control" placeholder="City" name="clientCity" id="Ciudad_Cliente" required>
						</div>

						<div class="col-xs-12 col-sm-3 col-md-3">
							<label class="label-control">Zip Code: <span class="required">*</span></label>
							<input type="text" class="form-control" placeholder="Zip Code" name="clientZipcode" id="Codigo_Postal_Cliente" required>
						</div>
							
						<div class="col-xs-12 col-sm-4 col-md-4">
							<label class="label-control">Phone: <span class="required">*</span></label>
							<input type="text" name="clientPhone" id="phone" class="form-control" placeholder="Phone" required >
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<label class="label-control">Email Address: <span class="required">*</span></label>
							<input type="email" name="clientEmail" id="email" class="form-control email" placeholder="Email Address" required >
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<label class="label-control">Repeat Email: <span class="required">*</span></label>
							<input type="email" name="repeatEmail" id="repeatEmail" class="form-control email" placeholder="Repeat Email Address" required >
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<label class="label-control">Special Requirements:</label>
							<textarea name="specialRequests" id="requirements" class="form-control" placeholder="Special Requirements"></textarea>
						</div>
					</div>
				</div>
				<div class="information-content">
					<div class="information-title">Guests names</div>
					<p class="text-danger">Mark the person whose data are mentioned above</p>
					<div class="information-form">
						@foreach ($roomsSelection->rooms as $i => $room)
							<div class="col-xs-12 col-sm-6 col-md-4">
								<h4>Room <br><small>{{$room->roomTypeName}}/{{$room->ratePlanName}}</small></h4>
								@for ($j = 0; $j < $room->adults; $j++)
								<div>
									<label class="label-control">Adult {{$j+1}}</label>
									<div class="input-group">
										<input type="text" name="guestNames[{{$room->rph}}][]" {{$i==0 && $j==0?'readonly':'required'}} class="form-control" >
										<span class="input-group-addon" data-toggle='tooltip' title="This person is the holder"><input type="radio" {{$i==0 && $j==0?'checked':''}} name="primaryGuest" value="{{$room->rph}}.{{$j}}"></span>
									</div>
								</div>
								@endfor
								@for ($j = 0; $j < $room->children; $j++)
								<div>
									<label class="label-control">Child {{$j+1}} (age {{$room->ages[$j]}})</label>
									<input type="text" name="guestNames[{{$room->rph}}][]" class="form-control"  required>									
								</div>
								@endfor
							</div>
						@endforeach
					</div>
				</div>
				<div class="information-content">
					<div class="information-title">Payment info</div>
					<div class="information-form">
					<div id="stripe-card"></div>
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<label class="label-control">Card holder name: <span class="required">*</span></label>
							<input type="text" name="cardHolder" class="form-control" required />
						</div>						
						<div class="col-xs-12 col-sm-6">
							<label class="label-control">Card number: <span class="required">*</span></label>
							<input type="text" name='cardNumber' class="form-control creditcard" required />
						</div>						
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-2">
							<label class="label-control">Month: <span class="required">*</span></label>
							<select name="cardMonth" id="countryId" class="form-control" required >
							@for ($i = 1; $i <= 12; $i++)
								<option value="{{$i}}">{{$i<10?'0':''}}{{$i}}</option>
							@endfor
							</select>
						</div>						
						<div class="col-xs-6 col-sm-2">
							<label class="label-control">Year: <span class="required">*</span></label>
							<select name="cardYear" id="countryId" class="form-control" required >
							@for ($i = date('Y'); $i <= date('Y')+30; $i++)
								<option value="{{$i}}">{{$i}}</option>
							@endfor
							</select>
						</div>						
						<div class="col-xs-6 col-sm-2">
							<label class="label-control">CVC: <span class="required">*</span></label>
							<input type="text" name='cardCvc' class="form-control" required />
						</div>						
						<div class="col-xs-6 col-sm-2">
							<label class="label-control">Postal Code: <span class="required">*</span></label>
							<input type="text" name='cardPostalCode' class="form-control" required />
						</div>						
					</div>				
					</div>
				</div>


			</article>
			@php
				$lastStep = true;
			@endphp
      		@include('cart.summary')
			</form>
		</div>
	</div>
</section>
@endsection

