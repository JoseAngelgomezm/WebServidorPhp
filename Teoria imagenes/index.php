<?php
// control errores de imagen
if (isset($_POST["enviar"])) {
    // cuando se sube una imagen se genera una variable llamada $_FILES
    // que contiene a su vez los arrays name, error, size, type, tmp_name                      // usado para saber si es un archivo de imagen o no, si no devuelve nada , no es una imagen
    $error_imagen_vacia = $_FILES["archivo"]["name"] == "" || $_FILES["archivo"]["error"] || !getimagesize($_FILES["archivo"]["tmp_name"]) || $_FILES["archivo"]["size"] > 500 * 1024;
}

if (isset($_POST["enviar"]) && !$error_imagen_vacia) {
// pagina con los datos de la imagen subida que se muestra si no hay errores al subir la imagen
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Datos de mi fichero imagen</h1>
    <?php
    // crear un id unico para esa imagen
    $nombreNuevo = md5(uniqid(uniqid(),true));
    // obtener la extension, separamos con explode por punto el nombre de archivo
    $arrayNombre = explode(".",$_FILES["archivo"]["name"]);
    $ext= "";
    // si tiene mas de una separacion por punto, significa que en el nombre hay puntos aparte de la extension
    // podria ser image.2.jpg
    // si tiene mas de un punto
    if(count($arrayNombre) > 1)
        $ext=".".end($arrayNombre);
   
    $nombreNuevo.=$ext;
    

    // movemos el archivo, de la ruta temporal a una carpeta
    @$var = move_uploaded_file($_FILES["archivo"]["tmp_name"], "images/".$nombreNuevo);
    if($var){
        echo "Imagen subida satisfactoriamente";
        echo "<p><strong>Nombre: </strong>".$_FILES["archivo"]["name"]."</p>";
        echo "<p><strong>tipo: </strong>".$_FILES["archivo"]["type"]."</p>";
        echo "<p><strong>tamaño: </strong>".$_FILES["archivo"]["size"]."</p>";
        echo "<p><strong>error: </strong>".$_FILES["archivo"]["error"]."</p>";
        echo "<p><strong>Nombre temporal con el que se guarda: </strong>".$_FILES["archivo"]["tmp_name"]."</p>";
        echo "<p><img src='images/".$nombreNuevo."' alt='Foto' title='Foto'></img>";
    }else{
        echo "No se ha podido mover la imagen a la carpeta destino del servidor";
    }
   


    ?>
</body>
</html>



<?php
} else {
    ?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <p>
                <label for="archivo"> Seleccione un archivo de image (Max 500KB): </label>
                <input type="file" name="archivo" id="archivo" accept="image/*" />
                <?php
                // si se ha ppulsado enviar y hay un error de imagen vacia
                if (isset($_POST["enviar"]) && $error_imagen_vacia) {
                    // si el nombre no está vacio, existe una imagen
                    if ($_FILES["archivo"]["name"] != "") {
                        // avisar por los distintos errores
                        if ($_FILES["archivo"]["error"]) {
                            echo "<span class='error'> no se ha podido subir el archivo al servidor</span>";
                        } else if (!getimagesize($_FILES["archivo"]["tmp_name"])) {
                            echo "<span class='error'> no se ha seleccionado un archivo de imagen</span>";
                        } else {
                            echo "<span class='error'> El archivo supera los 500KB, selecciona uno mas pequeño</span>";
                        }
                    }

                }// si el nombre no esta vacio, no avisar de ningun error, el type file ya avisa de que no hay sleecion de archivo
                ?>
            </p>

            <p>
                <button type="submit" name="enviar">Enviar</button>
            </p>
        </form>

        <style>
            .error {
                color: red;
            }
        </style>
    </body>

    </html>
    <?php
}
?>