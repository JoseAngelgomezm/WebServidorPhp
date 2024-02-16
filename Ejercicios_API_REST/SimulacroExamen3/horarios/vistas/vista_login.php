<?php
if (isset($_POST["login"])) {
    $errorUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["contraseña"] == "";

    $errorFormulario = $errorUsuario || $errorContraseña;

    if (!$errorFormulario) {
        // realizar la consulta
        $url = URLBASE . "/login";
        $datos["usuario"] = $_POST["usuario"];
        $datos["contraseña"] = md5($_POST["contraseña"]);
        $respuesta = consumir_servicios_REST($url, "get", $datos);
        $archivo = json_decode($respuesta);

        if (!$archivo) {
            session_destroy();
            die(error_page("Error", "no se ha obtenido respuesta al consultar la url " . $url . ""));
        }

        if (isset($archivo->error)) {
            $errorUsuario = true;
        }

        if (isset($archivo->usuario)) {
            $_SESSION["api_session"] = $archivo->api_session;
            $_SESSION["usuario"] = $archivo->usuario->usuario;
            $_SESSION["contraseña"] = $archivo->usuario->clave;
            $_SESSION["ultimaAccion"] = time();
            header("location:index.php");
            exit();
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
</head>

<body>
    <form action="index.php" method="post">

        <p>
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"]))
                echo $_POST["usuario"] ?>">
                <?php
            if (isset($_POST["usuario"]) && $errorUsuario) {
                if ($_POST["usuario"] == "") {
                    echo "<span>El usuario no puede estar vacio</span>";
                } else {
                    echo "<span>El usuario y contraseña son incorrectos</span>";
                }
            }
            ?>
        </p>

        <p>
            <label for="contraseña">Contraseña:</label>
            <input type="text" name="contraseña" id="contraseña">
            <?php
            if (isset($_POST["contraseña"]) && $errorContraseña) {
                if ($_POST["contraseña"] == "") {
                    echo "<span>La contraseña no puede estar vacia</span>";
                }
            }
            ?>
        </p>

        <p>
            <button type="submit" name="login">Login</button>
        </p>

        <?php
        if (isset($_SESSION["mensajeError"])) {
            echo "<p>" . $_SESSION["mensajeError"] . "</p>";
            session_destroy();
        }
        ?>


    </form>
</body>

</html>