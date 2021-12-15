@php
	$actionField = isset($actionField)?$actionField:'#';
@endphp
<h1 class="text-center">DISCOVER THE BEST TOURS OF MEXICO</h1>
<div class="bookingbox">
	<form id="book_hotels" class="booking-form row" method="get" action="{{$actionField}}">
		<input type="hidden" name="search" value="1">
		<div class="col-xs-12 col-sm-10 col-md-10">
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-md-8">
					<label for="destination" class="label-control">Tours / Destination</label>
					<input type="text" name="destination" id="destination-tours" class="form-control" placeholder="Tour" value="" required/>
				</div>

				<div class="col-xs-6 col-sm-2 col-md-2" >
					<label class="label-control">Day</label>
					<input type="hidden" name="arrival" id="arrival2" class="form-control" value="" />
					<input type="text" id="arrival" class="form-control" readonly="readonly" value="" required />
				</div>

				<div id="adultsFake" class="col-xs-4 col-sm-1 col-md-1" style="">
					<label class="label-control">Adults</label>
					<select class="form-control"  required >
						@for ($i = 1; $i <=10; $i++)
							<option value="{{$i}}" >{{$i}}</option>
						@endfor
					</select>
				</div>
				<div id="childrenFake" class="col-xs-4 col-sm-1 col-md-1" style="">
					<label class="label-control">Children</label>
					<select class="form-control"  required >
						@for ($i = 0; $i <=10; $i++)
							<option value="{{$i}}" >{{$i}}</option>
						@endfor
					</select>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-2 col-md-2">
			<label for="searchHotel" class="label-control">&nbsp;</label>
			<input class="form-control btn btn-book" name="searchHotel" type="submit" value="Search" />
		</div>
	</form>
    <div id='children-age-example' class="col-xs-4 col-sm-2 col-md-1" style="display: none;">
    	<label class="label-control">Edad <span>1</span></label>
        <select name="" class="form-control">
			@for ($j = 0; $j <= 17; $j++)
				<option value="{{$j}}" >{{$j}}</option>
			@endfor
        </select>
    </div>
</div>