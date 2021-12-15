@extends('layouts.modal')

@section('title','Nuevo afiliado')

@section('body-content')
<form 
	action="{{url('admin/affiliates')}}" 
	method="post" 
	class="validate-form ajax-submit" 
	data-close-modal-on-success='true' 
	data-reload-tables='true'>
	{{ csrf_field() }}
	@include("admin.affiliates.info",['model'=>$model])
	<div class="text-right">
		<button type="submit" class="btn btn-primary">Guardar</button>
	</div>
</form>
@endsection