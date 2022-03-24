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