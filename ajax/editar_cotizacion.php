<?php
include('is_logged.php'); // Verifica login

// Verificación de versión PHP (código original)
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
	exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
	require_once("../libraries/password_compatibility_library.php");
}

if (empty($_POST['mod_cotizacion'])) {
	$errors[] = "No ha colocado ningún valor.";
} elseif (!empty($_POST['mod_cotizacion'])) {

	require_once("../config/db.php");
	require_once("../config/conexion.php");

	$cotizacion = mysqli_real_escape_string($con, (strip_tags($_POST["mod_cotizacion"], ENT_QUOTES)));
	$id = intval($_POST['mod_id']);

	// 1. ACTUALIZAR TASA
	$sql = "UPDATE cotizacion SET precio='" . $cotizacion . "' WHERE id_coti='" . $id . "';";
	$query_update = mysqli_query($con, $sql);

	// 2. ACTUALIZACIÓN MASIVA CON REDONDEO A 2 DECIMALES
	if ($query_update) {

		// CAMBIO AQUÍ: Agregamos ROUND(..., 2) para forzar 2 decimales en la BD
		$update_precios = "UPDATE products SET 
                               dolar_boli = '$cotizacion', 
                               precio_producto = ROUND(precio_dolar * $cotizacion, 2)";

		mysqli_query($con, $update_precios);

		$messages[] = "La tasa se actualizó y los precios de los productos se actualizaron.";

	} else {
		$errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
	}

} else {
	$errors[] = "Un error desconocido ocurrió.";
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
		<strong>¡Exito!</strong>
		<?php
		foreach ($messages as $message) {
			echo $message;
		}
		?>
	</div>
	<?php
}
?>