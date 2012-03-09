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
 			tablerow		: _.template( $("#table-row").html() ),
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
			    theme_advanced_buttons1 : "mylistbox,mysplitbutton,bold,italic,underline,separator,strikethrough,bullist,numlist,undo,redo,link,unlink",
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
			
		},
		
		fetch : function(element){
			
			$.post($(element).attr('data-source'),{},function(data){
			
				var tbody = $(element).find('tbody');
				var tr = null;
			
				$.each(data,function(i,row){
				
					tr = $('<tr></tr>');
					tr.append($('<td></td>').html($('<input type="checkbox"/>').attr('name', 'list-single')));
					
					$.each($(element).find('th[data-field]'), function(){
						
						$(this).attr('data-field') != "id" && tr.append($('<td></td>').html(row[$(this).attr('data-field')]));
						
					});
					
					tr.append($('<td></td>').append(
						$('<a></a>')
							.html($('<i></i>').addClass('icon-pencil').addClass('icon-white'))						
							.attr('href', '#').attr('data-controller', 'common').attr('data-action', 'edit')
							.addClass('btn').addClass('btn-mini').addClass('btn-inverse')
					).append(
						$('<a></a>')
							.html($('<i></i>').addClass('icon-trash').addClass('icon-white'))						
							.attr('href', '#').attr('data-controller', 'common').attr('data-action', 'delete')
							.addClass('btn').addClass('btn-mini').addClass('btn-inverse')
					).addClass('btn-group'))
					 .attr('data-id', row.id);
					
					tbody.append(tr);
					
				});
				
				BACKSTAGE.common.dataTable = $(element).dataTable( {
					"sDom": "<'row-fluid'<'span5'l><'span7'f>r>t<'row-fluid'<'span5'i><'span7'p>>",
					"sPaginationType": "bootstrap",
					"oLanguage": {
						"sLengthMenu": "Show _MENU_ records per page."
					}
				} );			
				
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
			var id = $(button).parent().parent().attr('data-id');
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
						
							if(value.length > 0){

								_.each(value, function(v){

									$('#box form [name="'+key+'[]"] option[value="'+v.id+'"]').attr('selected', 'selected');
									
								});

							}else{

								$('#box form [name="'+key+'"]').val(value.id);
								
							}
						
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
					
						break;
					
					case "OK":

						if(typeof(tinyMCE) !== 'undefined')
							tinyMCE.activeEditor.remove();
					
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
			var id = $(button).parent().parent().attr('data-id');
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
			var entities = $('input[name="list-single"]:checked');
			var template = _.template( $("#item-delete").html() );		
			
			if(entities.length == 0){
				
				$.when($(button).popover('show')).then(setTimeout(function(){$(button).popover('hide')}, 2000));
				
			}else{
				
				entities.each(function(){					
					BACKSTAGE.common.deleteList.push($(this).parent().parent().attr('data-id'));
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

							BACKSTAGE.common.dataTable.find('tr[data-id="' + v + '"]').fadeOut("slow", function () {
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
			
		},
		
		contains : function(list, id){
			
			for(var i = 0; i <list.length; i++){
				if( list[i]['id'] == id)
					return true;
			}
			
			return false;
			
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
		
	},

	release : {
		
		init : function(element){
			
			BACKSTAGE.common.datatable = $(element).dataTable({
				"aaSorting": [[ 5, "desc" ]],
				"bJQueryUI": true,
				"sPaginationType": "full_numbers",
				"sAjaxSource": $(element).attr('data-source'),
				"fnDrawCallback": function (){
					

					
				}
			});			
			
		},
		
		new : function(element){
			BACKSTAGE.common.new(element, this.tracklistSetup); 
		},
		
		edit : function(element){
			BACKSTAGE.common.edit(element, this.tracklistSetup);			
		},
		
		save : function(form){

			var trackList = [];
			
			$('#release-tracklist li').each(function(){
			
				trackList.push(parseInt($(this).attr('data-track-id')));
				
			});

			$.post(
				$(form).attr('action'), 
				{
					id				: $(form.id).val(),
					trackList		: trackList,					
					title			: $(form.title).val(),														
					catalog			: $(form.catalog).val(),	
					url				: $(form.url).val(),	
					info			: tinyMCE.activeEditor.getContent(),					
					enabled			: $(form.enabled).val(),													
					token			: $('meta[name="csrf"]').attr("content")
				},
				function(data){

					if(data.status == 'OK'){
						
						$.fancybox.close();
						
						BACKSTAGE.common.datatable.fnReloadAjax();
						
					}
				
				}
			);
			
		},
		
		tracklistSetup: function(response){
			
			BACKSTAGE.release.tracks = [];
			var suggestions = [];
			
			_.each(response.tracks, function(t){
				
				suggestions.push({
					value : parseInt(t.id),
					label : t.authorList[0].stageName+" - "+t.title
				});
				
				t.chosen = false;
				
				BACKSTAGE.release.tracks[parseInt(t.id)] = t;
				
			});
			
			$('#release-tracklist li').each(function(){
			
				BACKSTAGE.release.tracks[parseInt($(this).attr('data-track-id'))].chosen = true;
				
			});
			
			$('#track-suggest').autocomplete({
				
				source: suggestions,
	
				select: function(event, ui) {

					if(BACKSTAGE.release.tracks[ui.item.value].chosen == true){
						
						alert('Track already present in current release!');
						
					}else{
					
						var row = _.template( $("#release-single-track").html() );

						$('#release-tracklist').append(row({
							track : BACKSTAGE.release.tracks[ui.item.value]
						}));

						BACKSTAGE.release.tracks[ui.item.value].chosen = true;					
						
					}
					
					$('#track-suggest').val('');
					
					$('#release-tracklist li').length > 0 && $('.notracks').hide();
					
					return false;

				}

			});
			
			$('#release-tracklist').sortable({});
			
			$('#release-tracklist li').length > 0 && $('.notracks').hide();
			
		},
		
		removeTrack: function(element){
			
			BACKSTAGE.release.tracks[parseInt($(element).parent().attr('data-track-id'))].chosen = false;
			$(element).parent().remove();
			$('#release-tracklist li').length == 0 && $('.notracks').show();
			
		}
		
	},
	
	partner : {

		init : function(element){
			
			BACKSTAGE.common.datatable = $(element).dataTable({
				"aaSorting": [[ 2, "asc" ]],
				"bJQueryUI": true,
				"sPaginationType": "full_numbers",
				"sAjaxSource": $(element).attr('data-source'),
				"fnDrawCallback": function (){
					

					
				}
			});			
			
		},
		
		new : function(element){
			BACKSTAGE.common.new(element);
		},
		
		edit : function(element){
			BACKSTAGE.common.edit(element);			
		},
		
		save : function(form){
			
			$.post(
				$(form).attr('action'), 
				{
					id			: $(form.id).val(),
					name		: $(form.name).val(),
					url			: $(form.url).val(),													
					token		: $('meta[name="csrf"]').attr("content")
				},
				function(data){

					if(data.status == 'OK'){
						
						$.fancybox.close();
						
						BACKSTAGE.common.datatable.fnReloadAjax();
						
					}
				
				}
			);
			
		}		
		
	},
	
	
	
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