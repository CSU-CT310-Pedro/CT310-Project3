<?php
$title= "Add Friends";
include 'Head.php';
?>


<div id="body-container">
    <div id="header">
        <?php include 'proj1Header.php'; ?>
        <h1>Manage Friends</h1>
    </div>


    <?php include 'nav.php'; ?>
    <div id="content">
        <div id="sidebar_left">


            <?php
            $added = false;
            if(isset($_POST["newFriend"]) && $_POST["newFriend"]!=""){
                $user=$_SESSION['user'];
                $newFriend= $_POST["newFriend"];
                $string = $_POST["user"]."\t".$_POST["newFriend"]."\n";
                file_put_contents("requests.tsv",$string, FILE_APPEND);
                $added = true;
            }
            ?>

            <?php
            $added = false;
            if(isset($_POST["accept"]) && $_POST["accept"]!=""){
                $user=$_SESSION['user'];
                $newFriend= $_POST["accept"];
                $string1 = $user."\t".$newFriend."\n";
                $string2 = $newFriend."\t".$user."\n";
                file_put_contents("friends.tsv",$string1, FILE_APPEND);
                file_put_contents("friends.tsv",$string2, FILE_APPEND);
                $added = true;
            }
            ?>



            <?php include 'userData.php'; ?>


            <?php
            if(isset($_SESSION['user']) && $_SESSION['user']!=""){
                $user = $_SESSION['user'];
                ?>

                <form action= " " method = "post" enctype="multipart/form-data">
                    <input type="hidden" name="user" value="<?php echo $user; ?>">
                    Select Friend to add: <select name="newFriend">
                        <option value= "" >None</option>
                        <?php
                        foreach($users as $friend){
                            if("$user"!="$friend" && !(in_array($friend, $userData[$user]["friends"]))){
                                echo "<option value= ".$friend." >$friend</option>";
                            }
                        }
                        ?>
                    </select>
                    <br/>
                    <input type="submit" value="Send Request">
                </form>
                <br/>
                <br/>

            <?php
            }
            else{
                echo "You must be logged in to manage friends.";
            }
            if($added==true){
                echo "A friend request has been sent to $newFriend <br/>";
            }
            ?>


            <?php
            if(isset($_SESSION['user'])){
                echo "Requests: <br/>";
                $user=$_SESSION['user'];
                $u= array();
                $u=$userData[$user];

                foreach($u['requests'] as $request){


                    if(!(in_array("$request", $userData[$user]["friends"]))){
                        echo "<a href=\"profile.php?myUser=$request\"><img src=".$userData[$request]["picture"]." alt=".$request." height=\"40\"></a> ";

                        ?>
                        <br/>

                        <form method="post" action="">
                            <input type= "hidden" name="accept" value= "<?php echo $request;?>" />
                            <input type= "submit" value="Accept request from <?php echo $request;?>"/></br>
                        </form>
                        <br/>

                    <?php
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
