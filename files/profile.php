<?php
    include("./../elements/begin_php.php");

    function viewFormPassword(string $messageOldPassword, string $messageNewPassword, string $messagePassword2) {
        ?>
        <form action="profile.php" method="post">
            <?php
                if (!isset($messageOldPassword) || $messageOldPassword != "") {
                    echo "<span class=\"error\">".$messageOldPassword."</span><br/>";
                }
            ?>
            Ancien mot de passe : <br/>
            <input type="password" name="oldPassword" required><br/><br/>
            
            <?php
                if (!isset($messageNewPassword) || $messageNewPassword != "") {
                    echo "<span class=\"error\">".$messageNewPassword."</span><br/>";
                }
            ?>
            Nouveau mot de passe : <br/>
            <input type="password" name="newPassword" required><br/><br/>
            
            <?php
                if (!isset($messagePassword2) || $messagePassword2 != "") {
                    echo "<span class=\"error\">".$messagePassword2."</span><br/>";
                }
            ?>
            Ressaisir nouveau mot de passe : <br/>
            <input type="password" name="newPassword2" required><br/>
            <br/><input type="submit" value="Valider" name="modifyPassword">
            
        </form>
        <?php
    }

?>
<!DOCTYPE html>
<html lang="FR-fr">
    <head>
        <meta charset="UTF-8"/>
        <title>Profile</title>
        <link rel="stylesheet" type="text/css" href="./../css/span.css"/>
        <link rel="stylesheet" type="text/css" href="./../css/style.css"/>
        <link rel="stylesheet" type="text/css" href="./../css/input.css"/>
    </head>
        
    <body>
        <?php
			include("./../elements/header.php");
            if (isset($_POST['definitive_delete'])) {
                $request = "DELETE FROM `user` WHERE `identifier` = '".$user."';";
                $request_score = "DELETE FROM `score` WHERE `identifier` = '".$user."';";
                mysqli_begin_transaction($dbh);
                $ret = mysqli_query($dbh, $request);
                $ret2 = mysqli_query($dbh, $request_score);
                if ( !$ret || !$ret2 ) {
                    mysqli_rollback($dbh);
                    die ("Error".mysqli_error($dbh));
                } else {
                    mysqli_commit($dbh);
                    setcookie("user", "", time() - 3600, NULL, NULL, false, true);
                    mysqli_close($dbh);
                    header("Location: ./welcome.php");
                    exit(0);
                }
            }
        ?>

        <div id="boxProfile">
            <h1 id="username"><?= $user ?></h1>
 
            <?php
                if (!isset($_POST['logOut']) && !isset($_POST['delete'])) {
                    ?>
                    <h2>Changer de mot de passe : </h2>
                    <?php
                    if (isset($_POST['modifyPassword'])) {
                        $messageOldPassword = "";
                        $messageNewPassword = "";
                        $messagePassword2 = "";
                        $error = false;
                    
                        if (!isset($_POST['oldPassword']) || !isset($_POST['newPassword']) || !isset($_POST['newPassword2'])){
                            viewFormInscription("", "", "");
                        } else {
                            if ($_POST['oldPassword'] == "") {
                                $messageOldPassword = "Vous devez saisir votre mot de passe";
                                $error = true;
                            } else {
                                $request = "SELECT `password` FROM `user` WHERE `identifier` = '".$user."';";
                                //echo $request;
                                $ret = mysqli_query($dbh, $request);
                                if ( mysqli_errno($dbh) ) {
                                    $messageOldPassword = "Une erreur innatendue est survenue réessayer plus tard";
                                    $error = true;
                                }
                                $line = mysqli_fetch_assoc($ret);
                                if (!password_verify($_POST['oldPassword'], $line['password'])) {
                                    $messageOldPassword = "Mot de passe incorect";
                                    $error = true;
                                }
                            }
                            if ($_POST['newPassword'] == "") {
                                $messageNewPassword = "Vous devez saisir votre mot de passe";
                                $error = true;
                            } else {
                                $patern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)\S{6,20}$/";
                                if (!preg_match($patern, $_POST['newPassword'])) {
                                    $messageNewPassword = "Le mot de passe doit posséder au moins : 
                                                        <li> 6 caractères et 20 caractères maximums</li>
                                                        <li>Un chiffre</li>
                                                        <li>Une majuscule</li>
                                                        <li>Une minuscule</li>
                                                        <li>Un caractères spécial ex: &, %, !, #</li>";
                                    $error = true;
                                    
                                }
                                if ($_POST['newPassword2'] == "") {
                                    $messagePassword2 = "Vous devez ressaisir votre mot de passe";
                                    $error = true;
                                } else if ($_POST['newPassword2'] != $_POST['newPassword']) {
                                    $messagePassword2 = "Vous devez saisir le même mot de passe";
                                    $error = true;
                                }
                            } 
                        }
                        if ($error) {
                            viewFormPassword($messageOldPassword, $messageNewPassword, $messagePassword2);
                        } else {
                            $request = "UPDATE `user` SET `identifier` = '".$user."', `password` = '".password_hash($_POST['newPassword'], PASSWORD_DEFAULT)."' WHERE identifier = '".$user."';";
                            mysqli_begin_transaction($dbh);
                            if ( !mysqli_query($dbh, $request) ) {
                                mysqli_rollback($dbh);
                                die ("Error".mysqli_error($dbh));
                            } else {
                                mysqli_commit($dbh);
                                viewFormPassword("", "", "");
                                echo "<span class=\"sucess\">Mot de passe modifié avec succès !</span><br/>";
                            }
                        }
                    } else {
                        viewFormPassword("", "", "");
                    } 
                ?>


                <form method="post" action="profile.php">
                    <br/><input type="submit" value="Se déconnécter" name="logOut">    
                    <input class="emergency" type="submit" value="Supprimer le compte" name="delete">
                </form>
                <?php
                } else {
                    if (isset($_POST['logOut'])) {
                        setcookie("user", "", time() - 3600, NULL, NULL, false, true);
                        mysqli_close($dbh);
                        header("Location: ./welcome.php");
                        exit(0);
                    } else if (isset($_POST['delete'])) {
                        echo "<span class='error'>Etês vous sûr de vouloir supprimer votre compte et toutes ses données !!!</span>";
                        ?>
                        <form method="post" action="profile.php">
                            <br/><input type="submit" value="Annuler" name="cancel">    
                            <input class="emergency" type="submit" value="Je suis sûr" name="definitive_delete">
                        </form>
                        <?php
                    }
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