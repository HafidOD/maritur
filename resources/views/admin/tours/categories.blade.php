<h2>Categorias</h2>
<table class="table table-condensed table-bordered">
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Seleccionar</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($tourCategories as $tc)
			<tr>
				<td>{{$tc->name}}</td>
				<td><input type="checkbox" {{$tour->categories->contains($tc)?'checked':''}} name="categories[]" value="{{$tc->id}}"></td>
			</tr>
		@endforeach
	</tbody>
</table>