<?php
error_reporting(0);
$matchname = $_GET['matchname'];
if($matchname == ""){
	echo "You have not entered a match name!";
	return;
}
if(file_exists("matchs/{$matchname}.txt")){
	echo "This match already exists";
}else{
	$arraymatch = array("players" => "", "1" => "", "2" => "", "3" => "", "4" => "", "5" => "", "6" => "", "7" => "", "8" => "", "9" => "");
	file_put_contents("matchs/{$matchname}.txt", json_encode($arraymatch));
	echo "The match was successfully created";
}
?>