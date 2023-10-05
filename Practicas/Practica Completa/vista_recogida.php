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
    // para recoger los datos, al enviar el formulario, se reciben aqui como un array
    // para acceder a ese array que se ha creado, se utilizara $_GET ó $_POST
    
    // existen arrays asociativos que se accede al array mediante el nombre del campo
    // que hemos puesto en el formulario (name="nombre") se accede ($_GET["nombre"] $_POST["nombre"])
    
    // accediendo a cada dato de $post
    echo "<p><strong>Nombre: </strong>" . $_POST["nombre"] . "</p>";
    echo "<p><strong>Apellidos: </strong>" . $_POST["apellidos"] . "</p>";
    echo "<p><strong>Dni: </strong>" . $_POST["dni"] . "</p>";
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

    // si la imagen se ha seleccionado
    if ($_FILES["image"]["name"] != "") {
        // obtener el nuevo nombre del archivo en md5
        $nombreNuevo = md5(uniqid(uniqid(), true));
        // obtener la extension, separamos con explode por punto el nombre de archivo
        $arrayNombre = explode(".", $_FILES["image"]["name"]);
        $ext = "";
        if(count($arrayNombre) > 1){
            $ext=".".end($arrayNombre);
        }
        // mover el archivo 
        move_uploaded_file($_FILES["image"]["tmp_name"], "images/".$nombreNuevo);
        // mostrar los datos
        echo "<p><strong>Nombre: </strong>" . $_FILES["image"]["name"] . "</p>";
        echo "<p><strong>Tipo: </strong>" . $_FILES["image"]["type"] . "</p>";
        echo "<p><strong>Error: </strong>" . $_FILES["image"]["error"] . "</p>";
        echo "<p><strong>Tamaño: </strong>" . $_FILES["image"]["size"] . "</p>";
        echo "<p><strong>ruta temporal: </strong>" . $_FILES["image"]["tmp_name"] . "</p>";
        echo "<img src='images/".$nombreNuevo."'></img>";

    }

    
    ?>
</body>


</html>