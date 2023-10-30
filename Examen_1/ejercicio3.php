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
if (isset($_POST["codificar"])) {
    $errorTexto = $_POST["texto"] == "";
    $errorDesplazamiento = !is_numeric(trim($_POST["desplazamiento"])) || trim($_POST["desplazamiento"]) == "" || $_POST["desplazamiento"] < 1 || $_POST["desplazamiento"] > 25;
    $errorArchivo = $_FILES["claves"]["name"] != "claves_cesar.txt" || $_FILES["claves"]["type"] != "text/plain" || $_FILES["claves"]["size"] > 125 * 1024 || $_FILES["claves"]["error"];

    $errorFormulario = $errorArchivo || $errorDesplazamiento || $errorTexto;
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
    <h1>Ejercicio 3. Codifica una frase</h1>
    <form action="#" method="post" enctype="multipart/form-data">
        <p>
            <label for="texto">Introduzca un Texto: </label>
            <input type="text" name="texto" id="texto" value='<?php if (isset($_POST["codificar"]))
                echo $_POST["texto"] ?>' />

                <?php
            if (isset($_POST["texto"]) && $errorTexto) {
                echo "<span>El texto está vacio</span>";
            }
            ?>
        </p>


        <p>
            <label for="desplazamiento">Desplazamiento</label>
            <input type="text" name="desplazamiento" id="desplazamiento" value='<?php if(isset($_POST["codificar"])) echo $_POST["desplazamiento"] ?>'>
            <?php
            if (isset($_POST["codificar"]) && $errorDesplazamiento) {
                echo "<span>Introduce un numero mayor que 0 y menor que 26</span>";
            }
            ?>
        </p>

        <p>
            <label for="claves">Selecciones el archivo de claves (.txt y menor 1,25MB)</label>
            <input type="file" name="claves" id="claves">
            <?php
            if (isset($_POST["codificar"]) && $errorArchivo) {
                if ($_FILES["claves"]["type"] != "text/plain") {
                    echo "<span>El archivo no es un fichero de texto plano</span>";
                } else if ($_FILES["claves"]["name"] != "claves_cesar.txt") {
                    echo "<span>El archivo no se llama claves_cesar.txt</span>";
                } else {
                    echo "<span>El archivo supera los 1,25 MB</span>";
                }

            }

            ?>
        </p>
        <p>
            <button name="codificar" id="codificar">Codificar</button>
        </p>
    </form>
    <?php
    if (isset($_POST["codificar"]) && !$errorFormulario) {
        @$fp = fopen("Ficheros/claves_cesar.txt", "r");
        if (!$fp) {
            die("<span>El archivo no se ha podido abrir</span>");
        } else {
            while ($linea = fgets($fp)) {
                $datosLinea[] = myExplode($linea, ";");
            }
        }
        fclose($fp);
        $arrayDesplazamiento = $datosLinea[$_POST["desplazamiento"] + 1];
        $arrayLetras = $datosLinea[1];
        $texto = $_POST["texto"];
        $i = 0;

        // transformar la palabra
        while (isset($texto[$i])) {
            // obtener la letra actual
            $letraActual = $texto[$i];
            // recorrer el array de letras
            for ($j = 0; $j < count($arrayLetras); $j++) {
                // si la letra actual esta en el array de letras
                if ($letraActual == $arrayLetras[$j]) {
                    // cambiarla por la letra de la posicion del la letra del array de desplazamiento
                    $texto[$i] = $arrayDesplazamiento[$j];
                }
            }
            $i++;
        }
        ;
        
        echo "La palabara transformada es " . $texto;
    }
    ?>
</body>

</html>