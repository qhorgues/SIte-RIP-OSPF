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
    setcookie("quiz", "true", 0, NULL, NULL, false, true);
    $user = "";
?>

<!DOCTYPE html>
<html lang="FR-fr">
    <head>
        <meta charset="UTF-8"/>
        <title>Le Quiz des protocoles</title>
        <link rel="stylesheet" type="text/css" href="./../css/style.css"/>
        <link rel="stylesheet" type="text/css" href="./../css/span.css"/>
        <link rel="stylesheet" type="text/css" href="./../css/input.css"/>
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
            <a href="./ospf.php">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Le protocole OSPF
            </a>
            <a href="#">
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
            <h1 id="titleqcm">Êtes-vous prêt aux questions ?</h1>
            <?php
                if ($user == "")
                {   
                    echo "<span class='error'>Vous n'êtes pas connecté, votre score ne sera pas enregistré !</span><br/>";
                }
            ?>
            <form action="./verif_qcm.php" method="post">

                <p class="titlequest">Le protocole RIP permet de trouver : </p>
                <span><input type="radio" name="question1" value="0"/> Le chemin le plus rapide.</span>
                <span><input type="radio" name="question1" value="1"/> Le chemin le plus circulaire.</span>
                <span><input type="radio" name="question1" value="2"/> Le chemin le plus court.</span>
                <span><input type="radio" name="question1" value="3"/> Le chemin qui met le plus de temps.</span>

                <br/>
                <br/>

                <p class="titlequest">Le protocole OSPF permet de faire : </p>
                <span><input type="radio" name="question2" value="0"/> Le chemin le plus rapide.</span>
                <span><input type="radio" name="question2" value="1"/> Le chemin le plus court.</span>
                <span><input type="radio" name="question2" value="2"/> Le chemin le plus long.</span>
                <span><input type="radio" name="question2" value="3"/> Le chemin le plus économique.</span>
                <span><input type="radio" name="question2" value="4"/> Le chemin le plus écologique.</span>

                <br/>
                <br/>

                <p class="titlequest">Le signal du protocole RIP est envoyé généralement : </p>
                <span><input type="radio" name="question3" value="0"/> Tous les 30 jours.</span>
                <span><input type="radio" name="question3" value="1"/> Toutes les 30 heures.</span>
                <span><input type="radio" name="question3" value="2"/> Toutes les 30 secondes.</span>
                <span><input type="radio" name="question3" value="3"/> Toutes les 3 secondes.</span>

                <br/>
                <br/>

                <p class="titlequest">La formule du poids est : </p>
                <span><input type="radio" name="question4" value="0"/> debit / 10⁸</span>
                <span><input type="radio" name="question4" value="1"/> 10⁸ / debit</span>
                <span><input type="radio" name="question4" value="2"/> (debit x 10⁸) / (10⁹ x debit)</span>
                <span><input type="radio" name="question4" value="3"/> debit² / 10⁸</span>

                <br/>
                <br/>

                <p class="titlequest">Le nombre qui caractérise un routeur down est : </p>
                <span><input type="radio" name="question5" value="0"/> 0</span>
                <span><input type="radio" name="question5" value="1"/> 255</span>
                <span><input type="radio" name="question5" value="2"/> 1</span>
                <span><input type="radio" name="question5" value="3"/> 2¹²⁷</span>
                <span><input type="radio" name="question5" value="4"/> &#x221E</span>

                <br/>

                <input type="submit" value="Vérifier" name="finish_qcm"/>

            </form>

        </div>

    </body>
</html>
<?php
    if (isset($dbh)) {
        mysqli_close($dbh);
    };
?>