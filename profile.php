<?php
$title = 'CT310 Social Networking User';
include 'userData.php';
include 'Head.php';
$user= $_GET['myUser'];
$header = $user."'s ProFile";
?>

    <div id="body-container">
    <div id="header">
        <?php

        include 'proj1Header.php';

        date_default_timezone_set ( 'America/Denver' );

        #check to see if user is making profile edit
        if(isset($_POST['edited'])){

            $name= $_POST['name'];
            $gender= $_POST['gender'];
            $number= $_POST['number'];
            $email= $_POST['email'];

            #update database
            $db = new PDO('sqlite:./users.db');

            if(preg_match('/^[a-z0-9 .\-]+$/i', $name)){
                $query = "UPDATE users SET realName='$name' WHERE userName='$user';";
                $db->exec($query);
            }
            else{
                echo "Names has invalid characters <br/>";
            }
            if(preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $number)){
                $query = " ";
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

                                //add email update for to profile page owner//TODO
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

        if(isset($_POST["accept"])){
            $db = new PDO('sqlite:./users.db');
            $user1=$_SESSION["user"];
            $user2=$_POST["accept"];
            $query = "INSERT INTO friends VALUES('$user1', '$user2');";
            $db->exec($query);
            $query = "INSERT INTO friends VALUES('$user2', '$user1');";
            $db->exec($query);

            $query = "DELETE FROM requests WHERE user1='$user2' AND user2='$user1';";
            $db->exec($query);
        }
        if(isset($_POST['reject'])){
            $db = new PDO('sqlite:./users.db');

            $user1=$_SESSION["user"];
            $user2=$_POST["reject"];

            $query = "DELETE FROM requests WHERE user1='$user2' AND user2='$user1';"; //make sure bd permission are read and write
            #echo "$query<br/>";
            $resp = $db->query($query);
        }

        if(isset($_POST['request'])){
            $db = new PDO('sqlite:./users.db');

            $u1 = $_SESSION['user'];

            $query = "INSERT INTO requests VALUES(\"$u1\", \"$user\");"; //make sure bd permission are read and write
            #echo "$query<br/>";
            $resp = $db->query($query);

            //add email update for to profile page owner//TODO

        }

        if(isset($_POST['comment'])){
            $db = new PDO('sqlite:./users.db');

            $response=0;
            $sender=$_SESSION['user'];
            $receiver=$_GET['myUser'];
            $time=date ( "l d, M. g:i a", time () );
            $message=filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
            $id=postID();

            $query = "INSERT INTO posts VALUES($id, $response, \"$sender\", \"$receiver\", \"$time\", \"$message\");";
            $db->exec($query);

            //add email update for to profile page owner//TODO


        }
        if(isset($_POST['response'])){

            $db = new PDO('sqlite:./users.db');

            $response=$_POST['respID'];
            $sender=$_SESSION['user'];
            $receiver=$_GET['myUser'];
            $time=date ( "l d, M. g:i a", time () );
            $message=filter_var($_POST['response'], FILTER_SANITIZE_STRING);
            $id=postID();

            $query = "INSERT INTO posts VALUES($id, $response, \"$sender\", \"$receiver\", \"$time\", \"$message\");";
            $db->exec($query);

            //add email update for to profile page owner//TODO
        }
        
        if(isset($_GET['random'])){
			//ip check, then update
			print_r($user);
			$db = new PDO('sqlite:./users.db');
			$query = "SELECT ip FROM newPassRequest WHERE userName='$user';";
			$oIp = $db->query($query);
			$ip = '';
			foreach($oIp as $I){
				$ip = $I[0];
			}

			$query = "SELECT hash FROM newPassRequest WHERE userName='$user';";
			$oHash = $db->query($query);
			$hash = '';
			foreach($oHash as $H){
				$hash = $H[0];
			}
			
			if($ip==$_SERVER['REMOTE_ADDR']){
				$query = "UPDATE users SET hash='$hash' WHERE userName='$user';";
				$db->exec($query);
				
				echo "<p>Password has been reset!</p>";
				$query = "DELETE FROM newPassRequest WHERE userName='$user';";
				$db->exec($query);
			}
			else{
				echo "You are not coming from the same address as you used to register";
				$s=$_SERVER['REMOTE_ADDR'];
				echo "$ip  and   $s";
			}
		}
		
		if(isset($_POST['newPass']) && isset($_POST['confNewPass'])){//password reset
			if($_POST['newPass'] == $_POST['confNewPass']){
				$hash = md5($_POST['newPass']);
				$db = new PDO('sqlite:./users.db');
				$query = "SELECT email FROM users WHERE userName='$user';";
				$oEmail = $db->query($query);
				$email = '';
				foreach($oEmail as $e){
					$email = $e[0];
				}
				$key = generateRandomString();
				$ip=$_SERVER['REMOTE_ADDR'];
				$query = "INSERT INTO newPassRequest VALUES('$user', '$hash', '$key', '$ip')";
				$db->exec($query);
				
				$message = 'To complete your password reset 
				<a href="http://www.cs.colostate.edu/~bckelly1/Project3/profile.php?myUser='.$user.
				'&random='.$key.'"> click here.</a> You must be clicking this link 
				from the IP address you used request the password change.';
				
				$message = wordwrap($message, 70, "\r\n");

				$subject = "Password Reset";
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				mail($email, $subject, $message, $headers);
				
				print_r ("Your new password request has been submitted. Please check your email.");
			}
			else{
				echo "New passwords do not match!";
			}
		}

        ?>

    </div>

    <?php include "nav.php" ?>

    <div id="content">
    <div id="sidebar_left">
        <div class="pict">
            <?php
            $user= $_GET['myUser'];
            $loggedIn= $_SESSION['user'];
            $s = getImageURL($user);
            foreach($s as $URL){
                echo "<img class=\"full\" src=\"$URL[0] \" alt=\"$user's profile picture\"/>";
            }


            ?>

        </div>

        <?php
        //addFriend functionality
        if(isset($_SESSION['user']) && $_SESSION['user']!=""){
            $isFriend=false;
            $reqSent=false;

            $frd=getUsersFriends($user);
            foreach($frd as $f){
                if($_SESSION['user']==$f[0]){
                    $isFriend=true;
                }
            }

            $req=getUsersReqs($loggedIn);

            #print_r($req);

            foreach($req as $r){
                if($user==$r[0]){
                    $reqSent=true;
                }
            }

            if($isFriend==false && $reqSent==false && $_SESSION['user']!="$user"){
                ?>

                <form method="post" action="">
                    <input type="hidden" name="request" value="<?php echo $user;?>">
                    <input type="submit" value="Add Friend"/>
                </form>

            <?php
            }
            elseif($isFriend==false && $reqSent==true && $_SESSION['user']!="$user"){
                echo "Friend Request Pending";
            }
        }

        ?>
    </div>

    <div id="sidebar_right">
    <h2> <?php echo $user;?>'s Profile</h2>


    <?php
    if(isset($_POST['edit'])){
        # profile edit form
        ?>
        <form action= " " method = "post" enctype="multipart/form-data">
            <input type= "hidden" name="edited" value= "true" />
            <table class="formTbl">
                <tr>
                    <td>Name:</td>
                    <td>
                        <input type="text" name="name" value="<?php echo $_POST['name']?>" /><br/>
                    </td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input type="radio" name="gender" value="Male" <?php if($_POST['gender']=="Male"){echo "checked";}?>/>Male
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="radio" name="gender" value="Female" <?php if($_POST['gender']=="Female"){echo "checked";}?>/>Female
                    </td>
                </tr>
                <tr>
                    <td>PhoneNumber:</td>
                    <td>
                        <input type="text" name="number" value="<?php echo $_POST['phone']?>"/><br/>
                    </td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>
                        <input type="text" name="email" value="<?php echo $_POST['email']?>"/><br/>
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
        $user= $_GET['myUser'];
        $db = new PDO('sqlite:./users.db');
        $query = "SELECT realName FROM users WHERE userName='$user';";
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
    if(IpCheck()==0 && isset($_SESSION['user'])){
        $user= $_GET['myUser'];
        if($_SESSION['user']=="$user" && !isset($_POST['edit'])){
            #show edit button of this is the logged in users profile
            ?>
            <form method="post" action="">
                <input type= "hidden" name="edit" value= "true" />
                <input type= "hidden" name="name" value="<?php echo $name?>" />
                <input type= "hidden" name="gender" value="<?php echo $gender?>" />
                <input type= "hidden" name="phone" value= "<?php echo $phone?>" />
                <input type= "hidden" name="email" value= "<?php echo $email?>" />
                <input type= "submit" value="Edit"/>
            </form>
            <br/>
            <p>Change your password here:</p>
			<form method="post" action="">
				<h4>Password</h4><input type="password" name="newPass">
				<h4>Confirm Password</h4><input type="password" name="confNewPass">
				<input type= "submit" value="Submit"/>
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


    //display pending requests from others if this is your profile
    if(isset($_SESSION['user']) && $_SESSION['user']=="$user"){
        $u=$_SESSION['user'];
        $db = new PDO('sqlite:./users.db');
        $query = "SELECT user1 FROM requests WHERE user2='$u';";
        $friends = $db->query($query);

        foreach($friends as $request){

            ?>

            <div class="spacer">
                <br/>
            </div>

            <?php

            $query = "SELECT imageurl FROM users WHERE userName='$request[0]';";
            $OURL = $db->query($query);
            $URL = "";
            foreach($OURL as $O){
                $URL = $O[0];
            }
            echo "<div class='friendThumbnail'>";
            echo "<a href=\"profile.php?myUser=$request[0]\"><img src=\"$URL\" alt=\"$request[0] \'s profile picture \" height=\"40\"></a><br/>";
            echo "$request[0]<br/>" ;
            echo "</div>";
            ?>

            <div class="acceptRegForm">
                <form method="post" action="">
                    <input type= "hidden" name="accept" value= "<?php echo $request[0];?>" />
                    <input type= "submit" value="Accept"/></br>
                </form>
                <form method="post" action="">
                    <input type= "hidden" name="reject" value= "<?php echo $request[0];?>" />
                    <input type= "submit" value="Reject"/></br>
                </form>
            </div>

            <div class="spacer">
            </div>
        <?php
        }
    }
    ?>


    <h2>Wall</h2>

    <div class="wall">
        <?php
        if(isset( $_SESSION['user'] ) && $_SESSION['user']!=""){
            $isFriend = isFriend($user,$loggedIn);
            if($isFriend || $user==$loggedIn){
                ?>

                <div class="commentArea">
                    <form method="post" action="">
                        <textarea class="post" name="comment">Write Something...</textarea><br/>
                        <input type= "submit" value="Post"/>
                    </form>
                </div>

            <?php
            }

            $posts=getPosts($user);
            foreach(array_reverse($posts) as $post){
                echo "<div class='post'>";
                $sender=$post['sender'];
                $time=$post['time'];
                $message=$post['message'];
                $s=getImageURL($sender);
                $imgURL="";

                foreach($s as $source){
                    $imgURL = $source[0];
                }

                echo "<div class=\"friendThumbnail\">";
                echo "<a href='profile.php?myUser=$sender'><img src='$imgURL' alt='$sender' height='50'></a>";
                echo "</div>";

                echo "<div class=\"acceptRegForm\">";
                echo "$sender <br/>";
                echo "$time<br/>";
                echo "</div>";

                echo "<div class=\"spacer\">";
                echo "$message<br/>";
                echo "</div>";

                $responses= $post['responses'];


                foreach($responses as $r){
                    echo "<div class='subpost'>";
                    $sender=$r['sender'];
                    $time=$r['time'];
                    $message=$r['message'];
                    $s=getImageURL($sender);
                    $imgURL="";

                    foreach($s as $source){
                        $imgURL = $source[0];
                    }
                    echo "<div class=\"friendThumbnail\">";
                    echo "<a href='profile.php?myUser=$sender'><img src='$imgURL' alt='$sender' height='50'></a>";
                    echo "</div>";

                    echo "<div class=\"acceptRegForm\">";
                    echo "$sender <br/>";
                    echo "$time<br/>";
                    echo "</div>";

                    echo "<div class=\"spacer\">";
                    echo "$message<br/>";
                    echo "</div>";
                    echo "</div>";
                }

                if($isFriend||$user==$loggedIn){
                    ?>
                    <div class="responseArea">
                        <form method="post" action="">
                            <input type="hidden" name="respID" value="<?php echo $post['id'];?>">
                            <textarea class="respond" name="response">Comment...</textarea><br/>
                            <input type= "submit" value="Post"/>
                        </form>
                    </div>
                <?php
                }

                echo "</div>";
                #echo "</div>";
            }
        }
        else{
            echo "Login to view posts.";
        }
        ?>
    </div>
    </div>
    <?php include 'proj1Footer.html'; ?>
    </div>
    </div>
<?php include 'Foot.html'; ?>
