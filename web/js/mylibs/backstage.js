/* Author: Diego Caponera

*/

BACKSTAGE = {
	
	common : {
		
		dataTable : null,
		deleteList : null,
		
		settings : {
			basePath : mwBasepath,
			draftInterval : 10000,
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
			$(document).on('submit', '.mw-form', function(){
				
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
			
		},

		cancel : function(){

			
			
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
						"sLengthMenu": "Show _MENU_ records per page"
					}
				} );			
				
			});
			
		},		
		
		new : function(button, delegate){
			
			var entityName = BACKSTAGE.common.dataTable.attr('data-entity');
			var id = $(button).parent().parent().attr('data-id');
			var template = _.template( $("#item-new").html() );
			
			$('#box').html(template());
			
			$('#box').modal({
				keyboard : false
			});
			
		},		
		
		edit : function(button, delegate){

			var entityName = BACKSTAGE.common.dataTable.attr('data-entity');
			var id = $(button).parent().parent().attr('data-id');
			var template = _.template( $("#item-edit").html() );
			
			$('#box').html(template());
			
			$('#box').modal({
				keyboard : false
			});
			
		},
		
		delete : function(button){
			
			BACKSTAGE.common.deleteList = [];
			
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
			
			BACKSTAGE.common.deleteList = [];
			
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
		
		uploadSetup: function(button){
			
			if($('#pic-uploader').length > 0){

			var uploader = new qq.FileUploader({
				element: document.getElementById('pic-uploader'),
		        action: BACKSTAGE.common.settings.basePath + 'backstage/'+$(button).attr('data-controller')+'/upload',
		        debug: false,
				multiple: false,
				fileTemplate: '<li>' +
				                  '<span class="qq-upload-file"></span>' +
				                  '<span class="qq-upload-spinner"></span>' +
				                  '<span class="qq-upload-size"></span>' +
				                  '<a class="qq-upload-cancel" href="#">Cancel</a>' +
				               '</li>',
				
		        onComplete: function(id, filename, responseJSON){

					if( responseJSON.success == true){

						$.post(
							BACKSTAGE.common.settings.basePath + 'backstage/'+$(button).attr('data-controller')+'/savepicture',
							{
								file : filename,
								id : $(button).attr('data-id')
							},
							function(data){

								if(data.status == 'OK'){

									$('#thumb').attr('src', data.filename + '?'+Math.random());
									
								}

								$('#picResponse').html(data.message);								
								
							}
						
						);
						
					}
					
		        }
		    });			
			
			}			
			
		},
		
		contains : function(list, id){
			
			for(var i = 0; i <list.length; i++){
				if( list[i]['id'] == id)
					return true;
			}
			
			return false;
			
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
	
	settings : {
		
		save : function(form){
			
			var button = $('input[name="settings_submit"]', form);
			var buttonText = button.val();
			
			$.post(
				$(form).attr('action'), 
				$(form).serialize()
				,
				function(data){

					$(button).val(data.message).attr('disabled', 'disabled').val(data.message);
					
					setTimeout(function(){

						$(button).val(buttonText).removeAttr('disabled');

					}, 2000);
				
				}
			);			
			
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