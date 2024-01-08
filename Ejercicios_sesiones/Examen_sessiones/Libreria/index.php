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

if (isset($_POST["entrar"])) {
    // comprobar errores
    $errorUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["contraseña"] == "";

    $errorFormulario = $errorUsuario || $errorContraseña;

    // si no hay errores de formulario, comprobar que existe el usuario
    if (!$errorFormulario) {

        // INTENTAR LA CONEXION
        try {
            $conexion = mysqli_connect(HOST, USERNAME, PASSWORD, NAMEDB);
        } catch (Exception $e) {
            session_destroy();
            die(error_page("Error examen", "<p>No se ha podido conectar a la base de datos para comprobar usuario</p>"));
        }

        // INTENTAR EL LOGIN
        try {
            $consulta = "select * from usuarios where lector='" . $_POST["usuario"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(error_page("Error examen", "<p>No se ha podido realizar la consulta a la bd para comprobar usuario</p>"));

        }


        mysqli_close($conexion);


        // SI TENGO TUPLAS
        if (mysqli_num_rows($resultado) > 0) {
            // el usuario logea
            $_SESSION["usuario"] = $_POST["usuario"];
            $_SESSION["ultimaAccion"] = time();
            $datosUsuario = mysqli_fetch_assoc($resultado);
            $_SESSION["tipo"] = $datosUsuario["tipo"];
            header("location:index.php");
            exit();
        } else {
            $_SESSION["errorCredenciales"] = "<p>Credenciales incorrectas</p>";
        }
    }
}

if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "admin") {
    
    header("location:admin/gest_libros.php");

} else if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "normal" || !isset($_SESSION["usuario"])) {
        // SI EL USUARIO ES TIPO NORMAL O NO EXISTE
        if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "normal") {
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
                header("location:index.php");
                exit();
            }

            // CONTROLAR TIEMPO INACTIVIDAD
            if (time() - $_SESSION["ultimaAccion"] > TIEMPODEFINIDOSESSION) {
                session_unset();
                $_SESSION["mensaje"] = "<p>Expulsado por inactividad</p>";
                header("location:index.php");
                exit();
            }

            // SI TODO HA IDO BIEN; REINICIAR EL TIEMPO
            $_SESSION["ultimaAccion"] = time();

        }

        // INTENTAR LA CONEXION
        try {
            $conexion = mysqli_connect(HOST, USERNAME, PASSWORD, NAMEDB);
        } catch (Exception $e) {
            session_destroy();
            die(error_page("Error examen", "<p>No se ha podido conectar a la base de datos para conseguir los libros</p>"));
        }

        // INTENTAR TRAER LOS LIBROS
        try {
            $consulta = "select * from libros";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(error_page("Error examen", "<p>No se ha podido realizar la consulta a la bd para traer los libros</p>"));
        }

        mysqli_close($conexion);

        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Examen</title>
            <style>
                div#listadoLibros {
                    display: flex;
                    flex-direction: row;
                    flex: 0 40%
                }

                div#listadoLibros div {
                    display: flex;
                    flex-direction: column;

                }

                img {
                    width: 100%;
                    height: auto;
                }
            </style>
        </head>

        <body>
            <h1>Libreria</h1>

            <?php
            if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "normal") {
                echo "<p>bienvenido <strong>" . $_SESSION["usuario"] . "</strong><form method='post' action='index.php'><button name='salir'>Salir</button></form></p>";
            } else {
                ?>
                <form action="index.php" method="post">
                    <p>
                        <label for="usuario">Ususario: </label>
                        <input type="text" name="usuario" maxlength="15" value="<?php if (isset($_POST["entrar"]))
                            echo $_POST["usuario"] ?>">
                            <?php
                        if (isset($_POST["entrar"]) && $errorFormulario) {
                            if ($_POST["usuario"] == "") {
                                echo "<span>Usuario vacio</span>";
                            }
                        }
                        ?>
                    </p>

                    <p>
                        <label for="contraseña">Contraseña: </label>
                        <input type="password" name="contraseña" maxlength="50">
                        <?php
                        if (isset($_POST["contraseña"]) && $errorFormulario) {
                            if ($_POST["contraseña"] == "") {
                                echo "<span>Contraseña vacia</span>";
                            }
                        }
                        ?>
                    </p>
                    <?php if (isset($_SESSION["errorCredenciales"]))
                        echo $_SESSION["errorCredenciales"] ?>
                        <p>
                            <button name="entrar" type="submit">Entrar</button>
                        </p>
                    </form>

                <?php if (isset($_SESSION["mensaje"])) {
                        echo $_SESSION["mensaje"];
                        session_destroy();
                    } ?>
                <?php
            }
            ?>


            <h2>Listado de los libros</h2>
            <div id="listadoLibros">
                <?php
                while ($datosLibros = mysqli_fetch_assoc($resultado)) {
                    echo "<div><img src='Images/" . $datosLibros["portada"] . "'><span>" . $datosLibros["titulo"] . " - " . $datosLibros["precio"] . "</span></div>";
                }
                ?>
            </div>

        </body>

        </html>

        <?php
    }
?>