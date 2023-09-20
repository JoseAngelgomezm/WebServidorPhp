<?php
// primero de todo comprobar que existe un formulario
if (isset($_POST["guardar"])) {

    // romper el php para incrustar html
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <h1>Recogida datos</h1>
        <?php

        // creacion de un array
        $a[0] = 3;
        $a[1] = 6;
        $a[2] = -1;
        $a[3] = 8;

        // count es el .length del array
        for ($i = 0; $i < count($a); $i++) {
            echo "<p>Numero: " . $a[$i] . "</p>";
        }



        // para recoger los datos, al enviar el formulario, se reciben aqui como un array
        // para acceder a ese array que se ha creado, se utilizara $_GET ó $_POST
    
        // existen arrays asociativos que se accede al array mediante el nombre del campo
        // que hemos puesto en el formulario (name="nombre") se accede ($_GET["nombre"] $_POST["nombre"])
    
        // accediendo a cada dato de $post
        echo "<p><strong>Nombre: </strong>" . $_POST["nombre"] . "</p>";
        echo "<p><strong>Apellidos: </strong>" . $_POST["apellidos"] . "</p>";
        echo "<p><strong>Clave: </strong>" . $_POST["contraseña"] . "</p>";
        echo "<p><strong>Nacido en: </strong>" . $_POST["provincia"] . "</p>";
        echo "<p><strong>Comentarios al respecto: </strong>" . $_POST["comentarios"] . "</p>";

        // acceso a un radiobutton controlando que no se selecciona nada
        if (isset($_POST["sexo"])) {
            echo "<p><strong>Sexo: </strong>" . $_POST["sexo"] . "</p>";
        } else {
            echo "<p><strong>Sexo: </strong> No Seleccionado </p>";
        }

        // acceso a un checkbox de boletin
        if (isset($_POST["suscrito"])) {
            echo "<p><strong>Suscrito al boletin: </strong> Sí </p>";
        } else {
            echo "<p><strong>Suscrito al boletin: </strong> No </p>";
        }
        ?>
    </body>


    </html>
<?php
}

// si no existe, enviar a la pagina inicial
else {
    header("location:index.php");
}
?>