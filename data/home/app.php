<?php 
//include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos

	session_start(); 
	error_reporting(0);

	$fecha_corta = date('d-m-Y');
	// cargar_productos_vendidos
	if (isset($_POST['cargar_productos_vendidos'])) {
		$resultado = mysqli_query($con,"SELECT D.id_producto, P.nombre_producto, SUM(CAST(D.cantidad AS INT)) total
										FROM detalle_factura D, products P WHERE D.id_producto = P.id_producto
										GROUP BY D.id_producto, P.nombre_producto 
										ORDER BY total DESC
										LIMIT 10");
		while ($row = mysqli_fetch_array($resultado)) {
			$data[] = array('name' => $row[1], 'y' => intval($row[2]));
		}
		echo $data = json_encode($data);
	}
	// fin



	// factura ventas diaria
	if (isset($_POST['cargar_facturas_venta'])) {
		$resultado = mysqli_query($con,"SELECT SUM(total_venta) total_venta FROM facturas WHERE fecha_actual = '$fecha_corta' AND estado = '1' ORDER BY total_venta DESC");
		while ($row = mysqli_fetch_array($resultado)) {
			$data = array('total_venta' => $row[0]);
		}
		echo $data = json_encode($data);
	}
	// fin

	


?>