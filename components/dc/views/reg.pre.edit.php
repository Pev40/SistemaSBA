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

    <div class="container">
    <style>
        .custom-input {
            max-width: 150px; /* Puedes ajustar el valor según tu preferencia */
        }
    </style>
        <div class="form-row">
            <div class="col-md-3 mb-3">
                <label class="control-label">Edad: </label>
                <input type="text" class="form-control custom-input" name="edad" required>                
            </div>
            <div class="col-md-3 mb-3">
                <label class="control-label">Sexo: </label>
                <select class="form-control custom-input" name="sexo" style="width:100%" required>
                    <option value="0">Femenino</option>
                    <option value="1">Masculino</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="control-label">Pais: </label>
                <input type="text" class="form-control custom-input" name="pais" required>                
            </div>
            <div class="col-md-3 mb-3">
                <label class="control-label">Alergias: </label>
                <input type="text" class="form-control custom-input" name="aler" required>                
            </div>
        </div>
    </div>
    <div>
        <p><br></p>
    </div>
    
    <div class="container">
    <style>
        .custom-input {
            max-width: 150px; /* Puedes ajustar el valor según tu preferencia */
        }
    </style>
        <div class="form-row">
            <div class="col-md-3 mb-3">
                <label class="control-label">Peso: </label>
                <input type="text" class="form-control custom-input" name="peso" required>KG.                
            </div>
            <div class="col-md-3 mb-3">
                <label class="control-label">Talla: </label>
                <input type="text" class="form-control custom-input" name="talla" required>M.                
            </div>
            <div class="col-md-3 mb-3">
                <label class="control-label">IMC: </label>
                <input type="text" class="form-control custom-input" name="imc" disabled="disabled" required>Kg/m2                
            </div>
            <div class="col-md-3 mb-3">
                <label class="control-label">Temperatura: </label>
                <input type="text" class="form-control custom-input" name="temp" required>C°                
            </div>
        </div>
    </div>
    <div>
        <p><br></p>
    </div>
    <div class="container">
    <style>
        .custom-input {
            max-width: 150px; /* Puedes ajustar el valor según tu preferencia */
        }
    </style>
        <div class="form-row">
            <div class="col-md-3 mb-3">
                <label class="control-label">Sat02 : </label>
                <input type="text" class="form-control custom-input" name="satu" required>%                
            </div>
            <div class="col-md-3 mb-3">
                <label class="control-label">P.A. : </label>
                <input type="text" class="form-control custom-input" name="presa" required>mmH                
            </div>
            <div class="col-md-3 mb-3">
                <label class="control-label">F.C. : </label>
                <input type="text" class="form-control custom-input" name="frec" required>lpm              
            </div>
            <div class="col-md-3 mb-3">
                <label class="control-label">F.R. : </label>
                <input type="text" class="form-control custom-input" name="fecr" required>rpm                
            </div>
        </div>
    </div>
    <div>
        <p><br></p>
    </div>

    <div class="form-horizontal" role="form" >
		<div class="form-group">
			<label class="col-sm-4 control-label">Tiempo de Enfermedad:</label>
				<div class="col-sm-8">
                <input type="text" class="form-control" name="tien" style="width:300px" required >
				</div>
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
			<label class="col-sm-4 control-label">Examen Fisico Dirigido:</label>
				<div class="col-sm-8">
					<textarea cols="30" rows="3" class="form-control" name="exfi"></textarea>
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