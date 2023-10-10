<?php
// comprobar errores de fechas
if(isset($_POST["calcular"])){
    // si el campo esta vacio
    // si en las fechas no hay tamaño 10
    // si no son grupos de DD/MM/YYYY , 2 2 y 4 digitos y no son numeros is_numeric(substr($_POST["fecha1"],2)
    // si los separadores son los indicados
    // si la fecha es valida
    $errorFecha1 = $_POST["fecha1"] == "" || strlen($_POST["fecha1"]) != 10 || substr($_POST["fecha1"],2,1) != '/' || substr($_POST["fecha1"],5,1) != '/' || !is_numeric(substr($_POST["fecha1"],0,2)) || !is_numeric(substr($_POST["fecha1"],3,2)) || !is_numeric(substr($_POST["fecha1"],6,4)) || !checkdate(substr($_POST["fecha1"],3,2),substr($_POST["fecha1"],0,2),substr($_POST["fecha1"],6,4));
    $errorFecha2 = $_POST["fecha2"] == "" || strlen($_POST["fecha2"]) != 10 || substr($_POST["fecha2"],2,1) != '/' || substr($_POST["fecha2"],5,1) != '/' || !is_numeric(substr($_POST["fecha2"],0,2)) || !is_numeric(substr($_POST["fecha2"],3,2)) || !is_numeric(substr($_POST["fecha2"],6,4)) || !checkdate(substr($_POST["fecha2"],3,2),substr($_POST["fecha2"],0,2),substr($_POST["fecha2"],6,4));
    
    $error_formulario = $errorFecha1 || $errorFecha2;
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
    <div id="formulario">
        <h1>Fechas- Formulario</h1>
        <form action="fecha1.php" method="post">
            <p>
                <label for="fecha1">Introduzca una fecha: (DD/MM/YYYY)</label>
                <input type="text" name="fecha1" id="fecha1" value="<?php if(isset($_POST["calcular"])) echo $_POST["fecha1"] ?>"></input>
                <?php if(isset($_POST["calcular"]) && $errorFecha1) echo "Campo erroneo"?>
            </p>
            <p>
                <label for="fecha2">Introduzca una fecha: (DD/MM/YYYY)</label>
                <input type="text" name="fecha2" id="fecha2" value="<?php if(isset($_POST["calcular"])) echo $_POST["fecha2"] ?>"></input>
                <?php if(isset($_POST["calcular"]) && $errorFecha2) echo "Campo erroneo"?>
            </p>
            <p>
                <button type="submit" name="calcular" id="calcular">Calcular</button>
            </p>
            
        </form>
    </div>
    <?php
      
        
        if(isset($_POST["calcular"]) && !$error_formulario ){
            // pasar las fechas a segundos desde la fecha que toma el sistema hasta hoy, dividirla en 86400 para saber los dias y redondearla hacia arriba
            $segundosFecha1= ceil(mktime(1,0,0,substr($_POST["fecha1"],3,2),substr($_POST["fecha1"],0,2),substr($_POST["fecha1"],6,4)) / 86400);
            $segundosFecha2= ceil(mktime(1,0,0,substr($_POST["fecha2"],3,2),substr($_POST["fecha2"],0,2),substr($_POST["fecha2"],6,4)) / 86400);
            $resultado = 0;

            // si la fecha 1 es mas grande que la fecha 2, restarle la 2 a la 1
            if($segundosFecha1 > $segundosFecha2){
                $resultado = ($segundosFecha1 - $segundosFecha2);
            // sino hacerlo alreves
            }else{
                $resultado = ($segundosFecha2 - $segundosFecha1);
            }

            // mostrar el div con los datos de resultado
            echo "<div id='respuesta'>";
            echo "<p>Entre las fechas ".$_POST['fecha1']." y ".$_POST['fecha2']." hay comprendidos ".$resultado." días</p>";
            echo "</div>";
        }
      
    ?>

    <style>
        h1 {
            text-align: center;
        }

        #formulario {
            background-color: lightblue;
            border: solid 1px;
        }

        p {
            margin: 1rem
        }

        label {
            margin: 1rem
        }

        button {
            margin: 1rem
        }

        #respuesta {
            background-color: lightgreen;
            border: solid 1px;
            margin-top: 1rem
        }
    </style>
</body>

</html>