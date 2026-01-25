<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Búsqueda de Productos (AJAX)
 * DISEÑO: Grid de Tarjetas Modernas
 *----------------------------------------------------------------------------------------------------------------*/
include('is_logged.php'); // Archivo verifica que el usuario que intenta acceder a la URL esta logueado
require_once("../config/db.php");
require_once("../config/conexion.php");
include("../funciones.php");

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';

// 1. LÓGICA DE ELIMINACIÓN (Mantenida del original)
if (isset($_GET['id'])) {
	$id_producto = intval($_GET['id']);
	// Validamos primero si existe en historial para no romper integridad referencial (Opcional, buena práctica)
	$query = mysqli_query($con, "SELECT * FROM historial WHERE id_producto='" . $id_producto . "'");
	$count = mysqli_num_rows($query);

	if ($count == 0) {
		if ($delete1 = mysqli_query($con, "DELETE FROM products WHERE id_producto='" . $id_producto . "'")) {
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>¡Bien hecho!</strong> Datos eliminados exitosamente.
			</div>
			<?php
		} else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Error!</strong> No se pudo eliminar el producto. Intenta nuevamente.
			</div>
			<?php
		}
	} else {
		?>
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Error!</strong> No se puede eliminar el producto porque tiene movimientos en el historial.
		</div>
		<?php
	}
}

