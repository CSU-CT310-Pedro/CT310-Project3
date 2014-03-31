<?php
$title= "View Friends";
include 'Head.php';
?>

<div id="body-container">
    <div id="header">
        <?php include 'proj1Header.php'; ?>
        <h1>View Friends</h1>
    </div>

<?php include 'nav.php'; ?>

<?php include 'userData.php'; ?>

<table border="1">
    <tr>
        <td style="padding:10px">User</td>
        <td style="padding:10px">Friends</td>
    </tr>
    <?php
    foreach($userData as $user){
        echo "<tr>";
        echo "<td style=\"padding:10px\">";
        echo "<a href=".$user["url"]."><img src=".$user["picture"]." alt=".$user." height=\"40\"></a>";
        echo "<br/>";
        echo $user["user"];
        echo "</td>";

        echo "<td style=\"padding:10px\">";

        foreach($user["friends"] as $friend){
            echo "<a href=".$userData[$friend]["url"]."><img src=".$userData[$friend]["picture"]." alt=".$friend." height=\"40\"></a> ";
        }
        echo "</td>";
        echo "</tr>";

    }
    ?>
</table>


    <?php include 'proj1Footer.html'; ?>
</div>
<?php include 'Foot.html'; ?>