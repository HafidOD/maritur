// var languageTable={
// 	search:"Buscar: ",
// 	infoEmpty: 'Mostrando 0 a 0 de 0 Registros',
// 	info : 'Mostrando _START_ a _END_ de _TOTAL_ Registros',
// 	infoFiltered: '(Filtrado de _MAX_ registros)',
// 	lengthMenu: '_MENU_ Registros por pagina',
// 	zeroRecords: 'No existen Registros',
// 	paginate: {
//             first:      '<span aria-hidden="true">&laquo;</span>',
//             previous:   '<span aria-hidden="true">&laquo;</span>',
//             next:       '<span aria-hidden="true">&raquo;</span>',
//             last:       '<span aria-hidden="true">&raquo;</span>'
//         },
//     processing: '<i class="fa fa-refresh fa-spin fa-2x"></i>'
// }
$(document).ready(function(){
	// alertify.logPosition('top right');
	// alertify.maxLogItems(1);

	$.fn.modalmanager.defaults['spinner'] = "<div class='loading-spinner fade in text-center' style='width: 200px; margin-left: -100px;color:#fff'><i class='fa fa-refresh fa-spin fa-4x'></i></div>";
	$.fn.modal.defaults['spinner'] = "<div class='loading-spinner fade in text-center' style='width: 200px; margin-left: -100px;'><i class='fa fa-refresh fa-spin fa-4x'></i></div>";
	// Evento que agrega un modal dinamico con contenido por Ajax
	$(document.body).on('click',"[data-toggle='modal-dinamic']",function(e){
		e.preventDefault();
		var obj = $(this);
		var backdrop = obj.data('modal-backdrop');
		var width = obj.data('modal-width');
		var keyboard = obj.data('modal-keyboard');
		var path = obj.attr('href')?obj.attr('href'):obj.data('url');
		generateModal(obj,path,width,backdrop,keyboard);
	});

	 //Evento que se activa al seleccionar un archivo a cargar
	$(document).on('change', '.btn-file :file', function() {
    	var input = $(this),
    	numFiles = input.get(0).files ? input.get(0).files.length : 1,
    	label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    	input.trigger('fileselect', [numFiles, label]);
    });

	/*Buscar con el search*/
	$(document.body).on('change','.imgField',function(){
		var $this = $(this);
		var container = $($this.data('container'));
		if(this.files && this.files.length>0){
			var aux = 0;
	  		for(var f in this.files){
				var reader = new FileReader();
				reader.onload = function (e) {
					var imgDiv = $("<div><img src='"+e.target.result+"'></div>");
					if ($this.attr('multiple')) container.append(imgDiv);
					else container.html(imgDiv);
		  		};
		  		reader.readAsDataURL(this.files[f]);
		  		if(aux>this.files.length-2) break;
	  			aux++;
	  		}
		}
	});
	$(document.body).on('hide.bs.modal',".modal-ajax",function(e){
		var m = $(this);
		m.on('hidden.bs.modal',function(e){
			setTimeout(function(){m.remove();},310);	
		});
	});
	// El select con esta clase hace una peticion por POST enviando atributo "value" de la opcion seleccionada
	// y lo que recibe lo pega dentro del contenedor con el que concuerde el data-containerload
	$(document.body).on('change','select.load-content',function(){
		var obj = $(this);
		var url = obj.data('url');
		var container = $(obj.data('container'));
		if(container.length>0 && url!='' && obj.val()!=''){
			container.html("<div class='text-center'><i class='fa fa-refresh fa-spin fa-4x'></i></div>");
			container.load(url,{id:obj.val()},function(response,status,xhr){
				applyToNewContent(container);
			});
		}
	});
	// El boton o link con esta clase, hace una peticion Ajax a la url especificada. "href" del "<a>" y "data-url" del "<button>".
	// el contenido devuelto lo pega en el contenedor con el selector especificado en "data-container"
	// Al dar click el boton cambia a su estado "loading" y al final regresa al estado original
	$(document.body).on('click','a.load-content,button.load-content',function(e){
		e.preventDefault();
		var obj = $(this);
		obj.button('loading');
		var container =$(obj.data('container')); 
		container.html("<i class='fa fa-refresh fa-spin fa-4x'></i>");
		container.load(obj.attr('href')?obj.attr('href'):obj.data('url'),function(r){
			obj.button('reset');
		});
	});

	// hace lo mismo que el anterior pero este agrega el contenido al contenedor
	$(document.body).on('click','a.append-content,button.append-content',function(e){
		e.preventDefault();
		var obj = $(this);
		obj.button('loading');
		$.get(obj.attr('href')?obj.attr('href'):obj.data('url'),function(r){
			$(obj.data('container')).append(r);	
		});
	});

	$(document.body).on('submit','form.load-content',function(e){
		e.preventDefault();
		var form = $(this);
		var btn = form.find("[type='submit']");
		btn.button('loading');
		form.ajaxSubmit({
			success:function(r){
				btn.button('reset');
				$(form.data('container')).html(r);
				applyToNewContent($(form.data('container')));
			},
			error:function(status){
				btn.button('reset');
				showAlert(status.statusText,'error');
			}
		});
	});
	// el boton con esa clase, agregara lo que contenta en el "data-element" en un contenedor con el selector
	//  escrito en "data-selector"
	$(document.body).on('click','.copy-append-element',function(e){
		e.preventDefault();
		var obj = $(this);
		var container = $(obj.data('container'));
		var element = container.children().eq(0);
		var newElement = element.clone();
		newElement.find('.remove-parent').show();
		container.append(newElement);
		applyToNewContent(newElement)
	});
	$(document.body).on('click','.remove-parent',function(e){
		var obj = $(this);
		var parentNumber = parseInt(obj.data('parent'));
		var parent = obj.parent();
		for (var i = 1; i < parentNumber; i++) {
			parent = parent.parent();
		}
		parent.remove();
	});

	//El checkbox con esta clase, cambia el estado de un "<input> o <select>"
	//aplica un "disabled" si esta activo, si no, lo quita. 
	$(document.body).on('change',".enable-field",function(e){
		$($(this).data('selector')).prop('disabled',$(this).prop('checked')?false:'disabled');
	});

	//El checkbox con esta clase, cambia el estado de un "<input> o <select>"
	//aplica un "readonly" si esta activo, si no, lo quita. 
	$(document.body).on('change',".readonly-field",function(e){
		$($(this).data('selector')).prop('readonly',$(this).prop('checked')?false:'readonly');
	});

	// El checkbox con esta clase, activa otros checkbox segun el selector en "data-selector"
	// 
	$(document.body).on('change',".check-field",function(e){
		$($(this).data('selector')).prop('checked',$(this).prop('checked')?'checked':false);

	});

	// El checkbox con esta clase, abrira un contenedor al estar activado. usa .collapse de bootstrap
	$(document.body).on('change',".check-collapse",function(e){
		$($(this).data('container')).collapse($(this).prop('checked')?'show':'hide');
	});

	// El link o boton con esta clase, ejecuta un evento focus en el objeto con el selector definido en "data-selector"
	// Funciona para mostrar calendarios en inputs con datepicker
	$(document.body).on('click',".click-focus",function(e){
		e.preventDefault();
		$($(this).data('selector')).focus();
	});
	$(document.body).on('click',"form.ajax-submit button[type='submit']",function(e){
		$(this).closest('form').data('submit-button',$(this));
	});
	$(document.body).on('submit','form.ajax-submit',function(e){
		e.preventDefault();
		form = $(this);
		btn = $(this).find("button[type='submit']");
		var extraData={};
		if(!form.hasClass('validate-form') || (form.hasClass('validate-form') && form.valid())){
			if(form.data('submit-button')) extraData[form.data('submit-button').attr('name')]= form.data('submit-button').attr('value');
			btn.button('loading');
			form.ajaxSubmit({
				data:extraData,
				success:function(r,status){
					var errors = r.errors || {}
					btn.button('reset');
					showAlert(r.message?r.message:(r.success?'Hecho!':'Error'),r.success?'success':'error');
					// setFormErrors(form,errors);
					// console.log(form.data('reloadtables'));
					if(form.data('reload-tables') && r.success) reloadAjaxTables();
					if(form.data('close-modal-on-success') && r.success) closeCurrentModal();
					if(form.data('reload-modal-on-success') && r.success) reloadCurrentModal();
					if(r.callbackScript) eval(r.callbackScript);
				},
				error:function(status){
					btn.button('reset');
					showAlert(status.statusText,'error');
				},
				dataType: 'json',
			});
		}
	});
	$(document.body).on('click','.show-warning',function(e){
	    	e.preventDefault();
	    	var warningModal = $("#warning-modal");
	    	var btn = $(this);
	    	warningModal.find('.extra-message').html(btn.data('warning-message')?btn.data('warning-message'):'');
	    	var actionButtton = warningModal.find('.action-button');
	    	actionButtton.attr('href',btn.attr('href'));
	    	if(btn.hasClass('ajax-link')) actionButtton.addClass('ajax-link');
	    	else actionButtton.removeClass('ajax-link');
			warningModal.modal();
	});

	$(document.body).on('click','a.ajax-link',function(e){
		e.preventDefault();
		var obj = $(this);
		if(!$(this).hasClass('show-warning'))
		{
			obj.button('loading');
			$.get(obj.attr('href'),function(r){
				obj.button('reset');
				if(r.success){
					if(obj.hasClass('action-button')) closeCurrentModal();					
					showAlert(r.message?r.message:'Hecho!');
				}
				else{
					showAlert(r.message?r.message:'Error!','error');
				}
				if(r.callbackScript) eval(r.callbackScript);
			},'json').fail(function(){
				obj.button('reset');
				showAlert('Error en sistema!','error');
			});
		}
	});
	$(document.body).on('change','form[data-table-id] input,form[data-table-id] select', function (e) {
		$("#"+$(this).closest('form').data('table-id')).bootstrapTable('refresh');
	});

	// $(document.body).on('shown.bs.tab','.dinamic-tab a[data-toggle="tab"]', function (e) {
	// 	var currentTarget = $(e.currentTarget);
	// 	var index = currentTarget.parent().index();
	// 	var tabPanes = currentTarget.closest('.dinamic-tab').find('.tab-content').eq(0).children();
	// 	tabPanes.hide();
	// 	tabPanes.eq(index).show();
	// });

	applyToNewContent($(document.body));
});
function generateModal(button,path,width,backdrop,keyboard){
	var modal = $("<div class='modal modal-ajax fade' tabindex='-1'>");
	width = width?width:null;
	backdrop = backdrop?backdrop:null;
	keyboard = keyboard?keyboard:null;
	if(width!==null){
		if(width=='container'){
			modal.addClass('container');
		}else{
			modal.data('width',width);
		}
	}
	if(backdrop!==null) modal.data('backdrop',backdrop);
	if(keyboard!==null) modal.data('keyboard',keyboard);
	if(path!=''){
		$(document.body).modalmanager('loading');
		modal.data('relatedButton',button);
		modal.data('relatedTarget',path);
		modal.load(path,function(response,status,xhr){
			if(status!='error'){
				$(document.body).append(modal);
				applyToNewContent(modal);
				modal.modal();			
			}else{
				$(document.body).modalmanager('removeLoading');
				modal.remove();
				showAlert('Error del sistema','error');
			}
		});	
	}
}
function applyToNewContent(container){
	applyValidation(container.find("form.validate-form"));
	applyBootstrapTable(container.find("[data-toggle='table']"));
	applyDatepickers(container.find('.calendar-field'));
	applyDatepickersRange(container.find('.input-daterange'));
	applyHtmlBox(container.find('.html-content'));
	applyAutocomplete(container.find('.autocomplete-ajax'));
	container.find('[data-toggle="tooltip"]').tooltip();
	// container.find(".number-field").number(true);
	// container.find(".number-field2").number(true,2);
	container.find('[data-toggle="popover"]').popover({html:true});
	container.find("a,button").data('loading-text',"<i class='fa fa-refresh fa-spin'></i>");
}
function applyAutocomplete(elements) {
	elements.each(function(i,o){
		var input = $(o);
		input.autocomplete({
		 	source: function(request,response){
	    		$.get(input.data('ajax'),{q:request.term},response,'json');	 		
		 	},
		 	minLength:2,
		 	select: function( event, ui ) {
		 		input.next().val(ui.item.id);
	      	}
		 });
	});
}
function applyHtmlBox(elements){
	elements.trumbowyg();
}
function applyDatepickers(elements){
	elements.datepicker({format:'yyyy-mm-dd'});
	// elements.each(function(i,o){
	// 	var auxF = $(o);
	// 	var parameters = {
	// 		changeMonth: true,
	//       	changeYear: true,
	// 		dateFormat:'yy-mm-dd',			
	// 	};
	// 	var datas = auxF.data();
	// 	for (var i in datas){
	// 		if(i.indexOf("datepicker")!=-1){
	// 			var auxStr = i.replace('datepicker','');
	// 			parameters[auxStr.charAt(0).toLowerCase()+auxStr.slice(1)]=datas[i];
	// 		}
	// 	}
	// 	auxF.datepicker(parameters);
	// 	// console.log(parameters);
	// 	// if(var aux = $(o).data('yearRange')) $(o).datepicker('option','yearRange',aux);
	// });
	// elements.datepicker({
	// });
}
function applyDatepickersRange(containers){
	containers.datepicker({format:'yyyy-mm-dd'});
	// containers.each(function(i,o){
	// 	$(o).datepicker();
	// });
	// 	var fieldStart = $(o).find('.calendar-field-start');
	// 	var fieldEnd = $(o).find('.calendar-field-start');
	// 	var parameters = {
	// 		changeMonth: true,
	//       	changeYear: true,
	// 		dateFormat:'yy-mm-dd',
	// 		onSelect:function(date,obj){
	// 			var current = $(this).datepicker('getDate');
	// 			if($(this).hasClass('.calendar-field-start')){
	// 				var next = current.add(1).day(); // http://datejs.com/test/date/index.html  -->link a la documentacion del plugin datejs
	// 				if(fieldEnd.datepicker('getDate')==null){
	// 					fieldEnd.datepicker('setDate',next);
	// 				}else if(fieldEnd.datepicker('getDate')<current){
	// 					fieldEnd.datepicker('setDate',next);
	// 				}					
	// 			}else{
	// 				var prev = current.add(-1).day(); // http://datejs.com/test/date/index.html  -->link a la documentacion del plugin datejs
	// 				if(fieldStart.datepicker('getDate')==null){
	// 					fieldStart.datepicker('setDate',prev);
	// 				}else if(fieldStart.datepicker('getDate')>current){
	// 					fieldStart.datepicker('setDate',next);
	// 				}					
	// 			}
	// 			$(this).keyup();
	// 			$(this).change();
	// 		}				
	// 	};
	// 	fieldStart.datepicker(parameters);
	// 	fieldEnd.datepicker(parameters);
	// });
}

