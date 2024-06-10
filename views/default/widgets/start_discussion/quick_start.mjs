import 'jquery';
	
$(document).on('change', '.elgg-form-start-discussion-quick-start select[name="container_guid"]', function () {
	var $form = $(this).closest('form');
	var selected_group = $(this).val();
	
	var $options = $form.find('select[name="access_id"]').find('option');
	$options.prop('selected', false);
	
	if (selected_group !== '') {
		$options.each(function (index, elem) {
			if ($(elem).html() === selected_group) {
				$(elem).prop('selected', true);
			}
		});
	}
});
