<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Búsqueda de Clientes (MODO DEBUG & SAFE)
 *----------------------------------------------------------------------------------------------------------------*/
include('is_logged.php');
require_once("../config/db.php");
require_once("../config/conexion.php");

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';

if (isset($_GET['id'])) {
	$id_cliente = intval($_GET['id']);
	// Intenta borrar usando id_cliente
	if ($delete1 = mysqli_query($con, "DELETE FROM clientes WHERE id='" . $id_cliente . "'")) {
		echo '<div class="alert alert-success alert-dismissible" role="alert">
             <button type="button" class="close" data-dismiss="alert">&times;</button>
             <strong>¡Bien!</strong> Cliente eliminado.
             </div>';
	} else {
		echo '<div class="alert alert-danger alert-dismissible" role="alert">
             <button type="button" class="close" data-dismiss="alert">&times;</button>
             <strong>Error al borrar:</strong> ' . mysqli_error($con) . '
             </div>';
	}
}

if ($action == 'ajax') {
	$q = (isset($_REQUEST['q']) && !empty($_REQUEST['q'])) ? mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES))) : "";

	// --- CONFIGURACIÓN SEGURA DE COLUMNAS ---
	// NOTA: He quitado 'rif_empresa' temporalmente de la búsqueda por si ese es el error.
	// Solo buscamos por nombre y email para asegurar que cargue la lista.
	$aColumns = array('nombres_completos', 'correo');

	$sTable = "clientes";
	$sWhere = "";

	if ($q != "") {
		$sWhere = "WHERE (";
		for ($i = 0; $i < count($aColumns); $i++) {
			$sWhere .= $aColumns[$i] . " LIKE '%" . $q . "%' OR ";
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';
	}

	$sWhere .= " ORDER BY nombres_completos ASC";

	include 'pagination.php';

	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	$per_page = 10;
	$adjacents = 4;
	$offset = ($page - 1) * $per_page;

	// 1. COUNT QUERY
	$count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable $sWhere");

	// DEBUG DE ERROR SQL SI FALLA EL CONTEO
	if (!$count_query) {
		die('<div class="alert alert-danger">Error SQL (Conteo): ' . mysqli_error($con) . '</div>');
	}

	$row = mysqli_fetch_array($count_query);
	$numrows = $row['numrows'];
	$total_pages = ceil($numrows / $per_page);
	$reload = './clientes.php';

	// 2. MAIN QUERY
	$sql = "SELECT * FROM $sTable $sWhere LIMIT $offset,$per_page";
	$query = mysqli_query($con, $sql);

	// DEBUG DE ERROR SQL SI FALLA LA CONSULTA PRINCIPAL
	if (!$query) {
		die('<div class="alert alert-danger">Error SQL (Consulta): ' . mysqli_error($con) . '</div>');
	}

	if ($numrows > 0) {
		?>
		<div class="table-responsive">
			<table class="table table-hover align-middle">
				<thead style="background-color: #f8f9fa;">
					<tr>
						<th style="padding:15px;">Documento</th>
						<th style="padding:15px;">Cliente</th>
						<th style="padding:15px;">Contacto</th>
						<th style="padding:15px;">Estado</th>
						<th style="padding:15px;">Agregado</th>
						<th class='text-right' style="padding:15px;">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while ($row = mysqli_fetch_array($query)) {
						$id_cliente = $row['id'];
						$nombre_cliente = $row['nombres_completos'];

						// INTENTO INTELIGENTE DE OBTENER EL RIF/DOC
						// Probamos los nombres de columna más comunes
						$rif_cliente = "S/D";
						if (isset($row['identificacion']))
							$rif_cliente = $row['identificacion'];
						elseif (isset($row['nombres_completos']))
							$rif_cliente = $row['nombres_completos'];
						elseif (isset($row['telefono1']))
							$rif_cliente = $row['telefono1'];
						elseif (isset($row['correo']))
							$rif_cliente = $row['correo'];

						$telefono_cliente = $row['telefono1'];
						$email_cliente = $row['correo'];
						$direccion_cliente = $row['direccion'];
						$status_cliente = $row['estado'];
						$date_added = date('d/m/Y', strtotime($row['fecha_creacion']));

						$estado_badge = ($status_cliente == 1)
							? '<span class="label label-success">Activo</span>'
							: '<span class="label label-danger">Inactivo</span>';
						?>

						<input type="hidden" value="<?php echo $nombre_cliente; ?>"
							id="nombres_completos<?php echo $id_cliente; ?>">
						<input type="hidden" value="<?php echo $rif_cliente; ?>" id="identificacion<?php echo $id_cliente; ?>">
						<input type="hidden" value="<?php echo $telefono_cliente; ?>" id="telefono1<?php echo $id_cliente; ?>">
						<input type="hidden" value="<?php echo $email_cliente; ?>" id="correo<?php echo $id_cliente; ?>">
						<input type="hidden" value="<?php echo $direccion_cliente; ?>" id="direccion<?php echo $id_cliente; ?>">
						<input type="hidden" value="<?php echo $status_cliente; ?>" id="status_cliente<?php echo $id_cliente; ?>">

						<tr style="border-bottom: 1px solid #eee;">
							<td><b><?php echo $rif_cliente; ?></b></td>
							<td><?php echo $nombre_cliente; ?></td>
							<td>
								<?php if ($telefono_cliente)
									echo "<i class='glyphicon glyphicon-phone'></i> $telefono_cliente<br>"; ?>
								<?php if ($email_cliente)
									echo "<small class='text-muted'>$email_cliente</small>"; ?>
							</td>
							<td><?php echo $estado_badge; ?></td>
							<td><?php echo $date_added; ?></td>
							<td class='text-right'>
								<a href="#" class='btn btn-default btn-circle btn-sm' title='Editar'
									onclick="obtener_datos('<?php echo $id_cliente; ?>');" data-toggle="modal"
									data-target="#myModal2"><i class="glyphicon glyphicon-edit text-primary"></i></a>
								<a href="#" class='btn btn-default btn-circle btn-sm' title='Borrar'
									onclick="eliminar('<?php echo $id_cliente; ?>')"><i
										class="glyphicon glyphicon-trash text-danger"></i></a>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan=6 class="text-center">
							<?php echo paginate($reload, $page, $total_pages, $adjacents); ?>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php
	} else {
		?>
		<div class="alert alert-warning text-center" style="margin-top: 20px;">
			<i class="glyphicon glyphicon-exclamation-sign" style="font-size: 24px;"></i><br>
			<strong>No se encontraron clientes.</strong>
		</div>
		<?php
	}
}
?>