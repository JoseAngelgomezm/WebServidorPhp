<?php
session_name("EJERCICIO2_API_REST_23/24");
session_start();

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

if (isset($_POST["confirmarInsertar"])) {
    // comprobar errores
    $errorCodigo = $_POST["codigo"] == "";

    // controlar codigo repetido
    if (!$errorCodigo) {
        $url = URLATAQUE . "/repetido/producto/cod/" . urlencode($_POST["codigo"]) . "";
        $respuesta = consumir_servicios_REST($url, "get");
        $archivo = json_decode($respuesta);
        if (isset($archivo->mensaje)) {
            die($archivo->mensaje);
        }
        $errorCodigoRepetido = $archivo->respuesta;
    }

    $errorNombreCorto = $_POST["nombreCorto"] == "";
    if (!$errorNombreCorto) {
        // controlar nombrecorto repetido
        $url = URLATAQUE . "/repetido/producto/nombre_corto/" . urlencode($_POST["nombreCorto"]) . "";
        $respuesta = consumir_servicios_REST($url, "get");
        $archivo = json_decode($respuesta);
        if (isset($archivo->mensaje)) {
            die($archivo->mensaje);
        }

        $errorNombreCortoRepetido = $archivo->respuesta;
    }

    $errorDescripcion = $_POST["descripcion"] == "";
    $errorPVP = $_POST["PVP"] == "" || $_POST["PVP"] < 0;


    $errorFormularioInsercion = $errorCodigo || $errorNombreCorto || $errorDescripcion || $errorPVP || $errorCodigoRepetido || $errorNombreCortoRepetido;

    // si no hay errores, hago la insercion
    if (!$errorFormularioInsercion) {
        $url = URLATAQUE . "/producto/insertar";
        $datos["cod"] = $_POST["codigo"];
        $datos["nombre"] = $_POST["nombre"];
        $datos["nombre_corto"] = $_POST["nombreCorto"];
        $datos["descripcion"] = $_POST["descripcion"];
        $datos["PVP"] = $_POST["PVP"];
        $datos["familia"] = $_POST["familia"];

        $respuesta = consumir_servicios_REST($url, "post", $datos);
        $archivo = json_decode($respuesta);

        $_SESSION["mensaje"] = $archivo->respuesta;
    }


    // si existe el boton borrar
} else if (isset($_POST["borrar"])) {

    $url = URLATAQUE . "/producto/borrar/" . $_POST["borrar"] . "";
    $respuesta = consumir_servicios_REST($url, "delete");
    $archivo = json_decode($respuesta);
    $_SESSION["mensaje"] = $archivo->mensaje;


    // si existe el boton confirmar editar
} else if (isset($_POST["confirmarEditar"])) {
    // revisar errores
    $errorNombreCorto = $_POST["nombreCorto"] == "";

    // nombre corto repetido
    if (!$errorNombreCorto) {
        $url = URLATAQUE . "/repetido/producto/nombre_corto/" . urlencode($_POST["nombreCorto"]) . "/cod/" . urlencode($_POST["confirmarEditar"]) . "";
        $respuesta = consumir_servicios_REST($url, "get");
        $archivo = json_decode($respuesta);
        if (isset($archivo->mensaje)) {

            die($archivo->mensaje);
        }
        $errorNombreCortoRepetido = $archivo->respuesta;
    }

    $errorDescripcion = $_POST["descripcion"] == "";
    $errorPVP = $_POST["PVP"] == "" || $_POST["PVP"] < 0;


    $errorFormularioEdicion = $errorNombreCorto || $errorDescripcion || $errorPVP || $errorNombreCortoRepetido;

    if (!$errorFormularioEdicion) {
        //editar
        $datos["nombre"] = $_POST["nombre"];
        $datos["nombre_corto"] = $_POST["nombreCorto"];
        $datos["descripcion"] = $_POST["descripcion"];
        $datos["PVP"] = $_POST["PVP"];
        $datos["familia"] = $_POST["familia"];
        $url = URLATAQUE . "/producto/actualizar/" . $_POST["confirmarEditar"] . "";
        $respuesta = consumir_servicios_REST($url, "put", $datos);
        $archivo = json_decode($respuesta);
        if (isset($archivo->mensaje)) {
            die($archivo->mensaje);
        } else {
            $_SESSION["mensaje"] = $archivo->respuesta;
        }

    }
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
    // si existe un mensaje, mostrarlo
    if (isset($_SESSION["mensaje"])) {
        echo $_SESSION["mensaje"];
        unset($_SESSION["mensaje"]);
    }

    // si existe el boton insertar, mostrar el formulario o si existe confirmar insertar y hay errores
    
    if (isset($_POST["insertar"]) || isset($_POST["confirmarInsertar"]) && $errorFormularioInsercion || isset($_POST["editar"]) || isset($_POST["confirmarEditar"]) && $errorFormularioEdicion) {

        // si existe el boton editar, traerme los datos de ese producto
        if (isset($_POST["editar"])) {
            $url = URLATAQUE . "/producto/" . $_POST["editar"] . "";
            $respuesta = consumir_servicios_REST($url, "get");
            $archivo = json_decode($respuesta);

            $codigo = $archivo->producto->cod;
            $nombre = $archivo->producto->nombre;
            $nombreCorto = $archivo->producto->nombre_corto;
            $descripcion = $archivo->producto->descripcion;
            $PVP = $archivo->producto->PVP;
            $familia_producto = $archivo->producto->familia;
        } else if (isset($_POST["confirmarEditar"])) {
            $codigo = $_POST["confirmarEditar"];
        }

        ?>
        <form action="index.php" method="post">
            <p>
                <?php
                if (isset($_POST["insertar"]) || isset($_POST["confirmarInsertar"])) {
                    ?>
                    <label for="codigo">Codigo:</label>
                    <input type="text" name="codigo" value="<?php if (isset($_POST["codigo"])) {
                        echo $_POST["codigo"];
                    } else if (isset($_POST["editar"])) {
                        echo $codigo;
                    } ?>">
                    <?php

                    // mostrar el error
                    if (isset($_POST["confirmarInsertar"]) && $errorFormularioInsercion || isset($_POST["confirmarEditar"]) && $errorFormularioEdicion) {
                        if ($_POST["codigo"] == "") {
                            echo "<span class='rojo'>Campo vacio</span>";
                        } else if ($errorCodigoRepetido) {
                            echo "<span class='rojo'>Ya existe este codigo</span>";
                        }
                    }
                    ?>
                    <?php
                }
                ?>
            </p>

            <p>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" value="<?php if (isset($_POST["nombre"])) {
                    echo $_POST["nombre"];
                } else if (isset($_POST["editar"])) {
                    echo $nombre;
                } ?>">
            </p>

            <p>
                <label for="nombreCorto">Nombre corto:</label>
                <input type="text" name="nombreCorto" value="<?php if (isset($_POST["nombreCorto"])) {
                    echo $_POST["nombreCorto"];
                } else if (isset($_POST["editar"])) {
                    echo $nombreCorto;
                } ?>">
                <?php
                // mostrar el error
                if (isset($_POST["confirmarInsertar"]) && $errorFormularioInsercion || isset($_POST["confirmarEditar"]) && $errorFormularioEdicion) {
                    if ($_POST["nombreCorto"] == "") {
                        echo "<span class='rojo'>Campo vacio</span>";
                    } else if ($errorNombreCortoRepetido) {
                        echo "<span class='rojo'>Ya existe un producto con este nombre</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="descripcion">Descripcion:</label>
                <input type="text" name="descripcion" value="<?php if (isset($_POST["descripcion"])) {
                    echo $_POST["descripcion"];
                } else if (isset($_POST["editar"])) {
                    echo $descripcion;
                } ?>">
                <?php
                // mostrar el error
                if (isset($_POST["confirmarInsertar"]) && $errorFormularioInsercion || isset($_POST["confirmarEditar"]) && $errorFormularioEdicion) {
                    if ($_POST["descripcion"] == "") {
                        echo "<span class='rojo'>Campo vacio</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="PVP">PVP:</label>
                <input type="text" name="PVP" value="<?php if (isset($_POST["PVP"])) {
                    echo $_POST["PVP"];
                } else if (isset($_POST["editar"])) {
                    echo $PVP;
                } ?>">
                <?php
                // mostrar el error
                if (isset($_POST["confirmarInsertar"]) && $errorFormularioInsercion || isset($_POST["confirmarEditar"]) && $errorFormularioEdicion) {
                    if ($_POST["PVP"] == "") {
                        echo "<span class='rojo'>Campo vacio</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="familia">Selecione una familia:</label>

                <select name="familia" id="familia">

                    <?php
                    // obtener los valores de la familia
                    $url = URLATAQUE . "/familias";
                    $respuesta = consumir_servicios_REST($url, "get");
                    $archivo = json_decode($respuesta);

                    // si no recibimos un json, no obtenemos respuestas
                    if (!$archivo) {
                        die("<p>No se ha obtenido respuesta</p>");
                    }

                    // si existe el mensaje, es que hay error
                    if (isset($archivo->mensaje)) {
                        die($archivo->mensaje);
                    }

                    // montar una opcion con cada familia
                    foreach ($archivo->familia as $value) {
                        if ($_POST["familia"] === $value->cod) {
                            echo "<option selected value='" . $value->cod . "'>" . $value->nombre . "</option>";
                        } else if (isset($_POST["editar"]) && $familia_producto == $value->cod) {
                            echo "<option selected value='" . $value->cod . "'>" . $value->nombre . "</option>";
                        } else {
                            echo "<option value='" . $value->cod . "'>" . $value->nombre . "</option>";
                        }

                    }
                    ?>
                </select>
            </p>

            <p>
                <?php
                if (isset($_POST["insertar"]) || isset($_POST["confirmarInsertar"])) {
                    echo "<button type='submit' name='confirmarInsertar'>Confirmar Insertar</button>";
                } else if (isset($_POST["editar"]) || isset($_POST["confirmarEditar"])) {
                    echo "<button type='submit' value='" . $codigo . "' name='confirmarEditar'>Confirmar Editar</button>";
                }
                ?>
            </p>

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
        echo "<td><form action='index.php' method='post'><button name='borrar' type='submit' value='" . $valor->cod . "'>Borrar</button> - <button name='editar' type='submit' value='" . $valor->cod . "'>Editar</button></form></td>";
        echo "</tr>";
    }
    echo "</table>";


    ?>
</body>

</html>