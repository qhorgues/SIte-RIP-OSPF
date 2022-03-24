<header>
	<a href="./welcome.php">
		<span></span>
		<span></span>
		<span></span>
		<span></span>
		Acceuil
	</a>
	<a href="./rip.php">
		<span></span>
		<span></span>
		<span></span>
		<span></span>
		Le protocole RIP
	</a>
	<a href="./ospf.php">
		<span></span>
		<span></span>
		<span></span>
		<span></span>
		Le protocole OSPF
	</a>
	<a href="./qcm.php">
		<span></span>
		<span></span>
		<span></span>
		<span></span>
		Questionnaire sur les protocoles
	</a>
		<?php
			if ($dbh != NULL && isset($_COOKIE['user']) && $_COOKIE['user'] != "")
			{
				$user = if_hash_user_exist($dbh, $_COOKIE['user']);
				if ($user != false) {
					
					echo "<a href='./profile.php' id='connect'>";
					echo $user;
				} else {
					echo "<a href='./login.php' id='connect'>";
					echo "Connectez-vous";
				}
			} else {
				echo "<a href='./login.php' id='connect'>";
				echo "Connectez-vous";
			}
		?>
	</a>
</header>