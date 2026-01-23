<?php
// optimizado_facturacion.php
require_once("../../config/db.php");
require_once("../../config/conexion.php");

if (!isset($_SESSION)) {
	session_start();
}
error_reporting(0); // Sugiero habilitar errores en desarrollo para depurar

// Función de saneamiento para prevenir Inyección SQL
function clean($con, $str)
{
	return mysqli_real_escape_string($con, trim($str));
}

// Función auxiliar para gestionar clientes (Evita repetir código 3 veces)
function gestionar_cliente($con, $post)
{
	if (empty($post['id_cliente'])) {
		// Calcular ID Cliente
		$id_cliente = 0;
		$sql = mysqli_query($con, "SELECT max(id) FROM clientes");
		if ($row = mysqli_fetch_array($sql)) {
			$id_cliente = $row[0] + 1;
		}

		$tipo_doc = (strlen($post['ruc']) == 12) ? 'RIF' : 'CEDULA';
		$fecha = date('Y-m-d H:i:s');

		// Datos saneados
		$ruc = clean($con, $post['ruc']);
		$nombre = clean($con, $post['cliente']);
		$tel = clean($con, $post['telefono']);
		$dir = clean($con, $post['direccion']);
		$mail = clean($con, $post['correo']);

		$sql_insert = "INSERT INTO clientes VALUES ('$id_cliente', '$tipo_doc', '$ruc', '$nombre', '', '$tel', '', '$dir', '$mail', '0.00', '', '1', '$fecha')";
		mysqli_query($con, $sql_insert);

		return $id_cliente;
	} else {
		return clean($con, $post['id_cliente']);
	}
}

