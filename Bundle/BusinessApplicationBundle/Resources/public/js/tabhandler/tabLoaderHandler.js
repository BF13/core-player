$.fn.tabLoaderHandler = function(options) {

	var defaults = {
		value : ''
	}

	var options = $.extend(defaults, options);

	var $objet = $(this);

	$objet.bind('show', function(e) {

        var target = $(e.target).attr('href');

        var $target = $(target);

        var url = $target.attr('data-target');

        if(mode = $target.attr('data-tab-handler')) {

        	var fn = function () {
        		$target.tabCrudHandler();
        	}

        } else {

        	var fn = function () {
            	console.log(target, 'no-data-tab-handler');
        	}
        }

        if(url != undefined && '' == $target.html()) {

            //load content for selected tab
            $target.load(url, fn);
        }
    });
}
