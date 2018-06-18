<?php

/**
 * @author Manuel Zarat
 */

if(!empty($_POST['name'])) { 

    $name = $_POST['name'];
    $taxonomy = $_POST['taxonomy'];
    /**
     * Neuen Term speichern
     */
    $id = $system->insert( array( "insert" =>"term (name, taxonomy_id)", "values" => "('$name', $taxonomy)" ) );
	
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
        
            <p>Parent Taxonomy?</p>
            <select name="taxonomy">
            <option value="0" selected="selected">Waehle</option>
            <?php            
            $taxonomy = new taxonomy();
            foreach( $taxonomy->taxonomies() as $taxonomy ) {
                echo "<option value='" . $taxonomy['id'] . "'>" . $taxonomy['taxonomy'] . "</option>";
            }  
            
            ?>   
            </select></p>

            <p>Term</p>
            <p><input type="text" name="name"></p>            
            
            <input type="submit" value="speichern">
                           
        </form>
    
    </div>
    
    </div>

</div>

<div style="clear:both;"></div>