// VALIDAMOS QUE HAYA UNA PETICIÓN
if (isset($_POST['btn_guardar']) || isset($_POST['btn_anular']) || isset($_POST['btn_anular_2'])) {

	$fecha_actual = date('Y-m-d H:i:s');
	$tipo_comprobante = $_POST["select_tipo_comprobante"];

	// ==========================================================================
	// CASO 1: FACTURAS (Tipo 1)
	// ==========================================================================
	if ($tipo_comprobante == 1) {

		if (isset($_POST['btn_guardar'])) {
			// 1. Obtener ID Factura
			$id_factura = 1;
			$res = mysqli_query($con, "SELECT max(id) FROM facturas");
			if ($row = mysqli_fetch_array($res)) {
				$id_factura = $row[0] + 1;
			}

			// 2. Gestionar Cliente
			$id_cliente = gestionar_cliente($con, $_POST);

			// 3. Insertar Factura
			$serie = clean($con, $_POST['serie']); // Usamos la serie enviada por POST o calculada aparte
			// Nota: Aquí asumo que la serie de factura viene correcta desde el formulario.

			$sql_factura = "INSERT INTO facturas VALUES (
                '$id_factura', '$id_cliente', '" . $_SESSION['user_id'] . "', '$id_factura', 
                '$serie', 
                '" . clean($con, $_POST['fecha_actual']) . "', 
                '" . clean($con, $_POST['hora_actual']) . "', 
                '', '1', 
                '" . clean($con, $_POST['select_tipo_precio']) . "', 
                '" . clean($con, $_POST['select_forma_pago']) . "', 
                '', '', '', 
                '" . clean($con, $_POST['subtotal']) . "', 
                '" . clean($con, $_POST['tarifa_0']) . "', 
                '" . clean($con, $_POST['tarifa']) . "', 
                '" . clean($con, $_POST['iva']) . "', 
                '" . clean($con, $_POST['otros']) . "', 
                '" . clean($con, $_POST['total_pagar']) . "', 
                '" . clean($con, $_POST['efectivo']) . "', 
                '" . clean($con, $_POST['cambio']) . "', 
                '', '', '1', '$fecha_actual', 
                '" . clean($con, $_POST['precinto_nro']) . "', 
                '" . clean($con, $_POST['factor']) . "', 
                '" . clean($con, $_POST['divisas']) . "', 
                '" . clean($con, $_POST['iva_igtf']) . "', 
                '" . clean($con, $_POST['total_pagar_dolar']) . "')";

			mysqli_query($con, $sql_factura);

			// 4. Procesar Detalles (Factura)
			guardar_detalles($con, $_POST, $id_factura, 'factura', $serie, $fecha_actual);

			// 5. Modificar Proforma
			if (!empty($_POST['id_proforma'])) {
				$id_prof = clean($con, $_POST['id_proforma']);
				mysqli_query($con, "UPDATE proforma SET estado = '2' WHERE id = '$id_prof'");
			}

			echo $id_factura;
		}

		// Anular Factura
		if (isset($_POST['btn_anular'])) {
			$id_fact = clean($con, $_POST['id_factura']);
			$fecha_anulacion = clean($con, $_POST['fecha_actual']);

			mysqli_query($con, "UPDATE facturas SET fecha_anulacion = '$fecha_anulacion', estado = '2' WHERE id = '$id_fact'");
			mysqli_query($con, "UPDATE detalle_factura SET estado = '2' WHERE id_factura = '$id_fact'");

			// Revertir Stock (Devolver productos)
			revertir_stock($con, $_POST, "Anulada F.V: " . clean($con, $_POST['serie']), $fecha_actual);

			echo 1;
		}
	}

	// ==========================================================================
	// CASO 2: NOTAS DE VENTA (Tipo 2)
	// ==========================================================================
	elseif ($tipo_comprobante == 2) {

		if (isset($_POST['btn_guardar'])) {
			// 1. ID DB Global
			$id_nota_db = 1;
			$res = mysqli_query($con, "SELECT max(id) FROM nota_venta");
			if ($row = mysqli_fetch_array($res)) {
				$id_nota_db = $row[0] + 1;
			}

			// 2. Correlativo NOTAS (N0000001)
			$num_corr = 1;
			// IMPORTANTE: Filtramos por tipo 2 para llevar la cuenta de Notas independientemente
			$res_corr = mysqli_query($con, "SELECT max(CAST(comprobante AS UNSIGNED)) FROM nota_venta WHERE tipo_comprobante = '2'");
			if ($row_corr = mysqli_fetch_array($res_corr)) {
				if ($row_corr[0] > 0)
					$num_corr = $row_corr[0] + 1;
			}

			// 3. Cliente
			$id_cliente = gestionar_cliente($con, $_POST);

			// 4. Insertar Nota
			// OJO: Guardamos $num_corr en 'comprobante' y $serie_formateada en 'serie'
			$sql_nota = "INSERT INTO nota_venta VALUES (
                '$id_nota_db', '$id_cliente', '" . $_SESSION['user_id'] . "', 
                '$num_corr', 
                '$num_corr', 
                '" . clean($con, $_POST['fecha_actual']) . "', 
                '" . clean($con, $_POST['hora_actual']) . "', 
                '', '2', 
                '" . clean($con, $_POST['select_tipo_precio']) . "', 
                '" . clean($con, $_POST['select_forma_pago']) . "', 
                '', '', '', 
                '" . clean($con, $_POST['subtotal']) . "', 
                '" . clean($con, $_POST['tarifa_0']) . "', 
                '" . clean($con, $_POST['tarifa']) . "', 
                '" . clean($con, $_POST['iva']) . "', 
                '" . clean($con, $_POST['otros']) . "', 
                '" . clean($con, $_POST['total_pagar']) . "', 
                '" . clean($con, $_POST['efectivo']) . "', 
                '" . clean($con, $_POST['cambio']) . "', 
                '', '', '1', '$fecha_actual', 
                '" . clean($con, $_POST['precinto_nro']) . "', 
                '" . clean($con, $_POST['factor']) . "', 
                '" . clean($con, $_POST['divisas']) . "', 
                '" . clean($con, $_POST['iva_igtf']) . "', 
                '" . clean($con, $_POST['total_pagar_dolar']) . "')";

			mysqli_query($con, $sql_nota);

			// 5. Detalles
			guardar_detalles($con, $_POST, $id_nota_db, 'nota', $serie_formateada, $fecha_actual);

			// 6. Proforma
			if (!empty($_POST['id_proforma'])) {
				$id_prof = clean($con, $_POST['id_proforma']);
				mysqli_query($con, "UPDATE proforma SET estado = '2' WHERE id = '$id_prof'");
			}

			echo $id_nota_db;
		}

		// Anular Nota
		if (isset($_POST['btn_anular_2'])) {
			$id_nota = clean($con, $_POST['id_nota']);
			$fecha_anulacion = clean($con, $_POST['fecha_actual']);

			mysqli_query($con, "UPDATE nota_venta SET fecha_anulacion = '$fecha_anulacion', estado = '2' WHERE id = '$id_nota'");
			mysqli_query($con, "UPDATE detalle_nota SET estado = '2' WHERE id_nota = '$id_nota'");

			revertir_stock($con, $_POST, "A.N: " . clean($con, $_POST['serie']), $fecha_actual);

			echo 1;
		}
	}

	// ==========================================================================
	// CASO 3: CRÉDITOS (Tipo 3) - CORREGIDO
	// ==========================================================================
	elseif ($tipo_comprobante == 3) {

		if (isset($_POST['btn_guardar'])) {
			// 1. ID DB Global
			$id_nota_db = 1;
			$res = mysqli_query($con, "SELECT max(id) FROM nota_venta");
			if ($row = mysqli_fetch_array($res)) {
				$id_nota_db = $row[0] + 1;
			}

			// 2. Correlativo CRÉDITOS (C0000001)
			$num_corr = 1;
			// FILTRO CLAVE: WHERE tipo_comprobante = '3'
			$res_corr = mysqli_query($con, "SELECT max(CAST(comprobante AS UNSIGNED)) FROM nota_venta WHERE tipo_comprobante = '3'");
			if ($row_corr = mysqli_fetch_array($res_corr)) {
				if ($row_corr[0] > 0)
					$num_corr = $row_corr[0] + 1;
			}
			// Formateamos la serie visualmente con 'C'
			$serie_formateada = 'NC-' . str_pad($num_corr, 6, "0", STR_PAD_LEFT);

			// 3. Cliente
			$id_cliente = gestionar_cliente($con, $_POST);

			// 4. Insertar Crédito
			// COLUMNA 4 (Comprobante) = $num_corr (El número entero: 1, 2, 3...)
			// COLUMNA 5 (Serie) = $serie_formateada (El texto: C0000001)
			$sql_credito = "INSERT INTO nota_venta VALUES (
                '$id_nota_db', '$id_cliente', '" . $_SESSION['user_id'] . "', 
                '$num_corr', 
                '$serie_formateada', 
                '" . clean($con, $_POST['fecha_actual']) . "', 
                '" . clean($con, $_POST['hora_actual']) . "', 
                '', '3', 
                '" . clean($con, $_POST['select_tipo_precio']) . "', 
                '" . clean($con, $_POST['select_forma_pago']) . "', 
                '', '', '', 
                '" . clean($con, $_POST['subtotal']) . "', 
                '" . clean($con, $_POST['tarifa_0']) . "', 
                '" . clean($con, $_POST['tarifa']) . "', 
                '" . clean($con, $_POST['iva']) . "', 
                '" . clean($con, $_POST['otros']) . "', 
                '" . clean($con, $_POST['total_pagar']) . "', 
                '" . clean($con, $_POST['efectivo']) . "', 
                '" . clean($con, $_POST['cambio']) . "', 
                '', '', '1', '$fecha_actual', 
                '" . clean($con, $_POST['precinto_nro']) . "', 
                '" . clean($con, $_POST['factor']) . "', 
                '" . clean($con, $_POST['divisas']) . "', 
                '" . clean($con, $_POST['iva_igtf']) . "', 
                '" . clean($con, $_POST['total_pagar_dolar']) . "')";

			mysqli_query($con, $sql_credito);

			// 5. Detalles
			guardar_detalles($con, $_POST, $id_nota_db, 'nota', $serie_formateada, $fecha_actual, 'C.R:');

			// 6. Proforma
			if (!empty($_POST['id_proforma'])) {
				$id_prof = clean($con, $_POST['id_proforma']);
				mysqli_query($con, "UPDATE proforma SET estado = '2' WHERE id = '$id_prof'");
			}

			echo $id_nota_db;
		}

		// Si necesitas anular créditos, la lógica iría aquí similar al Tipo 2
	}
}

