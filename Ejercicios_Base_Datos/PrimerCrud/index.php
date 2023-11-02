<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información usuarios</title>
</head>
<style>
    table , td, th{
        border: solid 1px black;
        text-align: center;
    }   
    table{
        border-collapse: collapse;
    }
    </style>
<body>
    <h1>Listado de usuarios</h1>
    <?php
        try{
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro",);
            mysqli_set_charset($conexion,"UTF8");
        }
        catch(Exception $e){
            die("<p>no se ha podido conectar a la base de datos ".$e->getMessage()."</p></body></html>");
        }

        try{
            $consulta = "select * from usuarios";
            $resultado = mysqli_query($conexion, $consulta);
        }
        catch(Exception $e){
            mysqli_close($conexion);
            die("Error al realizar la consulta".$e->getMessage()."</p></body></html>");
        }

        // lectura de los datos de la bd para mostrarlos en una tablas
        echo "<table>";
            echo "<tr><td>Nombre de usuario</td><td>Borrar</td><td>Editar</td></tr>";
            while($fila = mysqli_fetch_assoc($resultado)){
                echo "<tr>
                <td>".$fila["nombre"]."</td>
                <td><img src='Images/bx-pencil.svg'></td>
                <td><img src='Images/bx-x-circle.svg'></td>
                </tr>";
            }
        echo "</table>";
        echo "<form action='nuevousuario.php' method='post'>";
            echo "<p><button type='submit' name='nuevousuario' id='nuevousuario'>Insertar nuevo usuario</button></p>";
        echo "</form>";
        
        
        

        mysqli_close($conexion);
    ?>
</body>

</html>