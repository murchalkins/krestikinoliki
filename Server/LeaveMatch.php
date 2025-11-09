<?php
error_reporting(0);
$ciphering = "AES-256-CTR";
$encryption_iv = "01234567891234343345673452";
$encryption_key = "prostocode";
$matchname = $_GET['matchname'];
$id = $_GET['id'];
if(file_exists("matches/{$matchname}.txt")){
	$content = file_get_contents("matches/{$matchname}.txt");
	$contentarray = json_decode($content, true);
	$playersarray = explode('|', $contentarray['players']);
	$decryptedplayerarray = "";
	foreach($playersarray as $value){
		$value2 = openssl_decrypt($value, $ciphering, $encryption_key, 0, $encryption_iv);
		if($decryptedplayerarray == ""){
		$decryptedplayerarray .= $value2;
		}else{
			$decryptedplayerarray .= "|{$value2}";
		}
	}
	$decryptedplayerarray2 = explode('|', $decryptedplayerarray);
	if(in_array($id, $decryptedplayerarray2)){
        $indexik = array_search($id, $decryptedplayerarray2);
		unset($decryptedplayerarray2[$indexik]);
        $tempik = "";
        foreach($decryptedplayerarray2 as $value){
           $valueciphered = openssl_encrypt($value, $ciphering, $encryption_key, 0, $encryption_iv);
           if($tempik == ""){
              $tempik .= $valueciphered;
           }else{
             $tempik .= "|{$valueciphered}";
           }
        }
        if(count(explode('|', $contentarray['players'])) == 2){
			$contentarray['started'] = "false";
			$contentarray['ended'] = "true";
		}else{
			$contentarray['queue'] = "";
		}
		$contentarray['players'] = $tempik;
        if(openssl_decrypt($contentarray['queue'], $ciphering, $encryption_key, 0, $encryption_iv) == $id){
            $contentarray['queue'] = $contentarray['players'];    
        }
		file_put_contents("matches/{$matchname}.txt", json_encode($contentarray));
		echo "You leaved match";
	}else{
		echo "Your ID not found on this match";
	}
}else{
	echo "Match not found";
}
?>