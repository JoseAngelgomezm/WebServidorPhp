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

// poaginacion , numero de elementos a mostrar
define("N_REGISTROS_MOSTRAR", "3");

// si no existe, la pagina sera la 1
if (!isset($_SESSION["pagina"])) {
    $_SESSION["pagina"] = 1;
}

define("HOST", "localhost");
define("USERNAME", "jose");
define("PASSWORD", "josefa");
define("NAMEDB", "bd_foro");

// CAMBIAR LA PAGINA PARA EL LIMIT DE LA PAGINACION
if(isset($_POST["botonPagina"])){
    $_SESSION["pagina"] = $_POST["botonPagina"];
}

// si existe el boton continuar edicion
if (isset($_POST["continuarEdicion"])) {
    // controlar los errores
    $errorNombre = $_POST["nombreEdicion"] == "" || strlen($_POST["nombreEdicion"]) > 30;
    $errorUsuario = $_POST["usuarioEdicion"] == "" || strlen($_POST["usuarioEdicion"]) > 20;

    // si no hay error de usuario comprobar que no esta repetido
    if (!$errorUsuario) {
        // conexion mediante PDO
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));


        // intentar la consulta
        try {
            $consulta = "select * from usuarios where usuario=? AND id_usuario <> ?";
            $sentencia = $conexion->prepare($consulta);
            $sentencia->execute([$_POST["usuarioEdicion"], $_POST["continuarEdicion"]]);
        } catch (Exception $e) {
            $sentencia = null;
            $conexion = null;
            die(errorPagina("Practica1 CRUD error", "<p>usuario No se ha podido hacer la consulta comprobar usuario repetido</p>"));
        }

        // si tiene mas de 1 tupla el nombre de usuario ya existirá, error de usuario sera true
        $errorUsuario = $sentencia->rowCount() > 0;
        $sentencia = null;
        $conexion = null;
    }

    $errorContraseña = strlen($_POST["contraseñaEdicion"]) > 15;

    $errorEmail = $_POST["emailEdicion"] == "" || strlen($_POST["emailEdicion"]) > 50 || !filter_var($_POST["emailEdicion"], FILTER_VALIDATE_EMAIL);

    // si no hay error de email, comprobar que no este repetido
    if (!$errorEmail) {

        // conexion mediante PDO
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

        // intentar la consulta
        try {
            $consulta = "select * from usuarios where email=? AND id_usuario <> ?";
            $sentencia = $conexion->prepare($consulta);
            $sentencia->execute([$_POST["emailEdicion"], $_POST["continuarEdicion"]]);
        } catch (Exception $e) {
            $sentencia = null;
            $conexion = null;
            die(errorPagina("Practica1 CRUD error", "<p>email No se ha podido hacer la consulta comprobar email repetido</p>"));
        }
        // si tiene mas de 1 tupla el email ya existirá, error de email sera true
        $errorEmail = $sentencia->rowCount() > 0;
        $sentencia = null;
        $conexion = null;

    }

    $errorFormulario = $errorNombre || $errorUsuario || $errorContraseña || $errorEmail;

    // si no hay error de formulario editar los datos
    if (!$errorFormulario) {
        // conexion mediante PDO
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

        // si la contraseña no esta vacia cambiarla por la nueva
        if ($_POST["contraseñaEdicion"] !== "") {
            // intentar la consulta de edicion
            try {
                $consulta = "update usuarios set nombre=?,usuario=?,clave=?,email=? where id_usuario=? ";
                $sentencia = $conexion->prepare($consulta);
                $claveEncriptada = md5($_POST["contraseñaEdicion"]);
                $sentencia->execute([$_POST["nombreEdicion"], $_POST["usuarioEdicion"], $claveEncriptada, $_POST["emailEdicion"], $_POST["continuarEdicion"]]);
            } catch (Exception $e) {
                $sentencia = null;
                $conexion = null;
                die(errorPagina("Practica1 CRUD error", "<p>No se ha podido editar el usuario con contraseña</p>"));
            }
            // sino , la contraseña se quedara como estaba y no se mete en el update
        } else {
            // intentar la consulta de edicion
            try {
                $consulta = "update usuarios set nombre=?,usuario=?,email=? where id_usuario=? ";
                $sentencia = $conexion->prepare($consulta);
                $claveEncriptada = md5($_POST["contraseñaEdicion"]);
                $sentencia->execute([$_POST["nombreEdicion"], $_POST["usuarioEdicion"], $_POST["emailEdicion"], $_POST["continuarEdicion"]]);
            } catch (Exception $e) {
                $sentencia = null;
                $conexion = null;
                die(errorPagina("Practica1 CRUD error", "<p>No se ha podido editar el usuario con contraseña</p>"));
            }
        }

        $sentencia = null;
        $conexion = null;

        // enviarnos a index
        header("location:index.php");
    }
}

