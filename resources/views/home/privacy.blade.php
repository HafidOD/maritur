@extends('layouts.front')

@section('content')
<section class="main">
<div class="container">
	<h2>Privacy Policy</h2>
	<hr>
	{!!SettingsComponent::get('privacyText')!!}
</div>	
</section>
@endsection