<?php
    setcookie("user", "", time() + 3600 * 24 * 365, NULL, NULL, false, true);
    // setcookie("attempt", "false", time() + 3600, NULL, NULL, false, true);

    function connect_ddb($host, $dbname, $username, $passwd) {
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

    function addTable($dbh, $dbname, $table, $element) {
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

    function mysqli_request($dbh, $request) {
        mysqli_begin_transaction($dbh);
        if ( !mysqli_query($dbh, $request) ) { // si une erreur, on annule toutes les requêtes
            mysqli_rollback($dbh);
            return mysqli_error($dbh);
        } else { // on commite si aucune erreur
            mysqli_commit($dbh);
            return 0;
        }
    }


    /* **** Connexion DATA BASE ***** */
    $host 	 = "localhost";
    $dbname 	 = "account_quiz";
    $username = "root";
    $passwd 	 = "";

    $dbh = connect_ddb($host, $dbname, $username, $passwd);
    if ($dbh != NULL)
    {
        echo "data open";
        
    } else {
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
    <body>
        <h2>Connexion : </h2>
        

        <?php
			echo password_hash("1234", PASSWORD_DEFAULT);
            if ( !isset($_POST['identifier']) || !isset($_POST['password'])) {
        ?>
            <form action="Connexion.php" method="post">
                <?php
                    echo "<span style='color:red'>Vous devez remplir tous les champs</span><br/>";
                ?>
                <input type="text" name="identifier" required><br/>
                <input type="password" name="password" required><br/>
                <?php
                    // setcookie("attempt", "true", time() + 3600, NULL, NULL, false, true);
                ?>
                <input type="submit" value="Valider">
            </form>
        <?php
            } else {
				$request = "SELECT * FROM `user` WHERE `identifier` = '".$_POST['identifier']."';";
				echo $request;
				$ret = mysqli_query($dbh, $request);
				$account_exist = mysqli_num_rows($ret);
				if ( !mysqli_errno($dbh) ) {
					$ligne = mysqli_fetch_assoc($ret);
				} else {
					echo "Impossible de récupérer les comptes existants";
				}
				if ($account_exist && password_verify($_POST['password'], $ligne['password']))
				{    ?>
					<h1>Voici les codes d'accès :</h1>
            
					<p><strong>CRD5-GTFT-CK65-JOPM-V29N-24G1-HH28-LLFV</strong></p>
				<?php
				} else {
					
					echo '<p>Mot de passe incorrect</p>';
				}
			} ?>
            </body>
</html>