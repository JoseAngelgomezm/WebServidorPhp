<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rellena tu cv</title>
</head>

<body>
    <h1>Rellena tu CV</h1>
    <!-- enctype para enviar archivos y el metodo post porque lleva archivos y contraseña -->
    <form action="index.php" method="post" enctype="multipart/form-data">

        <p>
            <label for="nombre">Nombre</label>
            <br />
            <input type="text" id="nombre" name="nombre" value="<?php if (isset($_POST["nombre"]))
                echo $_POST["nombre"]; ?>"></input>
            <?php
            if (isset($_POST["guardar"]) && $error_nombre) {
                echo "<span> campo vacio</span>";
            }
            ?>
        </p>



        <p>
            <label for="apellidos">Apellidos</label>
            <br />
            <input type="text" id="apellidos" size="50" name="apellidos" value="<?php if (isset($_POST["apellidos"]))
                echo $_POST["apellidos"]; ?>"></input>
            <?php
            if (isset($_POST["guardar"]) && $error_apellidos) {
                echo "<span> campo vacio </span>";
            }
            ?>
        </p>

        <p>
            <label for="dni">DNI</label>
            <br/>
            <input type="text" name="dni" id="dni" placeholder="12345678L" value="<?php if(isset($_POST["guardar"])) echo $_POST["dni"] ?>"></input>
            <?php
            if(isset($_POST["guardar"]) && $error_dni){
                if($_POST["dni"] == ""){
                    echo"El DNI está vacio";
                }elseif(!dni_bien_escrito(strtoupper($_POST["dni"]))){
                    echo "El DNI no tiene un formato correcto";
                }else{
                    echo "DNI no existe";
                }
            }
            
            ?>
        </p>

        <p>
            <label for="contraseña">Contraseña</label>
            <br />
            <input type="password" id="contraseña" name="contraseña"></input>
            <?php
            if (isset($_POST["guardar"]) && $error_contraseña) {
                echo "<span> campo vacio </span>";
            }
            ?>
        </p>

        <p>
            <label for="sexo">Sexo</label>
            <br />
            <input type="radio" <?php if (isset($_POST["sexo"]) && $_POST["sexo"] == "hombre")
                echo "checked"; ?>
                name="sexo" value="hombre"></input>
            <label for="hombre">Hombre</label>
            <input type="radio" <?php if (isset($_POST["sexo"]) && $_POST["sexo"] == "mujer")
                echo "checked"; ?>
                name="sexo" value="mujer"></input>
            <label for="mujer">Mujer</label>

            <?php
            if (isset($_POST["guardar"]) && $error_sexo) {
                echo "<span> campo vacio </span>";
            }
            ?>
        </p>


        <p>
            <label for="image">incluir mi foto:</label>
            <input type="file" id="image" accept="image/*" name="image" src=""></input>

        </p>

        <p>
            <label for="provincia">Nacido en:</label>
            <select name="provincia" id="provincia">
                <option value="malaga" <?php if (isset($_POST["guardar"]) && $_POST["provincia"] == "malaga")
                    echo "selected";
                if (!isset($_POST["guardar"]))
                    echo "selected"; ?>>Malaga</option>
                <option value="cadiz" <?php if (isset($_POST["guardar"]) && $_POST["provincia"] == "cadiz")
                    echo "selected"; ?>>Cadiz</option>
                <option value="almeria" <?php if (isset($_POST["guardar"]) && $_POST["provincia"] == "almeria")
                    echo "selected"; ?>>Almeria</option>
            </select>
        </p>

        <p>
            <label for="comentarios">Comentarios:</label>
            <textarea name="comentarios" id="comentarios" cols="50" rows="10"><?php if (isset($_POST["comentarios"]))
                echo $_POST["comentarios"]; ?></textarea>
            <?php
            if (isset($_POST["guardar"]) && $error_comentario) {
                echo "<span> campo vacio</span>";
            }
            ?>

        </p>

        <p>
            <input type="checkbox" name="suscrito"><label for="suscrito">Subscribirse al boletín de
                novedades</label></input>
        </p>


        <p>
            <input type="submit" name="guardar" id="guardar"></input>
            <input type="reset" name="reset" id="reset"></input>
        </p>


    </form>



</body>

</html>