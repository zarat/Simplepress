<?php

/**
 * Taxonomie bearbeiten
 * 
 * @author Manuel Zarat
 */

if( isset($_GET['id']) ) { 

    $id = $_GET['id'];
    
    if( isset( $_POST['parent'] ) && isset( $_POST['name'] ) ) {
    
        $name = $_POST['name'];
        $parent = $_POST['parent'];
        $system->query( "update term_taxonomy set parent=$parent, taxonomy='$name' where id=$id" );    
    
    }
    
    $ret = $system->fetch_assoc( $system->query( "select * from term_taxonomy where id=$id" ) );
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
       
            <p>Parent Taxonomy?</p>
            <select name="parent">
            <option value="0" selected="selected">Waehle</option>
            <?php            
            $taxonomy = new taxonomy();
            foreach( $taxonomy->taxonomies() as $taxonomy ) {
                if( $ret['parent'] == $taxonomy['id']) { 
                    echo "<option value='" . $taxonomy['id'] . "' selected='selected'>" . $taxonomy['taxonomy'] . "</option>";
                } else {
                    echo "<option value='" . $taxonomy['id'] . "'>" . $taxonomy['taxonomy'] . "</option>";
                }
            }  
            
            ?>   
            </select></p>

            <p>Term</p>
            <p><input type="text" name="name" value="<?php echo $ret['taxonomy']; ?>"></p>            
            
            <input type="submit" value="speichern">
                           
        </form>
    
    </div>
    
    </div>

</div>

<div style="clear:both;"></div>