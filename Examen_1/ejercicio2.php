<?php
function myExplode($texto, $separador)
{
    $contadorPalabra = 0;
    $arrayPalabras = [""];
    $nLetra = 0;

    // saltartse todos los separadores del principio
    while (isset($texto[$nLetra]) && $texto[$nLetra] == $separador) {
        $nLetra++;
    }

    while (isset($texto[$nLetra])) {
        // si la posicion de la palabra no es el separador
        if ($separador != $texto[$nLetra]) {
            // ir quedandonos con las letras de la palabra y añadiendola al array
            $arrayPalabras[$contadorPalabra] .= $texto[$nLetra];
            // si no hemos llegado al final y el siguiente es un separador
        }
        // si encontramos un separador, y no esta en la ultima posicion
        else if ($separador == $texto[$nLetra] && $nLetra < strlen($texto)) {
            $contadorPalabra++;
            $arrayPalabras[$contadorPalabra] = "";
        }
        $nLetra++;
    }
    return $arrayPalabras;
}

// control de errores
if (isset($_POST["contar"])) {
    $errorTexto = $_POST["texto"] == "";
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
    <h1>Ejercicio 2. Contar palabras sin las vocales (a,e,i,o,u,A,E,I,O,U)</h1>
    <form action="#" method="post">
        <p>
            <label for="texto"></label>
            <input type="text" name="texto" id="texto" value='<?php if (isset($_POST["contar"]))
                echo $_POST["texto"] ?>'>
                <?php
            if (isset($_POST["contar"]) && $_POST["texto"] == "") {
                echo "<span>El texto no puede estar vacio</span>";
            }
            ?>
        </p>
        <label for="texto">Elija un separador</label>
        <select name="separador" id="separador">
            <?php
            $arraySeparadores = array("Punto y coma" => ";", "Espacio" => " ", "Dos puntos" => ":", "Coma" => ",");
            foreach ($arraySeparadores as $indice => $valor) {
                if ($_POST["separador"] == $valor) {
                    echo "<option selected value='$valor'>" . $indice . "</option>";
                } else {
                    echo "<option value='$valor'>" . $indice . "</option>";
                }

            }
            ?>
        </select>
        <p>
            <button name="contar" id="contar">Contar</button>
        </p>
    </form>
    <?php

    if (isset($_POST["contar"]) && !$errorTexto) {
        $arrayPalabras = myExplode($_POST["texto"], $_POST["separador"]);
        echo "<h1>Respuesta</h1>";


        echo "Hay " . count($arrayPalabras) . " Palabras";
    }
    ?>
</body>

</html>