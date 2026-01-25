<?php
if (isset($title)) {
  ?>
  <nav class="navbar navbar-default navbar-fixed-top navbar-modern">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
          data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">
          <i class="glyphicon glyphicon-qrcode text-primary"></i> VENPRO
        </a>
      </div>

      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li class="<?php echo $active_cotizacion; ?>">
            <a href="cotizacion.php"><i class='glyphicon glyphicon-list-alt text-info'></i> Precios y Stock</a>
          </li>
          <li class="<?php echo $active_facturas; ?>">
            <a href="/#/factura_venta"><i class='glyphicon glyphicon-list-alt text-success'></i> Facturas</a>
          </li>
          <li class="<?php echo $active_productos; ?>">
            <a href="productos.php"><i class='glyphicon glyphicon-barcode text-warning'></i> Productos</a>
          </li>
          <li class="<?php echo $active_clientes; ?>">
            <a href="clientes.php"><i class='glyphicon glyphicon-user text-danger'></i> Clientes</a>
          </li>
          <li class="<?php echo $active_usuarios; ?>">
            <a href="usuarios.php"><i class='glyphicon glyphicon-lock text-muted'></i> Usuarios</a>
          </li>
          <li class="<?php if (isset($active_perfil)) {
            echo $active_perfil;
          } ?>">
            <a href="perfil.php"><i class='glyphicon glyphicon-cog'></i> Configuración</a>
          </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li><a href="#" target='_blank'><i class='glyphicon glyphicon-question-sign'></i> Soporte</a></li>

          <li class="dropdown">
            <a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown" role="button" aria-haspopup="true"
              aria-expanded="false">
              <i class="glyphicon glyphicon-user"></i> Hola, Administrador <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li><a href="login.php?logout"><i class='glyphicon glyphicon-off text-danger'></i> Salir del sistema</a>
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