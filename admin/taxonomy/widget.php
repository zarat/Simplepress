<?php

/**
 * @author Manuel Zarat
 */

require_once "../../load.php";

//$system = new system();
$term = new term();

$parent_taxonomy = $_GET['id'];
$item_id = $_POST['item_id'];

$tax = new taxonomy();
$get_taxonomy_terms_an_item_belongs_to = $tax->get_taxonomy_terms_an_item_belongs_to( $item_id, $parent_taxonomy );

echo "<pre>";
//print_r($get_taxonomy_terms_an_item_belongs_to);
echo "</pre>";
?>


<fieldset>
  <legend>Choose Terms</legend>

<?php foreach( $term->get_existing_terms() as $term ) { ?>

  <div>
    
    <input  
    type="checkbox" 
    id="<?php echo "$term[id]"; ?>" 
    value="<?php echo "$term[id]"; ?>"
    <?php /** markiere die checkbox als aktiv, wenn der term bereits verlinkt ist */ 
    if($get_taxonomy_terms_an_item_belongs_to) { foreach($get_taxonomy_terms_an_item_belongs_to as $a) { if($a['name'] == $term['name']) { echo " checked=\"checked\" "; }; } } ?>       
    onclick="javascript:if(this.checked) { ajaxget('../admin/taxonomy.php','action=add_relation&item_id=<?php echo $item_id; ?>&taxonomy=<?php echo $parent_taxonomy; ?>&term=<?php echo $term['id']; ?>') } else { ajaxget('../admin/taxonomy.php','action=remove_relation&item_id=<?php echo $item_id; ?>&taxonomy=<?php echo $parent_taxonomy; ?>&term=<?php echo $term['id']; ?>') };">    
    <label for="<?php echo "$term[id]"; ?>"><?php echo "$term[name]"; ?></label>
    
  </div>

<?php } ?>

</fieldset>