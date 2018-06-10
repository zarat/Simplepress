<?php 

/**
 * Simplepress RSS 2.0 Feed
 *
 * @author Manuel Zarat
 * @version 0.2.0
 * @link https://github.com/zarat/simplepress   
 * @since 06/2018 
 */

header("Content-type: text/xml");

require_once "load.php";

echo "<?xml version=\"1.0\" encoding=\"utf-8\" " . "?" . ">";
echo "<rss version=\"2.0\">";

$system = new system(); 

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$channel_url = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$item_url = str_replace(basename(__file__), '', $channel_url);

echo "<channel>";
echo "<title>" . $system->settings('site_title') . " > Updates</title>";
echo "<link>" . $channel_url . "</link>";
echo "<description>" . html_entity_decode($system->settings('site_description')) . "</description>";

$cfg = array("select"=>"*","from"=>"item","where"=>"type='post' AND status=1 ORDER BY id DESC");
$rss = $system->archive($cfg);
    
foreach($rss as $row)    {
    
    echo "<item>";
    echo "<title>" . html_entity_decode($row['title']) . "</title>";
    echo "<link>" . htmlspecialchars($item_url . "?type=" . $row['type'] . "&id=" . $row['id']) . "</link>";
    echo "<description>" . htmlspecialchars( preg_replace("/[^ ]*$/", '', substr( strip_tags( html_entity_decode( $row['content'] ) ) , 0, 150) ) ) . "</description>";
    echo "<pubDate>" . date('r', $row['date']) . "</pubDate>"; /** moderne RSS Feeds haben RFC822 konformes Datum! */
    echo "</item>";

}

echo "</channel>";
echo "</rss>";

?>
