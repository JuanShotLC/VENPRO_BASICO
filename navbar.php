<?php
if (isset($title)) {
  ?>
  <nav class="navbar navbar-default navbar-fixed-top navbar-modern">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
          data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Navegación</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="cotizacion.php">
          <i class="glyphicon glyphicon-barcode text-primary"></i>
          <strong>VENPRO</strong>
        </a>
      </div>

      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">

          <li class="<?php if (isset($active_facturas)) {
            echo $active_facturas;
          } ?>">
            <a href="/#/factura_venta">
              <i class='glyphicon glyphicon-shopping-cart text-info'></i> Ventas (POS)
            </a>
          </li>

          <li class="<?php if (isset($active_cotizacion)) {
            echo $active_cotizacion;
          } ?>">
            <a href="cotizacion.php">
              <i class='glyphicon glyphicon-list-alt text-success'></i> Cotización
            </a>
          </li>

          <li class="<?php if (isset($active_productos)) {
            echo $active_productos;
          } ?>">
            <a href="productos.php">
              <i class='glyphicon glyphicon-tags text-warning'></i> Productos
            </a>
          </li>

          <li class="<?php if (isset($active_clientes)) {
            echo $active_clientes;
          } ?>">
            <a href="clientes.php">
              <i class='glyphicon glyphicon-user text-primary'></i> Clientes
            </a>
          </li>

          <li class="<?php if (isset($active_usuarios)) {
            echo $active_usuarios;
          } ?>">
            <a href="usuarios.php">
              <i class='glyphicon glyphicon-lock text-danger'></i> Usuarios
            </a>
          </li>

          <li class="<?php if (isset($active_perfil)) {
            echo $active_perfil;
          } ?>">
            <a href="perfil.php">
              <i class='glyphicon glyphicon-cog text-muted'></i> Config
            </a>
          </li>
          <li class="<?php if (isset($active_backup)) {
            echo $active_backup;
          } ?>">
            <a href="util_backup.php">
              <i class='glyphicon glyphicon-cog text-muted'></i> Mantenimiento
            </a>
          </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li class="hidden-xs">
            <a href="#" target='_blank' title="Soporte Técnico">
              <i class='glyphicon glyphicon-question-sign'></i>
            </a>
          </li>

          <li class="dropdown">
            <a href="#" class="dropdown-toggle user-menu-btn" data-toggle="dropdown" role="button" aria-haspopup="true"
              aria-expanded="false">
              <i class='glyphicon glyphicon-user'></i> Hola, <?php echo $_SESSION['user_name']; ?> <span
                class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <a href="login.php?logout">
                  <i class='glyphicon glyphicon-off text-danger'></i> Cerrar Sesión
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <?php
}
?>