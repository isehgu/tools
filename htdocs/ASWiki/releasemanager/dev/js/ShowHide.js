function doInputs(obj){
var checkboxs = obj.form.elements['CheckAll[]'];
var i =0, box;
	document.getElementById('Hide_me').style.visibility = 'hidden'; 
	while(box = checkboxs[i++]){
	if(!box.checked)continue;
	document.getElementById('Hide_me').style.visibility = 'visible'; 
	break;
	}
}
