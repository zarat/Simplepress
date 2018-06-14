<?php

/**
 * @author Manuel Zarat
 */

if(!empty($_POST['name'])) { 

    $name = $_POST['name'];
    $parent = $_POST['parent'] ? $_POST['parent'] : 0;
    /**
     * Neue Taxonomie speichern
     */
    $ret = $system->query( "insert into term_taxonomy (taxonomy, parent) values ('$name', $parent)" );
    print_r($ret);
	
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
       
<?php

$tax = new taxonomy();

echo "<select name='parent'>";
    foreach( $tax->get_existing_taxonomies() as $taxonomy ) {
        echo "<option value='" . $taxonomy['id'] . "'>" . $taxonomy['taxonomy'] . "</option>";
    }  
echo "</select>";

?>           
            <p>Taxonomy</p>
            <p><input type="text" name="name"></p>            
            
            <input type="submit" value="speichern">
                           
        </form>
    
    </div>
    
    </div>

</div>

<div style="clear:both;"></div>