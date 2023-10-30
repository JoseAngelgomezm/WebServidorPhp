<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 1. Generador de "claves_cesar.txt"</h1>
    <form action="#" method="post">
        <button name="generar" id="generar">Generar</button>
    </form>
    <?php
    if (isset($_POST["generar"])) {
        echo "<h1>Respuesta</h1>";
        // generear el archivo
        $arrayLetras = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
        $fp = fopen("Ficheros/claves_cesar.txt", "w");
        if (!$fp) {
            die("<p>el archivo no se ha podido abrir</p>");
        } else {
            fwrite($fp, "Letras/Desplazamiento;");
            for ($i = 1; $i < 27; $i++) {
                if ($i == 27) {
                    fwrite($fp, $i);
                } else {
                    fwrite($fp, $i . ";");
                }
            }
            fwrite($fp, $i . "\n");
            ;
            $linea = "";
            $contador = 0;
            $posicion = 0;
            
            // 26 lineas
            for ($i = 0; $i < 26; $i++) {
                // 27 letras, cuando llega a la ultima posicion vuelve al 0
                for ($j = 0; $j < 27; $j++) {
                    if ($posicion == 26) {
                        $posicion = 0;
                    }
                    if ($j == 26) {
                        $linea .= $arrayLetras[$posicion];
                    } else {
                        // añadir el punto y coma al final de cada letra
                        $linea .= $arrayLetras[$posicion] . ";";
                    }
                    // aumentar la posicion
                    $posicion++;
                }

                // escribir la linea en el archivo
                fwrite($fp, $linea . "\n");
                $linea = "";
            }
            ;

           
        }
        fclose($fp);
        
        echo "<textarea cols='100' rows='30'>";
        $fd = fopen("Ficheros/claves_cesar.txt","r");
        $linea ="";
        while($linea = fgets($fd)) {
            echo $linea;
        }
        echo "</textarea>";
        fclose($fd);

    }
    ?>
</body>

</html>