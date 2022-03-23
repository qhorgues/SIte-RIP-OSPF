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
            <a href="#">
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
            <h1>Le protocole RIP :</h1>
            <p>
                Le but du protocole RIP est de trouver le chemin le plus court.
                
                <br/>
                <br/>

                Chaques routeurs envoient à leurs voisins un signale régulièrement (habituellement toutes les 30 secondes).
                Ce signale sert à prendre des informations pour que les routeurs fassent leur propre table de routage.
                Les informations sont les voisins de ses voisins, oui on est dans inception version voisins.
                Il sauvegarde les informations si son voisin lui indique un nouveau routeur, ou un chemin plus court pour atteindre le routeur.
                Mais comment définit-t-on un chemin court ? On le définie par le nombre de sauts qu'on doit faire pour arriver à destination.
                <br/>
                Un saut est tout simplement le nombre de routeur qu'on traverse (sans compter celui de départ).
                <br/>
                Si un routeur ne décide de ne plus répondre, il est down, que se passe-t-il ? Alors notre routeur décidera que son nombre de saut est «infini».
                <br>L'infini représente donc une distance inaccessible, le routeur va devoir trouver un autre chemin 
                
                <br/>
                <br/>

                Sa charge pour faire une topologie de l'intégralité de son réseau met un certains temps, ce qui fait que le protocole RIP est utilisé pour des petits réseaux.
            </p>
        </div>

    </body>
</html>
<?php
    if (isset($dbh)) {
        mysqli_close($dbh);
    };
?>