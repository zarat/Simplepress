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

$tax = new taxonomy();
$term = new term();

$taxonomy_terms = $term->terms_by_taxonomy_id( $parent_taxonomy );
$item_terms = $term->terms_by_item_id($item_id, $parent_taxonomy);

?>


<fieldset>
  <legend>Choose Terms</legend>

<?php foreach( $taxonomy_terms as $i => $term ) { ?>

  <div>
    
    <input  
    type="checkbox" 
    id="<?php echo "$term[id]"; ?>" 
    value="<?php echo "$term[id]"; ?>"
    <?php 
    if($item_terms) {
        foreach( $item_terms as $i => $existing_term ) { 
            if($existing_term['term_id'] == $term['id'] ) { 
            echo "checked='checked'";
            }
        }
    } 
    ?>      
    onclick="javascript:if(this.checked) { ajaxget('../admin/taxonomy.php','action=add_relation&item_id=<?php echo $item_id; ?>&taxonomy=<?php echo $parent_taxonomy; ?>&term=<?php echo $term['id']; ?>') } else { ajaxget('../admin/taxonomy.php','action=remove_relation&item_id=<?php echo $item_id; ?>&taxonomy=<?php echo $parent_taxonomy; ?>&term=<?php echo $term['id']; ?>') };">    
    <label for="<?php echo "$term[id]"; ?>"><?php echo "$term[name]"; ?></label>
    
  </div>

<?php } ?>

</fieldset>
