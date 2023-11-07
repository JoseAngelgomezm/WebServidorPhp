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
    try {
        $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro", );
        mysqli_set_charset($conexion, "UTF8");
    } catch (Exception $e) {
        die("<p>no se ha podido conectar a la base de datos " . $e->getMessage() . "</p></body></html>");
    }

    try {
        $consulta = "select * from usuarios";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die("Error al realizar la consulta" . $e->getMessage() . "</p></body></html>");
    }

    // lectura de los datos de la bd para mostrarlos en una tablas
    echo "<table>";
    echo "<tr><td>Nombre de usuario</td><td>Borrar</td><td>Editar</td></tr>";
    while ($fila = mysqli_fetch_assoc($resultado)) {
        echo "<tr>
                <td><form action='#' method='post'> <button class='enlace' name='usuario' id='usuario' type='submit' value='" . $fila["id_usuario"] . "'>" . $fila["nombre"] . "</button></form></td>
                <td><form action='#' method='post'><button type='submit' id='editar' name='editar' value='" . $fila["id_usuario"] . "'><img class='enlace' src='Images/bx-pencil.svg'></button></form></td>
                <td><form action='#' method='post'><button type='submit' id='borrar' name='borrar' value='" . $fila["id_usuario"] . "'><img class='enlace' src='Images/bx-x-circle.svg'></button></form></td>
                </tr>";
    }
    echo "</table>";

    // si se ha pulsado algun boton de usuario, mostrar los detalles
    if (isset($_POST["usuario"])) {
        echo "se ha pulsado el boton usuario con id " . $_POST["usuario"];

        // consulta para obtener los datos de un usuario
        try {
            $consulta = "select * from usuarios where id_usuario = '" . $_POST["usuario"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die("Error al realizar la consulta detalles usuario" . $e->getMessage() . "</p></body></html>");
        }

        // si hemos obtenido alguna tupla
        if (mysqli_num_rows($resultado) > 0) {
            // obtener los datos en un array asociativo
            $datosUsuario = mysqli_fetch_assoc($resultado);
            // mostrar los datos
            echo "<p><strong>Nombre: </strong>" . $datosUsuario["nombre"] . "</p>";
            echo "<p><strong>Usuario: </strong>" . $datosUsuario["usuario"] . "</p>";
            echo "<p><strong>Email: </strong>" . $datosUsuario["email"] . "</p>";
        } else {
            // si no hemos obtenido ninguna tupla, avisar de que ya no existe el usuario en la bd
            echo "<p>Este usuario ya no existe en la base de datos</p>";
        }

        // boton volver solo si se ha pulsado algun boton de usuario
        echo "<form action='#' method='post'>";
        echo "<p><button type='submit'>Volver a insertar usuario</button></p>";
        echo "</form>";
    // si se ha pulsado el boton borrar
    } else if(isset($_POST["borrar"])) {
    echo "<p>se ha pulsado borrar de ".$_POST["borrar"]."";

    }else if(isset($_POST["editar"])){
        echo "<p>se ha pulsado editar de ".$_POST["editar"]."</p>";

    }// si no se ha pulsado el boton de ningun usuario mostrar el boton para insertar un usuario
    else{
        echo "<form action='nuevousuario.php' method='post'>";
        echo "<p><button type='submit' name='nuevousuario' id='nuevousuario'>Insertar nuevo usuario</button></p>";
        echo "</form>";
    }
    mysqli_free_result($resultado);
    mysqli_close($conexion);
    
    ?>
</body>

</html>