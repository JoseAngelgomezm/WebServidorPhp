<?php
session_name("examen3_23_24");
session_start();
define("USER", "jose");
define("PASSWD", "josefa");
define("ADDRESS", "localhost");
define("NAMEBD", "bd_libreria_exam");
define("TIEMPOINACTIVIDAD", "60");
function errorPagina($titulo, $mensaje)
{
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>" . $titulo . "</title>
</head>
<body>
    <p>" . $mensaje . "</p>
</body>
</html>";
}

if(!isset($_SESSION["numeroPaginas"])){
    $_SESSION["opcionesPaginacion"] = ["3" => "3 elementos", "6" => "6 elementos", "0" => "Todo"];
    $_SESSION["opcionElegida"] = 3;
    $_SESSION["pagina"] = 1;
}

if(isset($_POST["numeroElementos"])){
    $_SESSION["opcionElegida"] = $_POST["numeroElementos"];
}
 

// si se ha pulsado salir
if (isset($_POST["salir"])) {
    session_destroy();
    header("location:index.php");
}

// conectarse para listar siempre
try {
    $conexion = new PDO("mysql:host=" . ADDRESS . ";dbname=" . NAMEBD, USER, PASSWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (Exception $e) {
    die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
}

if (isset($_POST["entrar"])) {
    // verificar los datos del formulario

    $errorUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["contraseña"] == "";

    $errorFormulario = $errorUsuario || $errorContraseña;

    // si no hay error de formulario hacer el login
    if (!$errorFormulario) {

        // comprobar que existe el usuario y la contraseña
        try {
            $consulta = "select * from usuarios where lector=? and clave=?";
            $sentencia = $conexion->prepare($consulta);
            $contraseña = md5($_POST["contraseña"]);
            $sentencia->execute([$_POST["usuario"] , $contraseña]);
        } catch (Exception $e) {
            $consulta = null;
            $sentencia = null;
            session_destroy();
            die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar".$e->getMessage()));
        }

        // si he obtenido tuplas
        if ($sentencia->rowCount() >= 1) {
            // logear
            $_SESSION["usuario"] = $_POST["usuario"];
            $_SESSION["contraseña"] = md5($_POST["contraseña"]);
            $_SESSION["ultimaAccion"] = time();
            // obtener el tipo de usuario
            $datosUsuarioLogeado = $sentencia->fetch();
            if ($datosUsuarioLogeado["tipo"] == "normal") {
                header("location:index.php");
            } else {
                header("location:admin/gest_libros.php");
            }
        } else {
            $errorFormulario = true;
        }
    }
}

if (isset($_SESSION["usuario"])) {
    // control seguridad

    // obtener el usuario actual
    try {
        $consulta = "select * from usuarios where lector=? and clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$_SESSION["usuario"] , $_SESSION["contraseña"] ]);
    } catch (Exception $e) {
        $consulta = null;
        $sentencia = null;
        die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar".$e->getMessage()));
    }

    // si no obtengo tuplas
    if ($sentencia->rowCount() <= 0) {
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
    $datosUsuarioLogeado = $sentencia->fetch();

    // controlar que solo entren los tipo normal
    if ($datosUsuarioLogeado["tipo"] == "normal") {
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <style>
            div#contenido {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                flex: 0 33%;
            }

            div#contenido div {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                flex: 0 33%;
            }

            div#contenido div p {
                flex-direction: row;
                flex: 0 50%;
                text-align: center;
            }

            img {
                width: 100%;
                height: auto;
            }
        </style>
        </head>

        <body>
            <h3>Listado de libros</h3>
            <?php echo "<span>Bienvenido </span><strong>" . $_SESSION["usuario"] . "</strong><form action='index.php' method='post'><button type='submit' name='salir'>Salir</button></form>" ?>
            <div id="contenido">
                <?php
                // intentar la consulta de listado
                try {
                    $consulta = "SELECT * FROM libros";
                    $sentencia = $conexion->prepare($consulta);
                    $sentencia->execute();
                } catch (Exception $e) {
                    $consulta=null;
                    $sentencia= null;
                    session_unset();
                    die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar".$e->getMessage()));
                }

                while ($datosLibros = $sentencia->fetch()) {
                    echo "<div><img src='Images/" . $datosLibros["portada"] . "' /><p>" . $datosLibros["titulo"] . "</p><p>" . $datosLibros["precio"] . "</p> </div>";
                }
                ?>
            </div>
        </body>

        </html>
        <?php
    } else if ($datosUsuarioLogeado == "admin") {
        header("location:admin/gest_libros.php");
    }

} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            div#contenido {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                flex: 0 33%;
            }

            div#contenido div {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                flex: 0 30%;
            }

            div#contenido div p {
                flex-direction: row;
                flex: 0 50%;
                text-align: center;
            }

            img {
                width: 100%;
                height: auto;
            }
        </style>
    </head>

    <body>
        <h3>Libreria</h3>
        <form action="index.php" method="post">
            <p>
                <label for="usuario">Nombre Usuario:</label>
                <input type="text" name="usuario" value="<?php if (isset($_POST["usuario"]))
                    echo $_POST["usuario"] ?>">
                    <?php
                if (isset($errorFormulario) && $errorFormulario) {
                    if ($_POST["usuario"] == "") {
                        echo "<span>El usuario esta vacio</span>";
                    } else {
                        echo "<span>Usuario o clave incorrectos</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña">
                <?php
                if (isset($errorFormulario) && $errorFormulario) {
                    if ($_POST["contraseña"] == "") {
                        echo "<span>La contraseña esta vacia</span>";
                    }
                }
                ?>
            </p>

            <p>
                <button type="submit" name="entrar">Entrar</button>
            </p>
        </form>

        <?php
        if (isset($_SESSION["mensaje"])) {
            echo "<p>" . $_SESSION["mensaje"] . "</p>";
            unset($_SESSION["mensaje"]);
        }
        ?>

        <h3>Listado de libros</h3>
        
        <form action="index.php" method="post">
            <label for="numeroElementos">Mostrar</label>
            <select name="numeroElementos" id="numeroElementos">
                <?php foreach ($_SESSION["opcionesPaginacion"] as $key => $value) {
                    if($key == $_SESSION["opcionElegida"]){
                        echo " <option selected value='".$key."'>".$value."</option>";
                    }else{
                        echo " <option value='".$key."'>".$value."</option>";
                    }
                   
                } ?>
            </select>
            <button type="submit" name="aplicarPaginacion">Aplicar</button>
        </form>
       
        <div id="contenido">
            <?php

            // intentar la consulta de listado
            try {
                if($_SESSION["opcionElegida"] === 0){
                    
                }
                $inicio_limite = ($_SESSION["pagina"] - 1) * $_SESSION["opcionElegida"];
                $consulta = "SELECT * FROM libros limit ".$inicio_limite." , ".$_SESSION["opcionElegida"]."";
                $sentencia = $conexion->prepare($consulta);
                $sentencia->execute();
            } catch (Exception $e) {
                $consulta=null;
                $sentencia= null;
                session_unset();
                die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar".$e->getMessage()));
            }

            while ($datosLibros = $sentencia->fetch()) {
                echo "<div><img src='Images/" . $datosLibros["portada"] . "' /><p>" . $datosLibros["titulo"] . "</p><p>" . $datosLibros["precio"] . "</p> </div>";

            }
            ?>
        </div>
    </body>

    </html>
    <?php
}
$conexion = null;
$sentencia = null;
?>