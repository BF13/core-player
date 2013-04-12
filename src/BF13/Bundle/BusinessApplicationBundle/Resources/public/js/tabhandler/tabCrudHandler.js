$.fn.tabCrudHandler = function(options) {

	var defaults = {
		value : '',
		pattern: '<div class="main-zone">##CONTENT##</div><div class="edit-zone"></div>'
	}

	var options = $.extend(defaults, options);

	var $objet = $(this);

	var root_id = '#' + $objet.attr('id');

	var content = $objet.html();

	var newcontent = options.pattern.replace('##CONTENT##', content);

	$objet.html(newcontent);

	var $target_zone = $(root_id + ' .edit-zone');

	var mainActions = function() {
    	
    	$(root_id + ' .main-zone [data-zone-loader]').tabCrudActionHandler(
    			{
    				
    				target : $target_zone,
    				
    				onTargetLoad : function(result) {
    					
    					$(root_id + ' .edit-zone form[data-async]')
    					.tabCrudFormHandler(tabCrudFormHandlerOptions);
    				}
    			});
    	
    	$(root_id + ' .main-zone [data-modal-loader]').tabModalActionHandler({
    		
    		parent : $objet,
    		
    		target : $('#dialogbox')
    	});
    	
    	$(root_id + ' .edit-zone').html('');
    	
    };
			
	var tabCrudFormHandlerOptions = {

		close_edit_zone : function(data, status) {

			$target_zone.html('');
		},

		on_success : function(data, status) {

			if ('' == data) {

				var url = $objet.attr('data-target');
				
				$(root_id + ' .main-zone').load(url, mainActions);

			} else {

				$target_zone.html(data);

				$(root_id + ' .edit-zone form[data-async]').tabCrudFormHandler(
						tabCrudFormHandlerOptions);
			}
		}
	};

    var url = $objet.attr('data-target');
    
    $(root_id + ' .main-zone').load(url, mainActions);
}
