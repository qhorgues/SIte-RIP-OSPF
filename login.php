<?php
    
    // setcookie("attempt", "false", time() + 3600, NULL, NULL, false, true);

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

    function viewForm(string $messageAlert)
    { ?>
        <form action="login.php" method="post">
            <?php
                if (!isset($messageAlert) || $messageAlert != "") {
                    echo "<span style='color:red'>".$messageAlert."</span><br/>";
                }
            ?>
            Identifiant : <br/>
            <input type="text" name="identifier" ><br/><br/>
            Mot de passe : <br/>
            <input type="password" name="password" ><br/><br/>
            <?php
                // setcookie("attempt", "true", time() + 3600, NULL, NULL, false, true);
            ?>
            <br/><input type="submit" value="Valider" name="connect">
        </form> 
    <?php
    }

    function viewFormInscription(string $messageId, string $messagePassword, string $message2Password)
    { ?>
        <form action="login.php" method="post">
            <?php
                if (!isset($messageId) || $messageId != "") {
                    echo "<span style='color:red'>".$messageId."</span><br/>";
                }
            ?>
            Identifiant : <br/>
            <input type="text" name="createIdentifier" ><br/><br/>

            <?php
                if (!isset($messagePassword) || $messagePassword != "") {
                    echo "<span style='color:red'>".$messagePassword."</span><br/>";
                }
            ?>
            Mot de passe : <br/>
            <input type="password" name="createPassword" ><br/><br/>

            <?php
                if (!isset($message2Password) || $message2Password != "") {
                    echo "<span style='color:red'>".$message2Password."</span><br/>";
                }
            ?>
            Ressaisir mot de passe : <br/>
            <input type="password" name="createPassword2" ><br/><br/>
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
	
	addTable($dbh, $dbname, "user","identifier VARCHAR(40) PRIMARY KEY, 
                                    password VARCHAR(200) NOT NULL");

    addTable($dbh, $dbname, "score", "identifier VARCHAR(40) PRIMARY KEY,
                                      score TINYINT");

?>

<!DOCTYPE html>
<html lang="FR-fr">
    <head>
        <meta charset="UTF-8"/>
        <title>Un titre</title>
        
    </head>
    <body style="background-color:#222; color:#EEE">
        <h2>Connexion : </h2>
    
        <?php
			//echo password_hash("azerty", PASSWORD_DEFAULT);
            if (isset($_POST['connect'])) {
                if (!isset($_POST['identifier']) && !isset($_POST['password'])){
                    viewForm("");
                } else if ($_POST['identifier'] == "" || $_POST['password'] == "") {
                    viewForm("Vous n'avez pas saisie votre identifiant ou votre mot de passe");
                } else {
                    $request = "SELECT * FROM `user` WHERE `identifier` = '".$_POST['identifier']."';";
                    //echo $request;
                    $ret = mysqli_query($dbh, $request);
                    if ( !mysqli_errno($dbh) ) {
                        $ligne = mysqli_fetch_assoc($ret);
                    } else {
                        viewForm("Impossible de récupérer les comptes existants");
                    }
                    if (mysqli_num_rows($ret) > 0)
                    {
                        if (password_verify($_POST['password'], $ligne['password'])) {    
                            setcookie("user", password_hash($ligne['identifier'], PASSWORD_DEFAULT), time() + 3600 * 24 * 365, NULL, NULL, false, true);
                            setcookie("password", password_hash($ligne['password'], PASSWORD_DEFAULT), time() + 3600 * 24 * 365, NULL, NULL, false, true);
                            header("Location: index.html");
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
                                                    <li>Une majuscules</li>
                                                    <li>Une minuscules</li>
                                                    <li>Un caractères spécial ex: &, %, !, #</li>";
                                $error = true;
                                
                                if ($_POST['createPassword2'] == "") {
                                    $message2Password = "Vous devez ressaisir votre mot de passe";
                                    $error = true;
                                } else if ($_POST['createPassword2'] != $_POST['createPassword']) {
                                    $message2Password = "Vous devez saisir le même mot de passe";
                                    $error = true;
                                }
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
                            setcookie("user", password_hash($ligne['createIdentifier'], PASSWORD_DEFAULT), time() + 3600 * 24 * 365, NULL, NULL, false, true);
                            setcookie("password", password_hash($ligne['createPassword'], PASSWORD_DEFAULT), time() + 3600 * 24 * 365, NULL, NULL, false, true);
                            header("Location: index.html");
                        }
                    }
                } else {
                    viewFormInscription("", "", "");
                } 
            ?>
            </body>
</html>
<?php
    mysqli_close($dbh);
?>