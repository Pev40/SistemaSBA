	<?php global $f; ?>
	<form class="form-horizontal" role="form">
	<div class="form-group">
			<label class="col-sm-4 control-label">Historia Clinica: </label>
				<div class="col-sm-8 input-group">
					<input type="text" class="form-control" name="hist" disabled="disabled"  style="width:300px" required >
				</div>
		</div>
		<div class="form-group date" data-provide="datepicker" >
			<label class="col-sm-4 control-label">Fecha de Atencion: </label>
			<div class="col-sm-7.5 input-group">
				<input type="text" class="form-control"  name="fecreg" style="width:300px" disabled="disabled" required>
			</div>
		</div>
		<div name="paciente"><?php $f->response->view('mg/enti.mini'); ?></div>
		<div name="empresa"><?php $f->response->view('mg/enti.mini'); ?></div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Edad: </label>
				<div class="col-sm-8 input-group">
					<input type="text" class="form-control" name="edad" disabled="disabled"  style="width:300px" required >
				</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Sexo</label>
				<div class="col-sm-8">
					<select class="form-control" name="sexo" type = "text" style="width:300px" disabled="disabled" required>
						<option value="0">Femenino</option>
						<option value="1">Masculino</option>
					</select>
				</div>
		</div>
		<div class="form-horizontal" role="form" >
			<div class="form-group">
				<label class="col-sm-4 control-label">Desarrollo de informe:</label>
			</div>
			<div class="col-sm-8">
						<textarea rows="10" class="form-control" name="info" style="width: 100%;"></textarea>

			</div>
		</div>
	</form>