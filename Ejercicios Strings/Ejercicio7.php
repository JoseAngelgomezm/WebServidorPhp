<?php
if (isset($_POST["enviar"])) {
    $numeroIntroducido = trim($_POST["texto"]);
    $error_numero_vacio = $numeroIntroducido == "";
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
        <h1>Unifica separador decimal - Formulario</h1>
        <p>Escribe varios números separados por espacios y unificaré el separador decimal a puntos.</p>
        <form method="post" action="">
            <label for="numeros">Numeros: </label>
            <input type="text" name="numeros" id="numeros" value="<?php if(isset($_POST["enviar"]) && $error_numero_vacio) echo $numeroIntroducido; ?>"></input>
            <?php if (isset($_POST["enviar"]) && $error_numero_vacio)echo "Texto vacío";?>
            <br>
            <button name="enviar" id="enviar">Enviar</button>
        </form>
    </div>

    <?php
    // resultado
    if (isset($_POST["enviar"]) && !$error_numero_vacio) {
        echo "<div id ='respuesta'>";
        echo "<h1>Quitar acentos - Resultado</h1>";

        

        echo "Texto original:<br>" . $numeroIntroducido . "";
        echo "<br>";
        echo "Texto sin acentos:<br>";
        echo "$resultado";
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