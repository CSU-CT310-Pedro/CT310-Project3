<?php

function getUsers(){//returns an array of users
	$db = new PDO('sqlite:./users.db');
	$list = array();
	$query = "SELECT userName FROM users;";
	$users = $db->query($query);
	foreach ($users as $user){
		array_push($list, $user[0]);
	}
	return $list;
}

$users = getUsers();

function IpCheck(){
	$ipArray=array('129.82.', '65.128.28.114');
	$ok = 1;//assume wrong ip until proven false
	foreach($ipArray as $ip){
		if (strpos($_SERVER['REMOTE_ADDR'], $ip)===0) {
				return 0;
		}else{
				$ok=1;
		}
	}
	return $ok;
}

function getImageURL($user){
	$db = new PDO('sqlite:./users.db');
	$query = "SELECT imageurl FROM users WHERE userName='$user';";
	$source = $db->query($query);
	return $source;
}

function getUsersFriends($user){//gets the usernames of all friends of $user
	$usersFriends=array();
	$db = new PDO('sqlite:./users.db');
	$query = "SELECT user2 FROM friends WHERE user1='$user';";	
	$friends = $db->query($query);
	foreach($friends as $newUser){
		array_push($usersFriends, $newUser);
	}
	return $usersFriends;
}

function generateRandomString($length = 32) {
   return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

function authenticate($user, $email){
	$db = new PDO('sqlite:./users.db');
	$query = "SELECT key FROM pending WHERE userName='$user';";
	$oKey = $db->query($query);
	$key = '';
	foreach($oKey as $k){
		$key = $k[0];
	}

	$message = 'To complete your registration 
	<a href="http://www.cs.colostate.edu/~bckelly1/Project3/addUser.php?user='.$user.
	'&random='.$key.'"> click here.</a> You must be clicking this link 
	from the IP address you used to register.';
	
	$message = wordwrap($message, 70, "\r\n");

	$subject = "Two Factor Authentication";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	mail($email, $subject, $message, $headers);
}

?>