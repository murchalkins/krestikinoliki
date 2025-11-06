<?php
error_reporting(0);
$ciphering = "AES-256-CTR";
$encryption_iv = "0123456789";
$encryption_key = "prostocode";
$matchname = $_GET['matchname'];
$id = $_GET['id'];
$number = $_GET['number'];
if(file_exists("matches/{$matchname}.txt")){
	$arraymatch = json_decode(file_get_contents("matches/{$matchname}.txt"), true);
	$queueplayer = $arraymatch['queue'];
	if(openssl_decrypt($queueplayer, $ciphering, $encryption_key, 0, $encryption_iv) != $id){
		echo "It's not your queue";
		return;
	}
	$players = $arraymatch['players'];
	if(array_search($id, explode(';', openssl_decrypt($players, $ciphering, $encryption_key, 0, $encryption_iv))) == 0){
	   if($arraymatch[$number] != ""){
		   echo "The slot is occupied";
	   }else{
		   $arraymatch[$number] = "o";
		   file_put_contents("matches/{$matchname}.txt", json_encode($arraymatch));
		   echo "Done";
	   }
	}
	if(array_search($id, explode(';', openssl_decrypt($players, $ciphering, $encryption_key, 0, $encryption_iv))) == 1){
	   if($arraymatch[$number] != ""){
		   echo "The slot is occupied";
	   }else{
		   $arraymatch[$number] = "x";
		   file_put_contents("matches/{$matchname}.txt", json_encode($arraymatch));
		   echo "Done";
	   }
	}
}else{
	echo "The match does not exist";
}
?>