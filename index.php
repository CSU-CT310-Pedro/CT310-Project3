<?php
$title = 'CT310 Social Networking Home';
$header = 'Home';
include 'Head.php';
?>

	<div id="body-container">
		<div id="header">
			<?php include 'proj1Header.php'; ?>
		</div>

        <?php include "userData.php" ?>

        <?php include "nav.php" ?>

		<div id="content">
				
			<div id="sidebar_left">
				<?php include "about.html" ?>			
            </div>
			
            <div id="sidebar_right">

                <h2>Users</h2>

                <?php

                foreach($users as $user){

                ?>

                <div class="box">
					<div class="thumbpict">
						<a href="profile.php?myUser=<?php echo $user ?>"><img class ="thumbnail" src="<?php echo $userData[$user]["picture"] ?>" alt="<?php echo $user ?>'s profile picture" /> </a>
					</div>
					<div class="userinfo"><?php echo $user ?></div>
				</div>

                <?php
                }
                ?>

            </div>
            <?php include 'proj1Footer.html'; ?>
		</div>


	</div>

<?php include 'Foot.html'; ?>