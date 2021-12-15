var stripe;
$( document ).ready(function() {

	var arrivalField = $("#arrival");
	var departureField = $("#departure");
	var roomsField = $("#rooms");

	arrivalField.datepicker({
		altField:"#arrival2",
		altFormat:"yy-mm-dd",
	}).on('change',function(){
		var auxArrival = getDate($(this));
		auxArrival.setDate(auxArrival.getDate()+1);
		departureField.datepicker('option','minDate',auxArrival);	
		var auxDeparture = getDate(departureField);
		if(auxDeparture==null || auxArrival.getTime()>=auxDeparture.getTime()) departureField.datepicker('setDate',auxArrival);
	});
	departureField.datepicker({
		altField:"#departure2",
		altFormat:"yy-mm-dd",
	}).on('change',function(){
		var auxDeparture = getDate($(this));
		auxDeparture.setDate(auxDeparture.getDate()-1);
		var auxArrival = getDate(arrivalField);
		if(auxArrival==null || auxArrival.getTime()>=auxDeparture.getTime()) arrivalField.datepicker('setDate',auxDeparture);		
	});

	roomsField.change(function(e){
		var $this = $(this);
		var roomsSelected = $this.val();
		var container = $("#rooms-container");
		var currentRooms = $(".room-pax");
		// elimina si es necesario cuartos y datos
		for(var i=currentRooms.length-1;i+1>roomsSelected;i--){
			currentRooms.eq(i).remove();
		}
		// agrega faltantes
		var roomInfoExample = currentRooms.eq(0);
		for(var i=currentRooms.length;i<roomsSelected;i++){
			var aux = roomInfoExample.clone();
			aux.find("[name='adults[]']").val(2);
			aux.find("[name='children[]']").val(0);
			aux.find('.ages-container').children().remove();
			// aux.find('h4 span').text(i+1);
			container.append(aux);
			aux.show();
		}
		checkFirstRoom();
	});
	$(".btn-loading").click(function(e){
		$(this).data('loading-text',"<i class='fa fa-refresh fa-spin'></i>");
		$(this).button('loading');
	});
	$(".changeCurrency").click(function(e){
		e.preventDefault();
	});
	$("#rooms-container .room-pax").eq(0).find("[name='children[]']").change(function(e){
		$("#childrenFake select").val($(this).val());	
		checkFirstRoom();
	});
	$("#rooms-container .room-pax").eq(0).find("[name='adults[]']").change(function(e){
		$("#adultsFake select").val($(this).val());	
	});

	$("#adultsFake select").change(function(e){
		var firstRoom = $("#rooms-container .room-pax").eq(0);
		firstRoom.find("[name='adults[]']").val($(this).val());
	});
	$("#childrenFake select").change(function(e){
		var firstRoom = $("#rooms-container .room-pax").eq(0);
		firstRoom.find("[name='children[]']").val($(this).val());
		firstRoom.find("[name='children[]']").bind().change();
		checkFirstRoom();
	});
	$("[name='isPrimary']").change(function(e){
		$("[name='isPrimary']").each(function(i,o){
			var input = $(o).parent().parent().find('input');
			if ($(o).is(':checked')){
				input.val('');
				input.attr('readonly',true);
				input.attr('required',false);
			}
			else {
				input.attr('readonly',false);			
				input.attr('required',true);				
			} 
		});
	});
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();
	$(document.body).on('change',"select[name='children[]']",function(e){
		var childrenSelected = $(this).val();
		var roomInfoDiv = $(this).closest('.room-pax');
		var agesContainer = roomInfoDiv.find('.ages-container');
		var currentAges = roomInfoDiv.find('.ages-container').children();
		var roomInfoIndex = $('.room-pax').index(roomInfoDiv);
		// elimina si es necesario campos
		for(var i=currentAges.length-1;i+1>childrenSelected;i--){
			currentAges.eq(i).remove();
		}
		// agrega faltantes
		var childrenAgeExample = $("#children-age-example");
		for(var i=currentAges.length;i<childrenSelected;i++){
			var aux = childrenAgeExample.clone();
			aux.find('select').attr('name',"ages["+roomInfoIndex+"][]").val(0);
			aux.attr('id','');
			aux.find('p span').text(i+1);
			aux.show();
			agesContainer.append(aux);
		}
	});

	$("#book_hotels").validate({
		rules: {
		}
	});
	$("#clientInfo").submit(function(e){
		e.preventDefault();
		var form = $(this);
		var btn =  form.find('button');
		if (form.valid()) {
			btn.button('loading');
			form.ajaxSubmit({
				success:function(r){
					if (r.success){
						window.location.href = $r.redirect; 
					}else{
						btn.button('reset');
						showAlert(r.message);
					}
	            },
	            dataType: 'json',
	            error: function(r){
                    btn.button('reset');
	                showAlert('Error, por favor vuelva a intentarlo.');
	            }
			});
		}
	});
	$("#clientInfo").validate({
		rules: {
		    repeatEmail: {
		      equalTo: "#email"
		    }
		}
	});
	$("article .gg-delete").click(function(e){
		e.preventDefault();
		var $this = $(this);
		var rph = $this.data('rph');
		$.get('/cart/remove/'+rph,function(r){
			location.reload();
		});
	});
	$("a.add-room").click(function(e){
		e.preventDefault();
		var $this = $(this);
		var rph = $this.data('rph');
		$this.button('loading');
		if ($this.hasClass('add')) {
			$.post('/cart/addroom',{
				hotelCode : $this.data('hotelcode'),
				roomTypeId : $this.data('roomtypeid'),
				roomTypeName : $this.data('roomtypename'),
				ratePlanId : $this.data('rateplanid'),
				ratePlanName : $this.data('rateplanname'),
				subtotal : $this.data('subtotal'),
				total : $this.data('total'),
				currencyCode : $this.data("currencycode"),
				rph : rph,
				image : $this.data('image')
			},function(r){
				if (r.success) {
					$this.button('added');
					$this.removeClass('add').addClass('added');

					$("article[data-rph='"+rph+"']").hide();
					$this.closest('article').show();
				}else{
					$this.button('reset');
				}
			}).fail(function(){
				$this.button('reset');
			});
		}else{
			$.get('/cart/remove/'+rph,function(r){
				$this.button('reset');
				$this.text('Book');
				$this.removeClass('added').addClass('add');
				$("article[data-rph='"+rph+"']").show();
			});
		}
	});

    // Autocomplete con Categorias
    $.widget( "custom.catcomplete", $.ui.autocomplete, {
		_create: function() {
		   	this._super();
		    this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
		},
		_renderMenu: function( ul, items ) {
		    var that = this,
		    currentCategory = "";
		    $.each( items, function( index, item ) {
			    var li;
			    if ( item.category != currentCategory ) {
			        ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
			        currentCategory = item.category;
			    }
			    li = that._renderItemData( ul, item );
			    if ( item.category ) {
			        li.attr( "aria-label", item.category + " : " + item.label );
			    }
			});
		}
	});

    // Autocomplete Hotels
	$("#destination").catcomplete({
		// delay: 0,
	    source: "/hotels/autocomplete",
	    minLength: 3,
	    max:5,
	    select: function(event, ui) {
	    	$(this).closest('form').attr('action',ui.item.search_path);
	    }, 
		open: function(event, ui) {
            $(".ui-autocomplete").css("z-index", 1000);
        }
    });
	$("#destination-tours").catcomplete({
		// delay: 0,
	    source: "/tours/autocomplete",
	    minLength: 3,
	    max:5,
	    select: function(event, ui) {
	    	$(this).closest('form').attr('action',ui.item.search_path);
	    }, 
		open: function(event, ui) {
            $(".ui-autocomplete").css("z-index", 1000);
        }
    });
});
function getDate( element ) {
  var date;
  try {
    date = $.datepicker.parseDate( 'mm/dd/yy', element.val() );
  } catch( error ) {
    date = null;
  }
  return date;
}
function checkFirstRoom() {
	var rooms = $("#rooms-container .room-pax");
	var firstRoom = $("#rooms-container .room-pax").eq(0);
	if (rooms.length>1 || firstRoom.find("[name='children[]']").val()>0) {
		$("#adultsFake,#childrenFake").hide();
		firstRoom.show();
	}else{
		$("#adultsFake,#childrenFake").show();
		firstRoom.hide();
	}
}
function showAlert(message) {
	var modal = $("#alertModal")
	modal.find('p').html(message);
	modal.modal('show');
}
