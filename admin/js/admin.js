function delete_item(id) {
	confirmed = confirm("Diesen Inhalt wirklich entfernen?");
	if (confirmed) {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET","item_delete.php?id="+id,true);
		xmlhttp.send();
		document.getElementById(id).style="display:none";
	}
}
function update_status(id,status) {
	go_on = 1; //confirm("Den Status dieses Beitrages wirklich bearbeiten?");  
	if (go_on) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
       if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          document.getElementById("item_status_link_"+id).innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET","../admin/item_status.php?id="+id+"&status="+status,true);
    xmlhttp.send();
	}
}
function toggle(id) {
  var e = document.getElementById(id);
  if(e.style.display == 'block')
     e.style.display = 'none';
  else
     e.style.display = 'block';
}

/* CUSTOM FIELDS */

function getcustomfields(item_id) {
  
    // get it
    var customfieldsList = document.getElementById('customfieldsList');
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            customfieldsList.innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET","../admin/custom_fields.php?action=get&item_id=" + item_id, true);
    xmlhttp.send();
    
}

function savecustomfield(item_id) {
    var customfieldKey = document.getElementById('customfieldKey').value;
    var customfieldValue = document.getElementById('customfieldValue').value;
    var customfield = {
        key: customfieldKey,
        val: customfieldValue,
    }
    
    var xmlhttp = new XMLHttpRequest();    
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            getcustomfields(xmlhttp.responseText);
        }
    }        
    xmlhttp.open("POST","../admin/custom_fields.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("action=add&item_id=" + item_id + "&field_key=" + customfieldKey + "&field_value=" + customfieldValue);
    
    document.getElementById('customfieldKey').value = "";
    document.getElementById('customfieldValue').value = "";    
}

function deletecustomfield(field_id) {
    var customfieldsList = document.getElementById('customfieldsList');
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            getcustomfields(xmlhttp.responseText);
        }
    }
    xmlhttp.open("GET","../admin/custom_fields.php?action=delete&field_id=" + field_id, true);
    xmlhttp.send();
}

function setCustomfieldStatus(id,status) {
    var customfields = JSON.parse(localStorage.getItem('customfields'));    
    for (var i = 0; i < customfields.length; i++) {
        if (customfields[i].id == id) {
            customfields[i].status = status;
        }
    }
    localStorage.setItem('customfields', JSON.stringify(customfields));
    getcustomfields();
}

/* CUSTOM FIELDS ENDE */
