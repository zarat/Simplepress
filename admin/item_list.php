<script src="../admin/js/admin.js"></script>

<?php if(isset($_GET['type'])) { $type = $_GET['type']; } ?>

<div>

<h3>Alle <?php echo $type; ?> - <a href="./?page=item_add&type=<?php echo $type; ?>">Neu</a></h3><br>

<?php

$i=0;

$cfg = array("select"=>"*","from"=>"object","where"=>"type='$type' ORDER BY id DESC");
$results = $system->archive($cfg);

?>

<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
        </tr>
    </thead> 
    <tfoot></tfoot>    
    <tbody>

<?php
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
}else { 
    echo "<span id='item_status_link_$result[id]'><a style='cursor:pointer' onclick=\"update_status($result[id],0)\">deaktivieren</a></span>";
    echo " - <a href=\"#\" onClick=\"delete_item('../admin/item_delete.php?id=$result[id]',$result[id])\">entfernen</a>";
}
  
echo "</td>";
  
echo "\n</tr>\n";

}
}
?>

    </tbody>
</table>


</div>
