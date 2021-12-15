<div id='forms-container'>
	<ul class="">
	  <li ><a href="/transfers"><i class="fa fa-taxi"></i> Transfers</a></li>
	  <!-- <li  class="active"><a href="#hotels-tab" data-toggle='tab'><i class="fa fa-hotel"></i> Hotels</a></li> -->
	  <li ><a href="/tours"><i class="fa fa-map"></i> Tours</a></li>
	  <!-- <li ><a href="/offers"><i class="fa fa-info"></i> Ofertas</a></li> -->
	  {{-- <li ><a href="#houses-tab" data-toggle='tab'><i class="fa fa-home"></i> Vacational Rentals</a></li> --}}
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade in active" id='hotels-tab'>
		    @include('search-forms.transfers')
      		<!-- @include('search-forms.hotels') -->
		</div>
		<div class="tab-pane fade" id='tours-tab'>
      		@include('search-forms.tours')
		</div>
		<!-- <div class="tab-pane fade" id='transfer-tab'>
      		@include('search-forms.transfers')
		</div> -->
		<div class="tab-pane fade" id='houses-tab'>
      		@include('search-forms.houses')
		</div>
	</div>	
</div>