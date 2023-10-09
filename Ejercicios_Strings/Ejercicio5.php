<?php
if (isset($_POST["enviar"])) {
    $numeroIntroducido = trim($_POST["numero"]);
    $error_numero_vacio = $numeroIntroducido == "";
    $error_numero_mayor = $numeroIntroducido > 5000;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div id="formulario">
        <h1>Arabes a romanos - Formulario</h1>
        <p>Dime un número en arabe y lo convertire a cifras romanas</p>
        <form method="post" action="">
            <label for="numero">Numero: </label>
            <input type="text" name="numero" id="numero"
                value="<?php if (isset($_POST["enviar"]))
                    echo $numeroIntroducido; ?>"></input>
            <?php if (isset($_POST["enviar"]) && $error_numero_vacio)
                echo "campo vacío";
            elseif (isset($_POST["enviar"]) && $error_numero_mayor)
                echo "El numero es mayor a 5000"; ?>
            <br>
            <button name="enviar" id="enviar">Enviar</button>
        </form>
    </div>

    <?php

    // resultado
    if (isset($_POST["enviar"]) && !$error_numero_vacio && !$error_numero_mayor) {
        echo "<div id ='respuesta'>";
        echo "<h1>Romanos a árabes - Resultado</h1>";

        $resultado ="";

        // mientras el numero introducido sea mayor que 0
        while ($numeroIntroducido > 0) {
            // si al numero le quitas 1000 y sigue siendo mayor o igual que 0
            if ($numeroIntroducido - 1000 >= 0) {
                // quitarle los 1000
                $numeroIntroducido -= 1000;
                // añadir la letra que corresponda a 1000 al resultado
                $resultado .= "M";

            } else if ($numeroIntroducido - 500 >= 0) {
                $numeroIntroducido -= 500;
                $resultado .= "D";

            } else if ($numeroIntroducido - 100 >= 0) {
                $numeroIntroducido -= 100;
                $resultado .= "C";

            } else if ($numeroIntroducido - 50 >= 0) {
                $numeroIntroducido -= 50;
                $resultado .= "L";

            } else if ($numeroIntroducido - 10 >= 0) {
                $numeroIntroducido -= 10;
                $resultado .= "X";

            } else if ($numeroIntroducido - 5 >= 0) {
                $numeroIntroducido -= 5;
                $resultado .= "V";

            } else if ($numeroIntroducido - 1 >= 0) {
                $numeroIntroducido -= 1;
                $resultado .= "I";
            }


        }
        echo "El número <strong>" . $numeroIntroducido . "</strong> en romano es <strong>" . $resultado . "</strong>";
        echo "</div>";
    }


    ?>


    <style>
        h1 {
            text-align: center;
        }

        #formulario {
            background-color: lightblue;
            border: solid 1px;
        }

        p {
            margin: 1rem
        }

        label {
            margin: 1rem
        }

        button {
            margin: 1rem
        }

        #respuesta {
            background-color: lightgreen;
            border: solid 1px;
            margin-top: 1rem
        }
    </style>

</body>

</html>