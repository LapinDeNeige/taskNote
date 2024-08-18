function displayDialog()
{
	$('#modal-add').modal('show');
	
}

function deleteItem(id)
{
	$.ajax(
	{
		type:'POST',
		url:'/controllers/deleteItem'
		
	}
	
	)
}