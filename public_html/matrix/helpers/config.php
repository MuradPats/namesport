<?php

/* * *** CONFIGURABLE VARIABLES **** */

//Base url for assets and link
$baseURL = "/matrix/";
$fullURL = "http://namesport.co.uk/matrix/";

//Debug - true/false
$debug = false;

//Pages available
$pages = array(
    "Pallurid" => "ballers"
);

//Allow auth list
$allowAuth = array("mart.randala@gmail.com", "regdyn@gmail.com");

//DB Connection
define('DB_NAME', 'namesport');
define('DB_USER', 'namesport');
define('DB_PASSWORD', 'c542885238');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');

//Picture locations
$orig_img_url = "images/";
$crop_img_url = "cropped/";

/* * *** DO NOT EDIT **** */

// Show debug messages in-browser
if ($debug) {
    ini_set('display_errors', 'stderr');
}

mb_internal_encoding("UTF-8");
error_reporting(E_ALL);

//Parse URL to parts
$UE = array_diff(explode("/", (isset($_SERVER["PATH_INFO"]) ? $_SERVER["PATH_INFO"] : "")), array(""));
?>