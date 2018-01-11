<?php

/**
 * @author Manuel Zarat
 *
 * DataTables hier hergepackt, damit sie nicht auf jeder Seite geladen werden.
 * 
 */

if(isset($_GET['type'])) { 
    $type = $_GET['type']; 
} else {
    die("Error: var 'type' is not set!");
}

?>

<script type="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="./js/jquery.dataTables.js"></script>
<script type="text/javascript" src="./js/jquery.dataTables.min.js"></script>

<link href="./css/jquery.dataTables_themeroller.min.css" type="text/css" rel="stylesheet">
<link href="./css/jquery.dataTables_themeroller.css" type="text/css" rel="stylesheet">
<link href="./css/jquery.dataTables.css" type="text/css" rel="stylesheet">

<script type="text/javascript">
$(document).ready(function() {
    $('table#item_list').dataTable( {
        "paging":   true,
        "ordering": false,
        "info":     true
    } );
} );
</script>

<script src="../admin/js/admin.js"></script>

<div>

<h3><a href="./?page=item_add&type=<?php echo $type; ?>">Neu</a></h3><br>

<?php

echo "<table id=\"item_list\" class=\"display\" cellspacing=\"0\" width=\"100%\">\n
<thead>\n
<tr>\n
<th></th>\n
</tr>\n
</thead>\n 
<tfoot></tfoot>\n    
<tbody>\n";

$cfg = array("select"=>"*","from"=>"object","where"=>"type='$type' ORDER BY id DESC");
$results = $system->archive($cfg);

if(false !== $results) {
    foreach($results as $result){
    
    $status = $result['status'];  
    $id = $result['id'];
      
    echo "\n<tr id='$id'>";      
    echo "<td><strong><a href=\"./?page=item_modify&id=$id\" title=\"$result[keywords]\">$result[title]</a></strong><span style=\"display:none;\">$result[content]</span><br>";       
    echo "<br>";       
    if($status==0) { 
        echo "<span id='item_status_link_$result[id]'><a style='cursor:pointer' onclick=\"update_status($result[id],1)\">aktivieren</a></span>";
        echo " - <a href=\"#\" onClick=\"delete_item('../admin/item_delete.php?id=$result[id]',$result[id])\">entfernen</a>"; 
    } else { 
        echo "<span id='item_status_link_$result[id]'><a style='cursor:pointer' onclick=\"update_status($result[id],0)\">deaktivieren</a></span>";
        echo " - <a href=\"#\" onClick=\"delete_item('../admin/item_delete.php?id=$result[id]',$result[id])\">entfernen</a>";
    }      
    echo "</td>";      
    echo "\n</tr>\n";    
    }
}

echo "</tbody>\n</table>";

?>

    </tbody>
</table>


</div>
