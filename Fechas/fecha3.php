<?php
// comprobar errores
if (isset($_POST["calcular"])) {
    $errorFecha1Vacia = $_POST["fecha1"] == "";
    $errorFecha2Vacia = $_POST["fecha2"] == "";

    $errorFormulario = $errorFecha1Vacia || $errorFecha2Vacia;
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
        <h1>Fechas - Formulario</h1>
        <form method="post" action="fecha3.php">
            <label for="fecha1">Intorduce una fecha1: </label>
            <input type="date" name="fecha1" id="fecha1" value="<?php if(isset($_POST["calcular"])) echo $_POST["fecha1"] ?>"></input>
            <?php
                if(isset($_POST["calcular"]) && $errorFecha1Vacia) echo "campo vacio"
            ?>
            <br />
            <label for="fecha2">Introduce una fecha2: </label>
            <input type="date" name="fecha2" id="fecha2" value="<?php if(isset($_POST["calcular"])) echo $_POST["fecha2"] ?>"></input>
            <?php
                if(isset($_POST["calcular"]) && $errorFecha2Vacia) echo "campo vacio"
            ?>
            <br/>
            <button type="submit" name="calcular" id="calcular">Calcular</button>

        </form>
    </div>

    <?php
    if (isset($_POST["calcular"]) && !$errorFormulario) {
        // pasar a segundos las 2 fechas
        $segundosFecha1 = floor(mktime(0, 0, 0, substr($_POST["fecha1"],3,2),substr($_POST["fecha1"],0,2),substr($_POST["fecha1"],6,4)));
        $segundosFecha2 = floor(mktime(0, 0, 0, substr($_POST["fecha2"],3,2),substr($_POST["fecha2"],0,2),substr($_POST["fecha2"],6,4)));

        echo "<div id='respuesta'>";
        if($segundosFecha1> $segundosFecha2){
            $resultado = ($segundosFecha1 - $segundosFecha2) / 86400;
        }else{
            $resultado = ($segundosFecha2 - $segundosFecha1) / 86400;
        }
        echo "</div>";
    }
    ?>

</body>

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

</html>