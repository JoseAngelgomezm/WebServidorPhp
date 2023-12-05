<?php
    session_name("Ejercicio4MoverPunto");
    session_start();

    if(!isset($_SESSION["posicion"])){
        $_SESSION["posicion"] = 0;
    }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimiento Punto</title>
    <style>
        div#botones {
            text-align: center;
        }

        svg {
            width: "600px";
            height: "20px";
        }
    </style>
</head>

<body>
    <form action="comprobar.php" method="post">

        <div id="botones">
            <button name="accion" type="submit" value="izquierda">&#x261C;</button>
            <button name="accion" type="submit" value="reiniciar">Volver al centro</button>
            <button name="accion" type="submit" value="derecha">&#x261E;</button>
    </form>

    <div>

        <svg version="1.1" xmlns=http://www.w3.org/2000/svg viewbox="-300 0 600 20">
            <line x1="-300" y1="10" x2="300" y2="10" stroke="black" stroke-width="5" />
            <circle cx="<?php echo $_SESSION["posicion"] ?>" cy="10" r="8" fill="red" />
        </svg>
    </div>

</body>

</html>