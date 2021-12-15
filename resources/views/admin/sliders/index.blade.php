@extends('layouts.back')

@section('content')
<h2>Sliders</h2>
<hr>
<div class="row">
	@foreach ($sections as $key => $sectionData)
		<div class="col-md-3">
			<div class="well">
				<h3 class="text-center">{{$sectionData['name']}}</h3>
				@foreach ($sectionData['images'] as $up)
					<img src="{{$up->getFileUrl()}}">
				@endforeach
				<hr>
				<a href="/admin/sliders/add-images?section={{$key}}" class="btn btn-primary btn-block">EDITAR</a>
			</div>
		</div>
	@endforeach
</div>
@endsection