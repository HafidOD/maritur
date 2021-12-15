<form 
    action="{{url('admin/settings/update')}}" 
    method="post" 
    class="validate-form ajax-submit" 
>
{{ csrf_field() }}
	<div class="row">
		<div class="col-md-6">
			<h4>Stripe</h4>
			<div class="form-group">
				<label>
					<input type="checkbox" name="settings[paymentMethods][]" value="stripe" {{in_array('stripe',$settings['paymentMethods'])?'checked':''}}> Recibir pagos con Stripe
				</label>
			</div>
			<div class="form-group">
				<label>Llave de produccion (Api Key Live)</label>
				<input type="text" class="form-control" name="settings[stripeApiKeyLive]" value="{{$settings['stripeApiKeyLive']}}">				
			</div>
			<div class="form-group">
				<label>Llave de prueba (Api Key Sandbox)</label>
				<input type="text" class="form-control" name="settings[stripeApiKeySandbox]" value="{{$settings['stripeApiKeySandbox']}}">				
			</div>
		</div>
		<div class="col-md-6">
			<h4>Paypal</h4>
			<div class="form-group">
				<label>
					<input type="checkbox" name="settings[paymentMethods][]" value="paypal" {{in_array('paypal',$settings['paymentMethods'])?'checked':''}}> Recibir pagos con PayPal
				</label>
			</div>
			<div class="form-group">
				<label>Cuenta de Paypal</label>
				<input type='text' class='form-control' name='settings[paypalAccount]' value='<?= $settings["paypalAccount"] ?>'>
			</div>
			<div class="form-group">
				<label>Usuario</label>
				<input type='text' class='form-control' name='settings[paypalUser]' value='<?= $settings["paypalUser"] ?>'>
			</div>
			<div class="form-group">
				<label>Contraseña</label>
				<input type='text' class='form-control' name='settings[paypalPass]' value='<?= $settings["paypalPass"] ?>'>
			</div>
			<div class="form-group">
				<label>Firma</label>
				<input type='text' class='form-control' name='settings[paypalSign]' value='<?= $settings["paypalSign"] ?>'>
			</div>
		</div>
	</div>
	<hr>
    <button class="btn btn-primary" type="submit">Guardar</button>
</form>