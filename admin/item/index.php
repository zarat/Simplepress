<?php

/**
 * Zeigt alle Items an
 *
 * Die Datei wird ueber index.php eingebunden deshalb ist $system schon definiert.
 * Asynchron eingebundene Dateien muessen load.php einbinden und auch $system deklarieren!
 *
 * @author Manuel Zarat
 */
if( !$system->auth() ) header("Location: ../login.php");

echo "<script src=\"./js/admin.js\"></script>";
echo "<script type=\"text/javascript\" src=\"./js/jquery.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"./js/jquery.dataTables.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"./js/jquery.dataTables.min.js\"></script>\n";

echo "<link href=\"./css/jquery.dataTables_themeroller.min.css\" type=\"text/css\" rel=\"stylesheet\">\n";
echo "<link href=\"./css/jquery.dataTables_themeroller.css\" type=\"text/css\" rel=\"stylesheet\">\n";
echo "<link href=\"./css/jquery.dataTables.css\" type=\"text/css\" rel=\"stylesheet\">\n";

echo "<script type=\"text/javascript\">\n
$(document).ready(function() { $('table#item_list').dataTable( { 'paging':   true, 'ordering': false, 'info': true } ); } );\n
</script>\n";

echo "<div class=\"sp-content\">\n";
echo "<div class=\"sp-content-item\">\n";
echo "<div class=\"sp-content-item-head\"><a href=\"./item.php?action=add\">Neu</a></div>";
echo "<div class=\"sp-content-item-body\">";

echo "<table id=\"item_list\" class=\"display\" cellspacing=\"0\" width=\"100%\">\n
<thead>\n
<tr>\n
<th></th>\n
</tr>\n
</thead>\n 
<tfoot></tfoot>\n    
<tbody>\n";


$results = $system->fetch_all_assoc( $system->query( "select * from item ORDER BY id DESC" ) );

    foreach($results as $result){
    
    $status = $result['status'];  
    $id = $result['id'];
      
    echo "\n<tr id='$id'>";      
    echo "<td><strong><a href=\"../admin/item.php?action=edit&id=$id\" title=\"$result[keywords]\">$result[title]</a></strong><span style=\"display:none;\">$result[content]</span><br>";       
    echo "<br>";       
    if($status==0) { 
        echo "<span id='item_status_link_$result[id]'><a style='cursor:pointer' onclick=\"update_status($result[id],1)\">aktivieren</a></span>";
        echo " - <a href=\"#\" onClick=\"delete_item($result[id])\">entfernen</a>"; 
    } else { 
        echo "<span id='item_status_link_$result[id]'><a style='cursor:pointer' onclick=\"update_status($result[id],0)\">deaktivieren</a></span>";
        echo " - <a href=\"#\" onClick=\"delete_item($result[id])\">entfernen</a>";
    }      
    echo "</td>";      
    echo "\n</tr>\n";    
    }

echo "</tbody>\n</table>";
echo "</div>";
echo "</div>";
echo "</div>";

?>
