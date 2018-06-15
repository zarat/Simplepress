<?php

/**
 * Speichert eine leere neue Taxonomie in der DB -> term_taxonomy
 * wird asynchron ueber widget.php aufgerufen.
 * 
 * @author Manuel Zarat
 */

if(!empty($_POST['name'])) { 

    /**
     * speichern
     */
    $name = $_POST['name'];
    $parent = isset($_POST['parent']) ? $_POST['parent'] : 0;    
    $id = $system->query( "insert into term_taxonomy (taxonomy, parent) values ('$name', $parent)" );
	
}

/**
 * Anzeigen
 */

?>

<div class="sp-content">

    <div class="sp-content-item">
    
    <div class="sp-content-item-head"><?php echo $system->_t('item_add'); ?></div>
    
    <div class="sp-content-item-body">
    
        <form id="frm" method="post">
       
            <p>Parent?</p>
            <select name="parent">
            <option value="0" selected="selected">Waehle</option>
            <?php            
            $taxonomy = new taxonomy();
            foreach( $taxonomy->get_existing_taxonomies() as $taxonomy ) {
                echo "<option value='" . $taxonomy['id'] . "'>" . $taxonomy['taxonomy'] . "</option>";
            }  
            
            ?>   
            </select></p>
                    
            <p>Name</p>
            <p><input type="text" name="name"></p>            
            
            <input type="submit" value="speichern">
                           
        </form>
    
    </div>
    
    </div>

</div>

<div style="clear:both;"></div>