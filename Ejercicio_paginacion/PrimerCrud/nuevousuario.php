<?php
function errorPagina($titulo, $body)
{
    $page = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . $titulo . '</title>
    </head>
    <body>
        ' . $body . '
    </body>
    </html>';
    return $page;
}

define("HOST", "localhost");
define("USERNAME", "jose");
define("PASSWORD", "josefa");
define("NAMEDB", "bd_foro");

// si se han pulsado alguno de los 2 botones, el de continuar de esta pagina
// o el boton nuevo usuario del index
if (isset($_POST["nuevousuario"]) || isset($_POST["continuar"])) {

    // si se ha pulsado el boton continuar comprobar los errores
    if (isset($_POST["continuar"])) {
        $errorNombre = $_POST["nombre"] == "" || strlen($_POST["nombre"]) > 30;
        $errorUsuario = $_POST["usuario"] == "" || strlen($_POST["usuario"]) > 20;

        // si no hay error de usuario comprobar que no esta repetido
        if (!$errorUsuario) {

            // intentar la conexion
            $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

            // intentar la consulta
            try {
                $consulta = "select * from usuarios where usuario=?";
                $sentencia = $conexion->prepare($consulta);
                $sentencia->execute([$_POST["usuario"]]);
            } catch (Exception $e) {
                $sentencia = null;
                $conexion = null;
                die(errorPagina("Practica1 CRUD error", "<p>usuario No se ha podido hacer la consulta</p>"));
            }
            // si tiene mas de 1 tupla el nombre de usuario ya existirá, error de usuario sera true
            $errorUsuario = $sentencia->rowCount() > 0;
            $sentencia = null;
            $conexion = null;
        }

        $errorContraseña = $_POST["contraseña"] == "" || strlen($_POST["contraseña"]) > 15;

        $errorEmail = $_POST["email"] == "" || strlen($_POST["email"]) > 50 || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
        // si no hay error de email, comprobar que no este repetido
        if (!$errorEmail) {
            // comprobar si la conexion esta abierta
            if (!isset($conexion)) {
                // intentar la conexion
                $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

                // intentar la consulta
                try {
                    $consulta = "select * from usuarios where email=?";
                    $sentencia = $conexion->prepare($consulta);
                    $sentencia->execute([$_POST["email"]]);
                } catch (Exception $e) {
                    $sentencia = null;
                    $conexion = null;
                    die(errorPagina("Practica1 CRUD error", "<p>usuario No se ha podido hacer la consulta</p>"));
                }
                // si tiene mas de 1 tupla el email ya existirá, error de email sera true
                $errorEmail = $sentencia->rowCount() > 0;
                $sentencia = null;
                $conexion = null;
            }
        }

        $errorFormulario = $errorNombre || $errorUsuario || $errorContraseña || $errorEmail;

        // si no hay errores, insertar los datos y llevarnos al index que ya se veran los nuevos datos
        if (!$errorFormulario) {
            // intentar la conexion
            $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

            // hacer la consulta insert
            try {
                $consulta = "insert into usuarios(nombre, usuario, clave, email) VALUES (?,?,?,?)";
                $sentencia = $conexion->prepare($consulta);
                $claveEncriptada = md5($_POST["clave"]);
                $sentencia->execute([$_POST["nombre"], $_POST["usuario"], $claveEncriptada, $_POST["email"]]);
            } catch (Exception $e) {
                $sentencia = null;
                $conexion = null;
                die(errorPagina("Error insertar", "<p>Error de inserccion en la base de datos</p>"));
            }

            $sentencia = null;
            $conexion = null;

            // enviarnos al index
            header("location:index.php");
        }
    }
    ?>
    <!-- mostrar la web con el formulario manteniendo campos -->
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>nuevo usuario</title>
    </head>

    <body>
        <h1>Nuevo usuario</h1>
        <form action="" method="post">
            <p>
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="<?php if (isset($_POST["nombre"]))
                    echo $_POST["nombre"] ?>" maxlength="30">
                    <?php
                if (isset($_POST["continuar"]) && $errorNombre) {
                    if ($_POST["nombre"] == "") {
                        echo "<span>Campo vacio</span>";
                    } else {
                        echo "<span>Tamaño erroneo</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="usuario">Usuario: </label>
                <input type="text" name="usuario" value="<?php if (isset($_POST["usuario"]))
                    echo $_POST["usuario"] ?>" maxlength="20">
                    <?php
                if (isset($_POST["continuar"]) && $errorUsuario) {
                    if ($_POST["usuario"] == "") {
                        echo "<span>Campo vacio</span>";
                    } else if (strlen($_POST["usuario"]) > 20) {
                        echo "<span>Tamaño erroneo</span>";
                    } else {
                        echo "<span>Usuario repetido</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="contraseña">Contraseña: </label>
                <input type="password" name="contraseña" value="" maxlength="15">
                <?php
                if (isset($_POST["continuar"]) && $errorContraseña) {
                    if ($_POST["contraseña"] == "") {
                        echo "<span>Campo vacio</span>";
                    } else {
                        echo "<span>Tamaño erroneo</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="email">Email: </label>
                <input type="email" name="email" value="<?php if (isset($_POST["email"]))
                    echo $_POST["email"] ?>" maxlength="50">
                    <?php
                if (isset($_POST["continuar"]) && $errorEmail) {
                    if ($_POST["email"] == "") {
                        echo "<span>Campo vacio</span>";
                    } else if (strlen($_POST["email"]) > 50) {
                        echo "<span>Tamaño erroneo</span>";
                    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                        echo "<span>El email no está bien escrito</span>";
                    } else {
                        echo "<span>El email está repetido</span>";
                    }
                }
                ?>
            </p>
            <p>
                <button type="submit" name="continuar">Continuar</button>
                <button type="submit" name="volver">Volver</button>
            </p>
        </form>
    </body>

    </html>

    <?php
    // si no se ha pulsado ningun boton de los requeridos enviarnos a index
} else {
    header("location:index.php");
}
?>