<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Centro de Mantenimiento y Utilidades + LOGS
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
$active_perfil = "";
$title = "Mantenimiento | Sistema";

// Datos de Base de Datos
$sql_size = "SELECT table_schema AS 'db_name', SUM(data_length + index_length) / 1024 / 1024 AS 'db_size' 
             FROM information_schema.TABLES WHERE table_schema = '" . DB_NAME . "' GROUP BY table_schema";
$query_size = mysqli_query($con, $sql_size);
$row_size = mysqli_fetch_array($query_size);
$db_size_mb = isset($row_size['db_size']) ? number_format($row_size['db_size'], 2) : "0.00";

$sql_tables = "SELECT COUNT(*) as total FROM information_schema.TABLES WHERE table_schema = '" . DB_NAME . "'";
$query_tables = mysqli_query($con, $sql_tables);
$row_tables = mysqli_fetch_array($query_tables);
$total_tables = isset($row_tables['total']) ? $row_tables['total'] : 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php include("head.php"); ?>
  <style>
    body {
      background-color: #f4f6f9;
    }

    .main-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 25px;
      align-items: center;
    }

    .card-util {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      margin-bottom: 25px;
      height: 100%;
      transition: transform 0.2s;
      border: 1px solid #eee;
    }

    .card-util:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .icon-box {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin-bottom: 15px;
    }

    .bg-backup {
      background-color: #e3f2fd;
      color: #1976d2;
    }

    .bg-optimize {
      background-color: #e8f5e9;
      color: #2e7d32;
    }

    .bg-info-db {
      background-color: #f3e5f5;
      color: #7b1fa2;
    }

    .bg-log {
      background-color: #fff3e0;
      color: #f57c00;
    }

    .card-title {
      font-weight: 700;
      font-size: 18px;
      color: #333;
      margin-bottom: 10px;
    }

    .card-desc {
      color: #666;
      font-size: 14px;
      margin-bottom: 20px;
      min-height: 40px;
    }

    .btn-action {
      width: 100%;
      border-radius: 30px;
      font-weight: 600;
      padding: 10px;
    }

    /* Estilos Tabla Logs */
    .log-container {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .table-log thead th {
      border-top: none;
      background: #fafafa;
      color: #777;
    }

    .user-avatar-xs {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background: #eee;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-right: 10px;
      color: #888;
    }
  </style>
</head>

<body>
  <?php include("navbar.php"); ?>

  <div class="container">

    <div class="main-header">
      <div>
        <h1 style="margin:0; font-weight:700;"><i class="glyphicon glyphicon-wrench text-primary"></i> Mantenimiento
        </h1>
        <p class="text-muted">Herramientas del sistema</p>
      </div>
      <a href="perfil.php" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> Volver</a>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="card-util">
          <div class="icon-box bg-info-db"><i class="glyphicon glyphicon-hdd"></i></div>
          <div class="card-title">Estado del Sistema</div>
          <ul class="list-group" style="margin-bottom: 0;">
            <li class="list-group-item">Base de Datos: <span class="badge"><?php echo DB_NAME; ?></span></li>
            <li class="list-group-item">Espacio Usado: <span class="badge"><?php echo $db_size_mb; ?> MB</span></li>
            <li class="list-group-item">Total Tablas: <span class="badge"><?php echo $total_tables; ?></span></li>
          </ul>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card-util">
          <div class="icon-box bg-backup"><i class="glyphicon glyphicon-cloud-download"></i></div>
          <div class="card-title">Respaldo de Datos</div>
          <p class="card-desc">Descarga una copia completa (.sql) para emergencias.</p>
          <button onclick="iniciarBackup()" class="btn btn-primary btn-action" id="btn-backup">
            <i class="glyphicon glyphicon-download-alt"></i> Descargar Copia
          </button>
          <p id="backup-msg" class="text-center text-success" style="display:none; margin-top:10px;">¡Generando...</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card-util">
          <div class="icon-box bg-optimize"><i class="glyphicon glyphicon-flash"></i></div>
          <div class="card-title">Optimización</div>
          <p class="card-desc">Limpia residuos y mejora la velocidad de las consultas.</p>
          <button onclick="optimizarDB()" class="btn btn-success btn-action" id="btn-optimize">
            <i class="glyphicon glyphicon-refresh"></i> Optimizar Tablas
          </button>
          <div id="optimize-result" style="margin-top:10px;"></div>
        </div>
      </div>
    </div>

    <div class="row" style="margin-top: 20px;">
      <div class="col-md-12">
        <div class="log-container">
          <h4 style="margin-top:0; margin-bottom: 20px; font-weight:700;">
            <i class="glyphicon glyphicon-eye-open text-warning"></i> Bitácora de Accesos Recientes
          </h4>

          <div class="table-responsive">
            <table class="table table-hover table-log">
              <thead>
                <tr>
                  <th>Usuario</th>
                  <th>Fecha y Hora</th>
                  <th>Dirección IP</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Verificar si existe la tabla antes de consultar
                $check_table = mysqli_query($con, "SHOW TABLES LIKE 'user_log'");
                if (mysqli_num_rows($check_table) > 0) {
                  $sql_log = "SELECT l.*, u.user_name, u.firstname, u.lastname 
                                                FROM user_log l 
                                                LEFT JOIN users u ON l.user_id = u.user_id 
                                                ORDER BY l.id DESC LIMIT 10";
                  $query_log = mysqli_query($con, $sql_log);

                  if (mysqli_num_rows($query_log) > 0) {
                    while ($r = mysqli_fetch_array($query_log)) {
                      $fecha = date("d/m/Y H:i:s", strtotime($r['fecha_login']));
                      ?>
                      <tr>
                        <td>
                          <div class="user-avatar-xs"><i class="glyphicon glyphicon-user"></i></div>
                          <strong><?php echo $r['firstname'] . ' ' . $r['lastname']; ?></strong>
                          <small class="text-muted">(<?php echo $r['user_name']; ?>)</small>
                        </td>
                        <td><?php echo $fecha; ?></td>
                        <td><code><?php echo $r['ip_address']; ?></code></td>
                        <td><span class="label label-success">Exitoso</span></td>
                      </tr>
                      <?php
                    }
                  } else {
                    echo "<tr><td colspan='4' class='text-center'>No hay registros de acceso aún.</td></tr>";
                  }
                } else {
                  echo "<tr><td colspan='4' class='text-center text-danger'>
                                        <i class='glyphicon glyphicon-alert'></i> Falta crear la tabla 'user_log' en la base de datos.
                                        </td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>

  <?php include("footer.php"); ?>

  <script>
    function iniciarBackup() {
      var btn = $('#btn-backup');
      var msg = $('#backup-msg');
      btn.attr('disabled', true);
      msg.fadeIn();

      setTimeout(function () {
        window.location.href = 'crea_respaldo.php';
        setTimeout(function () {
          btn.attr('disabled', false);
          msg.fadeOut();
        }, 3000);
      }, 1000);
    }

    function optimizarDB() {
      var btn = $('#btn-optimize');
      btn.attr('disabled', true);
      btn.html('<i class="fa fa-spinner fa-spin"></i> ...');

      $.ajax({
        url: "ajax/optimizar_db.php",
        type: "POST",
        success: function (data) {
          btn.attr('disabled', false);
          btn.html('<i class="glyphicon glyphicon-refresh"></i> Optimizar Tablas');
          swal("¡Optimización Completa!", data, "success");
        },
        error: function () {
          btn.attr('disabled', false);
          btn.html('<i class="glyphicon glyphicon-refresh"></i> Reintentar');
        }
      });
    }
  </script>
</body>

</html>