// -----------------------------------------------------------
// FUNCIONES AUXILIARES PARA DETALLES Y STOCK
// -----------------------------------------------------------

function guardar_detalles($con, $post, $id_padre, $tipo_doc, $referencia_visual, $fecha, $prefijo_kardex = '')
{
	$campo1 = $post['campo1'];
	$campo2 = $post['campo2'];
	$campo3 = $post['campo3'];
	$campo4 = $post['campo4'];
	$campo5 = $post['campo5'];
	$campo6 = $post['campo6'];

	$arr1 = explode('|', $campo1);
	$arr2 = explode('|', $campo2);
	$arr3 = explode('|', $campo3);
	$arr4 = explode('|', $campo4);
	$arr5 = explode('|', $campo5);
	$arr6 = explode('|', $campo6);
	$count = count($arr1);

	// Definir nombre de tabla y campos según tipo
	$tabla_det = ($tipo_doc == 'factura') ? 'detalle_factura' : 'detalle_nota';
	$col_id = ($tipo_doc == 'factura') ? 'id_factura' : 'id_nota';

	// Si no se pasó prefijo, deducirlo
	if ($prefijo_kardex == '') {
		$prefijo_kardex = ($tipo_doc == 'factura') ? 'F.V:' : 'N.';
		if ($tipo_doc == 'nota' && substr($referencia_visual, 0, 1) == 'N')
			$prefijo_kardex = 'N.';
	}

	for ($i = 1; $i < $count; $i++) {
		if (empty($arr1[$i]))
			continue; // Saltar vacíos

		// ID Detalle
		$id_det = 1;
		$r_det = mysqli_query($con, "SELECT max(id_detalle) FROM $tabla_det");
		if ($rw = mysqli_fetch_array($r_det)) {
			$id_det = $rw[0] + 1;
		}

		$id_prod = clean($con, $arr1[$i]);
		$cant = clean($con, $arr2[$i]);

		// Insertar Detalle
		$sql_det = "INSERT INTO $tabla_det VALUES (
            '$id_det', '$id_padre', '$id_prod', '$cant', 
            '" . clean($con, $arr3[$i]) . "', '" . clean($con, $arr4[$i]) . "', 
            '" . clean($con, $arr5[$i]) . "', '" . clean($con, $arr6[$i]) . "', 
            '1', '$fecha')";
		mysqli_query($con, $sql_det);

		// Actualizar Stock (Restar)
		mysqli_query($con, "UPDATE products SET stock = stock - $cant WHERE id_producto = '$id_prod'");

		// Kardex
		$id_kardex = 1;
		$r_kar = mysqli_query($con, "SELECT max(id_historial) FROM historial");
		if ($rwk = mysqli_fetch_array($r_kar)) {
			$id_kardex = $rwk[0] + 1;
		}

		$ref_final = $prefijo_kardex . $referencia_visual;

		$sql_kar = "INSERT INTO historial VALUES ('$id_kardex', '$id_prod', '" . $_SESSION['user_id'] . "', '$fecha', '$ref_final', '$id_prod', '$cant')";
		mysqli_query($con, $sql_kar);
	}
}

