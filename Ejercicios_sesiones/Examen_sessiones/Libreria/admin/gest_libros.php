<?php
session_name("examen3_22_23");
session_start();
define("HOST", "localhost");
define("USERNAME", "jose");
define("PASSWORD", "josefa");
define("NAMEDB", "bd_libreria_exam");
define("TIEMPODEFINIDOSESSION", "120");
function error_page($title, $body)
{
    $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html .= '<title>' . $title . '</title></head>';
    $html .= '<body>' . $body . '</body></html>';
    return $html;
}
if (isset($_POST["salir"])) {
    session_destroy();
    header("location:index.php");
}

if (isset($_POST["editar"])) {

    $_SESSION["mensajeCRUD"] = "Se ha EDITADO con exito el libro con referencia " . $_POST["editar"] . "";
    header("location:gest_libros.php");
    exit();

} else if (isset($_POST["borrar"])) {

    $_SESSION["mensajeCRUD"] = "Se ha BORRADO con exito el libro con referencia " . $_POST["borrar"] . "";
    header("location:gest_libros.php");
    exit();

} else if (isset($_POST["agregar"])) {
    // controlar los errores
    $errorReferencia = $_POST["referencia"] == "" || !is_numeric($_POST["referencia"]) || $_POST["referencia"] <= 0;
    if (!$errorReferencia) {
        // que no se repita
        try {
            $conexion = mysqli_connect(HOST, USERNAME, PASSWORD, NAMEDB);
        } catch (Exception $e) {
            session_destroy();
            die(error_page("Error examen", "<p>No se ha podido conectar a la base de datos para comprobar usuario</p>"));
        }

        // buscar la referencia
        try {
            $consulta = "select * from libros where referencia='" . $_POST["referencia"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(error_page("Error examen", "<p>No se ha podido realizar la consulta a la bd para comprobar usuario</p>"));

        }

        mysqli_close($conexion);

        // SI TENGO TUPLA ESTA DUPLICADA
        if (mysqli_num_rows($resultado) > 0) {
            $errorReferencia = true;
            $errorReferenciaRepetida = true;
        }
    }
    $errorTitulo = $_POST["titulo"] == "";
    $errorAutor = $_POST["autor"] == "";
    $errorDescripcion = $_POST["descripcion"] == "";
    $errorPrecio = $_POST["precio"] == "" || !is_numeric($_POST["precio"]) || $_POST["precio"] <= 0;
    $errorPortada = false;

    $errorFormulario = $errorTitulo || $errorAutor || $errorDescripcion || $errorPrecio || $errorPortada;

    if (!$errorFormulario) {
        // insertar el libro
        // conectar
        try {
            $conexion = mysqli_connect(HOST, USERNAME, PASSWORD, NAMEDB);
        } catch (Exception $e) {
            session_destroy();
            die(error_page("Error examen", "<p>No se ha podido conectar a la base de datos para insertar libro</p>"));
        }

        // insertar el libro
        try {
            $consulta = "INSERT INTO libros(`referencia`, `titulo`, `autor`, `descripcion`, `precio`) VALUES ('" . $_POST["referencia"] . "','" . $_POST["titulo"] . "','" . $_POST["autor"] . "','" . $_POST["descripcion"] . "','" . $_POST["precio"] . "')";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(error_page("Error examen", "<p>No se ha podido realizar la consulta a la bd para insertar libro</p>"));
        }

        $_SESSION["mensajeCRUD"] = "<p>Se ha insertado un libro correctamente<p/>";

    }

    // si se ha seleccionado portada
    if ($_FILES["portada"]["name"] !== "") {
        // obtener extension
        $extensionDividida = explode(".", $_FILES["portada"]["name"]);
        $numeroPuntos = count($extensionDividida);
        $extension = end($extensionDividida);
        $errorFoto = !getimagesize($_FILES["portada"]["tmp_name"]) || $_FILES["name"]["size"] > 750 * 1024 || $numeroPuntos == 1;

        // si no hay errores en la foto
        if (!$errorFoto) {
            // mover la foto a la carpeta images
            @$fd = move_uploaded_file($_FILES["portada"]["tmp_name"], "../Images/" . $_POST["referencia"] . "." . $extension);
            // si se ha podido mover
            if ($fd) {
                // actualizar los datos del usuario
                try {
                    $conexion = mysqli_connect(HOST, USERNAME, PASSWORD, NAMEDB);
                } catch (Exception $e) {
                    session_destroy();
                    die(error_page("Error examen", "<p>No se ha podido conectar a la base de datos para update foto</p>"));
                }

                // insertar el libro
                try {
                    $consulta = "UPDATE libros SET portada='".$_POST["referencia"].".".$extension."' WHERE referencia='".$_POST["referencia"]."'";
                    $resultado = mysqli_query($conexion, $consulta);
                } catch (Exception $e) {
                    mysqli_close($conexion);
                    session_destroy();
                    die(error_page("Error examen", "<p>No se ha podido realizar la consulta a la bd para update foto</p>"));
                }

                // saltar con el mensaje
                $_SESSION["mensajeCRUD"] = "<p>Se ha insertado un libro con imagen correctamente</p>";
                header("location:gest_libros.php");
                exit();

            } else {
                unlink("../Images/" . $_POST["referencia"] . "." . $extension);
                $_SESSION["mensajeCRUD"] = "<p>No se ha podido mover la foto</p>";
            }
        }
    
        // este else hace que no se muestren los errores
    } else {
        header("location:gest_libros.php");
        exit();
    }
    /* a) Que no se quede ningún campo vacío (a excepción de la portada). Y si se selecciona portada, que
    ésta sea un archivo de tipo imagen con extensión y menor de 750KB. La portada subida se guardará en
    una carpeta “Images” con el nombre conveniente (img+Ref.extensión). Ejemplos de nombres correctos
    serían: img100.png, img105.jpg,... Para ello se recuerda la expresión: $last_id = mysqli_insert_id($conexion);
    b) Que no repita la Referencia y tanto ésta como el Precio sean un número positivo mayor que cero. */



}

if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "admin") {
    // control seguridad e inactividad

    // controlar la seguridad
    // INTENTAR LA CONEXION
    try {
        $conexion = mysqli_connect(HOST, USERNAME, PASSWORD, NAMEDB);
    } catch (Exception $e) {
        session_destroy();
        die(error_page("Error examen", "<p>No se ha podido conectar a la base de datos para comprobar usuario</p>"));
    }

    // INTENTAR EL LOGIN
    try {
        $consulta = "select * from usuarios where lector='" . $_SESSION["usuario"] . "'";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(error_page("Error examen", "<p>No se ha podido realizar la consulta a la bd para comprobar usuario</p>"));

    }

    mysqli_close($conexion);

    // SI NO OBTENGO TUPLAS, EL USUARIO LO HAN BORRADO
    if (mysqli_num_rows($resultado) < 0) {
        session_unset();
        $_SESSION["mensaje"] = "<p>Te han expulsado de la bd</p>";
        header("location:../index.php");
        exit();
    }

    // CONTROLAR TIEMPO INACTIVIDAD
    if (time() - $_SESSION["ultimaAccion"] > TIEMPODEFINIDOSESSION) {
        session_unset();
        $_SESSION["mensaje"] = "<p>Expulsado por inactividad</p>";
        header("location:../index.php");
        exit();
    }

    // SI TODO HA IDO BIEN; REINICIAR EL TIEMPO
    $_SESSION["ultimaAccion"] = time();

    // obtener los libros
    try {
        $conexion = mysqli_connect(HOST, USERNAME, PASSWORD, NAMEDB);
    } catch (Exception $e) {
        session_destroy();
        die(error_page("Error examen", "<p>No se ha podido conectar a la base de datos para obtener los libros</p>"));
    }

    // INTENTAR EL LOGIN
    try {
        $consulta = "select * from libros";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(error_page("Error examen", "<p>No se ha podido realizar la consulta a la bd para obtener los libros</p>"));

    }

    mysqli_close($conexion);

    // MONTAR LA WEB ADMIN
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            table {
                text-align: center;
                border-collapse: collapse;
                width: 50%;
            }

            td {
                border: solid 1px black;
            }
        </style>
    </head>

    <body>
        <p>Bienvenido <strong>
                <?php echo $_SESSION["usuario"] ?>
            </strong>
        <form method='post' action='../index.php'><button name='salir'>Salir</button></form>
        <?php if (isset($_SESSION["mensajeCRUD"]))
            echo $_SESSION["mensajeCRUD"] ?>
            </p>
            <h2>Listado de libros</h2>
            <table>
                <tr>
                    <td>Ref</td>
                    <td>titulo</td>
                    <td>Accion</td>
                </tr>
            <?php while ($datosLibros = mysqli_fetch_assoc($resultado)) {
            echo "<tr>
                <td>" . $datosLibros["referencia"] . "</td>
                <td>" . $datosLibros["titulo"] . "</td>
                <td><form method='post' action='gest_libros.php'><button name='editar' value='" . $datosLibros["referencia"] . "'>Editar</button><button name='borrar' value='" . $datosLibros["referencia"] . "'>Borrar</button></form></td>
            </tr>";
        } ?>

        </table>

        <h2>Agregar un nuevo libro</h2>
        <form action="gest_libros.php" method="post" enctype="multipart/form-data">
            <p>
                <label for="referencia">Referencia: </label>
                <input type="text" name="referencia" value="<?php if (isset($_POST["agregar"]))
                    echo $_POST["referencia"] ?>">
                    <?php
                if (isset($_POST["agregar"]) && $errorFormulario) {
                    if ($_POST["referencia"] == "") {
                        echo "<span>La referencia esta vacia</span>";
                    } else if (isset($errorReferenciaRepetida)) {
                        echo "<span>La referencia esta repetida</span>";
                    } else if (!is_numeric($_POST["referencia"])) {
                        echo "<span>La referencia tiene que ser numerica</span>";
                    } else if ($_POST["referencia"] <= 0) {
                        echo "<span>La referencia tiene que ser un valor mayor que 0</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="titulo">Titulo: </label>
                <input type="text" name="titulo" value="<?php if (isset($_POST["titulo"]))
                    echo $_POST["titulo"] ?>">
                    <?php
                if (isset($_POST["agregar"]) && $errorFormulario) {
                    if ($_POST["titulo"] == "") {
                        echo "<span>El titulo esta vacio</span>";
                    }
                }
                ?>
            </p>
            </p>

            <p>
                <label for="autor">Autor: </label>
                <input type="text" name="autor" value="<?php if (isset($_POST["autor"]))
                    echo $_POST["autor"] ?>">
                    <?php
                if (isset($_POST["agregar"]) && $errorFormulario) {
                    if ($_POST["autor"] == "") {
                        echo "<span>El autor esta vacio</span>";
                    }
                }
                ?>
            </p>
            </p>

            <p>
                <label for="descripcion">Descripcion: </label>
                <input type="text" name="descripcion" value="<?php if (isset($_POST["descripcion"]))
                    echo $_POST["descripcion"] ?>">
                    <?php
                if (isset($_POST["agregar"]) && $errorFormulario) {
                    if ($_POST["descripcion"] == "") {
                        echo "<span>La descripcion esta vacia</span>";
                    }
                }
                ?>
            </p>
            </p>

            <p>
                <label for="precio">Precio: </label>
                <input type="text" name="precio" value="<?php if (isset($_POST["precio"]))
                    echo $_POST["precio"] ?>">
                    <?php
                if (isset($_POST["agregar"]) && $errorFormulario) {
                    if ($_POST["precio"] == "") {
                        echo "<span>El precio esta vacio</span>";
                    } else if (!is_numeric($_POST["precio"])) {
                        echo "<span>El precio tiene que ser numerico</span>";
                    } else if ($_POST["precio"] <= 0) {
                        echo "<span>El precio tiene que ser un valor mayor que 0</span>";
                    }
                }
                ?>
            </p>
            </p>


            <p>
                <label for="portada">Portada: </label><input type="file" name="portada">
            </p>

            <p>
                <button type="submit" name="agregar">Agregar</button>
            </p>

        </form>
    </body>

    </html>





    <?php

} else {
    header("location:../index.php");
}

?>