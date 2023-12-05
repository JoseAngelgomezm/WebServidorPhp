<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen2 PHP</title>
</head>

<body>
    <h1>Examen2 PHP</h1>
    <h2>Horario de los Profesores</h2>
    <?php
    // conectarnos a la base de datos 
    define("USER", "jose");
    define("PASSWORD", "josefa");
    define("BD", "bd_horarios_exam");
    define("HOST", "localhost");

    // intentar la conexion con la bd
    try {
        $conexion = mysqli_connect(HOST, USER, PASSWORD, BD);
    } catch (Exception $e) {
        die("<p>No se ha podido conectar a la bd para mostrar los profesores</p></body></html>");
    }

    // intentar la consulta select para los profesores
    try {
        $consulta = "SELECT usuarios.id_usuario , usuarios.nombre FROM usuarios";
        $resultadoProfesores = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die("<p>No se ha podido consultar la bd para mostrar los profesores</p></body></html>");
    }


    mysqli_close($conexion);

    echo "<form method='POST' action='index.php'>";
    echo "<label for='profesores'>Horario del Profesor: </label>";
    echo "<select name='profesores'>";
    while ($datosProfesores = mysqli_fetch_assoc($resultadoProfesores)) {
        if (isset($_POST["btnProfesorSeleccionado"]) && $_POST["profesores"] == $datosProfesores["id_usuario"]) {
            echo "<option selected value='" . $datosProfesores["id_usuario"] . "'>" . $datosProfesores["nombre"] . "</option>";
            $nombreProfesor = $datosProfesores["nombre"];
        } else {
            echo "<option value='" . $datosProfesores["id_usuario"] . "'>" . $datosProfesores["nombre"] . "</option>";
        }

    }

    echo "</select>";

    echo "<button type='submit' name='btnProfesorSeleccionado' value=''>Ver Horario</button>";

    echo "</form>";



    if (isset($_POST["btnProfesorSeleccionado"])) {

        // intentar la conexion con la bd
        try {
            $conexion = mysqli_connect(HOST, USER, PASSWORD, BD);
        } catch (Exception $e) {
            die("<p>No se ha podido conectar a la bd para mostrar los profesores</p></body></html>");
        }

        // intentar la consulta select para los profesores
        try {
            $consulta = "SELECT * FROM horario_lectivo where usuario='" . $_POST["profesores"] . "'  ";
            $resultadoHorario = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die("<p>No se ha podido consultar la bd para mostrar los profesores</p></body></html>");
        }
         mysqli_close($conexion);

        
        echo "<h2>Horario del profesor " . $nombreProfesor . "</h2>";

        echo "<table border='1px'>";

        echo "<tr>";
        echo "<td></td>";
        echo "<td>Lunes</td>";
        echo "<td>Martes</td>";
        echo "<td>Miercoles</td>";
        echo "<td>Jueves</td>";
        echo "<td>Viernes</td>";

        echo "<tr>";

            echo "<td>8:15-9:15</td>";
            while($datosHorario = mysqli_fetch_assoc($resultadoHorario)){
                if($datosHorario["dia"] == 1 && $datosHorario["hora"] == 1){
                    echo "<td>".$datosHorario["grupo"]."</td>";
                }else if($datosHorario["dia"] == 2 && $datosHorario["hora"] == 1){
                    echo "<td>".$datosHorario["grupo"]."</td>";
                }else if($datosHorario["dia"] == 3 && $datosHorario["hora"] == 1){
                    echo "<td>".$datosHorario["grupo"]."</td>";
                }else if($datosHorario["dia"] == 4 && $datosHorario["hora"] == 1){
                    echo "<td>".$datosHorario["grupo"]."</td>";
                }
                else if($datosHorario["dia"] == 5 && $datosHorario["hora"] == 1){
                    echo "<td>".$datosHorario["grupo"]."</td>";
                }
            }
        
        echo "</tr>";
       
        echo "<tr>";
            echo "<td>9:15-10:15</td>";

        echo "</tr>";

        
        echo "<tr>";
            echo "<td>10:15-11:15</td>";
        
        echo "</tr>";


        echo "<tr>";
            echo "<td>11:15-11:45</td>";
            echo "<td colspan=5>RECREO</td>";
        
        echo "</tr>";

        
        

        echo "<tr>";
            echo "<td>11:45-12:45</td>";
        
        echo "</tr>";

        echo "<tr>";
            echo "<td>12:45-13:45</td>";

        echo "</tr>";

        echo "<tr>";
            echo "<td>13:45-14:45</td>";

        echo "</tr>";


        echo "</table>";


    }


    ?>


</body>

</html>