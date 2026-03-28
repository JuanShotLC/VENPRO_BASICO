<?php
include("is_logged.php");

if (empty($_POST["rif"])) {
    $errors[] = "RIF vacío";
} else if (empty($_POST["nombre"])) {
    $errors[] = "Nombre vacío";
} else if (!empty($_POST["email"]) && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Formato de correo inválido";
} else if (!empty($_POST["rif"])) {
    require_once("../config/db.php");
    require_once("../config/conexion.php");

    $rif = strip_tags($_POST["rif"], ENT_QUOTES);
    $nombre = strip_tags($_POST["nombre"], ENT_QUOTES);
    $telefono = strip_tags($_POST["telefono"], ENT_QUOTES);
    $email = strip_tags($_POST["email"], ENT_QUOTES);
    $direccion = strip_tags($_POST["direccion"], ENT_QUOTES);
    $date_added = date("Y-m-d H:i:s");

    // Refactored by Tom Dev: Secure Prepared Statements
    $sql = "INSERT INTO clientes (rif_empresa, nombre_cliente, telefono_cliente, email_cliente, direccion_cliente, status_cliente, date_added) 
            VALUES (?, ?, ?, ?, ?, '1', ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssss", $rif, $nombre, $telefono, $email, $direccion, $date_added);

    if ($stmt->execute()) {
        $messages[] = "Cliente ha sido ingresado satisfactoriamente.";
    } else {
        $errors[] = "Lo siento algo ha salido mal intenta nuevamente." . $con->error;
    }
    $stmt->close();
} else {
    $errors[] = "Error desconocido.";
}

if (isset($errors)) {
?>
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong>
        <?php
        foreach ($errors as $error) {
            echo $error;
        }
        ?>
    </div>
<?php
}
if (isset($messages)) {
?>
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>¡Bien hecho!</strong>
        <?php
        foreach ($messages as $message) {
            echo $message;
        }
        ?>
    </div>
<?php
}
?>