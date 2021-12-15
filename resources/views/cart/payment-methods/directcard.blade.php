<div class="row">
	<div class="col-xs-12 col-sm-6">
		<label class="label-control">Card holder name: <span class="required">*</span></label>
		<input type="text" name="cardHolder" class="form-control" required />
	</div>						
	<div class="col-xs-12 col-sm-6">
		<label class="label-control">Card number: <span class="required">*</span></label>
		<input type="text" name='cardNumber' class="form-control creditcard" required />
	</div>						
</div>
<div class="row">
	<div class="col-xs-6 col-sm-2">
		<label class="label-control">Month: <span class="required">*</span></label>
		<select name="cardMonth" class="form-control" required >
		@for ($i = 1; $i <= 12; $i++)
			<option value="{{$i}}">{{$i<10?'0':''}}{{$i}}</option>
		@endfor
		</select>
	</div>						
	<div class="col-xs-6 col-sm-2">
		<label class="label-control">Year: <span class="required">*</span></label>
		<select name="cardYear" class="form-control" required >
		@for ($i = date('Y'); $i <= date('Y')+30; $i++)
			<option value="{{$i}}">{{$i}}</option>
		@endfor
		</select>
	</div>						
	<div class="col-xs-6 col-sm-2">
		<label class="label-control">CVC: <span class="required">*</span></label>
		<input type="text" name='cardCvc' class="form-control" required />
	</div>						
	<div class="col-xs-6 col-sm-2">
		<label class="label-control">Postal Code: <span class="required">*</span></label>
		<input type="text" name='cardPostalCode' class="form-control" required />
	</div>						
</div>