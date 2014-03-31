<?php

function csv_to_array($filename='', $delimeter="\t"){
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header =NULL;
    $data = array();
    if(($handle = fopen($filename, 'r')) !== FALSE){
        while(($row = fgetcsv($handle, 1000, $delimeter)) !== FALSE){
            if(!$header)
                $header =$row;
            else if(count($row)==count($header)){
                $data[] = array_combine($header, $row);
            }
        }
        fclose($handle);
    }
    #print_r($data);
    return $data;
}

function getUsers($userList){
    $users = array();
    foreach($userList as $userArray){
        $users[]= $userArray["user"];
    }
    #print_r($users);
    return $users;
}

function getFriends($users, $friendsList){
    $userFriends = array();
    foreach($users as $user){
        $userFriends[$user]= array();
    }
    #print_r($userFriends);
    foreach($friendsList as $friend){
        $user1= $friend["user1"];
        $user2= $friend["user2"];
        $userFriends[$user1][] = $user2;
        #echo $user1."=>".$user2."<br/>";
    }
    #print_r($userFriends);
    return $userFriends;
}

function getRequests($users, $requestsList){
    $userRequests = array();
    foreach($users as $user){
        $userRequests[$user]= array();
    }
    #print_r($userFriends);
    foreach($requestsList as $request){
        $user1= $request["user1"];
        $user2= $request["user2"];
        $userRequests[$user2][] = $user1;
        #echo $user1."=>".$user2."<br/>";
    }
    #print_r($userFriends);
    return $userRequests;
}


$userList = csv_to_array("users.tsv");
#echo "UserList: "; print_r($userList); echo "<br/>";
$friendsList = csv_to_array("friends.tsv");
#echo "FriendsList: "; print_r($friendsList); echo "<br/>";
$requestsList = csv_to_array("requests.tsv");
#echo "RequestsList: "; print_r($requestsList); echo "<br/>";
$users = getUsers($userList);
#echo "Users: "; print_r($users); echo "<br/>";
$friends = getFriends($users,$friendsList);
#echo "Friends: "; print_r($friends); echo "<br/>";
$requests = getRequests($users,$requestsList);
#echo "Friends: "; print_r($friends); echo "<br/>";
if(count($users)>0){
    $userData = array_combine($users, $userList);
    #echo "UserData: "; print_r($userData); echo "<br/>";

    foreach($users as $user){
        $userData[$user]["friends"] = $friends[$user];
    }
    foreach($users as $user){
        $userData[$user]["requests"] = $requests[$user];
    }
    #echo "UserData: "; print_r($userData); echo "<br/>";
}
else{
    $userData= array();
}


?>