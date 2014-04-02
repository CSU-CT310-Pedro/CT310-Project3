<div id="nav">
    <a href="index.php">Home</a>
    | <a href="addFriends.php">Manage Friends</a>
    <?php
    if(isset($_SESSION['user'])){
		$db = new PDO('sqlite:./users.db');
		$user = $_SESSION['user'];
		$query = "SELECT isAdmin FROM users WHERE userName='$user';";
		$OIsAdmin = $db->query($query);
		$admin =0;
		foreach($OIsAdmin as $a){
			$admin = $a[0];
		}
		
        if($admin==1){
            echo "| <a href=\"addUser.php\">Add User</a>";
        }
		echo "| <a href=\"logout.php\">Logout</a>";
    }
	else{
		echo "| <a href=\"login.php\">Login</a>";
	}
	
    ?>
</div>