// 2. LÓGICA DE BÚSQUEDA
if ($action == 'ajax') {
	// Escaping
	$q = (isset($_REQUEST['q']) && !empty($_REQUEST['q'])) ? mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES))) : "";

	// Configuración de columnas
	$aColumns = array('codigo_producto', 'nombre_producto');
	$sTable = "products";
	$sWhere = "";

	// Filtro de Categoría
	$id_categoria = (isset($_REQUEST['id_categoria']) && !empty($_REQUEST['id_categoria'])) ? $_REQUEST['id_categoria'] : "";

	// Construcción del WHERE
	if ($q != "") {
		$sWhere = "WHERE (";
		for ($i = 0; $i < count($aColumns); $i++) {
			$sWhere .= $aColumns[$i] . " LIKE '%" . $q . "%' OR ";
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';

		if ($id_categoria != "") {
			$sWhere .= " AND id_categoria = '$id_categoria'";
		}
	} else {
		if ($id_categoria != "") {
			$sWhere = "WHERE id_categoria = '$id_categoria'";
		}
	}

	$sWhere .= " ORDER BY id_producto DESC";

	// Paginación
	include 'pagination.php';
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	$per_page = 12; // Mostramos 12 productos (multiplo de 3 y 4 para que cuadre el grid)
	$adjacents = 4;
	$offset = ($page - 1) * $per_page;

	$count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable $sWhere");
	$row = mysqli_fetch_array($count_query);
	$numrows = $row['numrows'];
	$total_pages = ceil($numrows / $per_page);
	$reload = './productos.php';

	$sql = "SELECT * FROM $sTable $sWhere LIMIT $offset,$per_page";
	$query = mysqli_query($con, $sql);

	if ($numrows > 0) {
		// Estilos Inline para asegurar que el grid se vea bien sin editar el CSS global
		?>
		<style>
			.product-card {
				background: #fff;
				border-radius: 12px;
				box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
				transition: transform 0.3s ease, box-shadow 0.3s ease;
				margin-bottom: 30px;
				overflow: hidden;
				border: 1px solid #eee;
				height: 100%;
				position: relative;
			}

			.product-card:hover {
				transform: translateY(-5px);
				box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
				border-color: #3498db;
			}

			.card-img-container {
				height: 180px;
				background: #f9f9f9;
				display: flex;
				align-items: center;
				justify-content: center;
				position: relative;
				padding: 15px;
			}

			.card-img-container img {
				max-height: 100%;
				max-width: 100%;
				object-fit: contain;
			}

			.card-body-custom {
				padding: 15px;
				text-align: center;
			}

			.product-name {
				font-size: 16px;
				font-weight: 700;
				color: #2c3e50;
				margin: 10px 0 5px;
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
			}

			.product-code {
				font-size: 12px;
				color: #95a5a6;
				margin-bottom: 10px;
				display: block;
			}

			.stock-badge-float {
				position: absolute;
				top: 10px;
				right: 10px;
				padding: 5px 10px;
				border-radius: 20px;
				font-size: 12px;
				font-weight: 600;
				background: rgba(255, 255, 255, 0.9);
				box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
			}

			.stock-ok {
				color: #27ae60;
				border: 1px solid #27ae60;
			}

			.stock-low {
				color: #e74c3c;
				border: 1px solid #e74c3c;
				background: #fff0f0;
			}

			.price-display {
				font-size: 18px;
				font-weight: bold;
				color: #3498db;
				margin-bottom: 15px;
				display: block;
			}

			.btn-view-details {
				border-radius: 50px;
				padding: 6px 20px;
				font-size: 13px;
				width: 100%;
				background-color: #f8f9fa;
				border: 1px solid #ddd;
				color: #555;
				transition: all 0.2s;
			}

			.btn-view-details:hover {
				background-color: #3498db;
				color: white;
				border-color: #3498db;
				text-decoration: none;
			}
		</style>

		<div class="row" style="display: flex; flex-wrap: wrap;">
			<?php
			while ($row = mysqli_fetch_array($query)) {
				$id_producto = $row['id_producto'];
				$codigo_producto = $row['codigo_producto'];
				$nombre_producto = $row['nombre_producto'];
				$stock = $row['stock'];
				$precio_producto = $row['precio_producto']; // Precio en Bs
				// Si tienes columna de imagen, úsala, sino usa default
				$image_path = (isset($row['image_path']) && !empty($row['image_path'])) ? $row['image_path'] : "img/producto.png";

				// Lógica de alerta de stock bajo (ejemplo: menos de 10)
				$stock_class = ($stock <= 5) ? "stock-low" : "stock-ok";
				$stock_icon = ($stock <= 5) ? "glyphicon-alert" : "glyphicon-ok-circle";
				?>

				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="margin-bottom: 20px;">
					<div class="product-card" onclick="window.location.href='producto.php?id=<?php echo $id_producto; ?>'"
						style="cursor: pointer;">

						<div class="card-img-container">
							<span class="stock-badge-float <?php echo $stock_class; ?>">
								<i class="glyphicon <?php echo $stock_icon; ?>"></i> <?php echo number_format($stock, 0); ?>
							</span>
							<img src="<?php echo $image_path; ?>" alt="<?php echo $nombre_producto; ?>">
						</div>

						<div class="card-body-custom">
							<div class="product-name" title="<?php echo $nombre_producto; ?>">
								<?php echo $nombre_producto; ?>
							</div>
							<span class="product-code">
								<i class="glyphicon glyphicon-barcode"></i> <?php echo $codigo_producto; ?>
							</span>

							<span class="price-display">
								Bs <?php echo number_format($precio_producto, 2, ',', '.'); ?>
							</span>

							<a href="producto.php?id=<?php echo $id_producto; ?>" class="btn btn-view-details">
								Ver Detalles
							</a>
						</div>
					</div>
				</div>

				<?php
			}
			?>
		</div>

		<div class="row">
			<div class="col-md-12 text-center">
				<?php echo paginate($reload, $page, $total_pages, $adjacents); ?>
			</div>
		</div>

		<?php
	} else {
		?>
		<div class="alert alert-info text-center" style="margin-top: 50px;">
			<i class="glyphicon glyphicon-search" style="font-size: 30px; margin-bottom: 10px;"></i>
			<h4>No se encontraron productos</h4>
			<p>Intenta con otro término de búsqueda o agrega un nuevo producto.</p>
		</div>
		<?php
	}
}
?>