<?php
// comprobar los errores
if (isset($_POST["comprobar"])) {
    $numeroIntroducido = trim($_POST["texto"]);
    $error_texto_vacio = $numeroIntroducido == "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio3</title>
</head>

<body>
    <div id="formulario">
        <h1>Romanos a árabes - Formulario</h1>
        <p>Dime un número en números romanos y lo convertires a cifras árabes</p>
        <form method="post" action="">
            <label for="texto">Numero: </label>
            <input type="text" name="texto" id="texto"
                value="<?php if (isset($_POST["comprobar"]) && !$error_texto_vacio)
                    echo $numeroIntroducido; ?>"></input>
            <?php if (isset($_POST["comprobar"]) && $error_texto_vacio)
                echo "campo vacío"; ?>
            <br>
            <button name="comprobar" id="comprobar">Comprobar</button>
        </form>
    </div>

    <?php
    if (isset($_POST["comprobar"]) && !$error_texto_vacio) {
        echo "<div id ='respuesta'>";
        echo "<h1>Romanos a árabes - Resultado</h1>";
        $traducible = true;
        $resultado = 0;

        // recorrer todo el string introducido
        for ($i = 0; $i < strlen($numeroIntroducido); $i++) {
            // variables que se reinician para obtener valores de las letras en la posicion
            $valorActual = 0;
            $valorSiguiente = 0;
            // si la posicion que miramos es algun numero romano
            if ($numeroIntroducido[$i] == "I" || $numeroIntroducido[$i] == "V" || $numeroIntroducido[$i] == "X" || $numeroIntroducido[$i] == "L" || $numeroIntroducido[$i] == "C" || $numeroIntroducido[$i] == "D" || $numeroIntroducido[$i] == "M") {
                // segun la letra, obtendremos un valor
                switch ($numeroIntroducido[$i]) {
                    case "I":
                        $valorActual = 1;
                        break;
                    case "V":
                        $valorActual = 5;
                        break;
                    case "X":
                        $valorActual = 10;
                        break;
                    case "L":
                        $valorActual = 50;
                        break;
                    case "C":
                        $valorActual = 100;
                        break;
                    case "D":
                        $valorActual = 500;
                        break;
                    case "M":
                        $valorActual = 1000;
                        break;
                }

                // obtener el resultado
                $resultado += $valorActual;

               
                

                // Si el numero contiene algo que no sea un numero romano, decir que no se puede traducir y parar el bucle
            } else {
                $traducible = false;
                break;
            }
        }

        // si es traducible mostrarl el resultado, sino no
        if ($traducible) {
            echo "El resultado del numero <strong>" . $numeroIntroducido . "</strong> en árabe es: " . $resultado . "";
        } else {
            echo "El numero <strong>" . $numeroIntroducido . "</strong> no es traducible, está mal escrito.";
        }

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