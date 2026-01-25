<?php
// MANTENER LA LÓGICA PHP ORIGINAL INTACTA
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
	exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
	require_once("libraries/password_compatibility_library.php");
}

require_once("config/db.php");
require_once("config/conexion.php");
require_once("classes/Login.php");

$login = new Login();

// Si ya está logueado, redirigir
if ($login->isUserLoggedIn() == true) {
	header("location: /#/factura_venta");
	exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Iniciar Sesión | VENPRO</title>

	<link href="bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/login.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

	<div class="login-container">

		<div class="login-banner">
			<div class="banner-content">
				<h1 class="banner-title">VENPRO</h1>
				<p class="banner-text">Sistema de Gestión de Ventas e Inventario</p>
				<br>
				<small>&copy; 2026 Todos los derechos reservados</small>
			</div>
		</div>

		<div class="login-form-wrapper">
			<div class="form-content">

				<div class="login-header">
					<img src="img/logo-icon.png" alt="Logo" style="max-height: 50px; margin-bottom: 20px;">
					<h3>Bienvenido de nuevo</h3>
					<p>Ingresa tus credenciales para acceder al sistema.</p>
				</div>

				<?php
				if (isset($login)) {
					if ($login->errors) {
						?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong>Error!</strong>
							<?php
							foreach ($login->errors as $error) {
								echo $error;
							}
							?>
						</div>
						<?php
					}
					if ($login->messages) {
						?>
						<div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong>Aviso!</strong>
							<?php
							foreach ($login->messages as $message) {
								echo $message;
							}
							?>
						</div>
						<?php
					}
				}
				?>

				<form method="post" accept-charset="utf-8" action="login.php" name="loginform" autocomplete="off">

					<div class="form-group">
						<label class="sr-only" for="user_name">Usuario</label>
						<div class="input-group">
							<span class="input-group-addon" style="background:none; border:none; padding-right:0;">
								<i class="fa fa-user text-muted"></i>
							</span>
							<input class="form-control" style="border-left:none;" placeholder="Nombre de usuario"
								name="user_name" type="text" required autofocus>
						</div>
					</div>

					<div class="form-group">
						<label class="sr-only" for="user_password">Contraseña</label>
						<div class="input-group">
							<span class="input-group-addon" style="background:none; border:none; padding-right:0;">
								<i class="fa fa-lock text-muted"></i>
							</span>
							<input class="form-control" style="border-left:none;" placeholder="Contraseña"
								name="user_password" type="password" required autocomplete="off">
						</div>
					</div>

					<button type="submit" class="btn btn-login" name="login" id="submit">
						Ingresar <i class="fa fa-arrow-right"></i>
					</button>

				</form>

				<div style="margin-top: 30px; text-align: center;">
					<a href="#" class="text-muted" style="font-size: 13px;">¿Olvidaste tu contraseña?</a>
				</div>

			</div>
		</div>
	</div>

	<script src="js/jquery.min.js"></script>
	<script src="bootstrap-3.3.7/js/bootstrap.min.js"></script>
</body>

</html>