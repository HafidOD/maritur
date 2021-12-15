@extends('layouts.modal')

@section('title','Edicion de destino')

@section('body-content')
<form 
	action="{{url('admin/destinations/info',[$dest->id])}}" 
	method="post" 
	class="validate-form ajax-submit" 
	data-reload-tables='true'
	>
	{{ csrf_field() }}
	<h3>{{$dest->name}}</h3>
	<div class="form-group">
		<label>Descripción (Ingles)</label>
		<textarea name='description' class="form-control" rows="8">{{$desc}}</textarea>
	</div>
	<div class="text-right">
		<button type="submit" class="btn btn-primary">Guardar</button>
	</div>
</form>
@endsection