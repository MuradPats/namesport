<?php
//ini_set('display_errors', 'stderr');

mb_internal_encoding("UTF-8");
error_reporting(E_ALL);

require("/var/www/namesport.co.uk/public_html/matrix/oauth2.php");

$UE = array_diff(explode("/",(isset($_SERVER["PATH_INFO"])?$_SERVER["PATH_INFO"]:"")), array(""));
$pages =array("images");
if(!empty($UE)) $page = $UE[1]; else $page = $pages[0];

mb_internal_encoding("UTF-8");
$db=mysqli_connect("localhost", "namesport", "c542885238", "namesport") or die("Ei suutnud luua ühendust andmebaasiga, proovi mõne hetke pärast uuesti");
mysqli_query($db, "SET NAMES utf8");

require("functions.inc");
printHeader();

echo "<span class='lrg'>".$_SESSION["email"]." ";
foreach($pages as $p) if($p != "kliendid") echo "<a href='/soleil/$p'>$p</a> ";
echo "<a href='?login=logout'>logi välja</a></span><div id='intra'>";

if(is_file("$page.inc")) require("$page.inc");

echo "</div>";

printFooter();

?>