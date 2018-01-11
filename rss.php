<?php 

/**
 * Simplepress RSS Feed
 *
 * @author Manuel Zarat
 * 
 */

header("Content-type: text/xml");

echo "<?xml version=\"1.0\" encoding=\"utf-8\" " . "?" . ">";
echo "<rss version=\"2.0\">";

include "load.php";

$system = new system(); 

$channel_url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$item_url = str_replace(basename(__file__), '', $channel_url);

echo "<channel>\n";
echo "<title>" . $system->settings('site_name') . " > Updates</title>\n";
echo "<link>" . $channel_url . "</link>\n";
echo "<description>" . html_entity_decode($system->settings('site_description')) . "</description>\n";

$cfg = array("select"=>"*","from"=>"object","where"=>"type='post' AND status=1 ORDER BY id DESC");
$rss = $system->archive($cfg);
    
foreach($rss as $row)    {
    
    echo "<item>\n";
    echo "<title>" . htmlspecialchars(html_entity_decode($row['title'])) . "</title>\n";
    echo "<link>" . htmlspecialchars($item_url . "?type=post&id=" . $row['id']) . "</link>\n";
    echo "<description>" . substr(strip_tags(html_entity_decode($row['content'])),0,320) . "</description>\n";
    echo "<pubDate>" . gmdate("Y-m-d\TH:i:s\Z", $row['date']) . "</pubDate>\n";
    echo "</item>\n";

}

echo "</channel";
echo "</rss>";

?>
