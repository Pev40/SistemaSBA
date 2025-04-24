<?php global $f; ?>
<form class="form-horizontal" role="form">
	<div class="form-group">
		<label class="col-sm-4 control-label">Historia Clinica: </label>
			<div class="col-sm-8 input-group">
				<input type="text" class="form-control" name="hist" disabled="disabled"  style="width:300px" required >
			</div>
	</div>
	<div name="paciente"><?php $f->response->view('mg/enti.mini'); ?></div>
    <div name="empresa"><?php $f->response->view('mg/enti.mini'); ?></div>

    <div class="container">
    <style>
        .custom-input {
            max-width: 150px; /* Puedes ajustar el valor según tu preferencia */
        }
    </style>
        <div class="form-row">
            <div class="col-md-3 mb-3">
                <label class="control-label">Edad: </label>
                <input type="text" class="form-control custom-input" disabled="disabled" name="edad" required>                
            </div>
            <div class="col-md-3 mb-3">
                <label class="control-label">Sexo: </label>
                <select class="form-control custom-input" name="sexo" style="width:100%" disabled="disabled" required>
                    <option value="0">Femenino</option>
                    <option value="1">Masculino</option>
                </select>
            </div>
        </div>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="form-horizontal" role="form" >
		<div class="form-group">
			<label class="col-sm-4 control-label">Interconsulta con la especialidad de: :</label>
				<div class="col-sm-8">
                <input type="text" class="form-control" name="inter" required >
				</div>
		</div>
	</div>

    <div class="form-horizontal" role="form" >
		<div class="form-group">
			<label class="col-sm-4 control-label">Motivo de Interconsulta:</label>
				<div class="col-sm-8">
					<textarea cols="30" rows="3" class="form-control" name="moir"></textarea>
				</div>
		</div>
	</div>
   
</form>