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

        <div class="box">
            <h1>Bienvenue !</h1>

            <br/>

            <p>Ici vous allez apprendre les protocoles RIP et OSPF.</p>
        </div>

    </body>
</html>
<?php
    if (isset($dbh)) {
        mysqli_close($dbh);
    };
?>