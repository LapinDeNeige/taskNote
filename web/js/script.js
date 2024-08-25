function displayDialog()
{
	$('#modal-add').modal('show');
	
}




function displayEditDialog()
{
	var note_id=$(':focus').attr('name');
	document.cookie='note-id='+note_id;
	
	$('#modal-edit').modal('show');
}