<!-- colores afiliado -->
<?php 
	$c1 = SettingsComponent::get('color1');
	$c2 = SettingsComponent::get('color2');
	$c3 = SettingsComponent::get('color3');
	$c4 = SettingsComponent::get('color4');
 ?>
<style type="text/css">
	#gg-header .navbar-default .navbar-nav>li>a,
	#gg-header .navbar-default .navbar-nav>li>a:focus, 
	#gg-header .navbar-default .navbar-nav>li>a:hover{
		color: {{$c2}};
	}
	body > header{
		border-color: {{$c1}}
	}
	#forms-container,#bookingbox form,#bookingbox.with-back{
		background: {{$c1}}
	}
	#bookingbox ul li.active a{
		color: {{$c2}}
	}
	#bookingbox form button[type='submit']{
		background: {{$c3}};
		border-color: {{$c3}};
		color: {{$c4}};
	}
	.topitems article .desc .title{
		color: {{$c2}}
	}
	.topitems article .image span{
		background: {{$c1}}
	}
	.topitems article .desc p i{
		color: {{$c2}}
	}
	.topitems article .desc .price strong{
		color: {{$c2}}
	}
	.topitems article .desc a{
		background: {{$c1}}
	}
	#home h3,#home .destinations h4{
		color: {{$c2}}
	}
	#home #seemore, #section-filters .reset-btn,.white-popup,.gg-btn-book{
		background: {{$c3}} !important;
		color: {{$c4}} !important
	}
/*	footer{
		background-color: {{$c3}};
	}
*/	footer p{
		color: {{$c2}}
	}
	footer ul li a{
		color: {{$c2}}
	}
	#mc-embedded-subscribe{
		background: {{$c1}}
	}
	a{
		color: {{$c2}}
	}
	.gg-datasearch h1{
		color: {{$c2}};
	}
	.gg-item-name, .gg-item-name:hover, .gg-item-name:active, .gg-item-name:visited{
		color: {{$c2}}
	}
	#section-filters .slider .slider-selection,#hotel-stars-filter .btn,#detail-container .title,.chevron.active{
		background: {{$c1}}
	}
	.chevron.active:before{
		border-color: transparent transparent transparent {{$c1}}
	}
	.chevron.active:after{
		border-color: {{$c1}} transparent
	}
	.overview-title,.overview-item{
		color: {{$c1}}
	}
</style>