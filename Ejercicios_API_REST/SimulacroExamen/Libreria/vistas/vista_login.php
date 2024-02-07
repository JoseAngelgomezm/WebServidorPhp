<?php
if (isset($_POST["entrar"])) {
    $errorNombre = $_POST["usuario"] == "";
    $errorContraseña = $_POST["password"] == "";

    $errorFormulario = $errorNombre || $errorContraseña;

    if (!$errorFormulario) {
        // comprobar que existe el usuario
        $url = URLBASE . "/login";
        $datos["lector"] = $_POST["usuario"];
        $datos["clave"]= md5($_POST["password"]);
        $respuesta = consumir_servicios_REST($url, "post", $datos);
        $archivo = json_decode($respuesta);

        if(!$archivo){
            die(error_page("Error", "No se ha obtenido respuesta al logear"));
        }

        if (isset($archivo->error)) {
            die(error_page("Error login", "No se ha podido conectar"));
        }

        if (isset($archivo->mensaje)) {
            $errorNombre = true;
            $_SESSION["mensajeUsuario"] = $archivo->mensaje;
        }

        if (isset($archivo->usuario)) {
            // loguearlo
            $_SESSION["usuario"] = $archivo->usuario->lector;
            $_SESSION["contraseña"] = $archivo->usuario->clave;
            $_SESSION["ultimaAccion"] = time();
            $_SESSION["api_session"] = $archivo->api_session;
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
    <title>Login</title>
    <style>
        div#libros{
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
        }

        div#libros div{
            display: flex;
            flex: 33% 0;
            flex-direction: column;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Libreria</h2>
    <form action="index.php" method="post">

        <p>
            <label for="usuario">Nombre de usuario:</label>
            <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"]))
                echo $_POST["usuario"] ?>">
                <?php
            if (isset($_POST["entrar"]) && $errorNombre) {
                if ($_POST["usuario"] == "") {
                    echo "<span>No puede estar vacio</span>";
                } else if (isset($_SESSION["mensajeUsuario"])) {
                    echo "<span>".$_SESSION["mensajeUsuario"]."</span>";
                }
            }
            ?>
        </p>

        <p>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" value="<?php if (isset($_POST["password"]))
                echo $_POST["password"] ?>">
                <?php
            if (isset($_POST["password"]) && $errorContraseña) {
                if ($_POST["password"] == "") {
                    echo "<span>No puede estar vacio</span>";
                }
            }
            ?>
        </p>

        <button type="submit" name="entrar">Entrar</button>
    </form>

    <h2>Listado de libros</h2>
    <?php
    $url = URLBASE . "/obtenerLibros";
    $respuesta = consumir_servicios_REST($url, "get");
    $archivo = json_decode($respuesta);

    if (!$archivo) {
        session_destroy();
        die(error_page("Error obtencion", "No se ha obtenido respuesta"));
    }

    if (isset($archivo->error)) {
        session_destroy();
        die(error_page("Error obtencion", $archivo->error));
    }

    if (isset($archivo->mensaje)) {
        $_SESSION["mensajeLibros"] = $archivo->mensaje;
    }

    echo "<div id='libros'>";
    if (isset($archivo->libros)) {
        foreach ($archivo->libros as $value) {
            echo "<div>
            <img src='" . $value->portada . "'>
            <p>$value->titulo - $value->precio €</p>
            </div>";
        }
    }
    echo "</div>";


    ?>
</body>

</html>