<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teoria BD</title>
</head>

<body>

    <?php
    // CONEXION BASE DE DATOS
    // dirección, usuario, contraseña, nombreBD
    // si no conecta, devuelve una excepción,hacer trycath por si falla la conexión
    try {
        $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_teoria");
        // al tener exito, hacemos que el charset sea utf8, para las ñ
        mysqli_set_charset($conexion,"utf8");

    // si lanza excepcion
    } catch (Exception $e) {
        die("<p>No se ha podido conectar con la base de datos ".$e->getMessage()."</p></body></html>");
    }

    // REALIZAR UNA CONSULTA
    // crear el string de la consulta
    $consulta = "select * from t_alumnos";
    // controlar errores al hacer la consultar
    try {
        // hacer la consultar
        $resultado = mysqli_query($conexion,$consulta);

    // si lanza excepcion
    }catch (Exception $e) {
        // cerrar la conexión
        mysqli_close($conexion);
        // terminar ejecucion
        die("<p>No se ha podido realizar la consulta a la base de datos ".$e->getMessage()."</p></body></html>");
    }

    // obtener numero de tuplas (registros o filas)
    $numeroFilas = mysqli_num_rows($resultado);
    echo "<p>Se han obtenido un total de : ".$numeroFilas." filas ";

    // ITERACION SOBRE LAS TUPLAS (REGISTROS Ó FILAS)
    // cada fetch, itera sobre los datos de la siguiente tupla
    // mysqli_fetch_assoc devuelve un array asociativo, nombreColumna -> valor
    $fila=mysqli_fetch_assoc($resultado);
    echo "<br>";
    var_dump($fila);
    echo "<br>";
    echo "El nombre del primer alumno es ".$fila["nombre"];

    // ahora iterará sobre la segunda fila
    // mysqli_fetch_row devuelve un array, donde cada posicion es lo que hay en la columna
    // $resultado[0] tendra el cod alumno, $resultado[1] tendra el nombre ....
    $fila = mysqli_fetch_row($resultado);
    echo "<br>";
    var_dump($fila);
    echo "<br>";
    echo "El nombre del segundo alumno es ".$fila[1];

    // ahora iteraremos sobre la tercera fila
    // crea un array asociativo y escalar, se puede acceder a la columna mediante indice y mediante asocicion por el nombre de la columna
    $fila = mysqli_fetch_array($resultado);
    echo "<br>";
    var_dump($fila);
    echo "<br>";
    echo "El nombre del tercer alumno es ".$fila[1];
    echo "<br>";
    echo "El nombre del tercer alumno es ".$fila["nombre"];
    
    // volver al principio, porque solo tenemos 3 filas en nuestra bd
    mysqli_data_seek($resultado,0);

    // iterar de nuevo sobre la primera fila
    // mysqli_fetch_object devuelve un objeto de la fila que estamos iterando
    echo "<br>";
    $fila = mysqli_fetch_object($resultado);
    var_dump($fila);
    echo "<br>";
    echo "El nombre del primer alumno es ".$fila->nombre;


    //volver al principio
    mysqli_data_seek($resultado,0);

    // hacer una tablita con todos los datos de nuestra tabla alumnos
    echo "<table border='solid 1px'";
    echo "<tr> <th>Código</th> <th>Nombre</th> <th>Teléfono</th> <th>CodPostal</th> </tr>";
    // mientras tengamos filas hacer un tr, con cada uno de los datos
    while($fila=mysqli_fetch_assoc($resultado)){
        echo "<tr>";
            echo "<td>".$fila["cod_alu"]."</td>";
            echo "<td>".$fila["nombre"]."</td>";
            echo "<td>".$fila["telefono"]."</td>";
            echo "<td>".$fila["cp"]."</td>";
        echo "</tr>";
    }
    echo "</table>";

    // limpiar la variable resultados, de todos los resultados obtenidos, hacerlo cuando se obtienen resultados (select)
    mysqli_free_result($resultado);

    // cerrar la conexión si se ha realizado todo
    mysqli_close($conexion);


    ?>
</body>

</html>