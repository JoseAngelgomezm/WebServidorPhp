<?php
if (isset($_POST["enviar"])) {
    $textoIntroducido = trim($_POST["texto"]);
    $error_texto_vacio = $textoIntroducido == "";
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
        <h1>Quitar acentos - Formulario</h1>
        <p>Escribe un texto y le quitaré los acentos.</p>
        <form method="post" action="">
            <label for="texto">Texto: </label>
            <textarea name="texto" id="texto" rows="3" col="4" style="resize:none;"><?php if(isset($_POST["enviar"]) && !$error_texto_vacio) echo $textoIntroducido;?></textarea>
            <?php if (isset($_POST["enviar"]) && $error_texto_vacio)echo "Texto vacío";?>
            <br>
            <button name="enviar" id="enviar">Enviar</button>
        </form>
    </div>

    <?php
    function eliminarAcentos($cadenaTexto){
        $vocalesBuscar = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "Ñ");
        $vocalesSustituir = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N");

        return str_replace($vocalesBuscar, $vocalesSustituir, $cadenaTexto);
    }

    // resultado
    if (isset($_POST["enviar"]) && !$error_texto_vacio) {
        echo "<div id ='respuesta'>";
        echo "<h1>Quitar acentos - Resultado</h1>";

        $resultado = eliminarAcentos($textoIntroducido);

        echo "Texto original:<br>" . $textoIntroducido . "";
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