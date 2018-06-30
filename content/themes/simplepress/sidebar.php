<?php

/**
 * @author Manuel Zarat 
 */

echo "<div class='sp-sidebar-item'>";
    echo "<div class='sp-sidebar-item-head'>Suche</div>";
    echo "<div class='sp-sidebar-item-box'>\n";
        echo "<div class='sp-sidebar-item-box-body'><div class='container'><form><input type='text' name='search'></form></div></div>\n";
    echo "</div>\n";
echo "</div>";

$cat_query = "
select term.id,term.name from term
join term_relation tr on tr.term_id=term.id
where tr.taxonomy_id=(select id from term_taxonomy where taxonomy='category')
and tr.term_id=term.id
group by term.id
";
$the_cats = $this->fetch_all_assoc( $this->query( $cat_query ) );

echo "<div class='sp-sidebar-item'>";
    echo "<div class='sp-sidebar-item-head'>Kategorien</div>";
    echo "<div class='sp-sidebar-item-box'>\n";
    foreach( $the_cats as $id => $cat ) {
    
        echo "<div class='sp-sidebar-item-box-head'><a href='../?category=$cat[id]'>" . $cat['name'] . "</a></div>";
    
    }
    echo "</div>\n";
echo "</div>";

?>
