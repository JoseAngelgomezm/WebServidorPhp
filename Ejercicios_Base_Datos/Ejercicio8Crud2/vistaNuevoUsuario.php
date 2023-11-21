<?php
?>
<h3>Agregar Nuevo Usuario</h3>
<form action="#" method="post" enctype="multipart/form-data">

    <label for="nombreInsercion">Nombre:</label>
    <br>
    <input type="text" name="nombreInsercion" maxlength="50" value="<?php if (isset($_POST["nombreInsercion"]))
        echo $_POST["nombreInsercion"] ?>">
        <?php
    if (isset($_POST["nombreInsercion"]) && $_POST["nombreInsercion"] == "") {
        echo "<span>El nombre no puede estar vacio</span>";
    }
    ?>
    <br>

    <label for="usuarioInsercion">Usuario:</label>
    <br>
    <input type="text" name="usuarioInsercion" maxlength="30" value="<?php if (isset($_POST["usuarioInsercion"]))
        echo $_POST["usuarioInsercion"] ?>">
        <?php
    if (isset($_POST["usuarioInsercion"])) {
        if ($_POST["usuarioInsercion"] == "") {
            echo "<span>El usuario no puede estar vacio</span>";
        } else if (strlen($_POST["usuarioInsercion"]) > 50) {
            echo "<span>El nombre de usuario es demasiado largo</span>";
        } else if ($repetidoUsuario) {
            echo "<span>El nombre de usuario no está disponible</span>";
        }
    }
    ?>
    <br>


    <label for="contraseñaInsercion">Contraseña:</label>
    <br>
    <input type="password" name="contraseñaInsercion" maxlength="15">
    <?php
    if (isset($_POST["contraseñaInsercion"]) && $_POST["contraseñaInsercion"] == "") {
        echo "<span>La contraseña no puede estar vacía</span>";
    }
    ?>
    <br>

    <label for="dniInsercion">DNI:</label>
    <br>
    <input type="text" name="dniInsercion" maxlength="9" value="<?php if (isset($_POST["dniInsercion"]))
        echo $_POST["dniInsercion"] ?>">
        <?php
    if (isset($_POST["dniInsercion"]) && $_POST["dniInsercion"] == "") {
        echo "<span>El DNI no puede estar vacío</span>";
    } else if (isset($errorDNI) && $errorDNI) {
        echo "<span>El dni que ha insertado no es un un formato de dni válido</span>";
    } else if (isset($repetidoDni) && $repetidoDni) {
        echo "<span>El dni que ha insertado ya se encuentra registrado</span>";
    } else if (isset($dniValido) && !$dniValido) {
        echo "<span>El dni que ha insertado no es un dni válido</span>";
    }
    ?>
    <br>

    <label>Sexo:</label>
    <br>
    <input type="radio" id="hombre" name="sexoInsercion" value="hombre" checked><label for="hombre"> Hombre</label>
    <input type="radio" id="mujer" name="sexoInsercion" value="mujer"><label for="mujer"> Mujer</label>
    <br>

    <label for="imagenInsercion">Incluir mi foto (MAX-500KB):</label>
    <input type="file" accept="img" name="imagenInsercion">
    <?php
    if (isset($errorFoto) && $errorFoto) {
        echo "<span>Error en la subida de la foto, imagen no válida o peso mayor a 500KB";
    }
    ?>

    <br>
    <button type="submit" name="guardarCambios">Guardar Cambios</button>
    <button type="submit" name="atras">Atrás</button>
</form>
<?php
?>