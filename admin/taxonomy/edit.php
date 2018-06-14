<?php

/**
 * Taxonomie bearbeiten
 * 
 * @author Manuel Zarat
 */

if( isset($_GET['id']) ) { 

    $id = $_GET['id'];
    $ret = $system->fetch_all_assoc( $system->query( "select * from term_taxonomy where id=$id" ) );
    
}

/**
 * Anzeigen
 */

?>

<div class="sp-content">

    <div class="sp-content-item">
    
    <div class="sp-content-item-head">Taxonomie editiren</div>
    
    <div class="sp-content-item-body">
    
        <form id="frm" method="post">
       
        <?php
            echo "<pre>";
            print_r($ret);
        	echo "</pre>";
        ?>
                           
        </form>
    
    </div>
    
    </div>

</div>

<div style="clear:both;"></div>