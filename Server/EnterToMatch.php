<?php
$matchname = $_GET['matchname'];
if(file_exists("matchs/{$matchname}.txt")){
	$content = file_get_contents("matchs/{$matchname}.txt");
	$countsplayer = json_decode($content, true)['players'];
	if(count(explode(';', $countsplayer)) - 1 >= 2){
		echo "The match is overcrowded";
	}else{
		$contentarray = json_decode($content, true);
		$arrayplayers = explode(';', $contentarray['players']);
		$id = "";
		$idwords = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@:";
		$lengthid = mt_rand(6, 19);
		for($i = 0; $i < $lengthid; $i++){
           $randindex = mt_rand(0, mb_strlen($idwords));
		   $word = $idwords[$randindex];
		   $id .= $word;           
		}
		array_push($arrayplayers, $id);
		$contentarray['players'] = implode(';', $arrayplayers);
		file_put_contents("matchs/{$matchname}.txt", json_encode($contentarray));
		echo $id;
	}
}else{
	echo "Match not found";
}
?>