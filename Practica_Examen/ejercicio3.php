<?php
if (isset($_POST["contar"])) {
    $errorTexto = trim($_POST["texto"] == "");
}

function myExplode($texto, $separador)
{
    $textosinEspacios = trim($texto);
    $nLetra = 0;
    $contadorPalabra = 0;
    $arrayPalabras = [""];
    while (isset($textosinEspacios[$nLetra])) {
        // si la posicion de la palabra no es el separador
        if ($separador != $textosinEspacios[$nLetra]) {
            // ir quedandonos con las letras de la palabra y añadiendola al array
            $arrayPalabras[$contadorPalabra] = $arrayPalabras[$contadorPalabra] . $textosinEspacios[$nLetra];
        }
        // si encontramos un separador, aumentar en 1 la posicion en la que concatenamos las letras
        else if ($separador == $textosinEspacios[$nLetra]) {
            $contadorPalabra ++;
            $arrayPalabras[$contadorPalabra] = "";
        }
        $nLetra++;
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
        for ($i = 0; $i < count($arrayPalabras); $i++) {
            echo $arrayPalabras[$i]." ";
        }
        echo "El texto contiene " . count($arrayPalabras) . " palabras";
    }
    ?>
</body>

</html>