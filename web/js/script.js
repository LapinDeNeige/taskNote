function displayDialog()
{
	$('#modal-add').modal('show');
	
}

function displayEditDialog()
{
	var val=$(':focus').attr('name');
	
	$('#hidden-id').val(12)
	$('#modal-edit').modal('show');
}