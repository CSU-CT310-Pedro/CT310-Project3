<?php
$title= "Add Friends";
$myFriends= array();
$notFriends= array();
include 'userData.php';
include 'Head.php';
?>


<div id="body-container">
    <div id="header">
        <?php include 'proj1Header.php'; ?>
        <h1>Manage Friends</h1>
    </div>
    <?php include 'nav.php'; ?>
    <div id="content">
        <div id="sidebar_left">
		<h3>Add friends:</h3>
            <?php
            if(isset($_SESSION['user']) && $_SESSION['user']!=""){
                $user = $_SESSION['user'];
                ?>

                <form method="post" action="">
				<select name="user" size="1">
				<?php
					$blah=$_SESSION['user'];
					echo "<option value=\"$blah\">$blah</option>";
				?>
				</select>
				<input type="submit" value="Submit" name="submit"/>
				<?php
				if(isset($_POST['user'])){
					$user=$_POST['user'];
					$notFriend = array();
					$db = new PDO('sqlite:./users.db');

					$query = "SELECT user2 FROM friends WHERE user1='$user';";
					$friends = $db->query($query);
					foreach($friends as $user){
						array_push($myFriends, $user[0]);
					}
					$notFriend=array_diff($users, $myFriends);
				
					echo "<select name=\"friend\" size=\"1\">";
					foreach($notFriend as $friend){
						echo "<option value=\"$friend\">$friend</option>";
					}
				}
				?>
				</form>
                <br/>
                <br/>

            <?php
            }
            else{
                echo "You must be logged in to manage friends.";
            }
		if(isset($_POST["accept"])){
			$db = new PDO('sqlite:./users.db');
			$user1=$_SESSION["user"];
			$user2=$_POST["accept"];
			$query = "INSERT INTO friends VALUES('$user1', '$user2');";
			$db->exec($query);
			$query = "INSERT INTO friends VALUES('$user2', '$user1');";
			$db->exec($query);
			
			$query = "DELETE FROM requests WHERE user1='$user1' AND user2='$user2';";
			$db->exec($query);
		}
		if(isset($_POST['friend'])){
			$db = new PDO('sqlite:./users.db');
			$user1 = $_SESSION['user'];
			$user2 = $_POST['friend'];

			$query = "INSERT INTO requests VALUES($user1, $user2);";
			$friends = $db->exec($query);
                echo "A friend request has been sent to $user2 <br/>";
		}
		if(isset($_SESSION['user'])){
			echo "<h3>Requests:</h3> <br/>";
			$user=$_SESSION['user'];
			$db = new PDO('sqlite:./users.db');
			$query = "SELECT user2 FROM requests WHERE user1='$user';";
			$friends = $db->query($query);

			foreach($friends as $request){

			$query = "SELECT imageurl FROM users WHERE userName='$request[0]';";
			$OURL = $db->query($query);
			$URL = "";
			foreach($OURL as $O){
				$URL = $O[0];
			}
					echo "<a href=\"profile.php?myUser=$request[0]\"><img src=\"$URL\" alt=\"$request[0] \'s profile picture \" height=\"40\"></a> ";
		?>
		<br/>

		<form method="post" action="">
			<input type= "hidden" name="accept" value= "<?php echo $request[0];?>" />
			<input type= "submit" value="Accept request from <?php echo $request[0];?>"/></br>
		</form>
		<br/>
		<?php
			}
		}
        ?>
            <br/><br/>
        </div>
        <?php include 'proj1Footer.html'; ?>
    </div>
</div>
<?php include 'Foot.html'; ?>
