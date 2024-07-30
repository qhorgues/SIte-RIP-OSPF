<?php
    include("./../elements/begin_php.php");
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
        <?= include("./../elements/header.php"); ?>

        <div class="box">
            <h1>Le protocole RIP :</h1>
            <p>
                Le but du protocole RIP est de trouver le chemin le plus court.
                
                <br/>
                <br/>

                Chaque routeur envoie à leurs voisins un signal régulièrement (habituellement toutes les 30 secondes).
                Ce signal sert à prendre des informations pour que les routeurs fassent leur propre table de routage.
                Les informations sont les voisines de ses voisins, oui, on est dans Inception.
                Il sauvegarde les informations si son voisin lui indique un nouveau routeur, ou un chemin plus court pour atteindre le routeur.
                Mais comment définit-on un chemin court ? On le définit par le nombre de sauts qu'on doit faire pour arriver à destination.
                <br/>
                Un saut est tout simplement le nombre de routeurs qu'on traverse (sans compter celui de départ).
                <br/>
                Si un routeur décide de ne plus répondre, il est down, que se passe-t-il ? Alors notre routeur décidera que son nombre de sauts est « infini ».
                <br>L'infini représente donc une distance inaccessible, le routeur va devoir trouver un autre chemin.
                
                <br/>
                <br/>

                Sa charge, pour faire une topologie de l'intégralité de son réseau met un certain temps, ce qui fait que le protocole RIP est utilisé pour des petits réseaux.
            </p>
        </div>

    </body>
</html>
<?php
    if (isset($dbh)) {
        mysqli_close($dbh);
    };
?>