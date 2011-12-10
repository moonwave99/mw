$(document).ready(function(){

	DEBUG = {
		
		init : function(){
			$('#debug, #log').hide();
			
			$(document).on('click', '[data-controller="debug"]', function(){

				DEBUG[$(this).attr('data-action')](this);

				return false;

			});			
			
			$(document).bind('keydown', 'ctrl+d', function(){
				
				if($('#log').is(':visible'))
					$('#log').hide('slow');
				
				$('#debug').toggle('slow');
				
			});
			
		},
		
		log : function(e){
			$('#log').toggle('slow');
		},
		
		hide : function(e){
			$('#debug, #log').hide('slow');
		}
		
	}
	

	
	
	
	DEBUG.init();
	
});