<?php

/**
 * @author Manuel Zarat
 */

require_once "../../load.php";

$system = new system();

if( !$system->auth() ) header("Location: ../login.php");

$term = new term();

$parent_taxonomy = $_GET['id'];

$item_id = $_POST['item_id'];

$t = new term();

$item_terms = $t->terms_by_item_id($item_id, $parent_taxonomy);

?>


<fieldset>

  <legend>Terme</legend>

  <div>
    <?php 
    if($item_terms) {
        foreach( $item_terms as $i => $term ) { 
    ?>     
    <input  
    type="checkbox" 
    id="<?php echo "$term[id]"; ?>" 
    value="<?php echo "$term[id]"; ?>"
    checked="checked"     
    onclick="javascript:if(this.checked) { ajaxget('../admin/taxonomy.php','action=add_relation&item_id=<?php echo $item_id; ?>&taxonomy=<?php echo $parent_taxonomy; ?>&term=<?php echo $term['id']; ?>') } else { ajaxget('../admin/taxonomy.php','action=remove_relation&item_id=<?php echo $item_id; ?>&taxonomy=<?php echo $parent_taxonomy; ?>&term=<?php echo $term['id']; ?>') };">    
    <label for="<?php echo "$term[id]"; ?>"><?php echo "$term[name]"; ?></label>
    <br>
    <?php 
        }
    } 
    ?>     
  </div>

    <div id="ajaxterms"></div>
    <p><input type="text" oninput="javascript:ajaxget('../admin/term/ajaxsearch.php', 'item_id=<?php echo $item_id; ?>&taxonomy=<?php echo $parent_taxonomy; ?>&term='+this.value, function( response ) {document.getElementById('ajaxterms').innerHTML = response;});" placeholder="find others"></p>    

</fieldset>