<?php
$title = 'CT310 Social Networking User1';
include 'Head.php';
$user = "";
if($_SERVER["REQUEST_METHOD"]=="GET" || $_SERVER["REQUEST_METHOD"]=="POST"){
    $user =  $_GET["myUser"];
}
$header = $user."'s ProFile";
?>

    <div id="body-container">
    <div id="header">
        <?php include 'proj1Header.php'; ?>
        <?php include 'userData.php';

        #check to see if user is making profile edit
        if(isset($_POST['name'])){

            $name= $_POST['name'];
            $gender= $_POST['gender'];
            $number= $_POST['number'];
            $email= $_POST['email'];


            #update userdata

            if(preg_match('/^[a-z0-9 .\-]+$/i', $name)){
                $userData[$user]['name']= "$name";
            }
            else{
                echo "Names has invalid characters <br/>";
            }
            if(preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $number)){
                $userData[$user]['number']= "$number";
            }
            else{
                echo "Invalid phone number: (1-###-###-####) <br/>";
            }
            if(preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $email)){
                $userData[$user]['email']= "$email";
            }
            else{
                echo "Invalid email <br/>";
            }





            $userData[$user]['gender']= "$gender";




            if(isset($_FILES["file"])){
                #print_r($_FILES["file"]);
                #echo $_FILES["file"]["tmp_name"];
                echo "<br/>";
                if($_FILES["file"]["error"]==0){
                    $type = explode("/",$_FILES["file"]["type"]);
                    if($type[0]="image"){
                        if($_FILES["file"]["size"]<1000000){
                            $flag = move_uploaded_file($_FILES["file"]["tmp_name"], "Images/".$_FILES["file"]["name"]);
                            if($flag){
                                echo "file uploaded succefully <br/>";

                                $userData[$user]['picture']= "Images".$_FILES['file']['name'];
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

            #write new user data to file

            $string = "user\tpassword\tname\tpicture\tgender\tnumber\temail\n";
            file_put_contents("users.tsv",$string);

            foreach($users as $u){
                $string = $userData[$u]['user']."\t".$userData[$u]['password']."\t".$userData[$u]['name']."\t".$userData[$u]['picture']."\t".$userData[$u]['gender']."\t".$userData[$u]['number']."\t".$userData[$u]['email']."\n";
                file_put_contents("users.tsv",$string, FILE_APPEND);
            }

        }

        ?>
    </div>

    <?php include "nav.php" ?>

    <div id="content">

        <?php
        $user= $_GET['myUser'];

        ?>

        <div id="sidebar_left">
            <div class="pict">
                <img class="full" src="<?php echo $userData[$user]["picture"];?>" alt="<?php echo $user;?>'s profile picture" />
            </div>
        </div>

        <div id="sidebar_right">
            <h2> <?php echo $user;?>'s Profile</h2>


            <?php
            if($_POST['edit']=="true"){

                # profile edit form
                ?>


                <form action= " " method = "post" enctype="multipart/form-data">
                    <input type="hidden" name="gender" value=""/>
                    <input type= "hidden" name="edit" value= "false" />
                    <table class="formTbl">

                        <tr>
                            <td>Name:</td>
                            <td>
                                <input type="text" name="name" value="<?php echo $userData[$user]['name']?>"/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td>
                                <input type="radio" name="gender" value="Male"
                                    <?php if($userData[$user]['gender'] == "Male"){echo "checked";} ?> />Male
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="radio" name="gender" value="Female"
                                    <?php if($userData[$user]['gender'] == "Female"){echo "checked";} ?> />Female
                            </td>
                        </tr>
                        <tr>
                            <td>PhoneNumber:</td>
                            <td>
                                <input type="text" name="number" value="<?php echo $userData[$user]['number']?>"/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td>
                                <input type="text" name="email" value="<?php echo $userData[$user]['email']?>"/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>Image:</td>
                            <td>
                                <input type="file" name="file" value="<?php echo $userData[$user]['picture']?>"><br/>
                            </td>
                        </tr>

                    </table>
                    <input type="submit">
                </form>

            <?php
            }
            elseif(isset($_SESSION['user'])){
                #show profile info if user is logged in
                ?>
                Name: <?php echo $userData[$user]["name"];?> <br/>
                Gender: <?php echo $userData[$user]["gender"];?> <br/>
                Phone Number: <?php echo $userData[$user]["number"];?> <br/>
                Email: <?php echo $userData[$user]["email"];?> <br/>
            <?php
            }
            else{
                echo "Login to view profile";
            }
            ?>

            <?php

            #whitelist
            $ipaddr = $_SERVER ['REMOTE_ADDR'];
            list($first, $second, $third, $forth) = explode('.', $ipaddr);
            if(
                (strcmp($first,"129")==0 && strcmp($second,"82")==0)
                || strcmp($first,"::1")==0
                || (strcmp($first,"67")==0 && strcmp($second,"174")==0 && strcmp($third,"106")==0 && strcmp($forth,"156")==0)

            )

            {
                if($_SESSION['user']=="$user" && $_POST['edit']!="true"){
                    #show edit button of this is the logged in users profile
                    ?>
                    <form method="post" action="">
                        <input type= "hidden" name="edit" value= "true" />
                        <input type= "submit" value="edit"/></br>
                    </form>

                <?php
                }
            }
            ?>




            <?php

            echo "<h2>Friends</h2>";

            $u= array();
            $u=$userData[$user];

            foreach($u['friends'] as $friend){
                echo "<a href=\"profile.php?myUser=$friend\"><img src=".$userData[$friend]["picture"]." alt=".$friend." height=\"40\"></a> ";
            }
            ?>
        </div>

        <?php include 'proj1Footer.html'; ?>

    </div>


    </div>


<?php include 'Foot.html'; ?>