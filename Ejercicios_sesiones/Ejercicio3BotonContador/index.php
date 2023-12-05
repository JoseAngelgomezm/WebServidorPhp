<?php
session_name("Ejercicio2BotonContador");
session_start();

if (!isset($_SESSION["contador"]))
    $_SESSION["contador"] = 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contadores session</title>
    <style>
        button.grande {
            width: 50px;
            height: 50px;
        }

        span {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <form method="post" action="comprobar.php">
        <button class="grande" name="accion" type="submit" value="menos">-</button>
        <?php echo "<span>" . $_SESSION["contador"] . "</span>"; ?>
        <button class="grande" name="accion" type="submit" value="mas">+</button>
        <p><button name="accion" type="submit" value="reiniciar">Reiniciar</button></p>
    </form>
</body>

</html>