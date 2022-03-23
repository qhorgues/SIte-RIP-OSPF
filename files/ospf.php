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
        <title>Le protocole RIP</title>
        <link rel="stylesheet" type="text/css" href="./../css/style.css"/>
        <link rel="stylesheet" type="text/css" href="./../css/span.css"/>
    </head>
    <body>
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
            <a href="#">
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
            <h1>Le protocole OSPF :</h1>
            <p>
                Le but du protocole OSPF est de trouver le chemin le plus rapide.

                <br/>
                <br/>

                Le protocole OSPF découvre sa topographie en envoyant des messages à ces voisins, appelé généralement hello.
                Ce message fonctionne légèrement comme le signal du protocole RIP.
                <br/>
                La tapographie c'est bien, mais comment savoir si c'est le chemin le plus rapide ?
                <br/>
                Et bien les routeurs calculent les poids des liasons entre eux et leurs voisins.
                Ils calculent les poid avec la bande passante.
                La formule est 10⁸ / debit. Les valeurs sont en octets (bytes pour les anglais).

                <br/>
                <br/>

                Donc pour une liason de 1 Go/s, ce qui est équivalent à 1 000 000 000 o/s soit 1 X 10⁹ o.
                <br/>
                Ce qui donne un poid de 10⁸ / 10⁹ = 1/10 = 0,1.
                <br>
                Donc une liason de 1 Go/s a un poid de 0,1.
            </p>
        </div>

    </body>
</html>
<?php
    if (isset($dbh)) {
        mysqli_close($dbh);
    };
?>