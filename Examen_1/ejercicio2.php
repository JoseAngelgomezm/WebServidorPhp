<?php
function myExplode($texto, $separador)
{
    $arrayPalabras[] = "";
    $longitudTexto = strlen($texto) -1;
    $i = 0;
    while ($i < $longitudTexto && $texto[$i] == $separador) {
        $i++;
    }

    if ($i < $longitudTexto) {
        $j = 0;
        $arrayPalabras[$j] = $texto[$i];
        for ($i = $i +1; $i < $longitudTexto; $i++) {
            if ($texto[$i] != $separador) {
                $arrayPalabras[$j] .= $texto[$i];
            } else {
                while ($i < $longitudTexto && $texto[$i] == $separador) {
                        $i++;
                }
                if ($i < $longitudTexto) {
                    $j++;
                    $arrayPalabras[$j] = $texto[$i];
                }
            }
        }
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