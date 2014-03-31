<?php
$title= "Add User";
include 'Head.php';
?>
<div id="body-container">

<div id="header">
    <?php include 'proj1Header.php'; ?>
    <h1>Add User</h1>
</div>


<?php include 'nav.php'; ?>


<div id="content">
    <div id="sidebar_left">
        <?php
        include 'userData.php';

        $ipaddr = $_SERVER ['REMOTE_ADDR'];
        list($first, $second, $third, $forth) = explode('.', $ipaddr);
        ?>
        <?php
        if(
            (strcmp($first,"129")==0 && strcmp($second,"82")==0)
            || strcmp($first,"::1")==0
            || (strcmp($first,"67")==0 && strcmp($second,"174")==0 && strcmp($third,"106")==0 && strcmp($forth,"156")==0)

        )

        {
            ?>

            <?php
            if($_SESSION['user'] == "admin"){
                ?>

                <form action= " " method = "post" enctype="multipart/form-data">
                    <input type="hidden" name="gender" value=""/>
                    <table class="formTbl">
                        <tr>
                            <td>UserName:</td>
                            <td>
                                <input type="text" name="user"/><br/>
                            </td>
                        </tr>

                        <tr>
                            <td>Password:</td>
                            <td>
                                <input type="text" name="passwd"/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>Confirm Password:</td>
                            <td>
                                <input type="text" name="confpasswd"/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>Name:</td>
                            <td>
                                <input type="text" name="name"/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td>
                                <input type="radio" name="gender" value="Male"
                                    <?php if($gender == "Male"){echo "checked";} ?> />Male
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="radio" name="gender" value="Female"
                                    <?php if($gender == "Female"){echo "checked";} ?> />Female
                            </td>
                        </tr>
                        <tr>
                            <td>PhoneNumber:</td>
                            <td>
                                <input type="text" name="number"/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td>
                                <input type="text" name="email"/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td>Image:</td>
                            <td>
                                <input type="file" name="file"><br/>
                            </td>
                        </tr>

                    </table>
                    <input type="submit">
                </form>
            <?php
            }
            else{
                echo "Only admins can add users";
            }
            ?>


        <?php
        }
        else{
            echo "Your IP address is not white listed.";
        }
        ?>


        <?php



        $user = $_POST["user"];
        $passwd = $_POST["passwd"];
        $confPasswd = $_POST["confpasswd"];
        $md5passwd = md5($passwd);
        $name= $_POST["name"];
        $number= $_POST["number"];
        $email= $_POST["email"];


        /*  echo"username is ".$username."</br>";
        echo"password is ".$passwd."</br>";
        echo"hash is ".$md5passwd."</br>";*/

        if(preg_match("/^[A-z0-9]+$/",$user)){
            if(preg_match("/^(?!.*\s).{8,}$/",$passwd)){
                if("$passwd"=="$confPasswd"){
                    if(preg_match('/^[a-z0-9 .\-]+$/i', $name)){
                        if(preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $number)){
                            if(preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $email)){

                                if(isset($_FILES["file"])){
                                    #print_r($_FILES["file"]);
                                    #echo $_FILES["file"]["tmp_name"];
                                    echo "<br/>";
                                    if($_FILES["file"]["error"]==0){
                                        $type = explode("/",$_FILES["file"]["type"]);
                                        if($type[0]="image"){
                                            if($_FILES["file"]["size"]<1000000){

                                                if(!(in_array($_POST["user"] ,$users))){
                                                    $flag = move_uploaded_file($_FILES["file"]["tmp_name"], "Images/".$_FILES["file"]["name"]);
                                                    if($flag){
                                                        echo "User added succefully <br/>";

                                                        #add check for passwd
                                                        $md5hash= md5($_POST["passwd"]);

                                                        $string = $_POST["user"]."\t".$md5hash."\t".$_POST["name"]."\t"."Images/".$_FILES["file"]["name"]."\t".$_POST["gender"]."\t".$_POST["number"]."\t".$_POST["email"]."\n";
                                                        file_put_contents("users.tsv",$string, FILE_APPEND);
                                                    }
                                                }
                                                else{
                                                    echo "User already exists <br/>";
                                                }
                                            }
                                            else{
                                                echo "File is too big <br/>";
                                            }
                                        }
                                        else{
                                            echo "File is not an image <br/>";
                                        }
                                    }
                                }


                            }
                            else{
                                echo "Invalid email <br/>";
                            }

                        }
                        else{
                            echo "Invalid phone number: (1-###-###-####) <br/>";
                        }

                    }
                    else{
                        echo "Names has invalid characters <br/>";
                    }
                }
                else{
                    echo "Passwords do not match <br/>";
                }
            }
            else{
                echo"Password must be eight characters long <br/>";
            }
        }
        else{
            if(isset($_POST["user"])){
                echo "Username may only contain letters and numbers <br/>";
            }
        }
        ?>
        <br/><br/>
    </div>
    <?php include 'proj1Footer.html'; ?>
</div>
</div>
<?php include 'Foot.html'; ?>
