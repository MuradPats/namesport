<?php

include 'dbh.php';

$name = $_POST['name'];
$score = $_POST['score'];
$contact = $_POST['mail'];


$sql = "INSERT INTO scores (contact, name, score) VALUES ($contact,'$name','$score')
ON DUPLICATE KEY UPDATE score='$score'";

$result = $conn1->query($sql);