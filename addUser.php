<?php
$title= "Add User";
include 'userData.php';
include 'Head.php';
?>
<div id="body-container">
<div id="header">
    <?php include 'proj1Header.php'; ?>
    <h1>Add User</h1>
</div>
<?php include 'nav.php'; 
	$db = new PDO('sqlite:./users.db');
	$user = $_SESSION['user'];
	$query = "SELECT isAdmin FROM users WHERE userName='$user';";
	$OIsAdmin = $db->query($query);
	$admin =0;
	foreach($OIsAdmin as $a){
		$admin = $a[0];
	}
?>

<div id="content">
    <div id="sidebar_left">
        <?php
        if(IpCheck()!=0){
			if($admin==1){//multiple users can be admin
                ?>
                <form method="post" action = "addUser.php" enctype="multipart/form-data">
					<table>
						<tr><td>Full Name:</td> <td><input type="text" name="realName"/> </td></tr>
						<tr><td>User Name:</td> <td><input type="text" name="userName"/> </td></tr>
						<tr><td>Password:</td> <td><input type="password" name="newPass"/> </td></tr>
						<tr><td>Confirm Password:</td> <td><input type="password" name="newPassConf"/> </td></tr>
						<tr><td>Gender</td>
                            <td><input type="radio" name="gender" value="Male"
                                    <?php if($gender == "Male"){echo "checked";} ?> />Male
                            </td>
                        </tr>
                        <tr><td></td>
                            <td><input type="radio" name="gender" value="Female"
                                    <?php if($gender == "Female"){echo "checked";} ?> />Female
                            </td>
                        </tr>
                        <tr><td>PhoneNumber:</td>
                            <td><input type="text" name="number"/><br/></td>
                        </tr>
                        <tr><td>Email:</td>
                            <td><input type="text" name="email"/><br/></td>
                        </tr>
						<tr><td>Website:</td>
                            <td><input type="text" name="url"/><br/></td>
                        </tr>
						<tr><td>Add a photo:</td> <td><input type="file" name="file"></td></tr>
						<tr><td><input type="submit" value="Submit"/></td></tr>
					</table>
				</form>
            <?php
            }
            else{
                echo "Only admins can add users";
            }
        }
        else{
			echo ($_SERVER['REMOTE_ADDR']);
            echo "Your IP address is not white listed.";
        }
		if(isset($_POST["userName"]) &&isset($_POST["realName"]) &&isset($_POST["newPass"]) &&isset($_POST["newPassConf"]) &&isset($_POST['gender'])&&isset($_POST["number"])&&isset($_POST["email"])){
			$userName = strip_tags($_POST["userName"]);
			$realName = strip_tags($_POST["realName"]);
			$passwd = strip_tags($_POST["newPass"]);
			$confPasswd = strip_tags($_POST["newPassConf"]);
			$md5passwd = md5($confPasswd);
			$gender = strip_tags($_POST['gender']);
			$number= strip_tags($_POST["number"]);
			$email= strip_tags($_POST["email"]);
			$url = strip_tags($_POST['url']);
			
			if(preg_match("/^[A-z0-9]+$/",$userName)){//username must be letters and numers
				if(preg_match("/^(?!.*\s).{8,}$/",$passwd)){//strlen($passwd)>=8) password must be longer than 8 characters
					if("$passwd"=="$confPasswd"){//passwords match
						if(preg_match('/^[a-z0-9 .\-]+$/i', $userName)){//name must have valid characters
							if(preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $number)){//...I don't know what parameters this is checking
								if(preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $email)){//...neither this one
									if(isset($_FILES["file"])){//if they upload a file
										echo "<br/>";
										if($_FILES["file"]["error"]==0){//file is not in error
											$type = explode("/",$_FILES["file"]["type"]);
											if($type[0]="image"){//file is an image
												if($_FILES["file"]["size"]<100000){//size check
													$place="Images/$userName.jpg";
													$flag = move_uploaded_file($_FILES["file"]["tmp_name"], $place);
													//check if user already exists
													$used=0;
													$query = "SELECT userName FROM users WHERE userName='$userName';";
													$return=$db->query($query);												
													foreach($return as $r){//This will never loop if the userName has never been used
														if(isset($r)){//
															echo "$userName has already been taken. Please try again.";
															$used=1;
														}
													}
													if($used==0){
														$query = "INSERT INTO users VALUES('$userName', '$realName', '$md5passwd', 0, 0, 'Images/$userName.jpg', '$url', '$number', '$gender', '$email');";
														$return=$db->exec($query);//add user now
													}
													if($flag){
														echo "file uploaded successfully";
													}
													else{
														echo "error";
													}
												}
												else{echo "File is too big <br/>";}
											}
											else{echo "File is not an image <br/>";}
										}
									}
								}
								else{echo "Invalid email <br/>";}
							}
							else{echo "Invalid phone number: (1-###-###-####) <br/>";}
						}
						else{echo "Name has invalid characters <br/>";}
					}
					else{echo "Passwords do not match <br/>";}
				}
				else{echo"Password must be eight characters long <br/>";}
			}
			else{
				if(isset($_POST["user"])){
					echo "Username may only contain letters and numbers <br/>";
				}
			}
		}
        ?>
        <br/><br/>
    </div>
    <?php include 'proj1Footer.html'; ?>
</div>
</div>
<?php include 'Foot.html'; ?>
