function addRow(tableID) {
 
	var table = document.getElementById(tableID);
 
	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount);
 
	var cell1 = row.insertCell(0);
	var element1 = document.createElement("input");
	element1.type = "checkbox";
	cell1.appendChild(element1);
 
	var cell2 = row.insertCell(1);
	var element2 = document.createElement("input");
	element2.type = 'text';
	element2.setAttribute("class","text");
	element2.size = "15";
	element2.setAttribute("maxlength","150");
	element2.name = "group_name[][sir]";
	cell2.appendChild(element2);

	var cell3 = row.insertCell(2);
	var element3 = document.createElement("input");
	element3.type = "text";
	element3.setAttribute("class","text");
	element3.size = "15";
	element3.setAttribute("maxlength","150");
	element3.name = "group_name[][rfc]";
	cell3.appendChild(element3);

	var cell4 = row.insertCell(3);
	var element4 = document.createElement("input");
	element4.type = "text";
	element4.setAttribute("class","text");
	element4.size = "15";
	element4.setAttribute("maxlength","150");
	element4.name = "group_name[][app]";
	cell4.appendChild(element4);

	var cell5 = row.insertCell(4);
	var element5 = document.createElement("input");
	element5.type = "text";
	element5.setAttribute("class","text");
	element5.size = "15";
	element5.setAttribute("maxlength","150");
	element5.name = "group_name[][component]";
	cell5.appendChild(element5);

	var cell6 = row.insertCell(5);
	var element6 = document.createElement("input");
	element6.type = "text";
	element6.setAttribute("class","text");
	element6.size = "15";
	element6.setAttribute("maxlength","150");
	element6.name = "group_name[][version]";
	cell6.appendChild(element6);

	var cell7 = row.insertCell(6);
	var element7 = document.createElement("input");
	element7.type = "text";
	element7.setAttribute("class","text");
	element7.size = "15";
	element7.setAttribute("maxlength","150");
	element7.name = "group_name[][zip]";
	cell7.appendChild(element7);

	var cell8 = row.insertCell(7);
	var element8 = document.createElement("input");
	element8.type = "text";
	element8.size = "15";
	element8.setAttribute("maxlength","150");
	element8.name = "group_name[][dev]";
	element8.setAttribute("id",rowCount + 2);
	cell8.appendChild(element8);
	var element9 = document.createElement("input");
	element9.type = "button";
	element9.name = "today";
	element9.setAttribute("onclick","document.getElementById('" + element8.id + "').value=(new Date().getFullYear())+'/'+((new Date().getMonth()) + 1)+'/'+(new Date().getDate())");
	element9.value = "Today";
	cell8.appendChild(element9);

	var cell10 = row.insertCell(8);
	var element10 = document.createElement("input");
	element10.type = "text";
	element10.size = "15";
	element10.setAttribute("maxlength","150");
	element10.name = "group_name[][prod]";
	element10.setAttribute("id", 50 + element8.id);
	cell10.appendChild(element10);
	var element11 = document.createElement("input");
	element11.type = "button";
	element11.name = "today";
	element11.setAttribute("onclick","document.getElementById('" + element10.id + "').value=(new Date().getFullYear())+'/'+((new Date().getMonth()) + 1)+'/'+(new Date().getDate())");
	element11.value = "Today";
	cell10.appendChild(element11);
	
	//alert(document.getElementById('dataTable').rows[3].innerHTML);
	//alert(document.getElementById('dataTable').rows[4].innerHTML);
	//alert(document.getElementById('dataTable').rows[5].innerHTML);
}
 
function deleteRow(tableID) {
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
 
	for(var i=0; i<rowCount; i++) {
	var row = table.rows[i];
	var chkbox = row.cells[0].childNodes[0];
	if(null != chkbox && true == chkbox.checked) {
	table.deleteRow(i);
	rowCount--;
	i--;
	}

	}
	}catch(e) {
	alert(e);
	}
}