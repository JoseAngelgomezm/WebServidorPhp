<?php
// comprobar errores
if (isset($_POST["calcular"])) {
    // si la fecha no existe , es un error
    $errorFecha1 = !checkdate((int) $_POST["mes1"], (int) $_POST["dia1"], (int) $_POST["año1"]);
    $errorFecha2 = !checkdate((int) $_POST["mes2"], (int) $_POST["dia2"], (int) $_POST["año2"]);
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
        <form method="post" action="fecha2.php">
            <p>Introduce una fecha:</p>
            <label for="dia1">Día1:</label>
            <select name="dia1" id="dia1">
                <?php
                for ($i = 1; $i < 32; $i++) {
                    if(isset($_POST["calcular"]) && $_POST["dia1"] == $i){
                        echo "<option selected value=" . $i . ">" . $i . "</option>";
                    }else{
                        echo "<option value=" . $i . ">" . $i . "</option>";
                    }
                    
                }
                ?>
            </select>
            <label for="mes1">Mes1:</label>
            <select name="mes1" id="mes1">
                <?php
                $meses = ["1" => "Enero" , "2" =>"Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre" ];
                foreach($meses as $indice => $mes){
                    if(isset($_POST["calcular"]) && $_POST["mes1"] == $indice){
                        echo "<option selected value=". $_POST["mes1"] . ">" . $mes . "</option>";
                    }else{
                        echo "<option value=". $indice . ">" . $mes . "</option>";
                    }
                }
                ?>
            </select>
            <label for="año1">Año1:</label>
            <select name="año1" id="año1">
                <?php
                // Sacar el año actual
                // sacar la fecha actual y quedarnos con el año
                $añoActual = substr(date('d/m/YYYY'), 6, 4);
                echo $añoActual;
                for ($i = $añoActual; $i > $añoActual - 101; $i--) {
                    if(isset($_POST["calcular"]) && $_POST["año1"] == $i){
                        echo "<option selected value=" . $i . ">" . $i . "</option>";
                    }else{
                        echo "<option value=" . $i . ">" . $i . "</option>";
                    }
                    
                }
                ?>
            </select>
            <?php
            // si la fecha no existe avisar
            if (isset($_POST["calcular"]) && $errorFecha1) {
                echo "fecha no existe";
            }
            ?>


            <p>Introduce otra fecha:</p>
            <label for="dia2">Día2:</label>
            <select name="dia2" id="dia2">
            <?php
                for ($i = 1; $i < 32; $i++) {
                    if(isset($_POST["calcular"]) && $_POST["dia2"] == $i){
                        echo "<option selected value=" . $i . ">" . $i . "</option>";
                    }else{
                        echo "<option value=" . $i . ">" . $i . "</option>";
                    }
                    
                }
                ?>
                ?>
            </select>
            <label for="mes2">Mes2:</label>
            <select name="mes2" id="mes2">
            <?php
                $meses = ["1" => "Enero" , "2" =>"Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre" ];
                foreach($meses as $indice => $mes){
                    if(isset($_POST["calcular"]) && $_POST["mes2"] == $indice){
                        echo "<option selected value=". $_POST["mes2"] . ">" . $mes . "</option>";
                    }else{
                        echo "<option value=". $indice . ">" . $mes . "</option>";
                    }
                }
                ?>
            </select>
            <label for="año2">Año2:</label>
            <select name="año2" id="año2">
                <?php
                // Sacar el año actual
                // sacar la fecha actual y quedarnos con el año
                $añoActual = substr(date('d/m/YYYY'), 6, 4);
                echo $añoActual;
                for ($i = $añoActual; $i > $añoActual - 101; $i--) {
                    if(isset($_POST["calcular"]) && $_POST["año2"] == $i){
                        echo "<option selected value=" . $i . ">" . $i . "</option>";
                    }else{
                        echo "<option value=" . $i . ">" . $i . "</option>";
                    }
                   
                }
                ?>
            </select>
            <?php
            // si la fecha no existe avisar
            if (isset($_POST["calcular"]) && $errorFecha2) {
                echo "fecha no existe";
            }
            ?>
            <br />
            <button type="submit" name="calcular" id="calcular">Calcular</button>
        </form>
    </div>

    <?php
    if (isset($_POST["calcular"]) && !$errorFecha1 && !$errorFecha2) {
        // pasar las fechas a segundos desde la fecha que toma el sistema hasta hoy, dividirla en 86400 para saber los dias y redondearla hacia arriba
        $segundosFecha1 = floor(mktime(1, 0, 0, (int) $_POST["mes1"], (int) $_POST["dia1"], (int) $_POST["año1"]) / 86400);
        $segundosFecha2 = floor(mktime(1, 0, 0, (int) $_POST["mes2"], (int) $_POST["dia2"], (int) $_POST["año2"]) / 86400);
        $resultado = 0;
        // concatenar las fechas en una variable para usarlas al mostrar resultados
        $fecha1 = $_POST['dia1'] . "/" . $_POST['mes1'] . "/" . $_POST['año1'];
        $fecha2 = $_POST['dia2'] . "/" . $_POST['mes2'] . "/" . $_POST['año2'];
        // si la fecha 1 es mas grande que la fecha 2, restarle la 2 a la 1
        if ($segundosFecha1 > $segundosFecha2) {
            $resultado = ($segundosFecha1 - $segundosFecha2);
            // sino hacerlo alreves
        } else {
            $resultado = ($segundosFecha2 - $segundosFecha1);
        }

        // mostrar el div con los datos de resultado
        echo "<div id='respuesta'>";
        echo "<p>Entre las fechas " . $fecha1 . " y " . $fecha2 . " hay comprendidos " . $resultado . " días</p>";
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