function revertir_stock($con, $post, $referencia, $fecha)
{
	$campo1 = $post['campo1'];
	$campo2 = $post['campo2'];
	$arr1 = explode('|', $campo1);
	$arr2 = explode('|', $campo2);
	$count = count($arr1);

	for ($i = 1; $i < $count; $i++) {
		if (empty($arr1[$i]))
			continue;

		$id_prod = clean($con, $arr1[$i]);
		$cant = clean($con, $arr2[$i]);

		// Devolver Stock (Sumar)
		mysqli_query($con, "UPDATE products SET stock = stock + $cant WHERE id_producto = '$id_prod'");

		// Kardex Anulación
		$id_kardex = 1;
		$r_kar = mysqli_query($con, "SELECT max(id_historial) FROM historial");
		if ($rwk = mysqli_fetch_array($r_kar)) {
			$id_kardex = $rwk[0] + 1;
		}

		$sql_kar = "INSERT INTO historial VALUES ('$id_kardex', '$id_prod', '" . $_SESSION['user_id'] . "', '$fecha', '$referencia', '$id_prod', '$cant')";
		mysqli_query($con, $sql_kar);
	}
}

// -----------------------------------------------------------
// BLOQUES DE CARGA DE DATOS (AJAX) - Sin cambios mayores
// -----------------------------------------------------------

if (isset($_POST['cargar_facturero'])) {
	$resultado = mysqli_query($con, "SELECT * FROM facturero WHERE estado = '1'");
	while ($row = mysqli_fetch_array($resultado)) {
		$data_facturero = array(
			'fecha_inicio' => $row[1],
			'fecha_caducidad' => $row[2],
			'inicio_facturero' => $row[3],
			'finaliza_facturero' => $row[4],
			'num_items' => $row[6]
		);
	}
	print_r(json_encode($data_facturero));
}

