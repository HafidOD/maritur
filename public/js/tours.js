$(document).ready(function(){
	$(".check-prices").on('change',function(e){
		var article = $(this).closest('article');
		var adultPrice = article.data('adult'); 
		var childrenPrice = article.data('children'); 
		var transportation = parseInt($('.transportation-selector',article).val());
		if (transportation>0) {
			adultPrice += article.find('.transportation-selector option:selected').data('adult');
			childrenPrice += article.find('.transportation-selector option:selected').data('children');
		}
		$(".per-adult-price",article).text($.number(adultPrice,2));
		$(".per-children-price",article).text($.number(childrenPrice,2));
		// actualiza total
		var total = 0;
		total += parseInt($(".adults-selector",article).val()) * adultPrice; 
		total += parseInt($(".children-selector",article).val()) * childrenPrice; 
		$(".total",article).text($.number(total,2));
	});
	$(".add-tour").click(function(e){
		e.preventDefault();
		var $this = $(this);
		var article = $this.closest('article');
		var tp = article.data('tp');
		var adults = $('.adults-selector',article).val();
		var children = $('.children-selector',article).val();
		var transportation = $('.transportation-selector',article).val();
		$this.button('loading');
		$.post('/cart/addtour',{
			_token:$('meta[name="csrf-token"]').attr('content'),
			tp:tp,
			adults:adults,
			children:children,
			transportation:transportation,
		},function(r){
			window.location.href = '/cart'; 
		});
	});
	$(".remove-tour").click(function(e){
		e.preventDefault();
		$.post('/cart/removetour',{
			_token:$('meta[name="csrf-token"]').attr('content'),
			index:$(this).data('index')
		},function(e){
			location.reload();
		});
	});
});