jQuery(document).ready( function($) {	
	$('#DataTypeSelect').change(function() {
		var selectedDataTypeType = dataTypeTypeList[$(this).val()];
		
		if( $.inArray( selectedDataTypeType, ['9', '91'] ) === -1 ) {
			$('#modelFieldModelIdDiv').hide();
		} else {
			$('#modelFieldModelIdDiv').show(); 
		}
	});
});
