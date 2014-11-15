$(document).ready(function() {
	$('table').on('click', 'tr.check', function() {
		$(this).removeClass('check');
		$(this).addClass('uncheck');
		$(this).find("input[type='checkbox']").prop('checked', false);
	});

	$('table').on('click', 'tr.uncheck',function() {
		$(this).removeClass('uncheck');
		$(this).addClass('check');
		$(this).find("input[type='checkbox']").prop('checked', true);
	});
});