function checkAllFields(ref)
{
var chkAll = document.getElementById('checkitAll');
var checks = document.getElementsByName('CheckAll[]');
var removeButton = document.getElementById('removeChecked');
var boxLength = checks.length;
var allChecked = false;
var totalChecked = 0;
var privacy_label = document.getElementById('Hide_me');
	if ( ref == 1 )
	{
		if ( chkAll.checked == true )
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = true;
			privacy_label.style.visibility = 'visible';
		}
		else
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = false;
			privacy_label.style.visibility = 'hidden';
		}
	}
	else
	{
		for ( i=0; i < boxLength; i++ )
		{
			if ( checks[i].checked == true )
			{
			allChecked = true;
			continue;
			}
			else
			{
			allChecked = false;
			break;
			}
		}
		if ( allChecked == true )
		{
		chkAll.checked = true;
		}
		else
		{
		chkAll.checked = false;
		}
	}
}