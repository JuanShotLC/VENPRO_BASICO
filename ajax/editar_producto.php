<?php
include("is_logged.php");

if (empty($_POST["mod_id"])) {
    $errors[] = "ID vacío";
} else if (empty($_POST["mod_codigo"])) {
    $errors[] = "Código vacío";
} else if (empty($_POST["mod_nombre"])) {
    $errors[] = "Nombre del producto vacío";
} else if ($_POST["mod_categoria"] == "") {
    $errors[] = "Selecciona la categoría del producto";
} else if (empty($_POST["mod_dolar"])) {
    $errors[] = "Precio de venta vacío";
} else if (
    !empty($_POST["mod_id"]) &&
    !empty($_POST["mod_codigo"]) &&
    !empty($_POST["mod_nombre"]) &&
    $_POST["mod_categoria"] != "" &&
    !empty($_POST["mod_dolar"])
) {
    require_once("../config/db.php");
    require_once("../config/conexion.php");

    $codigo = strip_tags($_POST["mod_codigo"], ENT_QUOTES);
    $nombre = strip_tags($_POST["mod_nombre"], ENT_QUOTES);
    $categoria = intval($_POST["mod_categoria"]);
    $stock = intval($_POST["mod_stock"]);
    $precio_venta = floatval($_POST["mod_dolar"]);
    $id_producto = intval($_POST["mod_id"]);

    // Refactored by Tom Dev: Using Secure Prepared Statements
    $sql = "UPDATE products SET codigo_producto = ?, nombre_producto = ?, id_categoria = ?, precio_dolar = ?, stock = ? 
            WHERE id_producto = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssidii", $codigo, $nombre, $categoria, $precio_venta, $stock, $id_producto);

    if ($stmt->execute()) {
        $messages[] = "Producto ha sido actualizado satisfactoriamente.";
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