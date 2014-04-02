<?php
$title = 'CT310 Social Networking User';
include 'userData.php';
include 'Head.php';
$user= $_GET['myUser'];
/*if($_SERVER["REQUEST_METHOD"]=="GET" || $_SERVER["REQUEST_METHOD"]=="POST"){
    $user =  $_GET["myUser"];
}*/
$header = $user."'s ProFile";
?>

    <div id="body-container">
    <div id="header">
        <?php include 'proj1Header.php';

        #check to see if user is making profile edit
        if(isset($_POST['name'])){

            $name= $_POST['name'];
            $gender= $_POST['gender'];
            $number= $_POST['number'];
            $email= $_POST['email'];

            #update database
			$db = new PDO('sqlite:./users.db');

            if(preg_match('/^[a-z0-9 .\-]+$/i', $name)){//I have no idea what you're doing here.
				$query = "UPDATE users SET realName='$name' WHERE userName='$name';";//I don't know if we're supposed to set userName or realName
				$db->exec($query);
            }
            else{
                echo "Names has invalid characters <br/>";
            }
            if(preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $number)){
                $query = "UPDATE users SET phone='$number' WHERE userName='$user';";
				$db->exec($query);
            }
            else{
                echo "Invalid phone number: (1-###-###-####) <br/>";
            }
            if(preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $email)){
                $query = "UPDATE users SET email='$email' WHERE userName='$user';";
				$db->exec($query);
            }
            else{
                echo "Invalid email <br/>";
            }
            $query = "UPDATE users SET gender='$gender' WHERE userName='$user';";
			$db->exec($query);

            if(isset($_FILES["file"])){
                echo "<br/>";
                if($_FILES["file"]["error"]==0){
                    $type = explode("/",$_FILES["file"]["type"]);
                    if($type[0]="image"){
                        if($_FILES["file"]["size"]<1000000){
                            $flag = move_uploaded_file($_FILES["file"]["tmp_name"], "Images/".$_FILES["file"]["name"]);
                            if($flag){
                                echo "file uploaded succefully <br/>";
								//$image = ".$_FILES[\"file\"][\"name\"]";//TODO
								//$query = "UPDATE users SET image='Images/$image' WHERE userName='$user';";//TODO
								//$db->exec($query);
                            }
                        }
                    }
                    else{
                        echo "File is too big <br/>";
                    }
                }
                else{
                }
            }
		}
        ?>
    </div>

    <?php include "nav.php" ?>

    <div id="content">
        <div id="sidebar_left">
            <div class="pict">
				<?php 
				$s = getImageURL($user);
				foreach($s as $URL){
					//print_r($URL[0]);
					echo "<img class=\"full\" src=\"$URL[0] \" alt=\"$user's profile picture\"/>";
				}
				?>
                
            </div>
        </div>

        <div id="sidebar_right">
            <h2> <?php echo $user;?>'s Profile</h2>


            <?php
            if(isset($_POST['edit'])){
                # profile edit form
                ?>
                <form action= " " method = "post" enctype="multipart/form-data">
                    <!--<input type="hidden" name="gender" value=""/>   I see no reason for this 
                    <input type= "hidden" name="edit" value= "false" />     or this   -->
                    <table class="formTbl">
                        <tr>
                            <td>Name:</td>
                            <td>
                                <input type="text" name="name" /><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td>
                                <input type="radio" name="gender" value="Male"/>Male
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="radio" name="gender" value="Female"/>Female
                            </td>
                        </tr>
                        <tr>
                            <td>PhoneNumber:</td>
                            <td>
                                <input type="text" name="number" /><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td>
                                <input type="text" name="email" /><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>Image:</td>
                            <td>
                                <input type="file" name="file" ><br/>
                            </td>
                        </tr>

                    </table>
                    <input type="submit" value= "Submit">
                </form>

            <?php
            }
            elseif(isset($_SESSION['user'])){
                #show profile info if user is logged in
				$db = new PDO('sqlite:./users.db');
				$query = "SELECT userName FROM users WHERE userName='$user';";
				$Oname = $db->query($query);
				$name="";
				foreach($Oname as $name1){
					$name = $name1[0];
				}

				$query = "SELECT gender FROM users WHERE userName='$user';";
				$Ogender = $db->query($query);
				$gender="";
				foreach($Ogender as $gender1){
					$gender = $gender1[0];
				}

				$query = "SELECT phone FROM users WHERE userName='$user';";
				$OPhone = $db->query($query);
				$phone="";
				foreach($OPhone as $phone1){
					$phone = $phone1[0];
				}

				$query = "SELECT email FROM users WHERE userName='$user';";
				$Oemail = $db->query($query);
				$email="";
				foreach($Oemail as $email1){
					$email = $email1[0];
				}
				echo "Name: $name <br/>";
				echo "Gender: $gender <br/>";  
				echo "Phone Number: $phone <br/>";  
				echo "Email: $email <br/>";
            }
            else{
                echo "Login to view profile";
            }
            ?>

            <?php
            #whitelist
            $ipaddr = $_SERVER ['REMOTE_ADDR'];
            list($first, $second, $third, $forth) = explode('.', $ipaddr);
            if(IpCheck()!=0 && isset($_SESSION['user'])){
                if($_SESSION['user']=="$user" && !isset($_POST['edit'])){
                    #show edit button of this is the logged in users profile
                    ?>
                    <form method="post" action="">
                        <input type= "hidden" name="edit" value= "true" />
                        <input type= "submit" value="edit"/></br>
                    </form>

                <?php
                }
            }
			else{
				echo "</br> You need to be whitelisted and logged in to edit!";
			}
            echo "<h2>Friends</h2>";
				$friends = getUsersFriends($user);
            foreach($friends as $friend){
				$s = getImageURL($friend[0]);
				foreach($s as $source){
					echo "<a href=\"profile.php?myUser=$friend[0]\"><img src=\"$source[0]\" alt=\"$friend[0]\" height=\"40\"></a>";
				}
            }
            ?>
        </div>
        <?php include 'proj1Footer.html'; ?>
    </div>
    </div>
<?php include 'Foot.html'; ?>