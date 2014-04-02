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
	$ipArray='129.82.';
	if (strpos($_SERVER['REMOTE_ADDR'], $ipArray)===0) {
			return 0;
	}else{
			return 1;
	}
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
?>