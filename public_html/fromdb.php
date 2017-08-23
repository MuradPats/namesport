<?php

include 'dbh.php';

$sql = "SELECT name, score FROM scores ORDER BY score DESC LIMIT 10 ";
$result = mysqli_query($conn1, $sql);
if (mysqli_num_rows($result) > 0) {
	$data = array(); 
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("name"=>$row['name'], "score"=>$row['score']);
		$post_data = json_encode(array($data));
	    /*	
		$post_data = json_encode(array("name"=>$row['name'], "score"=>$row['score']));		*/
	}
	echo $post_data;
}
