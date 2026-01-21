<?php
//include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
/* Connect To Database*/
require_once("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
session_start();
error_reporting(0);

$resultado = mysqli_query($con, "SELECT * FROM products  WHERE codigo_producto = '$_GET[codigo_barras]'");
while ($row = mysqli_fetch_array($resultado)) {

	$data = array(
		'id' => $row[0],
		'codigo_producto' => $row[1],
		'producto' => $row[2],
		'date_added' => $row[3],
		'precio_dolar' => $row[4],
		'precio_boli' => $row[5],
		'precio_producto' => $row[6],
		'stock' => $row[7],
		'cantidad' => '1'

	);

}
echo $data = json_encode($data);
?>