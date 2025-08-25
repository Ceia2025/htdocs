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
<!-- REGISTRAR ANOTACION POSITIVA -->
<div class="modal fade" id="positiva_<?php echo $row['run']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1650px!important;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">ANOTACIÓN POSITIVA</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" action="in.anotapositiva.php?id=<?php echo $row['run']; ?>">
					<div class="row form-group">
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Run:</label>
							</div>
							<div class="col-sm-2">
							<input type="text" class="form-control" readonly="readonly" name="run" value="<?php echo $row['run']; ?>"> 
							</div>					
<!--
						<div class="row form-group">
							<div class="col-sm-2">
							<label class="control-label" style="position:relative; top:7px;">ID:</label>
							</div>
							<div class="col-sm-10">
								-->
							<input type="hidden" class="form-control" readonly="readonly" name="id" value="<?php echo $row['idalum']; ?>">
							<!--</div>
						</div>-->
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Nombre:</label>
							</div>
							<div class="col-sm-3">
                            <input type="text" class="form-control" readonly="readonly" name="nom" value="<?php echo htmlspecialchars($row['nombres'] . ' ' . $row['apaterno'] . ' ' . $row['amaterno']); ?>">							
							</div>
													
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Curso:</label>
							</div>
							<div class="col-sm-3">
                            <input type="text" class="form-control" readonly="readonly" name="descgrado" value="<?php echo $row['descgrado']; ?>">
							</div>
							<p></p>



							<div class="row form-group">
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Fecha:</label>
							</div>
							<div class="col-sm-2">
                   			<input type="date" class="form-control" name="fanota" value="<?php ini_set('date.timezone' , 'America/Santiago');
								$fecha=date('Y-m-d', time());
								echo $fecha; ?>">
							</div>
							
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Hora:</label>
							</div>	
							<div class="col-sm-2">
                   			<input type="time" class="form-control"  name="hanota" value="<?php ini_set('date.timezone' , 'America/Santiago');
							$hora=date('H:i:s', time());
							echo $hora; ?>">
							</div>
							</div>
							<p></p>
							<div class="row form-group">
                            <div class="col-sm-10">
                                <textarea rows="9" class="form-control" name="anota" value="" step="any" placeholder="Ingrese anotación"></textarea>
                                
                                <input type="hidden" class="form-control" name="tipoanota" value="P" step="any" readonly="readonly">
                            </div>
                        </div>
						</div>
						<p></p>         
            </div>
            <div class="modal-footer">
			<button type="submit" name="positiva" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> GUARDAR</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>CANCELAR</button>
               
                </form>
            </div>
			</div>
        </div>
</div>
</div>



<!-- REGISTRAR ANOTACION OTROS REGISTROS -->

<div class="modal fade" id="otros_registros_<?php echo $row['run']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1650px!important;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">OTROS REGISTROS</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" action="in.anotaotrosregistros.php?id=<?php echo $row['run']; ?>">
					<div class="row form-group">
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Run:</label>
							</div>
							<div class="col-sm-2">
							<input type="text" class="form-control" readonly="readonly" name="run" value="<?php echo $row['run']; ?>"> 
							</div>					
<!--
						<div class="row form-group">
							<div class="col-sm-2">
							<label class="control-label" style="position:relative; top:7px;">ID:</label>
							</div>
							<div class="col-sm-10">
								-->
							<input type="hidden" class="form-control" readonly="readonly" name="id" value="<?php echo $row['idalum']; ?>">
							<!--</div>
						</div>-->
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Nombre:</label>
							</div>
							<div class="col-sm-3">
                            <input type="text" class="form-control" readonly="readonly" name="nom" value="<?php echo htmlspecialchars($row['nombres'] . ' ' . $row['apaterno'] . ' ' . $row['amaterno']); ?>">							
							</div>
													
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Curso:</label>
							</div>
							<div class="col-sm-3">
                            <input type="text" class="form-control" readonly="readonly" name="descgrado" value="<?php echo $row['descgrado']; ?>">
							</div>
							<p></p>


							<div class="row form-group">
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Fecha:</label>
							</div>
							<div class="col-sm-2">
                   			<input type="date" class="form-control" name="fanota" value="<?php ini_set('date.timezone' , 'America/Santiago');
								$fecha=date('Y-m-d', time());
								echo $fecha; ?>">
							</div>
							
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Hora:</label>
							</div>	
							<div class="col-sm-2">
                   			<input type="time" class="form-control"  name="hanota" value="<?php ini_set('date.timezone' , 'America/Santiago');
							$hora=date('H:i:s', time());
							echo $hora; ?>">
							</div>
							</div>
							<p></p>
							<div class="row form-group">
                            <div class="col-sm-10">
                                <textarea rows="8" class="form-control" name="anota" value="" step="any" placeholder="Ingrese anotación"></textarea>
                                
                                <input type="hidden" class="form-control" name="tipoanota" value="O" step="any" readonly="readonly">
                            </div>
                        </div>
						</div>
						<p></p>         
            </div>
            <div class="modal-footer">
			<button type="submit" name="otroregistro" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> GUARDAR</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>CANCELAR</button>               
                </form>
            </div>
			</div>
        </div>