function applyBootstrapTable(tables){
    tables.bootstrapTable({
    	// ajax:function(request){
    		// console.log(params);
    		// var table = $(this.$el);
    		// var form = table.data('filter-form')?table.data('filter-form'):$("[data-table-id='"+table.attr('id')+"']");
    		// if(form.length>0){
    		// 	console.log(form);
    		// 	request.data=jQuery.param(request.data)+'&'+form.serialize();
    		// }
    		// $.ajax(request);
    	// },
    	 // onLoadSuccess: function(data){
      //          	height = WindowHeight - 380;
      //           if(height > 800) height = 800;
      //           if(height < 250) height = 250;
      //          	$('.fixed-table-container').height(height);
      //   }
    });
    tables.on('load-success.bs.table',function(e,data){
       	// pinta estilos en filas y aplica plugins al nuevo html
       	var obj = $(this);
    	var trs = obj.find("tr[data-index]");
    	if(data.rowsClasses) for(var i in data.rowsClasses) trs.eq(i).addClass(data.rowsClasses[i]);
    });
}
function reloadCurrentModal(callback){
	var best_modal = getCurrentModal();
	var callback = typeof callback !=='undefined'?callback:function(r){};
	best_modal.modal('loading');
	best_modal.load(best_modal.data('relatedTarget'),function(r){
		best_modal.modal('removeLoading');
		applyToNewContent(best_modal);
		callback(r);
	});
}
function reloadCurrentModalNewTarget(href){
	var modal = getCurrentModal();
	modal.data('relatedTarget',href);
	reloadCurrentModal();
}
function getCurrentModal(){
	var open_modals = $('.modal.in');
	var highest = 0;
	var best_modal = open_modals.eq(0); 
	open_modals.each(function(index,value){
		var zindex = parseInt($(this).parent().css('zIndex'),10);
		if(zindex>highest){
			highest=zindex;
			best_modal = open_modals.eq(index);
		}	
	});
	return best_modal;
}
function closeCurrentModal(callback){
	callback = callback || function(){};
	var m = getCurrentModal();
	m.on('hidden.bs.modal',function(){
		setTimeout(callback,330);//espera los 3ms de efecto de css
	}).modal('hide');
}
function closeReloadModal(parameters,callback){
	closeCurrentModal(function(){
		reloadCurrentModal(parameters,callback);
	});
}
// Esta funcion aplica una validacion de "jquery.validatio" solo para la estructura de formularios de bootstrap
function applyValidation(objects){
	if(objects.length>0){
		objects.each(function(){
			$(this).validate({
				debug: true,
				ignore: [],
				validClass:'has-success',
				errorClass: 'has-error',
				highlight: function(element, errorClass, validClass) {
					$(element).parent().addClass(errorClass).removeClass(validClass);
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).parent().removeClass(errorClass).addClass(validClass);
				},
				errorPlacement: function(error,element){

				}
			});
		});
	}
}
function reloadAjaxTables(){
	$("[data-toggle='table']").bootstrapTable('refresh');
}
// Bootstrap fixes
function showAlert(message,type,closeOthers,showClose){
	type = type || 'success';
	message = message || 'Hecho!';
	showClose = showClose || true;
	alertify.set('notifier','position', 'bottom-right');
	console.log(type);
    if(type=='success'){
    	alertify.success(message);
    }else{
    	alertify.error(message);    	
    }
}
function setFormErrors(form,errors) {
	form.find('.error-message').text('');
	form.find('.has-error').removeClass('has-error');
	var auxField;
	for(var e in errors){
		auxField = form.find("[name='"+e+"']");
		auxField.parent().addClass('has-error');
		if(auxField.next().hasClass('error-message')) auxField.next().text(errors[e]);
		else auxField.after("<span class='text-danger error-message'>"+errors[e]+"</span>");
	}
}