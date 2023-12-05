<?php
session_name("Ejercicio2BotonContador");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contadores session</title>
    <form method="post" action="comprobar.php">
        <h2>
            <?php
            if (isset($_SESSION["contador"]))
                echo $_SESSION["contador"];
            else
                echo "0";
            ?>
        </h2>
        <button name="accion" type="submit" value="mas">Aumentar</button>
        <button name="accion" type="submit" value="menos">Disminuir</button>
        <button name="accion" type="submit" value="reiniciar">Reiniciar</button>
    </form>
</head>

<body>

</body>

</html>