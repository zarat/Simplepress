<?php

echo "<div class='sidebar'>";

echo "<ul>";
foreach($system->archive($select="*",$from="object",$where="type='category'") as $cat) {
    echo "<li><a href='../?type=category&id=$cat[id]'>$cat[title]</a></li>";
}
echo "</ul>";

echo "</div>";

?>
