<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Historial de Movimientos (CORREGIDO)
 * FIX: Soluciona errores 'Undefined index: q' y mejora filtrado
 *----------------------------------------------------------------------------------------------------------------*/
include('is_logged.php'); // Archivo verifica que el usuario que intenta acceder a la URL esta logueado
require_once("../config/db.php");
require_once("../config/conexion.php");

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';
$id_producto = (isset($_REQUEST['id_producto'])) ? intval($_REQUEST['id_producto']) : 0;

if ($action == 'ajax') {

	// 1. CORRECCIÓN PRINCIPAL: Validamos si 'q' existe antes de usarlo
	$q = (isset($_REQUEST['q']) && !empty($_REQUEST['q'])) ? mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES))) : "";

	$aColumns = array('f.fecha', 'f.nota', 'u.user_name'); // Columnas de busqueda
	$sTable = "historial f, users u";
	$sWhere = " WHERE f.id_producto='$id_producto' AND f.user_id=u.user_id"; // Filtro base siempre activo

	// 2. CORRECCIÓN DE BÚSQUEDA: Usamos la variable $q validada
	if ($q != "") {
		$sWhere .= " AND (";
		for ($i = 0; $i < count($aColumns); $i++) {
			$sWhere .= $aColumns[$i] . " LIKE '%" . $q . "%' OR ";
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';
	}

	$sWhere .= " ORDER BY f.fecha DESC";

	include 'pagination.php';

	// Variables de paginación
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	$per_page = 5;
	$adjacents = 4;
	$offset = ($page - 1) * $per_page;

	// Contar filas totales
	$count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable $sWhere");
	$row = mysqli_fetch_array($count_query);
	$numrows = $row['numrows'];
	$total_pages = ceil($numrows / $per_page);
	$reload = './producto.php?id=' . $id_producto; // Corregida la concatenación

	// Consulta principal
	$sql = "SELECT f.fecha, u.user_name, f.nota, f.cantidad 
            FROM $sTable $sWhere 
            LIMIT $offset, $per_page";

	$query = mysqli_query($con, $sql);

	// Renderizar Tabla
	if ($numrows > 0) {
		?>
		<div class="table-responsive" style="margin-top: 1px;">
			<table class="table table-hover align-items-center">
				<thead style="background-color: #f8f9fa; color: #6c757d;">
					<tr>
						<th style="border-bottom: 2px solid #e9ecef;">Fecha</th>
						<th style="border-bottom: 2px solid #e9ecef;">Hora</th>
						<th style="border-bottom: 2px solid #e9ecef;">Usuario</th>
						<th style="border-bottom: 2px solid #e9ecef;">Movimiento</th>
						<th style="border-bottom: 2px solid #e9ecef;" class="text-right">Cantidad</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while ($row = mysqli_fetch_array($query)) {
						$fecha = date('d/m/Y', strtotime($row['fecha']));
						$hora = date('h:i A', strtotime($row['fecha'])); // Formato 12h AM/PM
						$user = $row['user_name'];
						$descrip = $row['nota'];
						$cantidad = $row['cantidad'];

						// Lógica visual para Entrada (Verde) / Salida (Rojo)
						if ($cantidad > 0) {
							$badge_class = "label-success";
							$icon = "glyphicon-arrow-up";
							$txt_cantidad = "+ " . $cantidad;
						} else {
							$badge_class = "label-danger";
							$icon = "glyphicon-arrow-down";
							$txt_cantidad = $cantidad;
						}
						?>
						<tr style="border-bottom: 1px solid #f1f1f1;">
							<td style="font-weight: 500; color:#444;"><?php echo $fecha; ?></td>
							<td class="text-muted"><small><?php echo $hora; ?></small></td>
							<td><i class="glyphicon glyphicon-user text-muted"></i> <?php echo $user; ?></td>
							<td><?php echo $descrip; ?></td>
							<td class='text-right'>
								<span class="label <?php echo $badge_class; ?>"
									style="font-size: 11px; padding: 2px 4px; border-radius: 4px;">
									<?php echo $txt_cantidad; ?>
								</span>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan=5>
							<div class="text-right">
								<?php echo paginate_histo($reload, $page, $total_pages, $adjacents); ?>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php
	} else {
		// Mensaje elegante cuando no hay datos
		?>
		<div class="alert alert-info text-center"
			style="background-color: #f1f8ff; border: 1px solid #d0e7ff; color: #004085; margin-top:20px; border-radius: 8px;">
			<i class="glyphicon glyphicon-info-sign" style="font-size: 24px; margin-bottom: 10px; display:block;"></i>
			<strong>Sin movimientos registrados</strong><br>
			Este producto aún no tiene historial de entradas o salidas de stock.
		</div>
		<?php
	}
}
?>