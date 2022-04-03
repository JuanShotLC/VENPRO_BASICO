	<?php
		if (isset($con))
		{
	?>
	<!-- Modal -->
	<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Modificar Cotización del dolar</h4>
		  </div>
		  <div class="modal-body">
			<form class="form-horizontal" method="post" id="editar_cotizacion" name="editar_cotizacion">
			<div id="resultados_ajax2"></div>
		   
			
			 
			 <div class="form-group">
				<label for="mod_cotizacion" class="col-sm-4 control-label">Precio del dolar en Bs.</label>
				<div class="col-sm-4">

				  <input type="text" min="0" class="form-control" id="mod_cotizacion" name="mod_cotizacion" placeholder="" required>
				  <input type="hidden" name="mod_id" id="mod_id" value="1">

				</div>
			</div>
			 
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" class="btn btn-primary" id="actualizar_datos">Actualizar datos</button>
		  </div>

		  </form>
		</div>
	  </div>
	</div>
	<?php
		}
	?>