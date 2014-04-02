<div id="nav">
    <a href="index.php">Home</a>
    | <a href="addFriends.php">Manage Friends</a>
    <?php
    if(isset($_SESSION['user'])){
        if($_SESSION['user'] == "admin"){
            echo "| <a href=\"addUser.php\">Add User</a>";
        }
		echo "| <a href=\"logout.php\">Logout</a>";
    }
	else{
		echo "| <a href=\"login.php\">Login</a>";
	}
	
    ?>
</div>

