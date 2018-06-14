<?php

/**
 * @author Manuel Zarat
 */

if(!empty($_POST['name'])) { 

    $name = $_POST['name'];
    
    /**
     * Neuen Term speichern
     */
    $id = $system->insert( array( "insert" =>"term (name)", "values" => "('$name')" ) );
	
}

/**
 * Anzeigen
 */

?>

<div class="sp-content">

    <div class="sp-content-item">
    
    <div class="sp-content-item-head">Add a term</div>
    
    <div class="sp-content-item-body">
    
        <form id="frm" method="post">

            <p>Term</p>
            <p><input type="text" name="name"></p>            
            
            <input type="submit" value="speichern">
                           
        </form>
    
    </div>
    
    </div>

</div>

<div style="clear:both;"></div>