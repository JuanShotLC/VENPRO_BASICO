<?php

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

	$id_producto=$_REQUEST['id_producto'];

	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $aColumns = array('fecha', 'cantidad');//Columnas de busqueda
		 $sTable = "f.fecha ,u.user_name, f.nota ,f.cantidad from historial f,users u";
		 $aTable = "historial";
		 $aWhere = " where id_producto='$id_producto'";
		 $sWhere = "  where f.id_producto='$id_producto' and f.user_id=u.user_id";
		if ( $_GET['q'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		$sWhere.=" order by f.fecha desc";
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $aTable  $aWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './producto.php?id=$id_producto';
		//main query to fetch the data
		$sql="SELECT   $sTable $sWhere LIMIT $offset,$per_page";
		// echo "<br>";
		// echo $prueba="SELECT count(*) AS numrows FROM $aTable  $aWhere";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			
			?>
			<div class="table-responsive">
			  <table class="table">
			  	<tr>
							<th class='text-center success' colspan=6 >HISTORIAL DE INVENTARIO</th>
						</tr>
				<tr  class="success">
					<th>Fecha</th>
					<th>Hora</th>
					<th>Usuario</th>
					<th>Descripción</th>
					<th>Total</th>

					
				</tr>
				<?php
				while ($row=mysqli_fetch_array($query)){
						$fecha=date('d/m/Y', strtotime($row['fecha']));
						$hora=date('H:i:s', strtotime($row['fecha']));
						$user=$row['user_name'];
						$descrip=$row['nota'];
						$cantidad= $row['cantidad'];
						
					?>
					
	
				
					<tr>
						<td><?php echo $fecha; ?></td>
						<td><?php echo $hora; ?></td>
						<td ><?php echo $user; ?></td>
						<td ><?php echo $descrip; ?></td>
						<td><?php echo $cantidad;?></td>
						
					
						
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=9><span class="pull-right">
					<?php
					 echo paginate_histo($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>