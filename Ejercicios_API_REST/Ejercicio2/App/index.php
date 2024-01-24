<?php
function consumir_servicios_REST($url, $metodo, $datos = null)
{
    $llamada = curl_init();
    curl_setopt($llamada, CURLOPT_URL, $url);
    curl_setopt($llamada, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($llamada, CURLOPT_CUSTOMREQUEST, $metodo);
    if (isset($datos))
        curl_setopt($llamada, CURLOPT_POSTFIELDS, http_build_query($datos));
    $respuesta = curl_exec($llamada);
    curl_close($llamada);
    return $respuesta;
}

define("URLATAQUE", "localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/Ejercicio2/Api");

if(isset($_POST["confirmarInsertar"])){
    // comprobar errores
    $errorCodigo= $_POST["codigo"] == "";
    $errorNombreCorto = $_POST["nombreCorto"] == "";
    $errorDescripcion = $_POST["descripcion"] == "";
    $errorPVP = $_POST["PVP"] == "" || $_POST["PVP"] < 0;


    $errorFormularioInsercion = $errorCodigo || $errorNombreCorto || $errorDescripcion || $errorPVP;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            border: solid 2px black;
            border-collapse: collapse;
            text-align: center;
            width: 100%;
        }

        table tr td {
            border: solid 2px black;

        }

        table tr:nth-child(2n) {
            background-color: aquamarine
        }

        table tr th {
            border: solid 2px black;
        }

        .rojo {
            color: crimson;
        }
    </style>
</head>

<body>
    <h1>Ejercicio 2</h1>
    <h2>Listado de productos </h2>
            <?php
            // si existe el boton insertar, mostrar el formulario
            if (isset($_POST["insertar"])) {
                ?>
                <form action="index.php" method="post">

                    <label for="nombre_corto">Codigo:</label>
                    <input type="text" name="codigo" value="<?php if (isset($_POST["codigo"]))
                        echo $_POST["codigo"] ?>">
                        <?php

                    // mostrar el error
                    if (isset($_POST["confirmarInserccion"]) && $errorFormularioInsercion) {
                        if ($_POST["codigo"] == "") {
                            echo "<span class='rojo'>Campo vacio</span>";
                        }
                    }
                    ?>
                    <label for="nombre_corto">Nombre:</label>
                    <input type="text" name="nombre" value="<?php if (isset($_POST["nombre"]))
                        echo $_POST["nombre"] ?>">
                        <?php
                    // mostrar el error
                    if (isset($_POST["confirmarInserccion"]) && $errorFormularioInsercion) {
                        if ($_POST["nombre"] == "") {
                            echo "<span class='rojo'>Campo vacio</span>";
                        }
                    }
                    ?>

                    <label for="nombreCorto">Nombre corto:</label>
                    <input type="text" name="nombreCorto" value="<?php if (isset($_POST["nombreCorto"]))
                        echo $_POST["nombreCorto"] ?>">
                        <?php
                    // mostrar el error
                    if (isset($_POST["confirmarInserccion"]) && $errorFormularioInsercion) {
                        if ($_POST["nombreCorto"] == "") {
                            echo "<span class='rojo'>Campo vacio</span>";
                        }
                    }
                    ?>

                    <label for="nombre_corto">Descripcion:</label>
                    <input type="text" name="descripcion" value="<?php if (isset($_POST["descripcion"]))
                        echo $_POST["descripcion"] ?>">
                        <?php
                    // mostrar el error
                    if (isset($_POST["confirmarInserccion"]) && $errorFormularioInsercion) {
                        if ($_POST["descripcion"] == "") {
                            echo "<span class='rojo'>Campo vacio</span>";
                        }
                    }
                    ?>

                    <label for="PVP">PVP::</label>
                    <input type="text" name="PVP" value="<?php if (isset($_POST["PVP"]))
                        echo $_POST["PVP"] ?>">
                        <?php
                    // mostrar el error
                    if (isset($_POST["confirmarInserccion"]) && $errorFormularioInsercion) {
                        if ($_POST["PVP"] == "") {
                            echo "<span class='rojo'>Campo vacio</span>";
                        }
                    }
                    ?>

                   

                    <select name="familia" id="familia">Seleccion una familia
                    <?php
                        // obtener los valores de la familia
                        $url = URLATAQUE."/familias";
                        $respuesta = consumir_servicios_REST($url,"get");
                        $archivo = json_decode($respuesta);

                        // si no recibimos un json, no obtenemos respuestas
                        if(!$archivo){
                            die("<p>No se ha obtenido respuesta</p>");
                        }

                        // si existe el mensaje, es que hay error
                        if(isset($archivo->mensaje)){
                            die($archivo->mensaje);
                        }
                    
                        // montar una opcion con cada familia
                        foreach ($archivo->familia as $value) {
                            echo "<option value='".$value->cod."'>".$value->nombre."</option>";
                        }
                    ?>
                    </select>
                    
                    <button type="submit" name="confirmarInsertar">Insertar</button>

                </form>
                <?php
            }
            ?>
            
            <?php
            // listar todos los productos llamando al metodo get que tenemos en la api de productos
            $url = URLATAQUE . "/productos";
            $respuesta = consumir_servicios_REST($url, "get");
            $archivo = json_decode($respuesta);

            // si no hay nada en el archivo, informar
            if (!$archivo) {
                die("<p>No se ha obtenido respuesta</p>");
            }

            // si el archivo contiene mensaje, es que hay un error, mostrar ese error
            if (isset($archivo->mensaje)) {
                die($archivo->mensaje);
            }

            $datos = $archivo->productos;


            // los datos de los productos, lo recibimos en $archivo->productos
            // tabla
            echo "<table>";
            echo "<tr>";
            echo "<th>COD</th>";
            echo "<th>Nombre</th>";
            echo "<th>PVP</th>";
            echo "<th><form action='index.php' method='post'><button name='insertar' type='submit'>Insertar</button></form></td>";
            echo "</tr>";

            foreach ($datos as $valor) {
                echo "<tr>";
                echo "<td>" . $valor->cod . "</td>";
                echo "<td>" . $valor->nombre_corto . "</td>";
                echo "<td>" . $valor->PVP . "</td>";
                echo "<td><form action='index.php' method='post'><button name='borrar' type='submit'>Borrar</button> - <button name='editar' type='submit'>Editar</button></form></td>";
                echo "</tr>";
            }
            echo "</table>";


            ?>
</body>

</html>