</div>
</div>

<!-- REGISTRAR ANOTACION NEGATIVA -->
<div class="modal fade" id="negativa_<?php echo $row['run']; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="max-width: 1650px!important;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">ANOTACIÓN NEGATIVA</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="col-md-12">
                        <form method="POST" action="in.anotanegativa.php?id=<?php echo $row['run']; ?>">
						<div class="row form-group">
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Run:</label>
							</div>
							<div class="col-sm-2">
							<input type="text" class="form-control" readonly="readonly" name="run" value="<?php echo $row['run']; ?>"> 
							</div>					
<!--
						<div class="row form-group">
							<div class="col-sm-2">
							<label class="control-label" style="position:relative; top:7px;">ID:</label>
							</div>
							<div class="col-sm-10">
								-->
							<input type="hidden" class="form-control" readonly="readonly" name="id" value="<?php echo $row['idalum']; ?>">
							<!--</div>
						</div>-->
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Nombre:</label>
							</div>
							<div class="col-sm-3">
                            <input type="text" class="form-control" readonly="readonly" name="nom" value="<?php echo htmlspecialchars($row['nombres'] . ' ' . $row['apaterno'] . ' ' . $row['amaterno']); ?>">							
							</div>
													
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Curso:</label>
							</div>
							<div class="col-sm-3">
                            <input type="text" class="form-control" readonly="readonly" name="descgrado" value="<?php echo $row['descgrado']; ?>">
							</div>
							<p></p>

							<div class="row form-group">
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Fecha:</label>
							</div>
							<div class="col-sm-2">
                   			<input type="date" class="form-control" name="fanota" value="<?php ini_set('date.timezone' , 'America/Santiago');
								$fecha=date('Y-m-d', time());
								echo $fecha; ?>">
							</div>
								
							<div class="col-sm-1">
							<label class="control-label" style="position:relative; top:7px;">Hora:</label>
							</div>	
							<div class="col-sm-2">
                   			<input type="time" class="form-control"  name="hanota" value="<?php ini_set('date.timezone' , 'America/Santiago');
							$hora=date('H:i:s', time());
							echo $hora; ?>">
							</div>

							<div class="col-sm-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="categoria" id="leve" checked value="L">
                                        <label class="form-check-label btn btn-secondary" for="leve">Leve</label>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="categoria" id="grave" value="G">
                                        <label class="form-check-label btn btn-warning" for="grave">Grave</label>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="categoria" id="gravisima" value="GMA">
                                        <label class="form-check-label btn btn-danger" for="gravisima">Gravisima</label>
                                    </div>
                                </div>
							<p> </p>	
						<div class="row form-group">
							<div class="col-sm-2">
							<label class="control-label" style="position:relative; top:7px;">Registro:</label>
							</div>
							<div class="col-sm-9">
								<p></p>
                                <div class="row form-group">
                                    <div class="col-sm-12">
                                        <textarea rows="9" class="form-control" name="anota" value="" step="any" placeholder="Ingrese anotación"></textarea>
                                        <p></p>
                                        <input type="hidden" class="form-control text-danger" name="tipoanota" value="N" step="any" readonly="readonly">
                                    </div>
                                </div>
                                <p></p>
                            </div>
							<p></p>
                            
                                <div class="modal-footer">
                                    <button type="submit" name="negativa" class="btn btn-success"><span class="glyphicon glyphicon-check"></span>GUARDAR</button>
                                    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>CANCELAR</button>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
		</div>
		</div>