@php
	use App\Components\ToursComponent;
@endphp
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Precios tour</h3>
</div>
<div class="modal-body">
	<form 
		action="{{url('admin/tours/affiliate-prices',[$tour->id])}}" 
		method="post" 
		class="validate-form ajax-submit" 
		data-reload-tables='true'
		data-reload-modal-on-success='true'
		>
		{{ csrf_field() }}
		<table class="table">
			<thead>
				<tr>
					<th rowspan="2" colspan="2">Variante</th>
					<th colspan="2">Costo base</th>
					<th colspan="2">Costo publico</th>
				</tr>
				<tr>
					<th>Costo adulto</th>
					<th>Costo Niño</th>
					<th>Costo adulto</th>
					<th>Costo Niño</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($tour->tourPrices as $tp)
				@php
					list($adultPrice,$childrenPrice) = ToursComponent::getTourPriceAmounts($tp,Auth::user()->affiliateId);
				@endphp
				<tr>
					<td colspan="2">{{$tp->name}}</td>
					<td><input type="text" class="form-control input-sm" value="${{number_format($adultPrice,2)}}" disabled></td>
					<td><input type="text" class="form-control input-sm" value="${{number_format($childrenPrice,2)}}" disabled></td>
					<td><input type="text" name="tourPricesAdults[{{$tp->id}}]" class="form-control input-sm" value="{{$adultPrice}}"></td>
					<td><input type="text" name="tourPricesChildren[{{$tp->id}}]" class="form-control input-sm" value="{{$childrenPrice}}"></td>
				</tr>
					@foreach ($tp->tourPriceTransportations as $tpt)
					@php
						list($adultPrice,$childrenPrice) = ToursComponent::getTourPriceTransportationAmounts($tpt,Auth::user()->affiliateId);
					@endphp
					<tr>
						<td></td>
						<td>from {{$tpt->destination->name}}</td>
						<td><input type="text" class="form-control input-sm" value="${{number_format($adultPrice,2)}}" disabled></td>
						<td><input type="text" class="form-control input-sm" value="${{number_format($childrenPrice,2)}}" disabled></td>
						<td><input type="text" name="tourPricesTransportAdults[{{$tpt->id}}]" class="form-control input-sm" value="{{$adultPrice}}"></td>
						<td><input type="text" name="tourPricesTransportChildren[{{$tpt->id}}]" class="form-control input-sm" value="{{$childrenPrice}}"></td>
					</tr>
					@endforeach
				@endforeach
			</tbody>
		</table>
		<div class="text-right">
			<button type="submit" class="btn btn-primary">Guardar</button>
		</div>
	</form>
</div>
