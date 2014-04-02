<?php
$title= "Login";
include 'Head.php';
?>

    <div id="body-container">

        <div id="header">
            <?php include 'proj1Header.php'; ?>
            <h1>Login</h1>
        </div>

        <?php

        include 'userData.php';

        $invalid = false;

        if($_SERVER["REQUEST_METHOD"]=="POST"){
            if(isset($_POST["user"])){
				$db = new PDO('sqlite:./users.db');
				
                $user = $_POST["user"];
                $md5hash = md5(strip_tags($_POST["passwd"]));

				$query = "SELECT hash FROM users WHERE userName='$user';";
				$OHash = $db->query($query);//gets the hash as an object
				$hash="";
				foreach($OHash as $hash1){
					$hash = $hash1[0];
				}
                if($hash == "$md5hash"){
                    $_SESSION['user'] = $user;
                }
                else{
					echo "$user";
					echo "$hash";
                    $invalid= true;
                }
            }
            else{
                if($_POST["logout"]=="true"){
                    unset($_SESSION['user']);
                }
            }
        }
        include 'nav.php';
        ?>

        <div id=\"content\">

            <div id="sidebar_left">
                <?php

                if (isset($_SESSION['user'])) {
                    $user=$_SESSION['user'];
                    echo "Logged in as $user";
                    ?>

                    <form method="post" action="">
                        <input type= "hidden" name="logout" value= "true" />
                        <input type= "submit" value="Log Out"/></br>
                    </form>

                <?php
                }
                else {
                    ?>
                    <form method="post" action=""><!-- We might consider directing it to the users page -->
                        <input type= "hidden" name="logout" vaule= "false" />
                        <?php
                        if($invalid==true){
                            echo "Invalid Username and Password <br/>";
                        }
                        ?>
                        UserName: <input type= "text" name="user"/></br>
                        Password: <input type= "password" name="passwd"/></br>
                        <input type= "submit" value="Login"/></br>
                    </form>
                <?php
                }
                ?>
                <br/><br/>
            </div>
            <?php include 'proj1Footer.html'; ?>
        </div>
    </div>

<?php include 'Foot.html'; ?>