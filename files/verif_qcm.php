<?php
    include("./../elements/begin_php.php");
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
        <?= include("./../elements/header.php"); ?>
        <div id="boxResult">
            <?php
            $score = 0;
            function verifRep($nameButton, $nameRep, $numRep, $tabRep, & $score) {
                if (isset($_POST[$nameButton])) {
                    if (isset($_POST[$nameRep])) {
                        $numRepUser = $_POST[$nameRep];
                        if($numRepUser >= count($tabRep)) {
                            echo "Bien tenté mais non ça ne marche pas !";
                        } else {
							echo $tabRep[$numRepUser];
							if ($numRep == $numRepUser)
							{
								echo " -> <span class='sucess'>Bonne réponse</span>";
								if (isset($score)) {
									$score++;
								}
							} else {
								echo " -> <span class='error'>Mauvaise réponse</span>";
							}
						}
                        
                    } else {
                        echo "<span class='error'>Vous n'avez pas répondu c'est dommage</span>";
                    }
                }            
            }

            $rep1 = array("le chemin le plus rapide", "le chemin le plus circulaire", 
                    "le chemin le plus court", "le chemin qui met le plus de temps");

            $rep2 = array("le chemin le plus rapide", "le chemin le plus court",
                    "le chemin le plus long", "le chemin le plus économique", "le chemin le plus écologique");

            $rep3 = array("tous les 30 jours", "toutes les 30 heures", "toutes les 30 secondes", "toutes les 3 secondes");

            $rep4 = array("debit / 10⁸", "10⁸ / debit", "(debit x 10⁸) / (10⁹ x debit)", "debit² / 10⁸");

            $rep5 = array("0", "255", "1", "2¹²⁷", "&#x221E");


            ?>

            <div>
                <br/>
                <span class="titlequestV">Le protocole RIP permet de trouver : </span><span><?= verifRep("finish_qcm", "question1", "2", $rep1, $score) ?></span>
                <br/>
                <br/>
                <span class="titlequestV">Le protocole OSPF permet de faire : </span><span><?= verifRep("finish_qcm", "question2", "0", $rep2, $score) ?></span>
                <br/>
                <br/>
                <span class="titlequestV">Le signal du protocole RIP est envoyé généralement : </span><span><?= verifRep("finish_qcm", "question3", "2", $rep3, $score) ?></span>
                <br/>
                <br/>
                <span class="titlequestV">La formule du poids est : </span><span><?= verifRep("finish_qcm", "question4", "1", $rep4, $score) ?></span>
                <br/>
                <br/>
                <span class="titlequestV">Le nombre qui caractérise un routeur down est : </span><span><?= verifRep("finish_qcm", "question5", "4", $rep5, $score)?></span>
            </div>
            <br/>
            
            <?php 
                $noteScore = array("à revoir", "insuffisant", "trop juste", "pas mal", "super", "un sans faute !!!");
            ?>
            <br/>
            Votre score est <?php echo (string)$score; ?>, c'est <?php echo $noteScore[$score]; ?><br/><br/>
            <?php
                if ($user != "") {
                    if (isset($_COOKIE['quiz']) && $_COOKIE['quiz'] == "true") {
                        setcookie("quiz", "false", 0, NULL, NULL, false, true);
                        $request = "INSERT INTO `score` VALUES ('".$user."', '".$score."', CURRENT_TIMESTAMP());";
                        mysqli_begin_transaction($dbh);
                        if ( !mysqli_query($dbh, $request) ) {
                            mysqli_rollback($dbh);
                            echo "Impossible d'enregistrer le score";
                        } else {
                            mysqli_commit($dbh);
                        }
                    }

                    $request = "SELECT `score`, `date_score` FROM `score` WHERE `identifier` = '".$user."' ORDER BY date_score DESC LIMIT 10;";
                    $ret = mysqli_query($dbh, $request);
                    $numScore = mysqli_num_rows($ret);
                    if ( mysqli_errno($dbh) ) {
                        die (mysqli_errno($dbh));
                    }
                    if ($numScore > 1) {
                        echo "Voici vos ".$numScore." derniers score.<br/>";
                        echo "<table>\n
                                <tr>\n
                                    <th>Score</th>\n
                                    <th>Date</th>\n
                                </tr>\n";
                        while( $line = mysqli_fetch_assoc($ret) ) { // on processe toutes les lignes résultats
                                echo "<tr>\n
                                        <th>".$line['score']."</th>\n
                                        <th>".date("d/m/Y à H:i:s", strtotime($line['date_score']))."</th>\n
                                    </tr>\n";
                        }
                        echo "</table>";
                    } else if ($numScore == 1) {
                        $line = mysqli_fetch_assoc($ret);
                        echo "Votre dernier score est ".$line['score']." le ".date("d/m/Y à H:i:s", strtotime($line['date_score'])); 
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