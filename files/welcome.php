<?php
    $host 	 = "localhost";
    $dbname 	 = "account_quiz";
    $username = "root";
    $passwd 	 = "";

    $dbh = @mysqli_connect($host,$username,$passwd,$dbname);
    if ( mysqli_connect_errno() ) {
        $dbh = NULL;
    }

    function if_hash_user_exist($dbh, string $hash_user) {
        $request = "SELECT `identifier` FROM `user`;";
        $ret = mysqli_query($dbh, $request);
        if ( mysqli_errno($dbh) ) {
			return mysqli_errno($dbh);
		}
        while( $line = mysqli_fetch_assoc($ret) ) { // on processe toutes les lignes résultats
            if (password_verify($line['identifier'], $hash_user)) {
                return $line['identifier'];
            }
        }
        return false;
    }
?>

<!DOCTYPE html>
<html lang="FR-fr">
    <head>
        <meta charset="UTF-8"/>
        <title>Les protocoles RIP et OSPF</title>
        <link rel="stylesheet" type="text/css" href="./../css/span.css"/>
        <link rel="stylesheet" type="text/css" href="./../css/style.css"/>
    </head>
    <body>
        <header>
            <a href="#">
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

        <div class="box">
            <h1>Bienvenue !</h1>

            <br/>

            <p>Ici vous allez apprendre les protocoles RIP et OSPF.</p>
        </div>

    </body>
</html>
<?php
    if (isset($dbh)) {
        mysqli_close($dbh);
    };
?>