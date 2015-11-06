(function($) {

	$.ajax({
		url:'/welcome/widgets',
		data:'widgets=features_items,category_tab,footer,recommended_items',
		type:'post',
		dataType:'json',
		success: function(data) {
			for (elem in data.htmls) {
				$(elem).html(data.htmls[elem]);
			}
			//$('#features_items').html(data);
		}
	});
})(jQuery);
