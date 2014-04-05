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

			<form method="post" action="">
				<input type= "hidden" name="accept" value= "<?php echo $request[0];?>" />
				<input type= "submit" value="Accept request from <?php echo $request[0];?>"/></br>
				
				<input type= "hidden" name="deny" value= "<?php echo $request[0];?>" />
				<input type= "submit" value="Deny request from <?php echo $request[0];?>"/></br>
				
			</form>
			
			<?php
			}
			?>
		</form>
    </div>
    <?php include 'proj1Footer.html'; ?>
	</div>
</div>
<?php include 'Foot.html'; ?>
