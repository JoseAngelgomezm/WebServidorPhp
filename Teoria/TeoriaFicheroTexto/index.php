<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    // todas las funciones de ficheros empiezan por f

    // fopen para abrir un archivo, primer parametro ruta, segundo modo apertura (lectura escritura continuacion)
    // siempre devuelve un puntero de fichero, hay que asignarlo a una variable que se le llama fd
    // siempre controlar errores con @
    // LECTURA
    @$fd1 = fopen("ficheros/prueba.txt","r");
    if(!$fd1){
        die("<p> No se ha podido abrir el fichero prueba.txt</p>");
    }else{
        echo "<h1> Ahora se abrira la comunicacion con prueba.txt </h1>";
        // con fgets obtenemos la informacion del fichero, por cada fgets obtenemos una linea
        // si hacemos 2, obtenemos las 2 primeras lineas
        $linea = fgets($fd1);
        echo "<p>$linea</p>";
        $linea = fgets($fd1);
        echo "<p>$linea</p>";

        // con fseek nos volvemos al principio del fichero, el segudno parametro nos lleva hasta la linea asignada
        fseek($fd1,0);
        $linea = fgets($fd1);
        echo "<p>$linea</p>";

        // para recorrer un fichero, se usa el bucle while
        // mientras fgets devuelve una linea, seguir
        echo "<h2>Bucle recorre fichero entero</h2>";
        fseek($fd1,0); // volver al principio para recorrerlo desde la primera linea
        while($linea=fgets($fd1)){
            echo "<p>$linea</p>";
        }

        // si intentamos escribir en el fichero, cuando esta abierto en modo lectura, no deberia dejarnos
        // se escribe con la funcion fwrite o fputs 
        // se puede abrir en modo lectura y escritura poniendo un + en el modo de apertura
        fwrite($fd1,"Intento de escribir");
    }
    fclose($fd1); // siempre hay que cerrar el fichero al terminar
    

    // ESCRITURA
    // si intentamos abrir en modo escritura y no existe, si tiene los persmisos de la carpeta, lo crea
    @$fd2 = fopen("ficheros/prueba2.txt","w+");
    if(!$fd2){
        die("<p> No se ha podido abrir el fichero prueba2.txt</p>");
    }else{
        echo "<h1> Ahora se abrira </h1>";
        // para escribir usar fwrite
        fwrite($fd2,"Intento de escribir");
        // cuando se escribe el puntero, se va al final, hay que volver al principio para mostrar
        fseek($fd2,0);
        while($linea=fgets($fd2)){
            echo "<p>$linea</p>";
        }
    }

    fclose($fd2); // siempre hay que cerrar el fichero al terminar

    // hay una funcion que puede leer un fichero completo
    $contenidoEntero = file_get_contents("ficheros/prueba.txt");
    // mostrar contenido con saltos de linea
    // echo "<pre>".$contenidoEntero."</pre>";
    echo nl2br($contenidoEntero)

    ?>
</body>

</html>