<?php

require_once '/var/www/lib/OAuth2/vendor/autoload.php';

use ohmy\Auth1;
use ohmy\Auth2;

if (isset($_GET["login"]) and in_array($_GET["login"], array("google", "logout")))
    $login = $_GET["login"];
//else die("wtf");
session_start();


/* $u["facebook"]=array(
  'id'       => '891246477558785',
  'secret'   => 'd8f994e785100c8c8291c52fb6520e31',
  'redirect' => 'https://www.monifor.com/client/?login=facebook',
  'scope'    => array('public_profile','email'),
  'auth'     => "https://www.facebook.com/v2.0/dialog/oauth",
  'token'    => 'https://graph.facebook.com/oauth/access_token',
  'URL'      => "https://graph.facebook.com/me/?access_token="
  ); */
$u["google"] = array(
    'id' => '411413612793-hacs81cd3m7kk8o1ckun7l129p707b1v.apps.googleusercontent.com',
    'secret' => 'X24mMascZc6l8aaapcU05r_C',
    'redirect' => 'http://namesport.co.uk/matrix/?login=google',
    'scope' => 'email',
    'auth' => 'https://accounts.google.com/o/oauth2/auth',
    'token' => 'https://accounts.google.com/o/oauth2/token',
    'URL' => "https://www.googleapis.com/plus/v1/people/me?access_token="
);
if (isset($login) && $login == "logout") {
    session_destroy();
    $cookieParams = session_get_cookie_params();
    setcookie(session_name(), '', 0, $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure'], $cookieParams['httponly']);
    $_SESSION = array();

    header("Location: http://namesport.co.uk/matrix");
    die();
}
if (isset($login)) {
    # the following line does the authentication heavy-lifting 
    $a = Auth2::legs(3)->set(array_slice($u[$login], 0, 4))->authorize($u[$login]["auth"])->access($u[$login]["token"])->finally(function($data) use(&$access_token) {
        $access_token = $data['access_token'];
    });

    # let's parse response!
    $a->GET($u[$login]["URL"] . $access_token)->then(function($response) {
        global $login;
        $j = $response->json();
        error_log(print_r($j, true));
        switch ($login) {
            case "facebook": {
                    $id = $j["id"];
                    $name = $j["name"];
                    $email = $j["email"];
                    break;
                }
            case "google": {
                    $id = $j["id"];
                    $name = $j["displayName"];
                    $email = $j["emails"][0]["value"];
                    break;
                }
            case "yandex": {
                    $id = $j["id"];
                    $name = $j["real_name"];
                    $email = $j["default_email"];
                    break;
                }
            case "vk": {
                    $id = $j["response"][0]["uid"];
                    $name = $j["response"][0]["first_name"] . " " . $j["response"][0]["last_name"];
                    $email = "";
                    break;
                }
            case "live": {
                    $id = $j["id"];
                    $name = $j["name"];
                    $email = $j["emails"]["preferred"];
                    break;
                }
        }
        $_SESSION["id"] = $id;
        $_SESSION["name"] = $name;
        $_SESSION["email"] = $email;
        $_SESSION["type"] = $login;
        //maybe implement account creation and merging here? So we can avoid setting global variables.
        //echo '<pre>'; var_dump($response->json()); echo '</pre>';
        // kui kasutajat pole varem olnud, siis lisame kasutajate tabelisse; kui on olnud, uuendame kirjet
    });
    if (in_array($_SESSION["email"], $allowAuth))
        header("Location: " . $fullURL);
    else
        header("Location: " . $fullURL . "?login=logout");
}

if (!isset($_SESSION["id"])) {
    require("content/login.php");
    exit();
}
?>
 
