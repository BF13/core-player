$.fn.tabCrudActionHandler = function(options) {

	var defaults = {
		target : $('.edit-zone'),
		msg : 'loading ...',

		onTargetLoad : function(result) {
		}
	}

	var options = $.extend(defaults, options);

	$.each($(this), function(key, objet) {

		$(objet).click(function(e) {

			e.preventDefault();

			options.target.html(options.msg);

			options.target.load($(this).attr('href'), options.onTargetLoad);
		});
	});
}
