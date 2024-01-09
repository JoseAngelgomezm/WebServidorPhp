<?php
session_name("examen3_23_24");
session_start();
define("USER", "jose");
define("PASSWD", "josefa");
define("ADDRESS", "localhost");
define("NAMEBD", "bd_libreria_exam");
define("TIEMPOINACTIVIDAD", "60");
if (isset($_POST["salir"])) {
    session_destroy();
    header("location:../index.php");
}

if (isset($_POST["editar"])) {
    $_SESSION["mensaje"] = "Se ha EDITADO con exito el libro con referencia " . $_POST["editar"] . "";
    mysqli_close($conexion);
    header("location:gest_libros.php");
}

if (isset($_POST["borrar"])) {
    $_SESSION["mensaje"] = "Se ha ELIMINADO con exito el libro con referencia " . $_POST["borrar"] . "";
    mysqli_close($conexion);
    header("location:gest_libros.php");
}

if (isset($_POST["agregar"])) {
    $errorReferencia = $_POST["referencia"] == "" || !is_numeric($_POST["referencia"]);
    if (!$errorReferencia) {
        // comprobar que no se repita
        // conectarse
        try {
            $conexion = mysqli_connect(ADDRESS, USER, PASSWD, NAMEBD);
        } catch (Exception $e) {
            die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
        }

        // comprobar que existe el usuario y la contraseña
        try {
            $resultado = mysqli_query($conexion, "select * from libros where referencia='" . $_POST["referencia"] . "'");
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
        }

        // si obtengo tuplas
        if (mysqli_num_rows($resultado) > 0) {
            $errorReferencia = true;
        }
    }
    $errorTitulo = $_POST["titulo"] == "";
    $errorAutor = $_POST["autor"] == "";
    $errorDescripcion = $_POST["descripcion"] == "";
    $errorPrecio = $_POST["precio"] == "" || !is_numeric($_POST["precio"]);
    $arrayPortada = !explode(".", $_FILES["portada"]["name"]);
    $errorPortada = $_FILES["portada"]["name"] !== "" && ($_FILES["portada"]["error"] || !$arrayPortada || !getimagesize($_FILES["portada"]["tmp_name"]) || $FILES["portada"]["size"] > 750 * 1024);

    $errorFormularioAgregar = $errorReferencia || $errorTitulo || $errorAutor || $errorDescripcion || $errorPrecio || $errorPortada;

    if (!$errorFormularioAgregar) {
        // insertar
        try {
            $resultado = mysqli_query($conexion, "INSERT INTO `libros`(`referencia`, `titulo`, `autor`, `descripcion`, `precio`) VALUES ('" . $_POST["referencia"] . "','" . $_POST["titulo"] . "','" . $_POST["descripcion"] . "','" . $_POST["precio"] . "'");
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
        }

        $_SESSION["mensaje"]= "se ha insertado un nuevo libro";
    }

    if (!$errorFormularioAgregar && $_FILES["portada"]["name"] !== "") {
        // subir la foto
        $extension = end($arrayPortada);
        $nombreNuevo = "img" . $_POST["referencia"] . "." . $ext;
        @$var = move_uploaded_file($_FILES["portada"]["tmp_name"], "..img/" . $nombreNuevo);
        if ($var) {
            try {
                $resultado = mysqli_query($conexion, "UPDATE libros SET portada='".$nombreNuevo."' where referencia='".$_POST["referencia"]."' ");
            } catch (Exception $e) {
                unlink("..img/".$nombreNuevo);
                $_SESSION["mensaje"].= " pero con la foto por defecto";
                mysqli_close($conexion);
                session_destroy();
                die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
            }

            $_SESSION["mensaje"].= " con la foto selccionada";
        }
    }

}

