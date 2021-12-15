@extends('layouts.modal')

@section('title','Edicion de usuario')

@section('body-content')
<form 
	action="{{url('admin/users',[$model->id])}}" 
	method="post" 
	class="validate-form ajax-submit" 
	data-close-modal-on-success='true' 
	data-reload-tables='true'>
	{{ csrf_field() }}
	{{ method_field('PUT') }}
	@include("admin.users.info",['model'=>$model])
	<div class="text-right">
		<button type="submit" class="btn btn-primary">Guardar</button>
	</div>
</form>
@endsection