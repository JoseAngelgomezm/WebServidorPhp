<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Esta es mi super página</h1>

    <form action="index.php" method="post" >
        <p>
            <label for="nombre">Nombre: </label>
             <!-- Si se ha pulsado enviar y existe un nombre, dejar el que habia    -->
            <input type="text" name="nombre" id="nombre" value="<?php if(isset($_POST["nombre"])) { echo $_POST["nombre"]; } ?>"></input> *
            <?php
                
                // si se ha pulsado el boton enviar y hay un error en el nombre, pedir el campo
                if(isset($_POST["enviar"]) && $error_nombre) {
                   
                    echo "<span>Campo requerido*</span>";
                }
            ?>
        </p>

        <p>
            <label for="nacido">Nacido en: </label>
            <select name="nacido">
                <!-- Si se ha pulsado enviar y existe contenido en nacido que sea el seleccionado, dejarlo seleccionado   -->
                <option value="malaga"  <?php if(isset($_POST["enviar"]) && $_POST["nacido"] =="malaga") echo "selected"?> >Málaga</option>
                <option value="cadiz" <?php if(isset($_POST["enviar"]) && $_POST["nacido"] =="cadiz") echo "selected"?> >Cádiz</option>
                <option value="almeria" <?php if(isset($_POST["enviar"]) && $_POST["nacido"] =="almeria") echo "selected"?> >Almería</option>
            </select>
        </p>

        <p>
            Sexo:
            <!-- Si se ha pulsado enviar y existe sexo siendo hombre, dejarlo seleccionado    -->
            <input type="radio" name="sexo" id="sexo" value="hombre"  <?php if(isset($_POST["sexo"]) && $_POST["sexo"] == "hombre") echo"checked"?> ></input>
            <label for ="hombre">Hombre</label>
             <!-- Si se ha pulsado enviar y existe sexo siendo mujer, dejarlo seleccionado    -->
            <input type="radio" name="sexo" id="sexo" value="mujer" <?php if(isset($_POST["sexo"]) && $_POST["sexo"] == "mujer") echo"checked"?>></input>
            <label for ="mujer">Mujer</label> *
            <?php
                // si se ha pulsado enviar y hay un error en sexo
                if(isset($_POST["enviar"]) && $error_sexo){
                   
                    echo "<span>Campo requerido*</span>";
                }
            ?>
        </p>

        <p>
            Aficiones: 
            <!-- Si existe algun $deporte , mantenerlo   -->
            <label for="deportes">Deportes: </label>
            <input type="checkbox" id="deportes" name="deportes"  <?php if(isset($_POST["deportes"]))  echo"checked"?> ></input>
            <label for="lectura">Lectura: </label>
            <input type="checkbox" id="lectura" name="lectura" <?php if(isset($_POST["lectura"]))  echo"checked"?> ></input>
            <label for="otros">Otros: </label>
            <input type="checkbox" id="otros" name="otros" <?php if(isset($_POST["otros"]))  echo"checked"?> ></input>
        </p>

        <p>
            <label for="comentarios">Comentarios: </label>
            <textarea name="comentarios" id="comentarios" style="resize:none"><?php if(isset($_POST["comentarios"]))  echo $_POST["comentarios"]?></textarea>
        </p>

        <p>
            <button type="submit" name="enviar" id="enviar">Enviar</button>
        </p>
    </form>


</body>

</html>