if (isset($_SESSION["usuario"])) {
    try {
        $conexion = mysqli_connect(ADDRESS, USER, PASSWD, NAMEBD);
    } catch (Exception $e) {
        die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
    }
    // obtener el usuario actual
    try {
        $resultado = mysqli_query($conexion, "select * from usuarios where lector='" . $_SESSION["usuario"] . "' and clave='" . $_SESSION["contraseña"] . "' ");
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
    }

    // si no obtengo tuplas
    if (mysqli_num_rows($resultado) <= 0) {
        session_unset();
        $_SESSION["mensaje"] = "no se encuentra el base de datos";
        header("location:index.php");
    }

    // comprobar inactividad
    if (time() - $_SESSION["ultimaAccion"] > TIEMPOINACTIVIDAD) {
        session_unset();
        $_SESSION["mensaje"] = "tiempo de inactividad caducado";
        header("location:index.php");
    }

    // obtener los datos del usuario para obtener tipo
    $datosUsuarioLogeado = mysqli_fetch_assoc($resultado);

    // controlar que solo entren los tipo normal
    if ($datosUsuarioLogeado["tipo"] == "normal") {
        header("location:../index.php");
    } else {

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
        <?php echo "<p>Bienvenido " . $_SESSION["usuario"] . "</p>"; ?>
        <form action="../index.php" method="post">
            <button type="submit" name="salir">Salir</button>
        </form>
        <?php
        if (isset($_SESSION["mensaje"])) {
            echo "<p>" . $_SESSION["mensaje"] . "</p>";
            unset($_SESSION["mensaje"]);
        }
        ?>
        <table border="solid 1px black">
            <tr>
                <td>Referencia</td>
                <td>Titulo</td>
                <td>Accion</td>
            </tr>
            <?php
            // conectarse para listar 
            try {
                $conexion = mysqli_connect(ADDRESS, USER, PASSWD, NAMEBD);
            } catch (Exception $e) {
                die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
            }

            try {
                $consulta = "SELECT * FROM libros";
                $resultado = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                session_unset();
                die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
            }

            while ($datosLibros = mysqli_fetch_assoc($resultado)) {
                echo "<tr>
                    <td>" . $datosLibros["referencia"] . "</td>
                    <td>" . $datosLibros["titulo"] . "</td>
                    <td><form action='' method='post'><button name='editar' value='" . $datosLibros["referencia"] . "'>Editar</button><button name='borrar' value='" . $datosLibros["referencia"] . "'>Borrar</button> </form></td>
                </tr>";
            }
            ?>
        </table>
        <h3>Agregar Libros </h3>
        <form action="gest_libros.php" method="post" enctype="multipart/form-data">
            <p>
                <label for="referencia">Referencia</label>
                <input type="text" name="referencia" id="referencia" value="<?php if (isset($_POST["referencia"]))
                    echo $_POST["referencia"] ?>">
                    <?php
                if (isset($_POST["agregar"]) && $errorReferencia) {
                    if ($_POST["referencia"] == "") {
                        echo "<span>El campo esta vacio</span>";
                    } else if (!is_numeric($_POST["referencia"])) {
                        echo "la referencia tiene que ser numerica";
                    } else {
                        echo "<span>La referencia ya existe</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="titulo">Titulo</label>
                <input type="text" name="titulo" id="titulo" value="<?php if (isset($_POST["titulo"]))
                    echo $_POST["titulo"] ?>">
                    <?php
                if (isset($_POST["agregar"]) && $errorTitulo) {
                    if ($_POST["titulo"] == "") {
                        echo "<span>El campo esta vacio</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="autor">Autor</label>
                <input type="text" name="autor" id="autor" value="<?php if (isset($_POST["autor"]))
                    echo $_POST["autor"] ?>">
                    <?php
                if (isset($_POST["agregar"]) && $errorAutor) {
                    if ($_POST["autor"] == "") {
                        echo "<span>El campo esta vacio</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="descripcion">Descripcion</label>
                <textarea name="descripcion" id="descripcion" cols="30" rows="10" value="<?php if (isset($_POST["descripcion"]))
                    echo $_POST["descripcion"] ?>"></textarea>
                    <?php
                if (isset($_POST["agregar"]) && $errorDescripcion) {
                    if ($_POST["descripcion"] == "") {
                        echo "<span>El campo esta vacio</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" value="<?php if (isset($_POST["precio"]))
                    echo $_POST["precio"] ?>">
                    <?php
                if (isset($_POST["agregar"]) && $errorPrecio) {
                    if ($_POST["precio"] == "") {
                        echo "<span>El campo esta vacio</span>";
                    } else if (!is_numeric($_POST["precio"])) {
                        echo "<span>El campo tiene que ser un numero</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="portada">Portada</label>
                <input type="file" accept="image/*" name="portada" id="portada">
                <?php
                if (isset($_POST["agregar"]) && $errorPortada) {
                    if (!explode(".", $_FILES["portada"]["name"])) {
                        echo "<span>La imagen no tiene extension</span>";
                    } else if (!getimagesize($arrayPortada)) {
                        echo "<span>Solo se aceptan imagenes</span>";
                    } else if ($FILES["portada"]["size"] > 750 * 1024) {
                        echo "<span>Supera el tamaño permitido</span>";
                    }
                }
                ?>
            </p>

            <p>
                <button name="agregar" type="submit">Agregar</button>
            </p>
        </form>
    </body>

    </html>
    <?php
} else {
    header("location:../index.php");
}

mysqli_close($conexion);
?>