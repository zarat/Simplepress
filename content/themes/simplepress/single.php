<?php

/**
 * @author Manuel Zarat
 */ 
 
while( $archive->have_items() ) {

    $item = $archive->the_item( );
    
    echo "<div class ='sp-content-item'>\n";
    
        echo "<div class ='sp-content-item-head'>" . $item['title'] . "</div>\n";
        echo "<div class ='sp-content-item-head-secondary'>";
        echo date("d.m.Y",$item['date']);
        if( $this->auth() ) {
            echo " - <a href=\"../admin/item.php?action=edit&id=$item[id]\">bearbeiten</a>";
        }
        echo "</div>\n";
    
        echo "<div class ='sp-content-item-body'>" . $item['content'] . "</div>\n";
    
        if( $item_tags = $this->terms( 'category', $item['id'] ) ) {
            foreach( $item_tags as $tag ) { 
                $tags[] = "<a href='../?category=$tag[id]'>$tag[name]</a>"; 
            }
            echo "<div class ='sp-content-item-body'>Kategorien: " . implode(', ', $tags) . "</div>";
        }
 
        if( $matches = $this->relation("category_(\w+)", $item) ) {
            if( in_array( 'Allgemein', $matches[1] ) ) {
                // echo "<div class ='sp-content-item-body'>'Allgemein' ist in Relation zu 'category'</div>";
            }
        }
    
    echo "</div>\n";
    
}

?>
