
<div id="nav">
    <a href="index.php">Home</a>
    | <a href="login.php">Login</a>
    | <a href="addFriends.php">Manage Friends</a>
    <?php
    if(isset($_SESSION['user'])){
        if($_SESSION['user'] == "admin"){
            echo "| <a href=\"addUser.php\">Add User</a>";
        }
    }
    ?>
</div>

