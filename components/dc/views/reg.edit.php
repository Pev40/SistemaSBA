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
			<input type="text" class="form-control"  name="fecreg" style="width:300px" required>
		</div>
	</div>
	<div name="paciente"><?php $f->response->view('mg/enti.mini'); ?></div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Lugar de Procedencia</label>
		<div class="col-sm-8">
			<ddiv class="row">
				<div class="col-md-4">
					<select class="form-control" name="procede_depa" type = "text" required>
					</select>
				</div>
			<div class="col-md-4">
				<label class="col-sm-4 control-label"></label>
				<div class="col-sm-8">
					<select class="form-control" name="procede_prov" type = "text" required>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<label class="col-sm-4 control-label"></label>
				<div class="col-sm-8">
					<select class="form-control" name="procede_dist" type = "text" required>
					</select>
				</div>
			</div>
			</ddiv>
		</div>
	</div>
    <div name="empresa"><?php $f->response->view('mg/enti.mini'); ?></div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Edad: </label>
			<div class="col-sm-8 input-group">
				<input type="text" class="form-control" name="edad"   style="width:300px" required >
			</div>
	</div>
    <div class="form-group">
		<label class="col-sm-4 control-label">Pais: </label>
			<div class="col-sm-8 input-group">
				<input type="text" class="form-control" name="pais"   style="width:300px" required >
			</div>
	</div>
    <div class="form-group">
		<label class="col-sm-4 control-label">Alergias: </label>
			<div class="col-sm-8 input-group">
				<input type="text" class="form-control" name="aler"   style="width:300px" required >
			</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Sexo</label>
			<div class="col-sm-8">
				<select class="form-control" name="sexo" type = "text" style="width:300px" required>
					<option value="0">Femenino</option>
					<option value="1">Masculino</option>
				</select>
			</div>
	</div>
	<div class="form-horizontal" role="form" >
		<div class="form-group">
			<label class="col-sm-4 control-label">Motivo de la Consulta:</label>
				<div class="col-sm-8">
					<textarea cols="30" rows="3" class="form-control" name="moti"></textarea>
				</div>
		</div>
	</div>
    <div class="form-horizontal" role="form" >
		<div class="form-group">
			<label class="col-sm-4 control-label">Antecedentes:</label>
				<div class="col-sm-8">
					<textarea cols="30" rows="3" class="form-control" name="ante"></textarea>
				</div>
		</div>
	</div>
    <div class="form-horizontal" role="form" >
		<div class="form-group">
			<label class="col-sm-4 control-label">Ananmesis:</label>
				<div class="col-sm-8">
					<textarea cols="30" rows="3" class="form-control" name="anan"></textarea>
				</div>
		</div>
	</div>
    <div class="form-horizontal" role="form" >
		<div class="form-group">
			<label class="col-sm-4 control-label">Evaluacion por teleconsulta:</label>
				<div class="col-sm-8">
					<textarea cols="30" rows="3" class="form-control" name="evte"></textarea>
				</div>
		</div>
	</div>
	<div class="form-horizontal" role="form" >
		<div class="form-group">
			<label class="col-sm-4 control-label">Examenes Auxiliares:</label>
				<div class="col-sm-8">
					<textarea cols="30" rows="3" class="form-control" name="exau"></textarea>
				</div>
		</div>
	</div>
    <div name="gridDiag">
    </div>    
    <div class="form-horizontal" role="form" >
		<div class="form-group">
			<label class="col-sm-4 control-label">Indicaciones:</label>
				<div class="col-sm-8">
					<textarea cols="30" rows="3" class="form-control" name="indi"></textarea>
				</div>
		</div>
	</div>
</form>