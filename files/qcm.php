<?php
    include("./../elements/begin_php.php");
    setcookie("quiz", "true", 0, NULL, NULL, false, true);
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
        <?= include("./../elements/header.php"); ?>

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
				<div class="quest">
					<span><input type="radio" name="question1" value="0"/> Le chemin le plus rapide.</span>
					<span><input type="radio" name="question1" value="1"/> Le chemin le plus circulaire.</span>
					<span><input type="radio" name="question1" value="2"/> Le chemin le plus court.</span>
					<span><input type="radio" name="question1" value="3"/> Le chemin qui met le plus de temps.</span>
				</div>

                <br/>
                <br/>

                <p class="titlequest">Le protocole OSPF permet de faire : </p>
                <div class="quest">
					<span><input type="radio" name="question2" value="0"/> Le chemin le plus rapide.</span>
					<span><input type="radio" name="question2" value="1"/> Le chemin le plus court.</span>
					<span><input type="radio" name="question2" value="2"/> Le chemin le plus long.</span>
					<span><input type="radio" name="question2" value="3"/> Le chemin le plus économique.</span>
					<span><input type="radio" name="question2" value="4"/> Le chemin le plus écologique.</span>
				</div>
				
                <br/>
                <br/>
				
                <p class="titlequest">Le signal du protocole RIP est envoyé généralement : </p>
                <div class="quest">
					<span><input type="radio" name="question3" value="0"/> Tous les 30 jours.</span>
					<span><input type="radio" name="question3" value="1"/> Toutes les 30 heures.</span>
					<span><input type="radio" name="question3" value="2"/> Toutes les 30 secondes.</span>
					<span><input type="radio" name="question3" value="3"/> Toutes les 3 secondes.</span>
				</div>
				
                <br/>
                <br/>

                <p class="titlequest">La formule du poids est : </p>
                <div class="quest">
					<span><input type="radio" name="question4" value="0"/> debit / 10⁸</span>
					<span><input type="radio" name="question4" value="1"/> 10⁸ / debit</span>
					<span><input type="radio" name="question4" value="2"/> (debit x 10⁸) / (10⁹ x debit)</span>
					<span><input type="radio" name="question4" value="3"/> debit² / 10⁸</span>
				</div>
                <br/>
                <br/>

                <p class="titlequest">Le nombre qui caractérise un routeur down est : </p>
                <div class="quest">
					<span><input type="radio" name="question5" value="0"/> 0</span>
					<span><input type="radio" name="question5" value="1"/> 255</span>
					<span><input type="radio" name="question5" value="2"/> 1</span>
					<span><input type="radio" name="question5" value="3"/> 2¹²⁷</span>
					<span><input type="radio" name="question5" value="4"/> &#x221E </span>
				</div>
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