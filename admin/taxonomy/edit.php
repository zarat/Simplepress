<?php

/**
 * Taxonomie bearbeiten
 * 
 * @author Manuel Zarat
 */
if( !$system->auth() ) header("Location: ../login.php");

if( isset($_GET['id']) ) { 

    $id = $_GET['id'];
    
    if( isset( $_POST['name'] ) ) {
    
        $name = $_POST['name'];
        $system->query( "update term_taxonomy set taxonomy='$name' where id=$id" );    
    
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

            <p>Taxonomy Name</p>
            <p><input type="text" name="name" value="<?php echo $ret['taxonomy']; ?>"></p>            
            
            <input type="submit" value="speichern">
                           
        </form>
    
    </div>
    
    </div>

</div>

<div style="clear:both;"></div>
