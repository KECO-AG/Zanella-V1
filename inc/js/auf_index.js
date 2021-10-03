function toggleByClass(className) {
     $("."+className).toggle();
}
// Dialog
$(function(){
	$('#dialog').dialog({
		autoOpen: false,
		width: 600,
		buttons: {
				"Speichern": function() {$('#new').submit(); $(this).dialog("close");},
				"Abbrechen": function() {$(this).dialog("close");}
				}
	});
	// Dialog Link
	$('#dialog_link').click(function(){
		$('#dialog').dialog('open');
		return false;
		});
});
$(function(){
	// Dialog
	$('#dialog-edit').dialog({
		autoOpen: false,
		width: 600,
		buttons: {
				"Speichern": function() {$('#edit').submit(); $(this).dialog("close");},
				"Abbrechen": function() {$(this).dialog("close");}
				}
	});
});
$(function() {
	$( "#datepicker" ).datepicker(
		{
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 2,
			firstDay: 1,
			showWeek: true,
			showAnim: 'slideDown',
		} );
});
$(function() {
	$( "#edit-datepicker" ).datepicker(
		{
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 2,
			firstDay: 1,
			showWeek: true,
			showAnim: 'slideDown',
		} );
});
$(function() {
	$( "#edit-erledigtPicker" ).datepicker(
		{
			dateFormat: 'yy-mm-dd',
			firstDay: 1,
			showWeek: true,
		} );
});