<?php
// ------------------------------------------------------------

// Autor: Felipe Gutierrez Alfaro
// Fecha de creación: 2024-11-01
// Versión: 1.0
// 
// Copyright (c) 2024 Felipe Gutierrez Alfaro. Todos los derechos reservados.
// 
// Este código fuente está sujeto a derechos de autor y ha sido facilitado 
// de manera gratuita y de por vida a la Escuela Juanita Zuñiga Fuentes CEIA PARRAL.
// 
// Restricciones y condiciones:
// - Este código NO puede ser vendido, ni redistribuido.
// - Solo la Escuela Juanita Zuñiga Fuentes tiene derecho a usar este código sin costo y modificarlo a su gusto.
// - No se otorga ninguna garantía implícita o explícita. Uso bajo su propia responsabilidad.
// - El establecimiento puede utilizar imágenes a su gusto y asignar el nombre que estime conveniente al sistema.
// 
// Contacto: 
// Email: felipe.gutierrez.alfaro@gmail.com
?>


<!-- REGISTRAR ATRASO -->

<div class="modal fade" id="retiro_<?php echo $row['run']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
   	  <div class="modal-content">
            <div class="modal-header">
                <center><h4 class="modal-title" id="myModalLabel">RETIRO</h4></center>
            </div>
            <div class="modal-body">
				<div class="container-fluid">
					<form method="POST" action="in.retiroguardar.php?id=<?php echo $row['run']; ?>">
						<div class="row form-group">
							<div class="col-sm-2">
							<label class="control-label" style="position:relative; top:7px;">Run:</label>
							</div>
							<div class="col-sm-10">
							<input type="text" class="form-control" readonly="readonly" name="run" value="<?php echo $row['run']; ?>"> 
							</div>
						</div>
                
						
						<div class="row form-group">
							<div class="col-sm-2">
							<label class="control-label" style="position:relative; top:7px;">ID:</label>
							</div>
							<div class="col-sm-10">
							<input type="text" class="form-control" readonly="readonly" name="id" value="<?php echo $row['idalum']; ?>">
							</div>
						</div>




						<div class="row form-group">
							<div class="col-sm-2">
							<label class="control-label" style="position:relative; top:7px;">Nombre:</label>
							</div>
							<div class="col-sm-10">
							<input type="text" class="form-control" readonly="readonly" name="nom" value="<?php echo $row['nombres']; ?>">
							</div>
						</div>
					
						<div class="row form-group">
							<div class="col-sm-2">
							<label class="control-label" style="position:relative; top:7px;">Motivo:</label>
							</div>
							<div class="col-sm-10">
							<textarea rows="5" class="form-control" name="jretiro" value="" step="any" placeholder="Ingrese justificación"> </textarea>
							<p> </p>

							</div>
						</div>
					

						<div class="row form-group">
							<div class="col-sm-2">
							<label class="control-label" placeholder="Ingresar Fecha" style="position:relative; top:7px;">Fecha Retiro:</label>
							</div>
							<div class="col-sm-10">
                   			<input type="date" class="form-control" name="fretiro" value="">
							</div>
						</div>	

						<div class="row form-group">
							<div class="col-sm-2">
							<label class="control-label" style="position:relative; top:7px;">Fecha Actual:</label>
							</div>
							<div class="col-sm-10">
                   			<input type="date" class="form-control" name="factual" readonly="readonly" value="<?php ini_set('date.timezone' , 'America/Santiago');
								$fecha=date('Y-m-d', time());
								echo $fecha; ?>">
							</div>
						</div>	

						<div class="row form-group">
							<div class="col-sm-2">
							<label class="control-label" style="position:relative; top:7px;">Hora:</label>
							</div>	
							<div class="col-sm-10">
                   			<input type="time" class="form-control" name="hretiro" readonly="readonly" value="<?php ini_set('date.timezone' , 'America/Santiago');
							$hora=date('H:i:s', time());
							echo $hora; ?>">
							</div>
</div>
					
				</div> 
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
				<button type="submit" name="retiro" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> guardar</a>
<!--			<button type="button" class="btn btn-info" data-dismiss="modal" onClick="window.print();"><span class="glyphicon glyphicon-print"></span> IMPRIMIR</button> -->

					</form>
        	</div>
    	</div>
    </div>
</div>

