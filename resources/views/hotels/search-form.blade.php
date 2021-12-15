<div id="bookingbox">
	<ul class="">
	  <li  class="active"><a href="#hotels-tab" data-toggle='tab'><i class="fa fa-hotel"></i> Hotels</a></li>
	  <li ><a href="#tours-tab" data-toggle='tab'><i class="fa fa-map"></i> Tours</a></li>
	  <li ><a href="#transfer-tab" data-toggle='tab'><i class="fa fa-taxi"></i> Transfers</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade in active" id='hotels-tab'>
      		@include('search-forms.hotels')
		</div>
		<div class="tab-pane fade" id='tours-tab'>
      		@include('search-forms.tours')
		</div>
		<div class="tab-pane fade" id='transfer-tab'>
      		@include('search-forms.transfers')
		</div>
	</div>
</div>