$.fn.tabModalActionHandler = function(options) {

	var defaults = {
		parent : null,
		target : $('#dialogbox'),
		msg : 'loading ...',

		onTargetLoad : function(result) {
		}
	}

	var root_id = '#' + options.target.attr('id');
	
	var parent_id = $(options.parent).attr('id');

	var $target_zone = $(parent_id + ' .edit-zone');
	
	var mainActions = function() {
    	
    	$(parent_id + ' .main-zone [data-zone-loader]').tabCrudActionHandler(
    			{
    				
    				target : $target_zone,
    				
    				onTargetLoad : function(result) {
    					
    					$(root_id + ' .edit-zone form[data-async]')
    					.tabCrudFormHandler(tabCrudFormHandlerModalOptions);
    				}
    			});
    	
    	$(parent_id + ' .main-zone [data-modal-loader]').tabModalActionHandler({
    		
    		parent : $(options.parent),
    		
    		target : $('#dialogbox')
    	});
    	
    	$(root_id + ' .edit-zone').html('');
    	
    };

    var tabCrudFormHandlerModalOptions = {

		close_edit_zone : function(data, status) {

			options.target.modal('hide');
		},

		on_success : function(data, status) {

			if ('' == data) {

				options.target.modal('hide');

				var url = options.parent.attr('data-target');

				// load content for selected tab
				$(parent_id + ' .main-zone').load(url, mainActions);

			} else {

				$target_zone = $(root_id + ' .modal-body');

				$target_zone.html(data);

				$(root_id + ' form[data-async]').tabCrudFormHandler(

				tabCrudFormHandlerModalOptions);
			}
		}
	};

	var options = $.extend(defaults, options);

	$.each($(this), function(key, objet) {

		$(objet).click(function(e) {

			e.preventDefault();

			root_id = '#' + options.target.attr('id');

			var url = $(this).attr('href');

			options.target.modal({
				remote : url
			});

			options.target.on('shown', function() {

				$(root_id + ' form[data-async]').tabCrudFormHandler(

				tabCrudFormHandlerModalOptions);
			});
		});
	});
}
