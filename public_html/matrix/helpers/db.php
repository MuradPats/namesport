<?php

mb_internal_encoding("UTF-8");

$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Ei suutnud luua ühendust andmebaasiga, proovi mõne hetke pärast uuesti");
mysqli_query($db, "SET NAMES ".DB_CHARSET);

?>