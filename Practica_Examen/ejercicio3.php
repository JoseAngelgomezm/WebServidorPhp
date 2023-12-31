<?php
if (isset($_POST["contar"])) {
    $errorTexto = trim($_POST["texto"] == "");
}

function myStrlen($texto)
{
    $numeroCaracteres = 0;
    $i = 0;
    while (isset($texto[$i])) {
        $numeroCaracteres++;
        $i++;
    }
    return $numeroCaracteres;
}

function myExplode($texto, $separador)
{
    $contadorPalabra = 0;
    $arrayPalabras = [""];
    $nLetra = 0;
    while (isset($texto[$nLetra]) && $texto[$nLetra] == $separador) {
        $nLetra++;
    }

    while (isset($texto[$nLetra])) {
        // si la posicion de la palabra no es el separador
        if ($separador != $texto[$nLetra]) {
            // ir quedandonos con las letras de la palabra y añadiendola al array
            $arrayPalabras[$contadorPalabra] = $arrayPalabras[$contadorPalabra] . $texto[$nLetra];
        }
        // si encontramos un separador, aumentar en 1 la posicion en la que concatenamos las letras
        else if ($separador == $texto[$nLetra]) {
            $contadorPalabra++;
            $arrayPalabras[$contadorPalabra] = "";
        }
        $nLetra++;
    }
    return $arrayPalabras;
}

function explodeClase($texto, $separador)
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="post" action="#" enctype="multipart/form-data">
        <p>
            <label for="texto">Introduce un texto con separadores</label>
            <input type="text" name="texto" id="texto" value="<?php if (isset($_POST["contar"]))
                echo trim($_POST["texto"]) ?>"></input>
                <?php
            if (isset($_POST["contar"]) && $errorTexto) {
                echo "texto vacio";
            }
            ?>
        <p>
            Selecciona un separador
            <select name="separador" id="separador">
                <?php
                $arraySeparadores = array(";", ",", " ", ":");
                for ($i = 0; $i < count($arraySeparadores); $i++) {
                    if ($arraySeparadores[$i] == $_POST["separador"]) {
                        echo "<option selected value='" . $arraySeparadores[$i] . "'>" . $arraySeparadores[$i] . "</option>";
                    } else {
                        echo "<option value='" . $arraySeparadores[$i] . "'>" . $arraySeparadores[$i] . "</option>";
                    }
                }
                ?>
            </select>
        </p>

        </p>
        <button type="contar" id="contar" name="contar">Contar</button>
    </form>

    <?php
    if (isset($_POST["contar"]) && !$errorTexto) {
        $arrayPalabras = myExplode($_POST["texto"], $_POST["separador"]);

        echo "El texto contiene " . count($arrayPalabras) . " palabras";

        for ($i = 0; $i < count($arrayPalabras); $i++) {
            echo "<p>" . $arrayPalabras[$i] . "</p>";
        }
    }
    ?>
</body>

</html>