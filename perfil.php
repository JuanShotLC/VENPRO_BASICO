<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Configuración de Empresa y Sistema
 * INCLUYE: Datos fiscales + Gestión de Backups
 *----------------------------------------------------------------------------------------------------------------*/
session_start();
if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
	header("location: login.php");
	exit;
}

require_once("config/db.php");
require_once("config/conexion.php");

$active_facturas = "";
$active_productos = "";
$active_clientes = "";
$active_usuarios = "";
$active_perfil = "active";
$title = "Configuración | Sistema";

// Obtener datos de la empresa (ID 1)
$query_empresa = mysqli_query($con, "SELECT * FROM perfil WHERE id_perfil=1");
$row = mysqli_fetch_array($query_empresa);
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<?php include("head.php"); ?>
	<style>
		body {
			background-color: #f4f6f9;
		}

		.page-title {
			font-size: 24px;
			font-weight: 700;
			color: #343a40;
			display: flex;
			align-items: center;
		}

		.page-title i {
			background-color: #f3e5f5;
			color: #8e24aa;
			padding: 10px;
			border-radius: 10px;
			margin-right: 15px;
			font-size: 20px;
		}

		/* Tarjetas Modernas */
		.card-modern {
			background: #fff;
			border-radius: 12px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
			border: none;
			overflow: hidden;
			margin-bottom: 20px;
			padding: 25px;
		}

		/* Columna Izquierda: Logo y Mantenimiento */
		.profile-section {
			text-align: center;
		}

		.logo-container {
			width: 100%;
			max-width: 200px;
			height: 200px;
			margin: 0 auto 15px;
			border: 2px dashed #e0e0e0;
			border-radius: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			background: #fafafa;
			overflow: hidden;
			position: relative;
		}

		.logo-container img {
			max-width: 100%;
			max-height: 100%;
		}

		.file-upload-btn {
			position: relative;
			overflow: hidden;
			margin-bottom: 10px;
		}

		.file-upload-btn input[type=file] {
			position: absolute;
			top: 0;
			right: 0;
			min-width: 100%;
			min-height: 100%;
			font-size: 100px;
			text-align: right;
			filter: alpha(opacity=0);
			opacity: 0;
			outline: none;
			background: white;
			cursor: pointer;
			display: block;
		}

		/* Sección de Backup */
		.backup-zone {
			background-color: #e3f2fd;
			border: 1px solid #bbdefb;
			border-radius: 10px;
			padding: 20px;
			margin-top: 30px;
			text-align: left;
		}

		.backup-title {
			font-weight: 700;
			color: #1565c0;
			margin-bottom: 10px;
			font-size: 16px;
		}

		.btn-backup {
			background-color: #1976d2;
			color: white;
			border: none;
			border-radius: 50px;
			padding: 10px 20px;
			font-weight: 600;
			width: 100%;
			transition: all 0.3s;
		}

		.btn-backup:hover {
			background-color: #1565c0;
			box-shadow: 0 4px 10px rgba(25, 118, 210, 0.3);
			color: white;
		}

		/* Formulario Derecha */
		.form-section h4 {
			border-bottom: 2px solid #f0f0f0;
			padding-bottom: 15px;
			margin-top: 0;
			margin-bottom: 25px;
			color: #555;
			font-weight: 600;
		}

		.control-label {
			color: #666;
			font-weight: 600;
		}

		.form-control {
			border-radius: 6px;
			height: 42px;
			border: 1px solid #dde2e5;
		}

		.form-control:focus {
			border-color: #8e24aa;
			box-shadow: 0 0 0 3px rgba(142, 36, 170, 0.1);
		}
	</style>
</head>

