<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Ficha de Producto (Frontend)
 * VERSIÓN: Final Corregida y Estilizada
 *----------------------------------------------------------------------------------------------------------------*/
session_start();
if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
	header("location: login.php");
	exit;
}

require_once("config/db.php");
require_once("config/conexion.php");
include("funciones.php");

$active_facturas = "";
$active_productos = "active";
$active_clientes = "";
$active_usuarios = "";
$title = "Detalle del Producto | Sistema";

// --------------------------------------------------------------------------------
// 1. LÓGICA DE ACTUALIZACIÓN DE STOCK (Backend en el mismo archivo)
// --------------------------------------------------------------------------------
if (isset($_POST['reference']) and isset($_POST['quantity'])) {
	$quantity = intval($_POST['quantity']);
	$reference = mysqli_real_escape_string($con, (strip_tags($_POST["reference"], ENT_QUOTES)));
	$id_producto = intval($_GET['id']);
	$user_id = $_SESSION['user_id'];
	$firstname = $_SESSION['firstname'];
	$nota = "$firstname agregó $quantity producto(s) al inventario";
	$fecha = date("Y-m-d H:i:s");

	guardar_historial($id_producto, $user_id, $fecha, $nota, $reference, $quantity);
	$update = agregar_stock($id_producto, $quantity);

	if ($update == 1) {
		$message = "Stock agregado exitosamente.";
	} else {
		$error = "No se pudo actualizar el stock.";
	}
}

if (isset($_POST['reference_remove']) and isset($_POST['quantity_remove'])) {
	$quantity = intval($_POST['quantity_remove']);
	$reference = mysqli_real_escape_string($con, (strip_tags($_POST["reference_remove"], ENT_QUOTES)));
	$id_producto = intval($_GET['id']);
	$user_id = $_SESSION['user_id'];
	$firstname = $_SESSION['firstname'];
	$nota = "$firstname eliminó $quantity producto(s) del inventario";
	$fecha = date("Y-m-d H:i:s");

	guardar_historial($id_producto, $user_id, $fecha, $nota, $reference, $quantity);
	$update = eliminar_stock($id_producto, $quantity);

	if ($update == 1) {
		$message = "Stock eliminado exitosamente.";
	} else {
		$error = "No se pudo actualizar el stock.";
	}
}

