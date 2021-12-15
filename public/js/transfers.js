$(document).ready(function(){
	$(".add-transfer").click(function(e){
		e.preventDefault();
		var $this = $(this);
		var ts = $this.data('ts');
		var pax = $this.data('pax');
		var arrival = $this.data('arrival');
		var triptype = $this.data('triptype');
		var destination = $this.data('destination');
		$this.button('loading');
		$.post('/cart/addtransfer',{
			_token:$('meta[name="csrf-token"]').attr('content'),
			ts:ts,
			pax:pax,
			triptype:triptype,
			arrival:arrival,
			destination:destination
		},function(r){
			window.location.href = '/cart'; 
		});
	});
	$(".remove-transfer").click(function(e){
		e.preventDefault();
		$.post('/cart/removetransfer',{
			_token:$('meta[name="csrf-token"]').attr('content'),
			index:$(this).data('index')
		},function(e){
			location.reload();
		});
	});
});