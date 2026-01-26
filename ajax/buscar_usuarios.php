<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Búsqueda de Usuarios (AJAX)
 * DISEÑO: Tabla Profesional con Gestión de Roles
 *----------------------------------------------------------------------------------------------------------------*/
include('is_logged.php');
require_once("../config/db.php");
require_once("../config/conexion.php");

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';

// --------------------------------------------------------------------------------
// 1. ELIMINAR USUARIO
// --------------------------------------------------------------------------------
if (isset($_GET['id'])) {
	$user_id = intval($_GET['id']);

	// Evitar que se borre el Super Admin (ID 1) o el usuario actual a sí mismo (opcional)
	if ($user_id != 1) {
		if ($delete1 = mysqli_query($con, "DELETE FROM users WHERE user_id='" . $user_id . "'")) {
			echo '<div class="alert alert-success alert-dismissible" style="margin:15px;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>¡Hecho!</strong> Usuario eliminado correctamente.
                  </div>';
		} else {
			echo '<div class="alert alert-danger alert-dismissible" style="margin:15px;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Error:</strong> No se pudo borrar el usuario.
                  </div>';
		}
	} else {
		echo '<div class="alert alert-warning alert-dismissible" style="margin:15px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Protegido:</strong> No se puede eliminar al Administrador Principal.
              </div>';
	}
}

// --------------------------------------------------------------------------------
// 2. LISTAR USUARIOS
// --------------------------------------------------------------------------------
if ($action == 'ajax') {

	// Validación de búsqueda
	$q = (isset($_REQUEST['q']) && !empty($_REQUEST['q'])) ? mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES))) : "";

	$aColumns = array('firstname', 'lastname', 'user_name', 'user_email');
	$sTable = "users";
	$sWhere = "";

	if ($q != "") {
		$sWhere = "WHERE (";
		for ($i = 0; $i < count($aColumns); $i++) {
			$sWhere .= $aColumns[$i] . " LIKE '%" . $q . "%' OR ";
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';
	}

	$sWhere .= " ORDER BY user_id ASC"; // Ordenar por ID para ver admin primero

	include 'pagination.php';

	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	$per_page = 10;
	$adjacents = 4;
	$offset = ($page - 1) * $per_page;

	$count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable $sWhere");
	$row = mysqli_fetch_array($count_query);
	$numrows = $row['numrows'];
	$total_pages = ceil($numrows / $per_page);
	$reload = './usuarios.php';

	$sql = "SELECT * FROM $sTable $sWhere LIMIT $offset,$per_page";
	$query = mysqli_query($con, $sql);

	if ($numrows > 0) {
		?>
		<div class="table-responsive">
			<table class="table table-hover align-middle">
				<thead style="background-color: #f8f9fa;">
					<tr>
						<th style="padding:15px; color:#777;">ID</th>
						<th style="padding:15px; color:#777;">Nombre Completo</th>
						<th style="padding:15px; color:#777;">Usuario (Login)</th>
						<th style="padding:15px; color:#777;">Correo Electrónico</th>
						<th style="padding:15px; color:#777;">Fecha Alta</th>
						<th class='text-right' style="padding:15px; color:#777;">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while ($row = mysqli_fetch_array($query)) {
						$user_id = $row['user_id'];
						$fullname = $row['firstname'] . " " . $row['lastname'];
						$user_name = $row['user_name'];
						$user_email = $row['user_email'];
						$date_added = date('d/m/Y', strtotime($row['date_added']));

						// Distinción visual para el Admin
						$row_style = "";
						$badge_admin = "";
						if ($user_id == 1) {
							$badge_admin = ' <span class="label label-primary" style="font-size:10px;">ADMIN</span>';
							$row_style = "background-color: #fdfdfd;";
						}
						?>

						<input type="hidden" value="<?php echo $row['firstname']; ?>" id="nombres<?php echo $user_id; ?>">
						<input type="hidden" value="<?php echo $row['lastname']; ?>" id="apellidos<?php echo $user_id; ?>">
						<input type="hidden" value="<?php echo $user_name; ?>" id="usuario<?php echo $user_id; ?>">
						<input type="hidden" value="<?php echo $user_email; ?>" id="email<?php echo $user_id; ?>">

						<tr style="border-bottom: 1px solid #eee; <?php echo $row_style; ?>">
							<td style="color:#999;"><?php echo $user_id; ?></td>
							<td style="font-weight: 600; color: #34495e;">
								<i class="glyphicon glyphicon-user text-muted"></i> <?php echo $fullname . $badge_admin; ?>
							</td>
							<td><?php echo $user_name; ?></td>
							<td><a href="mailto:<?php echo $user_email; ?>" class="text-muted"><?php echo $user_email; ?></a></td>
							<td><small class="text-muted"><?php echo $date_added; ?></small></td>

							<td class='text-right'>
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-circle btn-sm" title='Editar datos'
										onclick="obtener_datos('<?php echo $user_id; ?>');" data-toggle="modal"
										data-target="#myModal2">
										<i class="glyphicon glyphicon-edit text-primary"></i>
									</button>

									<button type="button" class="btn btn-default btn-circle btn-sm" title='Cambiar contraseña'
										onclick="get_user_id('<?php echo $user_id; ?>');" data-toggle="modal"
										data-target="#myModal3">
										<i class="glyphicon glyphicon-cog text-warning"></i>
									</button>

									<?php if ($user_id != 1) { ?>
										<button type="button" class="btn btn-default btn-circle btn-sm" title='Borrar usuario'
											onclick="eliminar('<?php echo $user_id; ?>')">
											<i class="glyphicon glyphicon-trash text-danger"></i>
										</button>
									<?php } ?>
								</div>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan=6 class="text-center" style="padding:15px;">
							<?php echo paginate($reload, $page, $total_pages, $adjacents); ?>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>

		<style>
			.btn-circle {
				border-radius: 50%;
				width: 32px;
				height: 32px;
				padding: 0;
				line-height: 30px;
				border: 1px solid #e0e0e0;
				background: white;
				margin-left: 3px;
				transition: all 0.2s;
			}

			.btn-circle:hover {
				background-color: #f1f1f1;
				box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
			}
		</style>
		<?php
	} else {
		?>
		<div class="alert alert-warning text-center" style="margin: 20px;">
			<i class="glyphicon glyphicon-info-sign" style="font-size:24px;"></i><br>
			<strong>No se encontraron usuarios.</strong><br>
			Verifica tu búsqueda o añade uno nuevo.
		</div>
		<?php
	}
}
?>