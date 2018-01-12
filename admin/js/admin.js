function delete_item(id) {
	go_on = confirm("Diesen Inhalt wirklich entfernen?");
	if (go_on) {
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
