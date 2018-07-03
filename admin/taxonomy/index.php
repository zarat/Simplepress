<?php if( !$system->auth() ) header("Location: ../login.php"); ?>
<script src="./js/admin.js"></script>
<script type="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="./js/jquery.dataTables.js"></script>
<script type="text/javascript" src="./js/jquery.dataTables.min.js"></script>

<link href="./css/jquery.dataTables_themeroller.min.css" type="text/css" rel="stylesheet">
<link href="./css/jquery.dataTables_themeroller.css" type="text/css" rel="stylesheet">
<link href="./css/jquery.dataTables.css" type="text/css" rel="stylesheet">

<script type="text/javascript">
$(document).ready(function() { 
    $('table#item_list').dataTable( { 'paging':   true, 'ordering': false, 'info': true } ); 
});
</script>

<div class="sp-content">
<div class="sp-content-item">
<div class="sp-content-item-head"><a href="../admin/taxonomy.php?action=add">Neu</a></div>
<div class="sp-content-item-body">

<table id="item_list" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
    <th></th>
    </tr>
    </thead>
    <tfoot></tfoot>
    <tbody>
    <?php

    $taxonomies = $system->taxonomies();
    if( $taxonomies ) {
        foreach( $taxonomies as $taxonomy){      
            echo "\n<tr>";      
            echo "<td id=\"$taxonomy[id]\">$taxonomy[taxonomy] - <a href=\"../admin/taxonomy.php?action=edit&id=$taxonomy[id]\">edit</a> <a href=\"#\" onclick=\"javascript:delete_taxonomy($taxonomy[id])\">delete</a></td>";      
            echo "\n</tr>\n";    
        }
    }
    ?>
</tbody>
</table> 

<script>
/**
 * Zeile ausblenden nachdem die Taxonomie entfernt wurde
 */
function delete_taxonomy( id ) {
    confirmed = confirm("Diese Taxonomie wirklich entfernen?");
	if (confirmed) {
        ajaxget( '../admin/taxonomy/delete.php', 'taxonomy_id='+id);
        document.getElementById( id ).style.display = "none";
    }    
}
</script>       
        
</div>        
</div>
</div>

<?php include "footer.php"; ?>
