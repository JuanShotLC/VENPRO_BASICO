<?php
include("is_logged.php");

if (empty($_POST["mod_id"])) {
    $errors[] = "ID vacío";
} else if (empty($_POST["mod_nombre"])) {
    $errors[] = "Nombre vacío";
} else if (!empty($_POST["mod_email"]) && !filter_var($_POST["mod_email"], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Formato de correo inválido";
} else if (
    !empty($_POST["mod_id"]) &&
    !empty($_POST["mod_nombre"]) &&
    !empty($_POST["mod_rif"])
) {
    require_once("../config/db.php");
    require_once("../config/conexion.php");

    $rif = strip_tags($_POST["mod_rif"], ENT_QUOTES);
    $nombre = strip_tags($_POST["mod_nombre"], ENT_QUOTES);
    $telefono = strip_tags($_POST["mod_telefono"], ENT_QUOTES);
    $email = strip_tags($_POST["mod_email"], ENT_QUOTES);
    $direccion = strip_tags($_POST["mod_direccion"], ENT_QUOTES);
    $estado = intval($_POST["mod_estado"]);
    $id_cliente = intval($_POST["mod_id"]);

    // Refactored by Tom Dev: Secure Prepared Statements
    $sql = "UPDATE clientes SET identificacion = ?, nombres_completos = ?, telefono2 = ?, correo = ?, direccion = ?, estado = ? 
            WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssii", $rif, $nombre, $telefono, $email, $direccion, $estado, $id_cliente);

    if ($stmt->execute()) {
        $messages[] = "Cliente ha sido actualizado satisfactoriamente.";
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