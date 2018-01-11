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

$channel_url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$item_url = str_replace(basename(__file__), '', $channel_url);

echo "<channel>";
echo "<title>" . $system->settings('site_title') . " > Updates</title>";
echo "<link>" . $channel_url . "</link>";
echo "<description>" . html_entity_decode($system->settings('site_description')) . "</description>";

$cfg = array("select"=>"*","from"=>"object","where"=>"type='post' AND status=1 ORDER BY id DESC");
$rss = $system->archive($cfg);
    
foreach($rss as $row)    {
    
    echo "<item>";
    echo "<title>" . html_entity_decode($row['title']) . "</title>";
    echo "<link>" . $item_url . "?type=" . $row['type'] . "&id=" . $row['id'] . "</link>";
    echo "<description>" . substr(strip_tags(html_entity_decode($row['content'])),0,320) . "</description>";
    echo "<pubDate>" . date(DATE_RFC822, strtotime(gmdate("Y-m-d\TH:i:s\Z", $row['date']))) . "</pubDate>"; /** moderne RSS Feeds haben RFC822 konformes Datum! */
    echo "</item>";

}

echo "</channel>";
echo "</rss>";

?>
