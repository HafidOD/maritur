@php
    $settings = SettingsComponent::getSettings();
@endphp
@extends('layouts.front')


@section('content')
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	  <div class="carousel slide carousel-fade" data-ride="carousel">
	      <div class="carousel-inner" role="listbox">
	          <div class="item active ">
	              <div class="fill" style="background-image:url(/images/slider/home1.jpg);"></div>
	          </div>
	      </div>
	  </div>
	  <div id="slidebook" class="book-container">
	    <section class="container">
			<h1 class="text-center">Contact us</h1>
	    </section>
	  </div>

	<section id="main" class="frame-shadow">
	  <header class="page-header">
	    <div class="container">
	      <h1 class="title">Contact Us</h1>
	    </div>	
	  </header>
	  <div class="container">
	    <div class="row">
	      <div class="content col-sm-12 col-md-12">
			<div class="row">
			  <div class="col-sm-8 col-md-8 contact-info bottom-padding">
				<address>
				  <div class="title">Address</div>
				  {!! $settings['address'] !!}
				</address>
				<div class="row">
				  <address class="col-sm-5 col-md-5">
					<div class="title">Phones</div>
					<div>Reservations: {{$settings['contactPhone']}}</div>
					{{-- <div>Contact Us: +52 998 253 61 51</div> --}}
				  </address>
				  <address class="col-sm-7 col-md-7">
					<div class="title">Email Addresses</div>
					<div>Reservations: <a href="mailto:{{$settings['emailNotifications']}}">{{$settings['emailNotifications']}}</a></div>
					{{-- <div>Contact Us: <a href="mailto:contact@goguytravel.com">contact@goguytravel.com</a></div> --}}
				  </address>
				</div>
				<hr>
			  </div>
			  <div class="col-sm-4 col-md-4 bottom-padding">
				<form id="contactform" class="form-box register-form contact-form" action="/contact" method="POST">
					{{ csrf_field() }}
				  <h3 class="title">Quick Contact</h3>
				  <div id="success">
				  @if (isset($success) && $success==true)
				  	<p><strong>Message sent succesfully</strong></p>
				  @elseif(isset($success) && !$success)
				  	<p><strong>Sending message error, try later.</strong></p>
				  @endif
				  </div>
				  @if (!isset($success) || (isset($success) && !$success))
					  <label>Name: <span class="required">*</span></label>
					  <input class="form-control" type="text" name="name" id="name" required>
					  <label>Email Address: <span class="required">*</span></label>
					  <input class="form-control" type="email" name="email" id="email" required>
					  <label>Phone:</label>
					  <input class="form-control" type="text" name="phone" id="phone">
					  <label>Message:</label>
					  <textarea class="form-control" name="message" id="msgs"></textarea>
					  <div class="clearfix"></div>
					  <div class="g-recaptcha" data-sitekey="6LfwAZAUAAAAABFwr3HS_PAgNzJzZIIDQEWo-0ZV"></div>
					  <br>
					  <br>
					  <div class="clearfix"></div>
					  <div class="buttons-box clearfix">
						<input type="submit" class="btn btn-primary" value="Submit" />
						<span class="required"><b>*</b> Required Field</span>
					  </div><!-- .buttons-box -->
				  @endif
				</form>
			  </div>
			</div>
	      </div>
	    </div>
	  </div><!-- .container -->
	</section><!-- #main -->    
@endsection
