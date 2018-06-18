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
<div class="sp-content-item-head"><a href="../admin/term.php?action=add">Neu</a></div>
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
    $term = new term();
    $terms = $term->terms();    
    if( $terms ) {
        foreach( $terms as $term){      
            echo "\n<tr>";      
            echo "<td>$term[name] <a href='../admin/term.php?action=edit&id=$term[id]'>edit</a></td>";      
            echo "\n</tr>\n";    
        }
    }
    ?>
</tbody>
</table>        
        
</div>        
</div>
</div>

<?php include "footer.php"; ?>
