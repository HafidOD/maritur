var stripe;
var currentFilterList;
$( document ).ready(function() {
	$('.flexslider').flexslider({
		controlNav:false,
	    animation: "slide",
	    itemWidth: 210,
	    itemMargin: 5,
	    minItems: 2,
	    maxItems: 4	
	});
	// var popupcookie = $.cookie("popup");
	// console.log(popupcookie);
	if (typeof(Storage) !== "undefined") {
		var aux = sessionStorage.getItem("popuphelp");
		if (aux!=="1") {
			setTimeout(function(){
				$.magnificPopup.open({
				  items: {
				    src: '#popupHelp', // can be a HTML string, jQuery object, or CSS selector
				    type: 'inline'
				  }
				});
				sessionStorage.setItem("popuphelp","1");
			},30000);		
		}
	} else {
	    // Sorry! No Web Storage support..
	}
	$('body').on('click', function (e) {
    //did not click a popover toggle or popover
    if ($(e.target).data('toggle') !== 'popover'
        && $(e.target).parents('.popover.in').length === 0) { 
        $('[data-toggle="popover"]').popover('hide');
    }
	});
	$(document.body).on('click',".click-focus",function(e){
		// e.preventDefault();
		$($(this).data('selector')).click();
	});
	$(document.body).on('click',".set-cur",function(e){
		e.preventDefault();
		$.get($(this).attr('href'),function(r){
			location.reload();			
		});
	});

	$(".filter-range input").bootstrapSlider({
		step:50,
		range:true,
		tooltip:'hide'
	});
	$(".reset-btn").click(function(){
		var form = $("#section-filters form");
		$(".filter-range input").bootstrapSlider('setValue',[0,10000]);
		$(".filter-keys").prop('checked',false).parent().removeClass('active');
		currentFilterList.filter();
	});
	$(".filter-range input").on('slide',function(slideEvt){
		$("#minOfRange").text(slideEvt.value[0]);
		$("#maxOfRange").text(slideEvt.value[1]);
	});
	$(".filter-range input").on('slideStop',function(slideEvt){
		applyFilters();
	});
	$(".filter-keys input").change(function(){
		applyFilters();
	});
	$('#slider').nivoSlider({
		controlNav:false,
		prevText: "<i class='fa fa-angle-left fa-4x'></i>",
		nextText: "<i class='fa fa-angle-right fa-4x'></i>",
	}); 

	$(".input-daterange").each(function(i,o){
		var field = $(o);
		var startField = $("[name='arrival']",$(field).closest('form'));
		var endField = $("[name='departure']",$(field).closest('form'));
		field.daterangepicker({
			autoApply: true,
			minDate:moment().add(2, 'days'),
			startDate: startField.val(),
			endDate: endField.val(),
			locale:{
				format:"Y-MM-DD"
			}
		},function(start,end,label){
			$(this.element).text(start.format('MMM D, Y') + ' - ' + end.format('MMM D, Y'));
			startField.val(start.format('Y-MM-DD'));
			endField.val(end.format('Y-MM-DD'));
		});

	});
	
	$('.input-datepicker').each(function(i,o){
		var container = $(o);
		var startField = $("[name='arrival']",container.closest('form'));
		var startDate = moment(startField.val());
		container.html(startDate.format('MMM D, Y'));
		container.daterangepicker({
			minDate:moment().add(2, 'days'),
			startDate: startDate,
			autoApply: true,
			singleDatePicker:true
		},function(start,end){
			container.html(start.format('MMM D, Y'));
			startField.val(start.format('YYYY-MM-DD'));
		});
	});
	$('.input-datepicker-field').each(function(i,o){
		var $this = $(o);
		var realField = $this.next();
		$this.daterangepicker({
			autoApply: true,
			singleDatePicker:true
		},function(start,end){
			$this.val(start.format('MM/DD/YYYY'));
			realField.val(start.format('YYYY-MM-DD'));
		});
	});

	$(document.body).on('click',".btn-loading",function(e){
		$(this).data('loading-text',"<i class='fa fa-refresh fa-spin'></i>");
		$(this).button('loading');
	});
	$(".changeCurrency").click(function(e){
		e.preventDefault();
	});
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();

	$("#clientInfo [name='paymentMethod']").change(function(e){
		$("#clientInfo [data-payment-method]").hide();
		$("#clientInfo [data-payment-method='"+$(this).val()+"']").show();
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
						window.location.href = r.redirect; 
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
	$(".remove-room").click(function(e){
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
		    var icons = {
		    	'destination':"<i class='fa fa-map-marker'></i>",
		    	'tour':"<i class='fa fa-map'></i>",
		    	'hotel':"<i class='fa fa-hotel'></i>",
		    	'houses':"<i class='fa fa-home'></i>",
		    };
		    var labels = {
		    	'destination':"Destinations",
		    	'tour':"Tours",
		    	'hotel':"Hotels",
		    	'houses':"Vacational Rentals",
		    };
		    $.each( items, function( index, item ) {
			    var li;
			    if ( item.category != currentCategory ) {
			        ul.append( "<li class='ui-autocomplete-category'>" + labels[item.category] + "</li>" );
			        currentCategory = item.category;
			    }
			    li = that._renderItemData( ul, item );
			    if ( item.category ) {
			        li.attr( "aria-label", item.category + " : " + item.label );
			        $('div',li).prepend(icons[item.category]+" ");
			    }
			});
		}
	});

    // Autocomplete Hotels
    $("form .destination-field").each(function(i,o){
		$(this).catcomplete({
			// delay: 0,
		    source: $(o).data('url'),
		    minLength: 3,
		    max:5,
		    select: function(event, ui) {
		    	$(this).closest('form').attr('action',ui.item.search_path);
		    	$(this).closest('form').find("input[name='searchPath']").val(ui.item.search_path);
		    }, 
			open: function(event, ui) {
	            $(".ui-autocomplete").css("z-index", 1000);
	        }
	    });    	
    });
    // hotelpaxes
   	$("#hotels-rooms").change(function(e){
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
			aux.find('.ages-container .row').children().remove();
			aux.find('.room-number span').text(i+1);
			// aux.find('h4 span').text(i+1);
			container.append(aux);
			aux.show();
		}
		checkFirstRoom();
	});

    $(document.body).on('change',"select[name='children[]']",function(e){
		var childrenSelected = $(this).val();
		var roomInfoDiv = $(this).closest('.room-pax');
		var agesContainer = roomInfoDiv.find('.ages-container .row');
		var currentAges = roomInfoDiv.find('.ages-container .row').children();
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
			aux.find('label span').text(i+1);
			aux.show();
			agesContainer.append(aux);
		}
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
	// hotelpaxes end
	$(".stickytop").stick_in_parent({parent:'#stickyparent'});
	$("[name='clientFirstName'], [name='clientLastName'], [name='primaryGuest']").change(function(){
		setPrimaryGuest();
	});
});
function applyFilters() {
	console.log('FILTERSS!');
	var filters = [];
	$(".filter-range").each(function(i,o){
		var obj = $(o);
		filters.push({
			name:obj.data('filter-name'),
			values: obj.find('input').bootstrapSlider('getValue'),
			type:'range'
		});
	});
	$(".filter-keys").each(function(i,o){
		var obj = $(o);
		var keys = [];
		obj.find('input:checked').each(function(i,o){
			keys.push(parseInt($(o).val()));
		});
		filters.push({
			name:obj.data('filter-name'),
			values: keys,
			type:'keys'
		});
	});
	currentFilterList.filter(function(item){
		var valid = true;
		for(var i=0;i<filters.length;i++){
			var filter = filters[i];
			if(filter.type=='range'){
				if(item.values()[filter.name] < filter.values[0] || item.values()[filter.name] > filter.values[1]){
					valid = false; 
					break;
				} 
			}else{
				// keys
				var itemKeys = $.parseJSON(item.values()[filter.name]); 
				for (var j=0;j<filter.values.length;j++) {
					if($.inArray(filter.values[j],itemKeys)==-1){
						valid = false;
						break;
					} 
				}
			}
		}
		return valid;
		return true;
	});
}
function setPrimaryGuest() {
	var clientName = $("[name='clientFirstName']").val()+' '+$("[name='clientLastName']").val();
	var primaryGuestField = $("[name='primaryGuest']:checked").parent().parent().find("input[type='text']").val(clientName);
}
function getDate( element ) {
  var date;
  try {
    date = $.datepicker.parseDate( 'mm/dd/yy', element.val() );
  } catch( error ) {
    date = null;
  }
  return date;
}
function showAlert(message) {
	var modal = $("#alertModal")
	modal.find('p').html(message);
	modal.modal('show');
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