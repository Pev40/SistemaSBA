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
            <div class="col-md-3 mb-3">
                <label class="control-label">Alergias: </label>
                <input type="text" class="form-control custom-input" name="aler" disabled="disabled" required>                
            </div>
        </div>
    </div>
    <div name="gridRec">
    </div>    
    <div class="form-horizontal" role="form" >
		<div class="form-group">
			<label class="col-sm-4 control-label">Recomendaciones:</label>
				<div class="col-sm-8">
					<textarea cols="30" rows="3" class="form-control" name="rec"></textarea>
				</div>
		</div>
	</div>
</form>