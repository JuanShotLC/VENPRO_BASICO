<?php
/* Connect To Database*/
require_once("../config/db.php");
require_once("../config/conexion.php");
include('is_logged.php');

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';

if ($action == 'ajax') {
	$q = mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
	$sTable = "products";
	$sWhere = "";

	if ($_GET['q'] != "") {
		$sWhere = "WHERE (nombre_producto LIKE '%$q%' OR codigo_producto LIKE '%$q%')";
	}

	$sWhere .= " ORDER BY nombre_producto ASC";

	include 'pagination.php'; //include pagination file

	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	$per_page = 9; //registros por pagina
	$adjacents = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;

	//Count the total number of row in your table
	$count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable $sWhere");
	$row = mysqli_fetch_array($count_query);
	$numrows = $row['numrows'];
	$total_pages = ceil($numrows / $per_page);
	$reload = './cotizacion.php';

	//main query to fetch the data
	$sql = "SELECT * FROM $sTable $sWhere LIMIT $offset,$per_page";
	$query = mysqli_query($con, $sql);

	// Obtener tasa actual para referencia visual
	$sql_tasa = mysqli_query($con, "SELECT precio FROM cotizacion ORDER BY id_coti DESC LIMIT 1");
	$row_tasa = mysqli_fetch_array($sql_tasa);
	$tasa_ref = isset($row_tasa['precio']) ? $row_tasa['precio'] : 0;

	if ($numrows > 0) {
		?>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr class="info">
					<th>Código</th>
					<th>Producto</th>
					<th class='text-right'>Precio Ref ($)</th>
					<th class='text-right'>Precio Venta (Bs)</th>
				</tr>
				<?php
				while ($row = mysqli_fetch_array($query)) {
					$id_producto = $row['id_producto'];
					$codigo_producto = $row['codigo_producto'];
					$nombre_producto = $row['nombre_producto'];
					$precio_dolar = $row['precio_dolar'];
					$precio_bs = $row['precio_producto'];
					?>
					<tr>
						<td><?php echo $codigo_producto; ?></td>
						<td><?php echo $nombre_producto; ?></td>
						<td class='text-right'>$ <?php echo number_format($precio_dolar, 2); ?></td>
						<td class='text-right'><strong>Bs. <?php echo number_format($precio_bs, 2); ?></strong></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=4><span class="pull-right">
							<?php
							echo paginate($reload, $page, $total_pages, $adjacents);
							?></span></td>
				</tr>
			</table>
		</div>
		<?php
	} else {
		?>
		<div class="alert alert-warning alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
					aria-hidden="true">&times;</span></button>
			<strong>Aviso!</strong> No hay productos registrados o no coinciden con la búsqueda.
		</div>
		<?php
	}
}
?>