<?php
session_name("Ejercicio5MoverPunto2D");
session_start();

if (!isset($_SESSION["posicionX"])) {
    $_SESSION["posicionX"] = 200;
    $_SESSION["posicionY"] = 200;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimiento Punto</title>
    <style>
        div#todo {
            display: flex;
            flex-direction: row;
        }

        svg {
            width: "600px";
            height: "20px";
        }
    </style>
</head>

<body>
    <div id="todo">
        <form action="comprobar.php" method="post">

            <div id="botones">
                <div>
                    <button name="accion" type="submit" value="arriba">&#x1F446;</button>
                </div>

                <div>
                    <button name="accion" type="submit" value="izquierda">&#x1F448;</button>
                    <button name="accion" type="submit" value="reiniciar">Volver al centro</button>
                    <button name="accion" type="submit" value="derecha">&#x1F449;</button>
                </div>

                <div>
                    <button name="accion" type="submit" value="abajo">&#x1F447;</button>
                </div>
            </div>
        </form>



        <svg id="canvas" width="400" height="400" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 800 20">
            <rect width="400" height="400" fill="transparent" stroke="black" stroke-width="2" />
            <circle cx="<?php echo $_SESSION["posicionX"] ?>" cy="<?php echo $_SESSION["posicionY"] ?>" r="10"
                fill="red" />
        </svg>

    </div>
</body>

</html>