

<?php
    if (isset($con))
    {
  ?>
  <!-- Modal -->
  <div class="modal fade" id="reporteNota" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel"><!-- <i class='glyphicon glyphicon-edit'></i> --> Reporte</h4>
      </div>
      <div class="modal-body">
      <form class="form-horizontal" method="GET" action="reportes/reporte_notas.php" id="guardar_cliente" name="guardar_cliente">
      <div id="resultados_ajax"></div>

     
        <div class="form-group">
        <label for="inicio" class="col-sm-3 control-label">Fecha Inicio:</label>
        <div class="col-sm-8">
          <input type="date" class="form-control" id="inicio" name="inicio" required>
        </div>
        </div>

        <div class="form-group">
        <label for="fin" class="col-sm-3 control-label">Fecha Fin:</label>
        <div class="col-sm-8">
          <input type="date" class="form-control" id="fin" name="fin" required>
        </div>
        </div>     
       
       
      
      </div>
      <div class="modal-footer">
      <button type="button"  class="btn btn-sm btn-danger pull-left" data-dismiss="modal" data-toggle="tooltip" title="Cerrar ventana">Cerrar</button>
      
      <button type="submit"  id="guardar_datos" class="btn btn-sm btn-success pull-right"  target="_blank" data-toggle="tooltip" title="Generar Pdf">Generar</button>
      </div>
      </form>
    </div>
    </div>
  </div>
  <?php
    }
  ?>