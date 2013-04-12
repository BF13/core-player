$.fn.tabCrudFormHandler = function(options) {

	var defaults = {
		btn_class : '.btn-primary',

		close_edit_zone : function(data, status) {
		},

		on_success : function(data, status) {
		},

		close_btn_class : '.zone-close',
	}

	var options = $.extend(defaults, options);

	$.each($(this), function(key, objet) {

		var root_id = '#' + $(objet).attr('id');

		$(root_id + ' ' + options.close_btn_class).click(
				options.close_edit_zone);

		$(objet).submit(function(event) {

			$(root_id + ' ' + options.btn_class).button('loading');

			$.ajax({

				type : $(objet).attr('method'),
				url : $(objet).attr('action'),
				data : $(objet).serialize(),

				success : options.on_success
			});

			event.preventDefault();
		});

	});
}
