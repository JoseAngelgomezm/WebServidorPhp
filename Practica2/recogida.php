<?php
if (isset($_POST["guardar"])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <h1>Recogida de datos</h1>
        <?php
        echo "<p><strong>Nombre: </strong> " . $_POST["nombre"] . "</p>";
        echo "<p><strong>Nacido en: </strong> " . $_POST["provincia"] . "</p>";
        if (isset($_POST["sexo"])) {
            echo "<p><strong>Sexo: </strong> " . $_POST["sexo"] . "</p>";
        } else {
            echo "<p><strong>Sexo: </strong> Sexo no marcado </p>";
        }
        

        if (isset($_POST["lectura"]) && isset($_POST["otros"]) && isset($_POST["deportes"])) {
            echo "<p><strong>Aficiones: </strong> lectura, otros, deportes </p>";
        } else if (isset($_POST["lectura"]) && isset($_POST["otros"])) {
            echo "<p><strong>Aficiones: </strong> lectura, otros </p>";
        } else if (isset($_POST["deportes"]) && isset($_POST["otros"])) {
            echo "<p><strong>Aficiones: </strong> deportes, otros </p>";
        } else if (isset($_POST["deportes"]) && isset($_POST["lectura"])) {
            echo "<p><strong>Aficiones: </strong> desportes, lectura </p>";
        } else if (isset($_POST["otros"])) {
            echo "<p><strong>Aficiones: </strong> otros </p>";
        } else if (isset($_POST["deportes"])) {
            echo "<p><strong>Aficiones: </strong> deportes </p>";
        } else if (isset($_POST["lectura"])) {
            echo "<p><strong>Aficiones: </strong> lectura </p>";
        }

        echo "<p><strong>comentarios: </strong> " . $_POST["comentarios"] . "</p>";

        ?>
    </body>

    </html>
    <?php
} else {
    header("location:index.html");
}

?>