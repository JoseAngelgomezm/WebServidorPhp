<?php
session_name("examen3_23_24");
session_start();
if (isset($_POST["salir"])) {
    session_destroy();
    header("location:../index.php");
}

if (isset($_SESSION["usuario"])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <?php echo "<p>Bienvenido ".$_SESSION["usuario"]."</p>"; ?>
        <form action="../index.php" method="post">
            <button type="submit" name="salir" >Salir</button>
        </form>
    </body>

    </html>
    <?php
} else {
    header("location:../index.php");
}
?>