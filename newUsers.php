<?php
$title= "Approve Users";
include 'userData.php';
include 'Head.php';
?>
<div id="body-container">
<div id="header">
    <?php include 'proj1Header.php'; ?>
    <h1>Approve Users</h1>
</div>
<?php include 'nav.php';?>

<div id="content">
    <div id="sidebar_left">
		<h3>Approve users</h3>
		<form method="post" action="">
		<?php
			$user=$_SESSION['user'];
			$db = new PDO('sqlite:./users.db');
			$query = "SELECT userName FROM pending;";
			$newUsers = $db->query($query);

			foreach($newUsers as $request){

			$query = "SELECT imageurl FROM pending WHERE userName='$request[0]';";
			$OURL = $db->query($query);
			$URL = "";
			foreach($OURL as $O){
				$URL = $O[0];
			}
					echo "<a href=\"profile.php?myUser=$request[0]\"><img src=\"$URL\" alt=\"$request[0] \'s profile picture \" height=\"40\"></a> ";
		?>
		<br/>

			<form method="post" action="newUsers.php">
				<input type= "hidden" name="accept" value= "<?php echo $request[0];?>" />
				<input type= "submit" value="Accept request from <?php echo $request[0];?>"/></br>
				
				<input type= "hidden" name="deny" value= "<?php echo $request[0];?>" />
				<input type= "submit" value="Deny request from <?php echo $request[0];?>"/></br>
				
			</form>
			
			<?php
			}
			if(isset($_POST['accept'])){
				$user=$_POST['accept'];
				$db = new PDO('sqlite:./users.db');
				$query = "INSERT INTO users (userName, realName, hash, invalids, isAdmin, imageurl, weburl, phone, gender, email) SELECT userName, realName, hash, invalids, isAdmin, imageurl, weburl, phone, gender, email FROM pending WHERE userName='$user';";
				$newUsers = $db->exec($query);
				$query = "DELETE FROM pending WHERE userName = '$user';";
				$db->exec($query);
				$query = "SELECT email FROM users WHERE userName='$user';";
				$oEmail = $db->query($query);
				$email='';
				foreach($oEmail as $e){
					$email=$e[0];
				}
				
				$message = "Your account has been approved! You are now free to log into the website! <a href='http://www.cs.colostate.edu/~bckelly1/Project3/'> click here.</a> ";
				$message = wordwrap($message, 70, "\r\n");
				$subject = "Two Factor Authentication";
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				mail($email, $subject, $message, $headers);
			}
			else if(isset($_POST['deny'])){
				$user=$_POST['deny'];
				$db = new PDO('sqlite:./users.db');
				$query = "DELETE FROM pending WHERE userName = '$user';";
				$db->exec($query);
			}
			
			?>
		</form>
    </div>
    <?php include 'proj1Footer.html'; ?>
	</div>
</div>
<?php include 'Foot.html'; ?>