// --------------------------------------------------------------------------------
// 2. CONSULTA DE DATOS DEL PRODUCTO
// --------------------------------------------------------------------------------
if (isset($_REQUEST['id'])) {
	$id_producto = intval($_REQUEST['id']);

	// Unimos con la tabla categorías para obtener el nombre, no solo el ID
	$sql = "SELECT p.*, c.nombre_categoria 
            FROM products p 
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
            WHERE p.id_producto = '$id_producto'";

	$query = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($query);

	if (!$row) {
		header("location: productos.php");
		exit;
	}

	// Variables de datos
	$codigo_producto = $row['codigo_producto'];
	$nombre_producto = $row['nombre_producto'];
	$nombre_categoria = $row['nombre_categoria']; // Nombre real de la categoría
	if (empty($nombre_categoria))
		$nombre_categoria = "Sin Categoría";

	$precio_bs = $row['precio_producto']; // Precio en Bolívares
	$precio_dolar = $row['precio_dolar'];    // Precio en Dólares
	$stock_producto = $row['stock'];
	$tasa_actual = $row['dolar_boli'];
	$id_categoria = $row['id_categoria']; // ID para el modal de edición
	$status_producto = $row['status_producto'];
	$image_path = "img/producto.png";

} else {
	header("location: productos.php");
	exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<?php include("head.php"); ?>
	<style>
		body {
			background-color: #f4f6f9;
		}

		/* Columna Izquierda: Perfil */
		.profile-card {
			background: #fff;
			border-radius: 12px;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
			padding: 30px;
			text-align: center;
			margin-bottom: 20px;
		}

		.product-image-container {
			position: relative;
			width: 180px;
			height: 180px;
			margin: 0 auto 20px;
			border-radius: 15px;
			overflow: hidden;
			border: 4px solid #f8f9fa;
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
		}

		.product-image {
			width: 100%;
			height: 100%;
			object-fit: cover;
		}

		.product-title {
			font-size: 20px;
			font-weight: 700;
			color: #2c3e50;
			margin-bottom: 5px;
		}

		.product-code {
			color: #95a5a6;
			font-size: 14px;
			letter-spacing: 1px;
			margin-bottom: 20px;
		}

		.status-badge {
			padding: 6px 15px;
			border-radius: 20px;
			font-size: 12px;
			font-weight: 600;
			text-transform: uppercase;
		}

		.status-active {
			background-color: #e8f5e9;
			color: #27ae60;
		}

		.status-inactive {
			background-color: #ffebee;
			color: #c62828;
		}

		/* KPI Cards (Estadísticas rápidas) */
		.kpi-row {
			margin-bottom: 20px;
		}

		.kpi-card {
			background: white;
			padding: 20px;
			border-radius: 10px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
			display: flex;
			align-items: center;
			margin-bottom: 15px;
		}

		.kpi-icon {
			width: 50px;
			height: 50px;
			border-radius: 10px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 24px;
			margin-right: 15px;
		}

		.bg-blue {
			background: #e3f2fd;
			color: #2196f3;
		}

		.bg-green {
			background: #e8f5e9;
			color: #43a047;
		}

		.bg-purple {
			background: #f3e5f5;
			color: #8e24aa;
		}

		.kpi-value {
			font-size: 22px;
			font-weight: 700;
			color: #344767;
			display: block;
			line-height: 1.2;
		}

		.kpi-label {
			font-size: 12px;
			color: #7b809a;
			font-weight: 500;
			text-transform: uppercase;
		}

		/* Tabs Modernas */
		.nav-tabs-modern {
			border-bottom: 2px solid #e9ecef;
			margin-bottom: 20px;
		}

		.nav-tabs-modern>li>a {
			border: none;
			color: #6c757d;
			font-weight: 600;
			padding: 15px 25px;
			font-size: 14px;
			background: transparent;
			transition: color 0.3s;
		}

		.nav-tabs-modern>li.active>a,
		.nav-tabs-modern>li.active>a:hover,
		.nav-tabs-modern>li.active>a:focus {
			border: none;
			border-bottom: 3px solid #3498db;
			color: #3498db;
			background-color: transparent;
		}

		.nav-tabs-modern>li>a:hover {
			color: #3498db;
			background: transparent;
		}

		/* Panel Contenedor Derecha */
		.details-panel {
			background: white;
			border-radius: 12px;
			padding: 25px;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
			min-height: 400px;
		}
	</style>
</head>

<body>
	<?php include("navbar.php"); ?>

	<div class="container" style="margin-top: 80px;">

		<div class="row" style="margin-bottom: 20px;">
			<div class="col-xs-12">
				<a href="productos.php" class="btn btn-default" style="border-radius: 20px; padding: 6px 15px;">
					<i class="glyphicon glyphicon-arrow-left"></i> Volver al inventario
				</a>
			</div>
		</div>

		<div class="row">

			<div class="col-md-4">
				<div class="profile-card">
					<div class="product-image-container">
						<img src="<?php echo $image_path; ?>" class="product-image" alt="Producto">
						<button class="btn btn-sm btn-primary"
							style="position: absolute; bottom: 10px; right: 10px; border-radius: 50%; width: 35px; height: 35px;"
							data-toggle="modal" data-target="#myModal2" title="Cambiar Imagen">
							<i class="glyphicon glyphicon-camera"></i>
						</button>
					</div>

					<h3 class="product-title"><?php echo $nombre_producto; ?></h3>
					<div class="product-code">
						<i class="glyphicon glyphicon-barcode"></i> <?php echo $codigo_producto; ?>
					</div>

					<div style="margin-top: 15px;">
						<?php if ($status_producto == 1) { ?>
							<span class="status-badge status-active">Activo</span>
						<?php } else { ?>
							<span class="status-badge status-inactive">Inactivo</span>
						<?php } ?>
					</div>

					<hr>

					<?php if ($_SESSION['user_name'] == 'baraka') { ?>
						<button type="button" class="btn btn-info btn-block btn-round" data-toggle="modal"
							data-target="#myModal2" data-codigo="<?php echo $codigo_producto; ?>"
							data-nombre="<?php echo $nombre_producto; ?>" data-categoria="<?php echo $id_categoria; ?>"
							data-precio="<?php echo $precio_bs; ?>" data-dolar="<?php echo $precio_dolar; ?>"
							data-stock="<?php echo $stock_producto; ?>" data-id="<?php echo $id_producto; ?>">
							<i class="glyphicon glyphicon-edit"></i> Editar Datos
						</button>
					<?php } ?>

				</div>

				<div class="profile-card" style="padding: 20px; text-align: left;">
					<h5 style="margin-top: 0; color: #777;">Precio de Venta</h5>
					<div style="display: flex; align-items: center; justify-content: space-between;">
						<div>
							<span
								style="font-size: 28px; font-weight: bold; color: #27ae60;">$<?php echo number_format($precio_dolar, 2); ?></span>
							<span style="font-size: 14px; color: #999;">USD</span>
						</div>
						<div style="text-align: right;">
							<span style="font-size: 18px; font-weight: 600; color: #555;">Bs
								<?php echo number_format($precio_bs, 2); ?></span>
							<div style="font-size: 11px; color: #aaa;">(A tasa: <?php echo $tasa_actual; ?>)</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-8">
				<div class="row kpi-row">
					<div class="col-md-6">
						<div class="kpi-card">
							<div class="kpi-icon bg-blue"><i class="glyphicon glyphicon-hdd"></i></div>
							<div>
								<span class="kpi-value">
									<?php echo $stock_producto; ?>
								</span>
								<span class="kpi-label">Stock Actual</span>
							</div>
						</div>
					</div>
					<div class="col-md-6 text-right">
						<?php if ($_SESSION['user_name'] == 'baraka') { ?>
							<button class="btn btn-success btn-stock-action" data-toggle="modal" data-target="#add-stock">
								<i class="glyphicon glyphicon-plus"></i> Agregar Stock
							</button>

							<?php if ($stock_producto > 0) { ?>
								<button class="btn btn-danger btn-stock-action" data-toggle="modal" data-target="#remove-stock">
									<i class="glyphicon glyphicon-minus"></i> Quitar Stock
								</button>
							<?php } ?>
						<?php } ?>
					</div>
				</div>

				<div class="row kpi-row">
					<div class="col-md-6 col-sm-6">
						<div class="kpi-card">
							<div class="kpi-icon bg-blue"><i class="glyphicon glyphicon-open-file"></i></div>
							<div>
								<span class="kpi-value">Historial</span>
								<span class="kpi-label">Movimientos Recientes</span>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-6">
						<div class="kpi-card">
							<div class="kpi-icon bg-purple"><i class="glyphicon glyphicon-stats"></i></div>
							<div>
								<span class="kpi-value">Cotizaciones</span>
								<span class="kpi-label">Apariciones en presupuestos</span>
							</div>
						</div>
					</div>
				</div>

				<div class="details-panel">
					<ul class="nav nav-tabs nav-tabs-modern" role="tablist">
						<li role="presentation" class="active">
							<a href="#historial" aria-controls="historial" role="tab" data-toggle="tab">
								<i class="glyphicon glyphicon-time"></i> Movimientos
							</a>
						</li>
						<li role="presentation">
							<a href="#info" aria-controls="info" role="tab" data-toggle="tab">
								<i class="glyphicon glyphicon-info-sign"></i> Ficha Técnica
							</a>
						</li>
					</ul>

					<div class="tab-content">

						<div role="tabpanel" class="tab-pane active" id="historial">

							<div id="loader_historial" class="text-center" style="display:none; ">
								<i class="fa fa-spinner fa-spin fa-2x text-muted"></i>
							</div>

							<form class="form-horizontal" role="form" id="datos_cotizacion">
								<input type="hidden" id="q" onkeyup='load_historial(1);'>
								<input type="hidden" id="id_producto" value="<?php echo $id_producto; ?>">
							</form>

							<div class='outer_div_historial'></div>
						</div>

						<div role="tabpanel" class="tab-pane" id="info">
							<table class="table table-bordered table-striped table-info">
								<tbody>
									<tr>
										<th width="30%">Nombre del Producto</th>
										<td><?php echo $nombre_producto; ?></td>
									</tr>
									<tr>
										<th>Código / SKU</th>
										<td><?php echo $codigo_producto; ?></td>
									</tr>
									<tr>
										<th>Categoría</th>
										<td><span class="label label-info"><?php echo $nombre_categoria; ?></span></td>
									</tr>
									<tr>
										<th>Estado</th>
										<td>
											<?php if ($status_producto == 1) {
												echo '<span class="text-success"><i class="glyphicon glyphicon-ok-sign"></i> Activo</span>';
											} else {
												echo '<span class="text-danger"><i class="glyphicon glyphicon-remove-sign"></i> Inactivo</span>';
											} ?>
										</td>
									</tr>
									<tr>
										<th>Precio (USD)</th>
										<td style="font-size: 16px; font-weight: bold; color: #27ae60;">
											$ <?php echo number_format($precio_dolar, 2); ?>
										</td>
									</tr>
									<tr>
										<th>Precio (Bs)</th>
										<td style="font-size: 16px; font-weight: bold;">
											Bs <?php echo number_format($precio_bs, 2, ',', '.'); ?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>
				</div>

			</div>
		</div>

		<?php
		include("footer.php");
		// Modales necesarios
		include("modal/editar_productos.php");
		include("modal/agregar_stock.php");
		include("modal/eliminar_stock.php");
		?>
		<script type="text/javascript" src="js/productos.js"></script>


		<script>


			$("#editar_producto").submit(function (event) {
				$('#actualizar_datos').attr("disabled", true);

				var parametros = $(this).serialize();
				$.ajax({
					type: "POST",
					url: "ajax/editar_producto.php",
					data: parametros,
					beforeSend: function (objeto) {
						$("#resultados_ajax2").html("Mensaje: Cargando...");
					},
					success: function (datos) {
						$("#resultados_ajax2").html(datos);
						$('#actualizar_datos').attr("disabled", false);
						window.setTimeout(function () {
							$(".alert").fadeTo(500, 0).slideUp(500, function () {
								$(this).remove();
							});
							location.reload(true);
						}, 1000);
					}
				});
				event.preventDefault();
			})

			$('#myModal2').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget) // Button that triggered the modal
				var codigo = button.data('codigo') // Extract info from data-* attributes
				var nombre = button.data('nombre')
				var categoria = button.data('categoria')
				var precio = button.data('precio')
				var dolar = button.data('dolar')
				var stock = button.data('stock')
				var id = button.data('id')
				var modal = $(this)
				modal.find('.modal-body #mod_codigo').val(codigo)
				modal.find('.modal-body #mod_nombre').val(nombre)
				modal.find('.modal-body #mod_categoria').val(categoria)
				modal.find('.modal-body #mod_precio').val(precio)
				modal.find('.modal-body #mod_dolar').val(dolar)
				modal.find('.modal-body #mod_stock').val(stock)
				modal.find('.modal-body #mod_id').val(id)
			})

			function eliminar(id) {
				var q = $("#q").val();
				if (confirm("Realmente deseas eliminar el producto")) {
					location.replace('inventario.php?delete=' + id);
				}
			}


		</script>

</body>

</html>