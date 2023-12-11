<?php
session_name("PrimerLogin");
session_start();
define("TIEMPOMAXIMOSEGUNDOS" , 60);

// si se ha pulsado salir, destruir la sesion
if (isset($_POST["salir"])) {
    session_destroy();
    header("location:index.php");
    exit();
}

// si exsite el session logeado, es que el usuario ya ha iniciado sesion
if (isset($_SESSION["usuario"])) {

    // COMPROBAR QUE EL USUARIO Y LA CONTRASEÑA CONTINUAN EN LA BASE DE DATOS
    //conectar a la base de datos
    try {
        $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
    } catch (Exception $e) {
        die("<p>No se ha podido conectar a la base de datos, una vez logeado</p></body></html>");
    }

    // intentar la consulta
    try {
        $consulta = "SELECT * from usuarios where usuario='" . $_SESSION["usuario"] . "' and clave='" . md5($_SESSION["contraseña"]) . "'";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(errorPagina("no se ha podido realizar la consulta para comprobar usuario/clave"));
    }


    // si no hay tuplas, el usuario ha sido eliminado
    if (mysqli_num_rows($resultado) <= 0) {
        // cerrar conexion
        mysqli_close($conexion);
        // eliminar todas las variables de session que contiene nombre usuario y contraseña
        session_unset();
        // crear el mensaje de seguridad para preguntar por el en index
        $_SESSION["seguridad"] = "Ya no se encuentra en la BD";
        // cargar de nuevo la web
        header("location:index.php");
        exit();

        // si hay tuplas, es que sigue en la base de datos
    }
    mysqli_close($conexion);

    // HACER EL CONTROL DE INACTIVIDAD
    if (time() - $_SESSION["ultimaAccion"] > TIEMPOMAXIMOSEGUNDOS) {
        // eliminar todas las variables de session que contiene nombre usuario y contraseña
        session_unset();
        // crear el mensaje de inactividad, para preguntar por el index
        $_SESSION["inactividad"] = "Ya ha expirado su session";
        header("location:index.php");
        exit();
    }

    // obtener los datos en array asociativo, para dar acceso normal o administrador
    $datosUsuarioLogeado = mysqli_fetch_assoc($resultado);



    // mostrar la pagina para tipo usuario normal o administrador
    if ($datosUsuarioLogeado["tipo"] == "normal") {
        echo "<h1>Tipo cuenta: normal</h1>";
    } else if ($datosUsuarioLogeado["tipo"] == "admin") {
        echo "<h1>Tipo cuenta: admin</h1>";
    }

    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ejercicio Login</title>
    </head>

    <body>
        <h2>Bienvenido
            <?php echo $_SESSION["usuario"] ?>
        </h2>
        <form action="index.php" method="post">
            <button name="salir">Salir</button>
            </from>
    </body>

    </html>


    <?php


    // sino, mostrar el formulario de login
} else {
    // controlar los errores
    if (isset($_POST["botonLogin"])) {
        $errorUsuario = $_POST["usuario"] == "";
        $errorContraseña = $_POST["contraseña"] == "";

        $errorFormulario = $errorUsuario || $errorContraseña;

        // si no hay errores en los campos, verificar el usuario
        if (!$errorFormulario) {
            //conectar a la base de datos
            try {
                $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
            } catch (Exception $e) {
                die("<p>No se ha podido conectar a la base de datos, 1</p></body></html>");
            }

            // intentar la consulta
            try {
                $consulta = "SELECT usuario from usuarios where usuario='" . $_POST["usuario"] . "' and clave='" . md5($_POST["contraseña"]) . "'";
                $resultado = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                session_destroy();
                die(errorPagina("no se ha podido realizar la consulta para obtener usuario, clave"));
            }

            mysqli_close($conexion);

            // si obtengo tuplas, el usuario y contraseña existen, logea
            if (mysqli_num_rows($resultado) > 0) {

                // saltar al index, guardando usuario y contraseña
                $_SESSION["usuario"] = $_POST["usuario"];
                $_SESSION["contraseña"] = $_POST["contraseña"];
                $_SESSION["ultimaAccion"] = time();
                header("location:index.php");
                exit();

                // sino, el usuario no existe 
            } else {
                $errorUsuarioNoExiste = true;
                $errorUsuario = true;
            }
        }
    }

    function errorPagina($mensaje)
    {
        echo " <!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Error ejercicio login</title>
</head>
<body>
    <p>" . $mensaje . "</p>
</body>
</html>";
    }
    ?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            .error {
                color: red;
            }
        </style>
    </head>

    <body>
        <h2>Primer Login</h2>
        <form action="index.php" method="post">
            <p>
                <label for="usuario">Usuario: </label>
                <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"]))
                    echo $_POST["usuario"] ?>">
                    <?php
                if (isset($_POST["usuario"]) && $errorUsuario) {
                    if ($_POST["usuario"] == "") {
                        echo "<span class='error'>Campo vacio</span>";
                    } else if (isset($errorUsuarioNoExiste) && $errorUsuarioNoExiste) {
                        echo "<span class='error'>El usuario no existe o la clave no es correcta</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="contraseña">Contraseña: </label>
                <input type="password" name="contraseña" id="contraseña" value="">
                <?php
                if (isset($_POST["contraseña"]) && $errorContraseña) {
                    if ($_POST["contraseña"] == "") {
                        echo "<span class='error'>Campo vacio</span>";
                    }
                }
                ?>
            </p>
            <p>
                <button type="submit" name="botonLogin">Login</button>
            </p>
        </form>
        <?php
        // si se ha eliminado el usuario y existe session seguridad
        if (isset($_SESSION["seguridad"])) {
            echo "<p class='error'>" . $_SESSION["seguridad"] . "</p>";
            // para eliminar este $_SESSION seguridad
            session_destroy();
        }

        // si se ha transcurrido el tiempo definido para la session
        if(isset($_SESSION["inactividad"])){
            echo "<p class='error'>" . $_SESSION["inactividad"] . "</p>";
            // para eliminar este $_SESSION inactividad
            session_destroy();
        }
        ?>
    </body>

    </html>
    <?php
}
?>