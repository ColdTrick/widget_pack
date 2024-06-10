import 'jquery';
import Ajax from 'elgg/Ajax';

$(document).on('submit', '.widget-user-search-form', function(){
	var ajax = new Ajax();
	
	var $form = $(this);
	
	ajax.view('widgets/user_search/content', {
		method: 'POST',
		data: ajax.objectify($form),
		success: function(result) {
			$form.parent().html(result);
		}
	});
	
	return false;
});
