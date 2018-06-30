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
        $system->query( "update term set name='$name' where id=$id" );    
    
    }
    
    $ret = $system->fetch_assoc( $system->query( "select * from term where id=$id" ) );
}

/**
 * Anzeigen
 */

?>

<div class="sp-content">

    <div class="sp-content-item">
    
    <div class="sp-content-item-head">Term editiren</div>
    
    <div class="sp-content-item-body">
    
        <form id="frm" method="post">

            <p>Term Name</p>
            <p><input type="text" name="name" value="<?php echo $ret['name']; ?>"></p>            
            
            <input type="submit" value="speichern">
                           
        </form>
    
    </div>
    
    </div>

</div>

<div style="clear:both;"></div>