//si se ha pulsado el boton continuar borrado
if (isset($_POST["continuarBorrado"])) {
    // conectarnos para eliminar un usuario de la base de datos
    // conexion mediante PDO
    $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

    // intentar borrado
    try {
        // crear la consulta
        $consulta = "delete from usuarios where id_usuario=?";
        // hacer el borrado
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$_POST["continuarBorrado"]]);
    } catch (Exception $e) {
        $sentencia = null;
        $conexion = null;
        die("Error al intentar eliminar el registro " . $e->getMessage() . "</p></body></html>");
    }

    $sentencia = null;
    $conexion = null;
    $_SESSION["pagina"] = 1;
    header("location:index.php");

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información usuarios</title>
</head>
<style>
    table,
    td,
    th {
        border: solid 1px black;
        text-align: center;
    }

    table {
        border-collapse: collapse;
    }

    .enlace {
        border: none;
        background: none;
        color: blue;
        text-decoration: underline;
        cursor: pointer;

    }
</style>

<body>
    <h1>Listado de usuarios</h1>
    <?php

    // conectarnos para listar los usuarios en una tabla
    // conexion mediante PDO
    $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

    // hacer la consulta de los usuarios
    try {
        // FORMULA PARA LA PAGINACION
        $inicio_limite = ($_SESSION["pagina"] - 1) * N_REGISTROS_MOSTRAR;
        // AÑADIR EL LIMIT A LA CONSULTA
        $consulta = "select * from usuarios limit " . $inicio_limite . " , " . N_REGISTROS_MOSTRAR . " ";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
    } catch (Exception $e) {
        $sentencia = null;
        $conexion = null;
        die("Error al realizar la consulta" . $e->getMessage() . "</p></body></html>");
    }

    // montar la tabla
    echo "<table>";
    echo "<tr><td>Nombre de usuario</td><td>Borrar</td><td>Editar</td></tr>";
    $fila = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    foreach ($fila as $cadaResultado) {
        // la tabla contiene un formulario por cada boton que nos redirige a nosotros mismos, con los resultados de cada tupla
        echo "<tr>
                <td><form action='#' method='post'> <button class='enlace' name='mostrarUsuario' id='usuario' type='submit' value='" . $cadaResultado["id_usuario"] . "'>" . $cadaResultado["nombre"] . "</button></form></td>
                <td><form action='#' method='post'><button type='submit' id='editar' name='editar' value='" . $cadaResultado["id_usuario"] . "'><img class='enlace' src='Images/bx-pencil.svg'></button></form></td>
                <td><form action='#' method='post'><input type='hidden' name='nombreUsuario' value='" . $cadaResultado["nombre"] . "'><button type='submit' id='borrar' name='borrar' value='" . $cadaResultado["id_usuario"] . "'><img class='enlace' src='Images/bx-x-circle.svg'></button></form></td>
                </tr>";
    }
    echo "</table>";

    // BOTONES PARA LA PAGINACION, CALCULAR CUANTOS NECESITO, OBTENIENDO TODOS LOS REGISTROS Y DIVIDIENDO ENTRE EL NUMERO DE REGISTROS QUE MUESTRO
    // conexion mediante PDO
    $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    try {
        $consulta = "select * from usuarios";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
    } catch (Exception $e) {
        $sentencia = null;
        $conexion = null;
        die("Error al realizar la consulta" . $e->getMessage() . "</p></body></html>");
    }

    // OBTENER EL NUMERO DE BOTONES
    $numeroBotones = ceil($sentencia->rowCount() / N_REGISTROS_MOSTRAR);

    // SI OBTENGO MAS DE UN BOTON , PONGO LOS BOTONES
    if ($numeroBotones > 1) {
        echo "<form method='post' action='index.php'>";

        // PONER LOS BOTONES
        echo "<p>";

        // SI ESTOY EN LA PRIMERA PAGINA NO PONGO LOS BOTONES DE ATRAS Y VOLVER AL PRINCIPIO
        if($_SESSION["pagina"] != 1){
            echo "<button type='submit' name='botonPagina' value='1'> <<< </button>";
            echo "<button type='submit' name='botonPagina' value='".($_SESSION["pagina"] -1)."'> < </button>";
        }

        for ($i = 1; $i <= $numeroBotones; $i++) {

            // SI EL BOTON ES POR LA PAGINA QUE ESTOY, PONERLO DISABLE
            if($i == $_SESSION["pagina"]){
                echo "<button type='submit' disabled name='botonPagina' value='".$i."'>$i</button>";
            }else{
                echo "<button type='submit' name='botonPagina' value='".$i."'>$i</button>";
            }
           
        }

        // SI ESTOY EN LA ULTIMA PAGINA NO PONGO LOS BOTONES DE ALANTE Y ULTIMA PAGINA
        if($_SESSION["pagina"] != $numeroBotones ){
            echo "<button type='submit' name='botonPagina' value='".($_SESSION["pagina"] +1)."'> > </button>";
            echo "<button type='submit' name='botonPagina' value='".$numeroBotones."'> >>> </button>";
        }

        echo "</p>";
        echo "</form>";
    }
    // SINO NO PONGO NINGUN BOTON
    

    // si se ha pulsado algun boton de usuario, mostrar los detalles
    if (isset($_POST["mostrarUsuario"])) {
        echo "se ha pulsado el boton usuario con id " . $_POST["mostrarUsuario"];

        // consulta para obtener los datos de un usuario
        try {
            $consulta = "select * from usuarios where id_usuario = ?";
            $sentencia = $conexion->prepare($consulta);
            $sentencia->execute([$_POST["mostrarUsuario"]]);
        } catch (Exception $e) {
            $sentencia = null;
            $conexion = null;
            die("Error al realizar la consulta detalles usuario" . $e->getMessage() . "</p></body></html>");
        }

        // si hemos obtenido alguna tupla
        if ($sentencia->rowCount() > 0) {
            // obtener los datos en un array asociativo
            $datosUsuario = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            foreach ($datosUsuario as $cadaResultado) {
                // mostrar los datos
                echo "<p><strong>Nombre: </strong>" . $cadaResultado["nombre"] . "</p>";
                echo "<p><strong>Usuario: </strong>" . $cadaResultado["usuario"] . "</p>";
                echo "<p><strong>Email: </strong>" . $cadaResultado["email"] . "</p>";
            }

        } else {
            // si no hemos obtenido ninguna tupla, avisar de que ya no existe el usuario en la bd
            echo "<p>Este usuario ya no existe en la base de datos</p>";
        }

        // boton volver solo si se ha pulsado algun boton de usuario
        echo "<form action='#' method='post'>";
        echo "<p><button type='submit'>Volver a insertar usuario</button></p>";
        echo "</form>";


        // si se ha pulsado el boton borrar
    } else if (isset($_POST["borrar"])) {
        // mostrar la confirmacion de borrado con los botones
        echo "<p>Está seguro que quieres borrar el usuario " . $_POST["nombreUsuario"] . "";
        echo "<form method='post' action='#'>";
        echo "<p><button type='submit' name='continuarBorrado' value='" . $_POST["borrar"] . "'>Eliminar usuario</button> <button type='submit'>Volver</button></p>";
        echo "</form>";

        // si se ha pulsado el boton editar o continuar edicion y hay errores
    } else if (isset($_POST["editar"]) || isset($_POST["continuarEdicion"])) {

        if (isset($_POST["editar"])) {
            $idUsuario = $_POST["editar"];
        } else {
            $idUsuario = $_POST["continuarEdicion"];
        }

        // intentar la conexion
        // conexion mediante PDO
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

        // consultar los datos del usuario que hemos pulsado el boton editar
        try {
            $consulta = "select * from usuarios where id_usuario = ?";
            $sentencia = $conexion->prepare($consulta);
            $sentencia->execute([$idUsuario]);
        } catch (Exception $e) {
            $sentencia = null;
            $conexion = null;
            die("Error al realizar la consulta para editar usuario" . $e->getMessage() . "</p></body></html>");
        }

        // si hemos obtenido alguna tupla
        if ($sentencia->rowCount() > 0) {
            // obtener los datos del usuario en un array asociativo
            $datosUsuario = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            foreach ($datosUsuario as $cadaResultado)
                // si existe el boton editar recoger datos de la bd
                if (isset($_POST["editar"])) {
                    // recoger los datos de usuario
                    $nombreUsuario = $cadaResultado["nombre"];
                    $usuarioUsuario = $cadaResultado["usuario"];
                    $emailUsuario = $cadaResultado["email"];

                    // si no recoger los datos de los $_POST del formulario mostrado
                } else {
                    // recoger los datos de usuario
                    $nombreUsuario = $_POST["nombreEdicion"];
                    $usuarioUsuario = $_POST["usuarioEdicion"];
                    $emailUsuario = $_POST["emailEdicion"];
                }


            // si el usuario no existe
        } else {
            $mensajeError = "Este usuario ya no existe en la base de datos";
        }

        // si el error de usuario existe mnostrar el error 
        if (isset($mensajeError)) {
            echo $mensajeError;
            // sino mostrar el formulario, que se muestra siempre que existe el boton editar y continuar editar
        } else {
            ?>
                    <form action="#" method="post">
                        <p>
                            <label for="nombre">Nombre: </label>
                            <input type="text" name="nombreEdicion" value="<?php echo $nombreUsuario ?>" maxlength="30">
                        <?php
                        if (isset($_POST["continuarEdicion"]) && $errorNombre) {
                            if ($_POST["nombreEdicion"] == "") {
                                echo "<span>Campo vacio</span>";
                            } else {
                                echo "<span>Tamaño erroneo</span>";
                            }
                        }
                        ?>
                        </p>

                        <p>
                            <label for="usuario">Usuario: </label>
                            <input type="text" name="usuarioEdicion" value="<?php echo $usuarioUsuario ?>" maxlength="20">
                        <?php
                        if (isset($_POST["continuarEdicion"]) && $errorUsuario) {
                            if ($_POST["usuarioEdicion"] == "") {
                                echo "<span>Campo vacio</span>";
                            } else if (strlen($_POST["usuarioEdicion"]) > 20) {
                                echo "<span>Tamaño erroneo</span>";
                            } else {
                                echo "<span>Usuario repetido</span>";
                            }
                        }
                        ?>
                        </p>
                        <p>
                            <label for="contraseña">Contraseña: </label>
                            <input type="text" name="contraseñaEdicion" placeholder="Contraseña no visible. teclea una para cambiarla"
                                maxlength="15">
                        <?php
                        if (isset($_POST["continuarEdicion"]) && $errorContraseña) {
                            if ($_POST["contraseñaEdicion"] == "") {
                                echo "<span>Campo vacio</span>";
                            } else {
                                echo "<span>Tamaño erroneo</span>";
                            }
                        }
                        ?>
                        </p>
                        <p>
                            <label for="email">Email: </label>
                            <input type="email" name="emailEdicion" value="<?php echo $emailUsuario ?>" maxlength="50">
                        <?php
                        if (isset($_POST["continuarEdicion"]) && $errorEmail) {
                            if ($_POST["emailEdicion"] == "") {
                                echo "<span>Campo vacio</span>";
                            } else if (strlen($_POST["emailEdicion"]) > 50) {
                                echo "<span>Tamaño erroneo</span>";
                            } else if (!filter_var($_POST["emailEdicion"], FILTER_VALIDATE_EMAIL)) {
                                echo "<span>El email no está bien escrito</span>";
                            } else {
                                echo "<span>El email está repetido</span>";
                            }
                        }
                        ?>
                        </p>
                        <p>
                            <button type="submit" name="continuarEdicion" value="<?php echo $idUsuario ?>">Continuar</button>
                            <button type="submit">Volver</button>
                        </p>
                    </form>
            <?php
        }

    } // si no se ha pulsado el boton de ningun usuario mostrar el boton para insertar un usuario
    else {
        echo "<form action='nuevousuario.php' method='post'>";
        echo "<p><button type='submit' name='nuevousuario' id='nuevousuario'>Insertar nuevo usuario</button></p>";
        echo "</form>";
    }
    $sentencia = null;
    $conexion = null;
    ?>
</body>

</html>