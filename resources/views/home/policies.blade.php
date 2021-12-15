@extends('layouts.front')

@section('content')
<section class="main">
<div class="container">
	<h2>Terms</h2>
	<hr>
	{!!SettingsComponent::get('termsText')!!}
</div>	
</section>
@endsection