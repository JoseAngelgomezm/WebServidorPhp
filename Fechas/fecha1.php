<?php
// comprobar errores
if(isset($_POST["calcular"])){
    // si en las fechas no hay tamaño 10
    // si no son grupos de DD/MM/YYYY y no son numeros is_numeric(substr($_POST["fecha1"],2)
    // si la fecha es valida
    // si los separadores son los indicados
    // si el campo esta vacio
    $errorFecha1Vacia = $_POST["fecha1"] == "";
    $errorFecha2Vacia = $_POST["fecha2"] == "";

    $errorTamañoFecha1 = strlen($_POST["fecha1"] != 10);
    $errorTamañoFecha2 = strlen($_POST["fecha2"] != 10);

    $errorSeparadorFecha1 = substr($_POST["fecha1"],2,1) != "/" || $errorSeparadorFecha1 = substr($_POST["fecha1"],5,1) != "/";
    $errorSeparadorFecha2 = substr($_POST["fecha2"],2,1) != "/" || $errorSeparadorFecha2 = substr($_POST["fecha2"],5,1) != "/";

    $errorFecha1 = $errorFecha1Vacia || $errorTamañoFecha1 || $errorSeparadorFecha1;
    $errorFecha2 = $errorFecha2Vacia || $errorTamañoFecha2 || $errorSeparadorFecha2;
    
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
            echo "<p>Entre las fechas ".$_POST['fecha1']." y ".$_POST['fecha2']." hay comprendidos ".$resultado." días</p>";
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