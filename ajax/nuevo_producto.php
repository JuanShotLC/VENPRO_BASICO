<?php
include("is_logged.php");

if (empty($_POST["codigo"])) {
    $errors[] = "Código vacío";
} else if (empty($_POST["nombre"])) {
    $errors[] = "Nombre del producto vacío";
} else if ($_POST["stock"] == "") {
    $errors[] = "Stock del producto vacío";
} else if (empty($_POST["precio"])) {
    $errors[] = "Precio de venta vacío";
} else if (
    !empty($_POST["codigo"]) &&
    !empty($_POST["nombre"]) &&
    $_POST["stock"] != "" &&
    !empty($_POST["precio"])
) {
    require_once("../config/db.php");
    require_once("../config/conexion.php");
    include("../funciones.php");

    $codigo = strip_tags($_POST["codigo"], ENT_QUOTES);
    $nombre = strip_tags($_POST["nombre"], ENT_QUOTES);
    $stock = intval($_POST["stock"]);
    $id_categoria = intval($_POST["categoria"]);
    $precio_venta = floatval($_POST["precio"]);
    $date_added = date("Y-m-d H:i:s");

    // Refactored by Tom Dev: Using Secure Prepared Statements
    $sql = "INSERT INTO products (codigo_producto, nombre_producto, date_added, precio_dolar, stock, id_categoria) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssdii", $codigo, $nombre, $date_added, $precio_venta, $stock, $id_categoria);

    if ($stmt->execute()) {
        $messages[] = "Producto ha sido ingresado satisfactoriamente.";
        $id_producto = get_row("products", "id_producto", "codigo_producto", $codigo);
        $user_id = $_SESSION["user_id"];
        $firstname = $_SESSION["firstname"];
        $nota = "$firstname agregó $stock producto(s) al inventario";
        echo guardar_historial($id_producto, $user_id, $date_added, $nota, $codigo, $stock);
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