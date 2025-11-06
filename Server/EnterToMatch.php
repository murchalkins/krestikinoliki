<?php
error_reporting(0);
$ciphering = "AES-256-CTR";
$encryption_iv = "01234567891234343345673452";
$encryption_key = "prostocode";
$matchname = $_GET['matchname'];
if(file_exists("matches/{$matchname}.txt")){
	$content = file_get_contents("matches/{$matchname}.txt");
	$countsplayer = json_decode($content, true)['players'];
	if(count(explode('|', $countsplayer)) >= 2){
		echo "The match is overcrowded";
	}else{
		$contentarray = json_decode($content, true);
		$arrayplayers = explode('|', $contentarray['players']);
		$id = "";
		$idwords = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@:";
		$lengthid = mt_rand(6, 19);
		for($i = 0; $i < $lengthid; $i++){
           $randindex = mt_rand(0, mb_strlen($idwords));
		   $word = $idwords[$randindex];
		   $id .= $word;           
		}
		array_push($arrayplayers, openssl_encrypt($id, $ciphering, $encryption_key, 0, $encryption_iv));
        $tempik = "";
        foreach($arrayplayers as $value){
           if($tempik == ""){
              $tempik .= $value;
           }else{
             $tempik .= "|{$value}";
           }
        }
		$contentarray['players'] = $tempik;
		if(count(explode('|', $contentarray['players'])) == 1){
		$contentarray['queue'] = openssl_encrypt($id, $ciphering, $encryption_key, 0, $encryption_iv);
	}
	   file_put_contents("matches/{$matchname}.txt", json_encode($contentarray));
		echo $id;
	}
}else{
	echo "Match not found";
}
?>