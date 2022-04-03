	<?php
		if (isset($title))
		{
	?>
<nav class="navbar navbar-default ">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">SIFONELC</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
       <!--  <li class="<?php echo $active_facturas;?>"><a href="facturas.php"><i class='glyphicon glyphicon-list-alt'></i> Facturas <span class="sr-only">(current)</span></a></li>

            <li class="<?php if (isset($active_nota_entrega)){echo $active_nota_entrega;}?>"><a href="Nota_de_entrega.php"><i class='glyphicon glyphicon-list-alt'></i> Nota de Entrega</a></li> -->
            
        <!--li class="<?php echo $active_productos;?>"><a href="productos.php"><i class='glyphicon glyphicon-barcode'></i> Productos</a></li-->
		<li class=""><a href="http://localhost/#/factura_venta"><i class='glyphicon glyphicon-shopping-cart'></i> Caja</a></li>
    <li class="<?php echo $active_clientes;?>"><a href="clientes.php"><i class='glyphicon glyphicon-user'></i> Clientes</a></li>
    

<?php if($_SESSION['user_name'] == 'baraka') { ?>

    <li class="<?php if (isset($active_productos)){echo $active_productos;}?>"><a href="inventario.php"><i class='glyphicon glyphicon-barcode'></i> Inventario</a></li>

    <li class="<?php if (isset($active_categoria)){echo $active_categoria;}?>"><a href="categorias.php"><i class='glyphicon glyphicon-tags'></i> Categorías</a></li>

		<li class="<?php echo $active_usuarios;?>"><a href="usuarios.php"><i  class='glyphicon glyphicon-lock'></i> Usuarios</a></li>

    <li class="<?php echo $active_reportes;?>"><a href="reportes.php"><i  class='glyphicon glyphicon-lock'></i> Reportes</a></li>

    <li class="<?php echo $active_cotizacion;?>"><a href="cotizacion.php"><i  class='glyphicon glyphicon-lock'></i> Cotizacion </a></li>

    <li class="<?php echo $active_precio;?>"><a href="actualizar_precios.php"><i  class='glyphicon glyphicon-lock'></i> Actualizar</a></li>

    

		<li class="<?php if(isset($active_perfil)){echo $active_perfil;}?>"><a href="perfil.php"><i  class='glyphicon glyphicon-cog'></i> Configuración</a></li>
<?php }         ?>

       </ul>
      <ul class="nav navbar-nav navbar-right">
        <!--li><a href="#" target='_blank'><i class='glyphicon glyphicon-envelope'></i> Soporte</a></li-->
        <!-- <li>
          <a href="#" target='_blank'></i> <?php echo date("d/m/Y");?></a>
        </li> -->

		<li><a href="login.php?logout"><i class='glyphicon glyphicon-off'></i></a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
	<?php
		}
	?>