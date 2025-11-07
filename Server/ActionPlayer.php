<?php
error_reporting(0);
$ciphering = "AES-256-CTR";
$encryption_iv = "01234567891234343345673452";
$encryption_key = "prostocode";
$matchname = $_GET['matchname'];
$id = $_GET['id'];
$number = $_GET['number'];
if(file_exists("matches/{$matchname}.txt")){
	if($number != "1" && $number != "2" && $number != "3" && $number != "4" && $number != "5" && $number != "6" && $number != "7" && $number != "8" && $number != "9"){
		echo "Wrong number";
		return;
	}
	$arraymatch = json_decode(file_get_contents("matches/{$matchname}.txt"), true);
	if($arraymatch['ended'] == "true"){
		echo "Game ended";
		return;
	}
	$queueplayer = $arraymatch['queue'];
	if(openssl_decrypt($queueplayer, $ciphering, $encryption_key, 0, $encryption_iv) != $id){
		echo "It's not your queue";
		return;
	}
	$players = $arraymatch['players'];
	if(openssl_decrypt(explode('|', $players)[0], $ciphering, $encryption_key, 0, $encryption_iv) == $id){
	   if($arraymatch[$number] != ""){
		   echo "The slot is occupied";
	   }else{
		   $arraymatch[$number] = "o";
		   $arraymatch['queue'] = explode('|', $arraymatch['players'])[1];
		   file_put_contents("matches/{$matchname}.txt", json_encode($arraymatch));
		   echo "Done";
	   }
	}
	if(openssl_decrypt(explode('|', $players)[1], $ciphering, $encryption_key, 0, $encryption_iv) == $id){
	   if($arraymatch[$number] != ""){
		   echo "The slot is occupied";
	   }else{
		   $arraymatch[$number] = "x";
		   $arraymatch['queue'] = explode('|', $arraymatch['players'])[0];
		   file_put_contents("matches/{$matchname}.txt", json_encode($arraymatch));
		   echo "Done";
	   }
	}
	$winner = "";
	$arraymatchafter = json_decode(file_get_contents("matches/{$matchname}.txt"), true);
	$pos1 = $arraymatchafter['1'];
	$pos2 = $arraymatchafter['2'];
	$pos3 = $arraymatchafter['3'];
	$pos4 = $arraymatchafter['4'];
	$pos5 = $arraymatchafter['5'];
	$pos6 = $arraymatchafter['6'];
	$pos7 = $arraymatchafter['7'];
	$pos8 = $arraymatchafter['8'];
	$pos9 = $arraymatchafter['9'];
	if($pos1 == "o" && $pos5 == "o" && $pos9 == "o"){
		$winner = "o";
	}
	if($pos3 == "o" && $pos5 == "o" && $pos7 == "o"){
		$winner = "o";
	}
    if($pos1 == "o" && $pos4 == "o" && $pos7 == "o"){
		$winner = "o";
	}
	if($pos2 == "o" && $pos5 == "o" && $pos8 == "o"){
		$winner = "o";
	}
	if($pos3 == "o" && $pos6 == "o" && $pos9 == "o"){
		$winner = "o";
	}
	if($pos1 == "o" && $pos2 == "o" && $pos3 == "o"){
		$winner = "o";
	}
	if($pos4 == "o" && $pos5 == "o" && $pos6 == "o"){
		$winner = "o";
	}
	if($pos7 == "o" && $pos8 == "o" && $pos9 == "o"){
		$winner = "o";
	}

	if($pos1 == "x" && $pos5 == "x" && $pos9 == "x"){
		$winner = "x";
	}
	if($pos3 == "x" && $pos5 == "x" && $pos7 == "x"){
		$winner = "x";
	}
    if($pos1 == "x" && $pos4 == "x" && $pos7 == "x"){
		$winner = "x";
	}
	if($pos2 == "x" && $pos5 == "x" && $pos8 == "x"){
		$winner = "x";
	}
	if($pos3 == "x" && $pos6 == "x" && $pos9 == "x"){
		$winner = "x";
	}
	if($pos1 == "x" && $pos2 == "x" && $pos3 == "x"){
		$winner = "x";
	}
	if($pos4 == "x" && $pos5 == "x" && $pos6 == "x"){
		$winner = "x";
	}
	if($pos7 == "x" && $pos8 == "x" && $pos9 == "x"){
		$winner = "x";
	}
	if($winner == "x"){
		$arraymatchafter['ended'] = "true";
		$arraymatchafter['winner'] = openssl_decrypt(explode('|', $arraymatchafter['players'])[1], $ciphering, $encryption_key, 0, $encryption_iv);
		file_put_contents("matches/{$matchname}.txt", json_encode($arraymatchafter));
		return;
	}
	if($winner == "o"){
		$arraymatchafter['ended'] = "true";
		$arraymatchafter['winner'] = openssl_decrypt(explode('|', $arraymatchafter['players'])[0], $ciphering, $encryption_key, 0, $encryption_iv);
		file_put_contents("matches/{$matchname}.txt", json_encode($arraymatchafter));
		return;
	}
	if($pos1 != "" && $pos2 != "" && $pos3 != "" && $pos4 != "" && $pos5 != "" && $pos6 != "" && $pos7 != "" && $pos8 != "" && $pos9 != ""){
		if($winner == ""){
			$arraymatchafter['ended'] = "true";
			file_put_contents("matches/{$matchname}.txt", json_encode($arraymatchafter));
		}
	}
}else{
	echo "The match does not exist";
}
?>