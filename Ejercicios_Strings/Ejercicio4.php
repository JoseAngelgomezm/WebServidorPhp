<?php
// comprobar los errores
if (isset($_POST["comprobar"])) {
    $numeroIntroducido = trim(strtoupper($_POST["texto"]));
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
            <input type="text" name="texto" id="texto" value="<?php if (isset($_POST["comprobar"]))
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

        function numeroValido($textoString)
        {
            $traducible = true;
            for ($i = 0; $i < strlen($textoString); $i++) {
                // si la posicion que miramos es algun numero romano
                if (
                    $textoString[$i] == "I" || $textoString[$i] == "V" || $textoString[$i] == "X" ||
                    $textoString[$i] == "L" || $textoString[$i] == "C" || $textoString[$i] == "D" ||
                    $textoString[$i] == "M"
                ) {
                    $traducible = true;
                    // Si el numero contiene algo que no sea un numero romano, decir que no se puede traducir y parar el bucle
                } else {
                    $traducible = false;
                    return $traducible;
                }
            }
            return $traducible;
        }

        function orden_es_correcto($textoString)
        {
            $orden_correcto = true;
            for ($i = 0; $i < strlen($textoString) - 1; $i++) {
                // variable que se reinicia para obtener el valor de las letras en la posicion
                $valorActual = 0;
                $valorSiguiente = 0;

                // segun la letra, obtendremos un valor
                switch ($textoString[$i]) {
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
                if ($i + 1 < strlen($textoString)) {
                    // segun la letra, obtendremos un valor de la posicion siguiente
                    switch ($textoString[$i + 1]) {
                        case "I":
                            $valorSiguiente = 1;
                            break;
                        case "V":
                            $valorSiguiente = 5;
                            break;
                        case "X":
                            $valorSiguiente = 10;
                            break;
                        case "L":
                            $valorSiguiente = 50;
                            break;
                        case "C":
                            $valorSiguiente = 100;
                            break;
                        case "D":
                            $valorSiguiente = 500;
                            break;
                        case "M":
                            $valorSiguiente = 1000;
                            break;
                    }
                }
                // si el valor actual es menor o igual al siguiente y hay mas de un valor
                if ($i + 1 < strlen($textoString) && $valorActual >= $valorSiguiente) {
                    $orden_correcto = true;
                    // sino poner el orden a false y parar el bucle 
                } else {
                    $orden_correcto = false;
                    break;
                }
            }

            return $orden_correcto;
        }

        function se_repite($textoString)
        {
            $repeticiones_I = 0;
            $repeticiones_V = 0;
            $repeticiones_X = 0;
            $repeticiones_L = 0;
            $repeticiones_C = 0;
            $repeticiones_D = 0;
            $repeticiones_M = 0;
        
            // recorrer el string para contar las repeticiones de cada letra
            for ($i = 0; $i < strlen($textoString); $i++) {
                switch ($textoString[$i]) {
                    case "I":
                        $repeticiones_I++;
                        break;
                    case "V":
                        $repeticiones_V++;
                        break;
                    case "X":
                        $repeticiones_X++;
                        break;
                    case "L":
                        $repeticiones_L++;
                        break;
                    case "C":
                        $repeticiones_C++;
                        break;
                    case "D":
                        $repeticiones_D++;
                        break;
                    case "M":
                        $repeticiones_M++;
                        break;
                }
            }
        
            // Verificar repeticiones válidas
            if ($repeticiones_I > 3 || $repeticiones_V > 1 || $repeticiones_X > 3 || $repeticiones_L > 1 || $repeticiones_C > 3 || $repeticiones_D > 1 || $repeticiones_M > 3) {
                return true; 
            }else{
                return false;
            }
        
            
        }

        // si el numero es valido, tiene el orden correcto y no se repiten las letras el maximo de veces
        if (numeroValido($numeroIntroducido) && orden_es_correcto($numeroIntroducido) && !se_repite($numeroIntroducido)) {
            $resultado = 0;
            // recorrer el array para obtener el valor de la letra
            for ($i = 0; $i < strlen($numeroIntroducido); $i++) {
                $valorLetra = 0;
                switch ($numeroIntroducido[$i]) {
                    case "I":
                        $valorLetra = 1;
                        break;
                    case "V":
                        $valorLetra = 5;
                        break;
                    case "X":
                        $valorLetra = 10;
                        break;
                    case "L":
                        $valorLetra = 50;
                        break;
                    case "C":
                        $valorLetra = 100;
                        break;
                    case "D":
                        $valorLetra = 500;
                        break;
                    case "M":
                        $valorLetra = 1000;
                        break;
                }
                // y obtener el resultado
                $resultado += $valorLetra;

            }

            // mostrar los resultado
            echo "El numero <strong>" . $numeroIntroducido . "</strong> en árabe vale: " . $resultado . "";
            // si no se cumplen los requisitos , decir que esta mal escrito
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