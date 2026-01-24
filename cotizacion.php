<?php
session_start();
if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
  header("location: login.php");
  exit;
}

/* Connect To Database*/
require_once("config/db.php");
require_once("config/conexion.php");

$active_facturas = "";
$active_productos = "";
$active_cotizacion = "active";
$active_clientes = "";
$active_usuarios = "";
$title = "Control de Tasa | VENPRO";

// OBTENER LA TASA ACTUAL
$sql_last = mysqli_query($con, "SELECT precio FROM cotizacion ORDER BY id_coti DESC LIMIT 1");
$row_last = mysqli_fetch_array($sql_last);
$precio_actual_header = isset($row_last['precio']) ? $row_last['precio'] : 0.00;
?>

<?php if ($_SESSION['user_name'] == 'baraka') { ?>
  <!DOCTYPE html>
  <html lang="es">

  <head>
    <?php include("head.php"); ?>

    <style>
      /* ESTILOS LIMPIOS Y MODERNOS */
      body {
        background-color: #f5f5f5;
        /* Fondo suave para resaltar los paneles */
      }

      /* Tarjeta de Tasa (KPI) */
      .rate-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        text-align: center;
        padding: 30px;
        border-top: 5px solid #00a65a;
        /* Linea verde arriba */
        margin-bottom: 30px;
      }

      .rate-title {
        color: #777;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 14px;
        margin-bottom: 10px;
      }

      .rate-value {
        font-size: 64px;
        font-weight: 700;
        color: #00a65a;
        margin: 0;
        line-height: 1;
      }

      .rate-currency {
        font-size: 24px;
        color: #999;
        vertical-align: top;
        margin-right: 5px;
      }

      .btn-update {
        margin-top: 20px;
        border-radius: 50px;
        padding: 10px 30px;
        font-weight: bold;
        text-transform: uppercase;
        box-shadow: 0 4px 6px rgba(0, 166, 90, 0.3);
        transition: all 0.3s;
      }

      .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 166, 90, 0.4);
      }

      /* Panel de Productos */
      .content-panel {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        padding: 20px;
        border: 1px solid #e3e3e3;
      }

      .panel-header-custom {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      .panel-title-custom {
        font-size: 18px;
        font-weight: 600;
        color: #444;
        margin: 0;
      }

      /* Input de búsqueda estilizado */
      .search-input {
        border-radius: 20px !important;
        border: 1px solid #ddd;
        padding-left: 20px;
      }

      .search-btn {
        border-radius: 20px !important;
        margin-left: -5px;
      }
    </style>
  </head>

  <body>
    <?php include("navbar.php"); ?>

    <div class="container">

      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <div class="rate-card">
            <div class="rate-title">Tasa de Cambio</div>
            <div class="rate-value">
              <span class="rate-currency">Bs.</span><span
                id="precio_actual_display"><?php echo number_format($precio_actual_header, 2); ?></span>
            </div>

            <button type="button" class="btn btn-success btn-update" data-toggle="modal" data-target="#myModal2">
              <i class="glyphicon glyphicon-refresh"></i> Actualizar Tasa
            </button>

            <p style="margin-top: 15px; color: #999; font-size: 12px;">
              <i class="glyphicon glyphicon-info-sign"></i> Al actualizar, todos los productos se recalcularán
              automáticamente.
            </p>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="content-panel">

            <div class="row" style="margin-bottom: 20px;">
              <div class="col-md-6">
                <h3 class="panel-title-custom">
                  <i class="glyphicon glyphicon-tags text-blue"></i> Precios en Sistema (Vista Previa)
                </h3>
              </div>
              <div class="col-md-6">
                <form class="form-horizontal" role="form" id="datos_cotizacion">
                  <div class="input-group">
                    <input type="text" class="form-control search-input" id="q"
                      placeholder="Buscar producto por nombre o código..." onkeyup='load(1);'>
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default search-btn" onclick='load(1);'>
                        <i class="glyphicon glyphicon-search"></i>
                      </button>
                    </span>
                  </div>
                </form>
              </div>
            </div>

            <div id="loader" class="text-center"></div>
            <div id="resultados"></div>
            <div class='outer_div'></div>

          </div>
        </div>
      </div>

    </div>
    <hr>
    <?php include("footer.php"); ?>

    <?php include("modal/editar_cotizacion.php"); ?>

    <script type="text/javascript" src="js/cotizacion.js"></script>
  </body>

  </html>
<?php } ?>