<body>
	<?php include("navbar.php"); ?>

	<div class="container-fluid">

		<div class="main-header">
			<h1 class="page-title">
				<i class="glyphicon glyphicon-cog"></i> Configuración del Sistema
			</h1>
		</div>

		<div class="row">

			<div class="col-md-4">
				<div class="card-modern profile-section">

					<h4 style="margin-top: 0; color: #555;">Logo de la Empresa</h4>
					<p class="text-muted small">Este logo aparecerá en facturas y reportes</p>

					<div class="logo-container">
						<?php
						$logo_path = isset($row['logo_url']) ? $row['logo_url'] : 'img/logo.png';
						// Ajuste por si la ruta en DB no tiene 'img/'
						if (!file_exists($logo_path) && file_exists("img/" . $logo_path)) {
							$logo_path = "img/" . $logo_path;
						}
						?>
						<img class="img-responsive" src="<?php echo $logo_path; ?>" alt="Logo" id="logo_img">
					</div>

					<div class="file-upload-btn btn btn-default btn-block">
						<span><i class="glyphicon glyphicon-camera"></i> Cambiar Logo</span>
						<input type="file" id="imagefile" name="imagefile" onchange="upload_image();">
					</div>
					<div id="load_img" style="height: 20px;"></div>


				</div>
			</div>

			<div class="col-md-8">
				<div class="card-modern form-section">
					<h4><i class="glyphicon glyphicon-list-alt"></i> Datos Fiscales y de Contacto</h4>

					<div id="resultados_ajax"></div>

					<form method="post" id="perfil" class="form-horizontal">

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="nombre_empresa" class="control-label">Nombre / Razón Social</label>
									<input type="text" class="form-control" name="nombre_empresa"
										value="<?php echo $row['nombre_empresa']; ?>" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="rif" class="control-label">RIF / Identificación Fiscal</label>
									<input type="text" class="form-control" name="rif"
										value="<?php echo $row['rif']; ?>" required>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="telefono" class="control-label">Teléfono</label>
									<input type="text" class="form-control" name="telefono"
										value="<?php echo $row['telefono']; ?>" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="email" class="control-label">Correo Electrónico</label>
									<input type="email" class="form-control" name="email"
										value="<?php echo $row['email']; ?>">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="direccion" class="control-label">Dirección Fiscal</label>
									<textarea class="form-control" name="direccion" rows="3"
										required><?php echo $row['direccion']; ?></textarea>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="ciudad" class="control-label">Ciudad</label>
									<input type="text" class="form-control" name="ciudad"
										value="<?php echo $row['ciudad']; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="estado" class="control-label">Estado / Provincia</label>
									<input type="text" class="form-control" name="estado"
										value="<?php echo $row['estado']; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codigo_postal" class="control-label">Código Postal</label>
									<input type="text" class="form-control" name="codigo_postal"
										value="<?php echo $row['codigo_postal']; ?>">
								</div>
							</div>
						</div>

						<hr>
						<h4><i class="glyphicon glyphicon-usd"></i> Configuración de Impuestos y Moneda</h4>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="impuesto" class="control-label">IVA (%)</label>
									<div class="input-group">
										<input type="text" class="form-control" name="impuesto"
											value="<?php echo $row['impuesto']; ?>" required>
										<span class="input-group-addon"><i
												class="glyphicon glyphicon-percentage"></i></span>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="moneda" class="control-label">Símbolo de Moneda</label>
									<select class="form-control" name="moneda" required>
										<option value="$" <?php if ($row['moneda'] == '$') {
											echo "selected";
										} ?>>$ (Dólar)
										</option>
										<option value="Bs" <?php if ($row['moneda'] == 'Bs') {
											echo "selected";
										} ?>>Bs
											(Bolívares)</option>
										<option value="€" <?php if ($row['moneda'] == '€') {
											echo "selected";
										} ?>>€ (Euro)
										</option>
									</select>
								</div>
							</div>
						</div>

						<div class="row" style="margin-top: 20px;">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-success btn-lg btn-round" id="guardar_datos">
									<i class="glyphicon glyphicon-floppy-saved"></i> Guardar Cambios
								</button>
							</div>
						</div>

					</form>
				</div>
			</div>

		</div>
	</div>

	<?php include("footer.php"); ?>

	<script>
		// 1. SUBIR IMAGEN (LOGO)
		function upload_image() {
			var inputFileImage = document.getElementById("imagefile");
			var file = inputFileImage.files[0];
			if ((typeof file === "object") && (file !== null)) {
				$("#load_img").html('<i class="fa fa-spinner fa-spin"></i> Cargando...');
				var data = new FormData();
				data.append('imagefile', file);

				$.ajax({
					url: "ajax/imagen_ajax.php",
					type: "POST",
					data: data,
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
						$("#load_img").html('');
						// Forzar recarga de imagen agregando timestamp
						var d = new Date();
						$("#logo_img").attr("src", "img/logo.png?" + d.getTime());

						// Alerta bonita
						swal("¡Actualizado!", "El logo de la empresa ha sido cambiado.", "success");
					}
				});
			}
		}

		// 2. GUARDAR DATOS DEL FORMULARIO
		$("#perfil").submit(function (event) {
			$('#guardar_datos').attr("disabled", true);
			var parametros = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "ajax/editar_perfil.php",
				data: parametros,
				beforeSend: function (objeto) {
					$("#resultados_ajax").html("Mensaje: Cargando...");
				},
				success: function (datos) {
					$("#resultados_ajax").html(datos);
					$('#guardar_datos').attr("disabled", false);
					// Opcional: Auto-ocultar alerta
					window.setTimeout(function () {
						$(".alert").fadeTo(500, 0).slideUp(500, function () {
							$(this).remove();
						});
					}, 3000);
				}
			});
			event.preventDefault();
		})
	</script>
</body>

</html>