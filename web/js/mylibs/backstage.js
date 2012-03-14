/* Author: Diego Caponera

*/

window.locale = {
    "fileupload": {
        "errors": {
            "maxFileSize": "File is too big",
            "minFileSize": "File is too small",
            "acceptFileTypes": "Filetype not allowed",
            "maxNumberOfFiles": "Max number of files exceeded",
            "uploadedBytes": "Uploaded bytes exceed file size",
            "emptyResult": "Empty file upload result"
        },
        "error": "Error",
        "start": "Start",
        "cancel": "Cancel",
        "destroy": "Delete"
    }
};

BACKSTAGE = {
	
	common : {
		
		dataTable : null,
		deleteList : null,
		
		templates : {
 			alert			: _.template( $("#alert").html() ),
			editsettings	: _.template( $("#settings-edit").html() )
		},
		
		settings : {
			basePath : mwBasepath,
			imgPath : mwBasepath + 'web/img/uploads/',
			tinymce : {
				
				// Location of TinyMCE script
				script_url : mwBasepath + 'web/js/libs/tiny_mce/tiny_mce.js',

				// General options
			    mode : "textareas",
			    theme : "advanced",
				plugins : "fullscreen",
			    theme_advanced_buttons1 : "mylistbox,mysplitbutton,bold,italic,underline,separator,bullist,numlist,undo,redo,link,unlink,image,code,fullscreen",
			    theme_advanced_buttons2 : "",
			    theme_advanced_buttons3 : "",
			    theme_advanced_toolbar_location : "top",
			    theme_advanced_toolbar_align : "left",
			    theme_advanced_statusbar_location : "bottom",
				width	: "300",
				height	: "200"
			}
		},
		
		init : function(){
			
			// Bind buttons	to controllers
			$(document).on('click', 'button[data-controller], input[type="button"][data-controller]', function(){		

				return BASE.exec(
					$(this).attr('data-controller'),
					$(this).attr('data-action'),
					this
				);

			});
			
			// Bind forms to controllers
			$(document).on('submit', '.backstage-form', function(){

				BASE.exec(
					$(this).attr('data-controller'),
					$(this).attr('data-action'),
					this
				);				

				return false;
				
			});
			
			// Bind links to controllers
			$(document).on('click', 'a[data-controller], .clickable[data-controller]', function(){
				
				BASE.exec(
					$(this).attr('data-controller'),
					$(this).attr('data-action'),
					this
				);				
				
				return false;
				
			});	
			
			// Bootstrap stuff
			$('a[data-content]').popover({trigger: 'manual', delay:'1000'});
			
			$('#box').on('hide', function(){

				if(typeof(tinyMCE) !== 'undefined' && typeof(tinyMCE.activeEditor) !== 'undefined')
					tinyMCE.activeEditor.remove();
				
			});
			
		},
		
		fetch : function(element){
			
			$.post($(element).attr('data-source'),{},function(data){

				var tbody = $(element).find('tbody');
				var template = _.template($('#item-table').html());

				$.each(data,function(i,row){
					
					tbody.append(template({
						header	: $(element).find('th[data-field]').map(function(){ return $(this).attr('data-field') != 'id' ? $(this).attr('data-field') : null}),
						row		: row
					}));					
					
				});
				
				BACKSTAGE.common.dataTable = $(element).dataTable( {
					"sDom": "<'row-fluid'<'span5'l><'span7'f>r>t<'row-fluid'<'span5'i><'span7'p>>",
					"sPaginationType": "bootstrap",
					"oLanguage": {
						"sLengthMenu": "Show _MENU_ records per page."
					},
					"aoColumnDefs": [ 
				      { "sWidth": "10px", "aTargets": [ 0 ] },
				      { "sWidth": "60px", "aTargets": [ $(element).find('th[data-field]').length ] },
					  { "bSortable": false, "aTargets": [ 0, $(element).find('th[data-field]').length ] }				
				    ]
				});			
				
			});
			
		},		
		
		new : function(button, delegate){
			
			var entityName = BACKSTAGE.common.dataTable.attr('data-entity');
			var id = $(button).parent().parent().attr('data-id');
			var template = _.template( $("#item-new").html() );
			
			$('#box').html(template());
			
			BACKSTAGE.common.uiSetup();
			
			$('#box').modal({
				keyboard : false
			});
			
		},		
		
		edit : function(button, delegate){

			var entityName = BACKSTAGE.common.dataTable.attr('data-entity');
			var id = $(button).closest('tr').find('[data-id]').attr('data-id');
			var template = _.template( $("#item-edit").html() );
			
			$.post(BACKSTAGE.common.settings.basePath + 'backstage/' + entityName + "/get", {id : id}, function(data){

				$('#box').html(template());
				$('#box [name="id"]').val(id);

				_.each(data.entity, function(value, key){

					switch(typeof value){
						
						case "string":
						
							$('#box form [name="'+key+'"]').val(value);							
							
							break;
							
						case "object":
						
							if(value == null) break;
				
							var multiple = '';
				
							_.each(value, function(id){

								multiple = key.substring(key.length-4,key.length) == 'List' ? '[]' : '';

								$('#box form [name="'+key+multiple+'"] option[value="'+id+'"]').attr('selected', 'selected');
								
							});
						
							break;
						
					}
					
					$('#thumb_'+key).each(function(){

						$('#_'+key).attr('value') != '' && $(this).attr('src', BACKSTAGE.common.settings.imgPath + $('#_'+key).attr('value'));
						
					});
					
				});
		
				BACKSTAGE.common.uiSetup();			
		
				$('#box').modal({
					keyboard : false
				});		
				
			});
		
		},
		
		save : function(form){

			var req = $('form').serializeArray();
			
			req.push({ name : 'token', value : $('meta[name="csrf"]').attr('content')});

			$.post($(form).attr('action'), req, function(data){
			
				switch(data.status)
				{
					
					case "Error":
					
						$('.btn-primary', form).addClass('btn-danger').attr('value', data.message);					
					
						break;
					
					case "OK":
			
						var row = $('#dataTable [data-id="'+data.entity.id+'"]').closest('tr');
						
						if(row.length > 0){
						
							var i = 0, pos = 0;
						
							for(p in data.entity)
							{
								i++;
								if(p == 'id') continue;
								
								BACKSTAGE.common.dataTable.fnUpdate(data.entity[p], row[0], i-1);
								
							}						
							
						}else{
							
							var template = _.template($('#item-table').html());

							BACKSTAGE.common.dataTable.dataTable().fnAddData(
								$(template({
									header	: $('#dataTable th[data-field]').map(function(){ return $(this).attr('data-field') != 'id' ? $(this).attr('data-field') : null}),
									row		: data.entity
								})).find('td').map(function(){return $(this).html()})
							);
							
						}
					
						$('#box').modal('toggle');
						
						$('#alertBox').html(
							BACKSTAGE.common.templates.alert({
								'mode' : 'success',
								'message' : data.message
							})					
						);
						
						break;
					
				}
				
			});
			
		},		
		
		delete : function(button){
			
			BACKSTAGE.common.deleteList = [];			
			
			var entityName = BACKSTAGE.common.dataTable.attr('data-entity');
			var id = $(button).closest('tr').find('[data-id]').attr('data-id');
			
			var template = _.template( $("#item-delete").html() );
			
			BACKSTAGE.common.deleteList.push(id);
			
			$('#box').html(template({
				entityName : entityName
			}));
			
			$('#box').modal({
				keyboard : false
			});
			
		},
	
		deleteCecked : function(button){
			
			BACKSTAGE.common.deleteList = [];			
			
			var entityName = BACKSTAGE.common.dataTable.attr('data-entity');
			var entities = $('#dataTable input[name="list-single[]"]:checked');
			var template = _.template( $("#item-delete").html() );		
			
			if(entities.length == 0){
				
				$.when($(button).popover('show')).then(setTimeout(function(){$(button).popover('hide')}, 2000));
				
			}else{
				
				entities.each(function(){					
					BACKSTAGE.common.deleteList.push($(this).attr('data-id'));
				});				
				
				$('#box').html(template({
					entityName : entityName
				}));		

				$('#box').modal({
					keyboard : false
				});

			}
			
		},	
		
		deleteConfirm : function(button){
				
			var entityName = $(button).attr('data-entity');		

			$.post(
				BACKSTAGE.common.settings.basePath + 'backstage/'+entityName+"/delete",
				{ 
					id 		: BACKSTAGE.common.deleteList,
					token	: $('meta[name="csrf"]').attr("content")
				 },
				function(data){

					$.when($('#box').modal('hide')).then(function(){
						
						$.each(BACKSTAGE.common.deleteList, function(i, v){

							BACKSTAGE.common.dataTable.find('[data-id="' + v + '"]').closest('tr').fadeOut("slow", function () {
								var pos = BACKSTAGE.common.dataTable.fnGetPosition(this);
								BACKSTAGE.common.dataTable.fnDeleteRow(pos);
							});						

						});				
						
					});
					
				}
			);
			
		},
		
		uploadSetup : function(element){

			$(element).fileupload({
			
				dataType : 'json',
				url : BACKSTAGE.common.settings.basePath + 'backstage/picture/upload',
				done : function(e, data){
					
					$('#thumb_' + $(data.fileInput[0]).attr('id').split('_')[1]).attr('src', data.result[0].url);
					$('#_' + $(data.fileInput[0]).attr('id').split('_')[1]).val(data.files[0].fileName);
					
				}
				
			});			
		
		},
		
		uiSetup : function(element)
		{
			
			$('#box select').chosen();
			$('#box [data-rich]').tinymce(BACKSTAGE.common.settings.tinymce);
			BACKSTAGE.common.uploadSetup($('#box input[type="file"]'));
			
		}
		
	},
	
	settings : {
		
		edit : function(element){
			
			$('#box').html(BACKSTAGE.common.templates.editsettings());
			$('#box select').chosen();			
			
			$('#box').modal({
				keyboard : false
			});		
			
		},
		
		save : function(form){
			
			var req = $('form').serializeArray();
			
			req.push({ name : 'token', value : $('meta[name="csrf"]').attr('content')});

			$.post($(form).attr('action'), req, function(data){
			
				switch(data.status)
				{
					
					case "Error":
					
						$('.btn-primary', form).addClass('btn-danger').attr('value', 'Error.');
					
						break;
					
					case "OK":
					
						$('.btn-primary', form).addClass('btn-success').attr('value', 'Saved!');

						break;
					
				}
				
			});
			
			
		}
		
	}
	
};

BASE = {
	
	exec: function(controller, action, elements) {

    	var ns = BACKSTAGE, 
			action = ( action === undefined ) ? "init" : action;

		if ( controller !== "" && ns[controller] && typeof ns[controller][action] == "function" ) {
      		return ns[controller][action](elements);
		}

	},

	init: function() {

		// Run common setup
    	BASE.exec("common");

		// Run startup actions [if any]
		$.each($('[data-startup]'), function(){
		
			BASE.exec(
				$(this).attr('data-controller'),
				$(this).attr('data-action'),
				this
			);
			
		});

  	}	
	
}

$(document).ready(BASE.init);