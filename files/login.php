<?php

    function connect_ddb(string $host, string $dbname, string $username, string $passwd) {
        $dbh = @mysqli_connect($host,$username,$passwd,$dbname);
        if ( mysqli_connect_errno() ) {
            $dbh = mysqli_connect( $host, $username, $passwd );
            if ( !mysqli_connect_errno() ) {
                $requeteSQL="CREATE DATABASE IF NOT EXISTS `$dbname`	
                    DEFAULT CHARACTER SET utf8mb4
                    DEFAULT COLLATE utf8mb4_general_ci;";
                $resultat = mysqli_query($dbh, $requeteSQL) ;
                if ( !$resultat ) {
                    die( "Erreur creation database : " . mysqli_error($dbh) . "<br>" );
                }
                
            } else {
                return NULL;
            }
        }
        return $dbh;
    }

    function addTable($dbh, string $dbname, string $table, string $element) {
        if (!mysqli_select_db($dbh, $dbname)) {
            die(mysqli_error($dbh));
        }
        $request = "CREATE TABLE IF NOT EXISTS `$table`(
            $element
            );";
        $resultat = mysqli_query($dbh, $request);
        if ( !$resultat ) {
            die(mysqli_error($dbh));
        }
        return 0;
    }

    function mysqli_request($dbh, string $request) {
        mysqli_begin_transaction($dbh);
        if ( !mysqli_query($dbh, $request) ) { // si une erreur, on annule toutes les requêtes
            mysqli_rollback($dbh);
            return mysqli_error($dbh);
        } else { // on commite si aucune erreur
            mysqli_commit($dbh);
            return 0;
        }
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

    function viewForm(string $messageAlert)
    { ?>
        <form action="login.php" method="post">
            <?php
                if (!isset($messageAlert) || $messageAlert != "") {
                    echo "<span class='error'>".$messageAlert."</span><br/>";
                }
            ?>
            Identifiant : <br/>
            <input type="text" autocomplete="off" name="identifier" required><br/><br/>
            Mot de passe : <br/>
            <input type="password" name="password" required><br/>
            <br/><input type="submit" value="Valider" name="connect">
        </form> 
    <?php
    }

    function viewFormInscription(string $messageId, string $messagePassword, string $message2Password)
    { ?>
        <form action="login.php" method="post">
            <?php
                if (!isset($messageId) || $messageId != "") {
                    echo "<span class=\"error\">".$messageId."</span><br/>";
                }
            ?>
            Identifiant : <br/>
            <input type="text" autocomplete="off" name="createIdentifier" required><br/><br/>

            <?php
                if (!isset($messagePassword) || $messagePassword != "") {
                    echo "<span class=\"error\">".$messagePassword."</span><br/>";
                }
            ?>
            Mot de passe : <br/>
            <input type="password" name="createPassword" required><br/><br/>

            <?php
                if (!isset($message2Password) || $message2Password != "") {
                    echo "<span class=\"error\">".$message2Password."</span><br/>";
                }
            ?>
            Ressaisir mot de passe : <br/>
            <input type="password" name="createPassword2" required><br/>
            <?php
            ?>
            <br/><input type="submit" value="Valider" name="inscription">
        </form> 
    <?php
    }
        

    /* **** Connexion DATA BASE ***** */
    $host 	 = "localhost";
    $dbname 	 = "account_quiz";
    $username = "root";
    $passwd 	 = "";

    $dbh = connect_ddb($host, $dbname, $username, $passwd);
    if ($dbh == NULL)
    {
        echo "Error : ".mysqli_error($dbh);
        die();
    }
	
	addTable($dbh, $dbname, "user","identifier VARCHAR(30) PRIMARY KEY, 
                                    password VARCHAR(255) NOT NULL"); // 255 : Valeur recomandée par la doc PHP

    addTable($dbh, $dbname, "score", "identifier VARCHAR(30) NOT NULL,
                                      score TINYINT NOT NULL,
                                      date_score TIMESTAMP NOT NULL");

?>

<!DOCTYPE html>
<html lang="FR-fr">
    <head>
        <meta charset="UTF-8"/>
        <title>Connexion</title>
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
        <div id="boxIdentifiant">
            <h2>Connexion : </h2>
        
            <?php

                if (isset($_POST['connect'])) {
                    if (!isset($_POST['identifier']) && !isset($_POST['password'])){
                        viewForm("");
                    } else if ($_POST['identifier'] == "" || $_POST['password'] == "") {
                        viewForm("Vous n'avez pas saisie votre identifiant ou votre mot de passe");
                    } else if (strlen($_POST['identifier']) > 30) {
                        viewForm("L'identifiant ne peut pas faire plus de 30 caractères");
                    } else {
                        $request = "SELECT * FROM `user` WHERE `identifier` = '".$_POST['identifier']."';";
                        $ret = mysqli_query($dbh, $request);
                        if ( !mysqli_errno($dbh) ) {
                            $line = mysqli_fetch_assoc($ret);
                        } else {
                            viewForm("Impossible de récupérer les comptes existants");
                        }
                        if (mysqli_num_rows($ret) > 0)
                        {
                            if (password_verify($_POST['password'], $line['password'])) {    
                                setcookie("user", password_hash($line['identifier'], PASSWORD_DEFAULT), 0, NULL, NULL, false, true);
                                mysqli_close($dbh);
                                header("Location: ./profile.php");
                                exit(0);
                            } else {
                                viewForm("Identifiant ou mot de passe incorecte");
                            }
                        } else {
                            viewForm("Identifiant ou mot de passe incorecte");
                        }
                    }
                } else {
                    viewForm("");
                } 
                
                
                
            ?>

            <h2>Inscription : </h2>

            <?php
                if (isset($_POST['inscription'])) {
                    $messageId = "";
                    $messagePassword = "";
                    $message2Password = "";
                    $error = false;
                
                    if (!isset($_POST['createIdentifier']) || !isset($_POST['createPassword']) || !isset($_POST['createPassword2'])){
                        viewFormInscription("", "", "");
                    } else {
                        if ($_POST['createIdentifier'] == "") {
                            $messageId = "Vous devez saisir votre identifiant";
                            $error = true;
                        } else if (strlen($_POST['createIdentifier']) > 30) {
                            viewForm("L'identifiant ne peut pas faire plus de 30 caractères");
                        } else {
                            $request = "SELECT * FROM `user` WHERE `identifier` = '".$_POST['createIdentifier']."';";
                            //echo $request;
                            $ret = mysqli_query($dbh, $request);
                            if (mysqli_num_rows($ret)) {
                                $messageId = "Vous devez saisir un autre identifiant";
                                $error = true;
                            }
                        }
                        if ($_POST['createPassword'] == "") {
                            $messagePassword = "Vous devez saisir votre mot de passe";
                            $error = true;
                        } else {
                            $patern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)\S{6,20}$/";
                            if (!preg_match($patern, $_POST['createPassword'])) {
                                $messagePassword = "Le mot de passe doit posséder au moins : 
                                                    <li> 6 caractères et 20 caractères maximums</li>
                                                    <li>Un chiffre</li>
                                                    <li>Une majuscule</li>
                                                    <li>Une minuscule</li>
                                                    <li>Un caractères spécial ex: &, %, !, #</li>";
                                $error = true;
                                
                            }
                            if ($_POST['createPassword2'] == "") {
                                $message2Password = "Vous devez ressaisir votre mot de passe";
                                $error = true;
                            } else if ($_POST['createPassword2'] != $_POST['createPassword']) {
                                $message2Password = "Vous devez saisir le même mot de passe";
                                $error = true;
                            }
                        } 
                    }
                    if ($error) {
                        viewFormInscription($messageId, $messagePassword, $message2Password);
                    } else {
                        $request = "INSERT INTO `user` VALUES ('".$_POST['createIdentifier']."', '".password_hash($_POST['createPassword'], PASSWORD_DEFAULT)."');";
                        mysqli_begin_transaction($dbh);
                        if ( !mysqli_query($dbh, $request) ) {
                            mysqli_rollback($dbh);
                            die ("Error".mysqli_error($dbh));
                        } else {
                            mysqli_commit($dbh);
                            setcookie("user", password_hash($_POST['createIdentifier'], PASSWORD_DEFAULT), 0, NULL, NULL, false, true);
                            mysqli_close($dbh);
                            header("Location: ./profile.php");
                            exit(0);
                        }
                    }
                } else {
                    viewFormInscription("", "", "");
                } 
            ?>
        </div>
    </body>
</html>
<?php
    if (isset($dbh)) {
        mysqli_close($dbh);
    };
?>