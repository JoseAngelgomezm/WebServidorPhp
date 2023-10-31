<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Los datos enviados son:</h1>


    <?php
    echo "<p><strong>El nombre enviado ha sido: </strong>" . $_POST["nombre"] . "</p>";
    echo "<p><strong>Ha nacido en: </strong>" . $_POST["nacido"] . "</p>";
    echo "<p><strong>El sexo es: </strong>" . $_POST["sexo"] . "</p>";

    if (isset($_POST["deportes"]) && isset($_POST["lectura"]) && isset($_POST["otros"])) {
        echo    "<ol>
                    <li>Deportes</li>
                    <li>Lectura</li>
                    <li>Otros</li>
                </ol>";
    } else if (isset($_POST["deportes"]) && isset($_POST["lectura"])) {
        echo    "<ol>
                    <li>Deportes</li>
                    <li>Lectura</li>
                </ol>";
    } else if (isset($_POST["deportes"]) && isset($_POST["otros"])) {
        echo    "<ol>
                <li>Deportes</li>
                <li>Otros</li>
                </ol>";
    }else if (isset($_POST["lectura"]) && isset($_POST["otros"])) {
        echo    "<ol>
                <li>Lectura</li>
                <li>Otros</li>
                </ol>";
    }else if(isset($_POST["deportes"])){
        echo    "<ol>
                    <li>Deportes</li>
                </ol>";
    }else if(isset($_POST["lectura"])){
        echo    "<ol>
                    <li>lectura</li>
                </ol>";
    }else if(isset($_POST["otros"])){
        echo    "<ol>
                    <li>otros</li>
                </ol>";
    }else{
        echo "<p><strong>No has seleccionado ninguna aficion</strong></p>";
    }

   


    if (($_POST["comentarios"]) == "") {
        echo "<p><strong>No has hecho ningun comentario</strong></p>";

    } else {
        echo "<p><strong>El comentario ha sido: </strong>" . $_POST["comentarios"] . "</p>";
    }

    ?>

</body>

</html>