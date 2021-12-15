<form 
    action="{{url('admin/settings/update')}}" 
    method="post" 
    class="validate-form ajax-submit" 
    enctype='multipart/form-data'
>
{{ csrf_field() }}
	<div class="form-group">
		<label>Dias previos para no crear reservas en omnibees</label>
	</div>
</form>