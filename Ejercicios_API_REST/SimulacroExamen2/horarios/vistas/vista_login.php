<?php
    if(isset($_POST["entrar"])){
        $errorUsuario = $_POST["usuario"] == "";
        $errorContraseña = $_POST["clave"] == "";

        $errorFormulario = $errorUsuario || $errorContraseña;

        if(!$errorFormulario){
            // comprobar login
            $url = URLBASE."/login";
            $datos["usuario"] = $_POST["usuario"];
            $datos["clave"] = md5($_POST["clave"]);
            $respuesta = consumir_servicios_REST($url,"post",$datos);
            $archivo = json_decode($respuesta);

            if(!$archivo){
                session_destroy();
                die(error_page("Error en login","No se ha obtenido respuesta en la url ".$url.""));
            }

            if(isset($archivo->error)){
                session_destroy();
                die(error_page("Error en login",$archivo->error));
            }

            if(isset($archivo->mensaje)){
                $errorUsuario = true;
            }else{

                $_SESSION["api_session"] = $archivo->api_session;
                $_SESSION["usuario"] = $archivo->usuario->usuario;
                $_SESSION["clave"] = $archivo->usuario->clave;
                $_SESSION["ultimaAccion"] = time();
                header("Location:index.php");
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
    <h2>Login Usuario</h2>
    <form action="index.php" method="post">
    <p>
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" value="<?php if(isset($_POST["usuario"])) echo $_POST["usuario"] ?>">
        <?php
            if(isset($_POST["entrar"]) && $errorUsuario){
                if($_POST["usuario"] == ""){
                    echo "<span>No puede estar vacio</span>";
                }else{
                    echo "<span>No existe en la bd usuario/contraseña</span>";
                }
            }
        ?>
    </p>

    <p>
        <label for="clave">Contraseña:</label>
        <input type="password" name="clave" value="<?php if(isset($_POST["clave"])) echo $_POST["clave"] ?>">
        <?php
            if(isset($_POST["entrar"]) && $errorUsuario){
                if($_POST["clave"] == ""){
                    echo "<span>No puede estar vacio</span>";
                }
            }
        ?>
    </p>

    <p>
        <button name="entrar" type="submit">Entrar</button>
    </p>

    </form>

    <?php
    if(isset($_SESSION["mensajeSeguridad"])){
        echo "<p>".$_SESSION["mensajeSeguridad"]."</p>";
        session_destroy();
    }
?>
</body>

</html>