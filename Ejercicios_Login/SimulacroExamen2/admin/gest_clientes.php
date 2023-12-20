<?php
session_name("ExamenSimulacro2");
session_start();
define("SERVIDOR_BD", "localhost");
define("NOMBRE_BD", "bd_videoclub_exam");
define("USUARIO_BD", "jose");
define("CLAVE_BD", "josefa");
define("TIEMPOMAXIMODEFINIDO", "60");

if (isset($_POST["editar"])) {

    $_SESSION["mensajeCrud"] = "<p> el cliente " . $_POST["editar"] . " ha sido editado</p>";
    header("Location:gest_clientes.php");
    exit();

} else if (isset($_POST["borrar"])) {

    $_SESSION["mensajeCrud"] = "<p> el cliente " . $_POST["borrar"] . " ha sido borrado</p>";
    header("Location:gest_clientes.php");
    exit();

}

if (isset($_SESSION["usuario"])) {
    // controlar la seguridad
    // buscar el usuario en la base de datos
    try {
        $conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    } catch (Exception $e) {
        session_destroy();
        die(errorPaginaExamen("no se ha podido conectar a la base de datos para comprobar la seguridad"));
    }

    // intentar la consulta
    try {
        $consulta = "select * from clientes where usuario='" . $_SESSION["usuario"] . "'";
        $resultadoConsulta = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(errorPaginaExamen("no se ha podido realizar la consulta del usuario para comprobar la seguridad"));
    }
    $datosUsuario = mysqli_fetch_assoc($resultadoConsulta);
    mysqli_close($conexion);

    // si no hay tuplas, no existe el usuario
    if (mysqli_num_rows($resultadoConsulta) <= 0) {
        session_destroy();
        header("Location:index.php");
        exit();
    }

    // comprobar el tiempo de conexion
    if (time() - $_SESSION["ultimaAccion"] > TIEMPOMAXIMODEFINIDO) {
        session_unset();
        $_SESSION["mensaje"] = "<p>has superado el tiempo de inactividad</p>";
        header("Location:../index.php");
        exit();
    }

    // actualizar el tiempo
    $_SESSION["ultimaAccion"] = time();

    // si todo ha ido bien, obtener los datos de los usuarios no administradores
    try {
        $conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    } catch (Exception $e) {
        session_destroy();
        die(errorPaginaExamen("no se ha podido conectar a la base de datos para comprobar la seguridad"));
    }

    // intentar la consulta
    try {
        $consulta = "select * from clientes where tipo='normal'";
        $resultadoConsulta = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(errorPaginaExamen("no se ha podido realizar la consulta del usuario para comprobar la seguridad"));
    }
    mysqli_close($conexion);

    //mostar la tabla para el crud
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            table{
                text-align: center;
            }
            img {
                width: 20%;
            }
        </style>
    </head>

    <body>
        <h1>VideoClub</h1>
        <form action="../index.php" method="post"><span>Bienvenido
                <?php echo $_SESSION["usuario"] ?> <button name="volver">salir</button></form><span>

            <h2>Clientes</h2>
            <?php
            if (isset($_SESSION["mensajeCrud"])) {
                echo $_SESSION["mensajeCrud"];
            }
            ?>
            <h2>Listado de los clientes (no admin)</h2>
            <table border="solid 1px">
                <tr>
                    <td>Usuario</td>
                    <td>Foto</td>
                    <td></td>
                </tr>
                <?php
                while ($datosUsuariosNoAdmin = mysqli_fetch_assoc($resultadoConsulta)) {
                    echo "<tr>";
                    echo "<td>" . $datosUsuariosNoAdmin["usuario"] . "</td>";
                    echo "<td><img src='../Images/" . $datosUsuariosNoAdmin["foto"] . "'/></td>";
                    echo "<td><form action='' method='post'><button name='editar' value='" . $datosUsuariosNoAdmin["id_cliente"] . "'>Editar</button>-<button name='borrar' value='" . $datosUsuariosNoAdmin["id_cliente"] . "'>Borrar</button></form></td>";
                    echo "</tr>";
                }
                ?>
            </table>
    </body>

    </html>
    <?php

} else {
    session_destroy();
    header("Location:../index.php");
    exit();
}

?>