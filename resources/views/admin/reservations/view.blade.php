<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>#{{$res->id}} {{$res::$statusLabels[$res->status]}} <a target="_blank" class="btn btn-warning btn-xs" href="{{url('confirmation',[$res->token])}}"><i class="fa fa-file"></i></a></h3>
</div>
<div class="modal-body">
	<h3>Datos del cliente</h3>
	<div class="row">
		<div class="col-sm-3">
			<strong>Nombre</strong>
			<p>{{$res->clientFirstName}}</p>
		</div>
		<div class="col-sm-3">
			<strong>Apellidos</strong>
			<p>{{$res->clientLastName}}</p>
		</div>
		<div class="col-sm-3">
			<strong>Email</strong>
			<p>{{$res->clientEmail}}</p>
		</div>
		<div class="col-sm-3">
			<strong>Telefono</strong>
			<p>{{$res->clientPhone}}</p>
		</div>
		<div class="col-sm-3">
			<strong>Ciudad</strong>
			<p>{{$res->clientCity}}</p>
		</div>
		<div class="col-sm-3">
			<strong>Estado</strong>
			<p>{{$res->clientState}}</p>
		</div>
		<div class="col-sm-3">
			<strong>Pais</strong>
			<p>{{$res->country->name}}</p>
		</div>
		<div class="col-sm-3">
			<strong>Hotel</strong>
			<p>{{$res->hotelname?$res->hotelname:'N/A'}}</p>
		</div>
	</div>
	@if ($res->hotelReservation)
		<h3> Habitaciones  <?= !$res->hotelReservation->isPushed()?'(No empujadas a OMNIBEES)':'' ?>
			<?php if ($res->hotelReservation->isPushed()): ?>
				<a href="{{url('admin/reservations/cancel',['id'=>$res->hotelReservation->id])}}" class="btn btn-danger btn-sm show-warning pull-right">Cancelar Reservación</a>
			<?php endif ?>
		</h3>
		<hr>
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Tipo</th>
					<th>Tarifa</th>
					<th>Total</th>
					<th>Estado</th>
					<th>Cancelar</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($res->hotelReservation->rooms as $i => $room)
				<tr>
					<td>{{$i}}</td>
					<td>{{$room->refRoomTypeName}}</td>
					<td>{{$room->refRatePlanName}}</td>
					<td>${{number_format($room->total,2)}} {{$res->currencyCode}}</td>
					<td>{{$room::$statusLabels[$room->status]}}</td>
					<td>
					@if ($room->status!=$room::STATUS_CANCELLED && $res->hotelReservation->isPushed())
						<a href="{{url('admin/reservations/cancel-room',['id'=>$room->id])}}" class="btn btn-danger ajax-link btn-xs show-warning">Cancelar hab.</a>
					@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	@endif
	@if ($res->toursReservations()->count())
	<h3>Tours reservados</h3>
		<table class="table">
			<thead >
				<tr>
					<th>#</th>
					<th>Nombre</th>
					<th>Día</th>
					<th>Adultos</th>
					<th>Niños</th>
					<th>Transportación</th>
					<th>Subtotal</th>
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
						<td>${{number_format($t->subtotal,2)}} {{$res->currencyCode}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
	@if ($res->transportReservations()->count())
		<h3>Transportacion</h3>
		@foreach ($res->transportReservations as $transport)
			<div class="row">
				<div class="col-sm-3">
					<strong>Destino</strong>
					<p>{{$transport->destName()}}</p>
				</div>
				<div class="col-sm-3">
					<strong>Pasajeros</strong>
					<p>{{$transport->pax}}</p>
				</div>
				<div class="col-sm-3">
					<strong>Tipo de servicio</strong>
					<p>{{$transport->transportServiceType->name}}</p>
				</div>
				<div class="col-sm-3">
					<strong>Tipo de viaje</strong>
					<p>{{$transport::$triptypeLabels[$transport->triptype]}}</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-3">
					<strong>Fecha de Llegada</strong>
					<p>{{$transport->arrivalDatetime->format('m/d/Y')}}</p>
				</div>
				<div class="col-sm-3">
					<strong>Hora de llegada</strong>
					<p>{{$transport->arrivalDatetime->format('H:i')}} hrs</p>
				</div>
				<div class="col-sm-3">
					<strong>Vuelo de llegada</strong>
					<p>{{$transport->arrivalFlight}}</p>
				</div>
			</div>
			@if ($transport->triptype==2)
				<div class="row">
					<div class="col-sm-3">
						<strong>Fecha de salida</strong>
						<p>{{$transport->departureDatetime->format('m/d/Y')}}</p>
					</div>
					<div class="col-sm-3">
						<strong>Hora de salida</strong>
						<p>{{$transport->departureDatetime->format('H:i')}} hrs</p>
					</div>
					<div class="col-sm-3">
						<strong>Vuelo de salida</strong>
						<p>{{$transport->departureFlight}}</p>
					</div>
				</div>
			@endif
		@endforeach
		<div class="row">
			<div class="col-sm-3">
				<strong>Costo transportacion</strong>
				<h4><strong>${{number_format($transport->subtotal,2)}} <?= $res->currencyCode ?></strong></h4>
			</div>
		</div>
	@endif
	<h4 style="text-align: right;">
	 Subtotal: <strong><?= number_format($res->subtotal,2) ?> <?= $res->currencyCode ?></strong><br>
	 Impuestos: <strong><?= number_format($res->total-$res->subtotal,2) ?> <?= $res->currencyCode ?></strong><br>
	 Total: <strong><?= number_format($res->total,2) ?> <?= $res->currencyCode ?></strong> 		
 	</h4>
</div>
<div class="modal-footer text-left">
	<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
</div>