if (isset($_POST['cargar_series'])) {
	$resultado = mysqli_query($con, "SELECT MAX(serie) FROM facturas GROUP BY id ORDER BY id asc");
	while ($row = mysqli_fetch_array($resultado)) {
		$data = array('serie' => $row[0]);
	}
	print_r(json_encode($data));
}

if (isset($_POST['cargar_iva'])) {
	$resultado = mysqli_query($con, "SELECT * FROM perfil ");
	while ($row = mysqli_fetch_array($resultado)) {
		$data = array('iva' => $row[9], 'igtf' => $row[12]);
	}
	print_r(json_encode($data));
}

if (isset($_POST['consultar_factor'])) {
	$resultado = mysqli_query($con, "SELECT * FROM cotizacion");
	while ($row = mysqli_fetch_array($resultado)) {
		$data = array('factor' => $row[1]);
	}
	print_r(json_encode($data));
}

if (isset($_POST['llenar_tipo_comprobante'])) {
	$resultado = mysqli_query($con, "SELECT id, codigo ,nombre_tipo_comprobante, principal FROM tipo_comprobante WHERE estado = '1' order by id asc");
	print '<option value="">&nbsp;</option>';
	while ($row = mysqli_fetch_array($resultado)) {
		$selected = ($row['principal'] == 'Si') ? 'selected' : '';
		print '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['codigo'] . ' - ' . $row['nombre_tipo_comprobante'] . '</option>';
	}
}

if (isset($_POST['llenar_tipo_factura'])) {
	$resultado = mysqli_query($con, "SELECT id, codigo ,nombre_tipo_factura, principal FROM tipos_facturas WHERE estado = '1' order by id asc");
	print '<option value="">&nbsp;</option>';
	while ($row = mysqli_fetch_array($resultado)) {
		$selected = ($row['principal'] == 'Si') ? 'selected' : '';
		print '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['codigo'] . ' - ' . $row['nombre_tipo_factura'] . '</option>';
	}
}

// ... (Resto de funciones de llenado se mantienen igual, solo asegura que las consultas SELECT no tengan inyeccion si usas variables POST) ...
// Por brevedad, las funciones de búsqueda (buscador_clientes, buscador_productos) se mantienen igual en lógica
// pero se recomienda añadir 'clean()' a $_POST['tipo_busqueda'] si se usan en WHERE.

if (isset($_POST['buscador_clientes'])) {
	$resultado = mysqli_query($con, "SELECT C.id, C.identificacion, C.nombres_completos, C.telefono2, C.direccion, C.correo FROM clientes C WHERE C.estado = '1'");
	$data = array();
	while ($row = mysqli_fetch_array($resultado)) {
		if ($_POST['tipo_busqueda'] == 'ruc') {
			$data[] = array('id' => $row[0], 'value' => $row[1], 'cliente' => $row[2], 'telefono' => $row[3], 'direccion' => $row[4], 'correo' => $row[5]);
		} elseif ($_POST['tipo_busqueda'] == 'cliente') {
			$data[] = array('id' => $row[0], 'value' => $row[2], 'ruc' => $row[1], 'telefono' => $row[3], 'direccion' => $row[4], 'correo' => $row[5]);
		}
	}
	echo json_encode($data);
}

if (isset($_POST['buscador_productos'])) {
	$resultado = mysqli_query($con, "SELECT * FROM products WHERE stock > 0");
	$data = array();
	while ($row = mysqli_fetch_array($resultado)) {
		if ($_POST['tipo_busqueda'] == 'codigo') {
			$data[] = array('id' => $row[0], 'value' => $row[1], 'producto' => $row[2], 'precio_dolar' => $row[4], 'precio_boli' => $row[5], 'precio_producto' => $row[6], 'stock' => $row[7]);
		} elseif ($_POST['tipo_busqueda'] == 'producto') {
			$data[] = array('id' => $row[0], 'codigo_producto' => $row[1], 'value' => $row[2], 'precio_dolar' => $row[4], 'precio_boli' => $row[5], 'precio_producto' => $row[6], 'stock' => $row[7]);
		}
	}
	echo json_encode($data);
}
?>