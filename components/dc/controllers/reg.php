<?php
class Controller_dc_reg extends Controller {
	function execute_lista(){
		global $f;
		$params = array("page"=>$f->request->data['page'],"page_rows"=>$f->request->data['page_rows']);
		if(isset($f->request->data['texto']))
			if($f->request->data['texto']!='')
				$params['texto'] = $f->request->data['texto'];
		if(isset($f->request->data['sort']))
			$params['sort'] = array($f->request->data['sort']=>floatval($f->request->data['sort_i']));
		$f->response->json( $f->model("dc/reg")->params($params)->get("lista") );
	}
	function execute_all(){
		global $f;
		if(isset($f->request->data['fields'])) $fields = array('nomb'=>true);
		else $fields = array();
		$model = $f->model('dc/reg')->params(array('fields'=>$fields))->get('all');
		$f->response->json($model->items);
	}
	function execute_get(){
		global $f;
		$items = $f->model("dc/reg")->params(array("_id"=>new MongoId($f->request->data['_id'])))->get("one")->items;
		$f->response->json( $items );
	}
	function execute_get_dash(){
		global $f;
		$consulta = array('CP' => 0, 'TC' => 0);
		$arbol = [];
		$fields = array();
		$data = array(
				'consulta' => array(),
				'medicinas' => array(),
				'patologia' => array(),
				'procedencia' => array()
			);

		$items = $f->model('dc/reg')->params(array('filter'=>array(),'fields'=>array()
		))->get('dash')->items;
		/******************************************************************************************
        * TIPOS DE CONSULTA
        ******************************************************************************************/
		if(isset($items) || $items != null){
			foreach ($items as $i => $datos) {
				if(isset($datos['tipo'])){
					if ($datos['tipo'] == 'CP') {
						if (!isset($data['consulta']['CP'])) {
							$data['consulta']['CP'] = 0;
						}
						$data['consulta']['CP']++;
					} else {
						if (!isset($data['consulta']['TC'])) {
							$data['consulta']['TC'] = 0;
						}
						$data['consulta']['TC']++;
					}
				}
				
			}
		}
		

		/******************************************************************************************
        * LISTA DE MEDICINAS
        ******************************************************************************************/
		$medicinas = array(); // Inicializa el array de medicinas
		if(isset($items) || $items != null){
			foreach ($items as $j => $medi) {
				if (isset($medi['receta'])) {
					foreach ($medi['receta'] as $k => $medicina) {
						$nombreMedicamento = $medicina['medicamento'];
						// Incrementar contador del medicamento en el array
						if (isset($medicinas[$nombreMedicamento])) {
							$medicinas[$nombreMedicamento]++;
						} else {
							$medicinas[$nombreMedicamento] = 1;
						}
					}
				}
			}
		}
		
		$data['medicinas'] = $medicinas; // Asigna el array de medicinas al resultado final
		
		/******************************************************************************************
        * LISTA DE PATOLOGIAS
        ******************************************************************************************/
		$diagnosticos = array();
		if(isset($items) || $items != null){
			foreach ($items as $p => $pato) {
				if (isset($pato['diag'])) {
					foreach ($pato['diag'] as $diag) {
						$patologia = $diag['cie10']['sigl']; // Accede directamente a la clave 'sigl'
						if (isset($diagnosticos[$patologia])) {
							$diagnosticos[$patologia]++;
						} else {
							$diagnosticos[$patologia] = 1;
						}
					}
				}
			}
			$data['patologia'] = $diagnosticos; // Convierte a un array numérico
		}
		

		/******************************************************************************************
		* PROCEDENCIA
		******************************************************************************************/
		$ubigeo_file = file_get_contents(IndexPath.DS."scripts/ubigeo-peru.min.json");
		$ubigeo = json_decode($ubigeo_file, true);
	
		if(isset($items) || $items != null){
			foreach ($items as $i => $datos) {
				if(isset($datos['procedencia'])){
					$procedencia = $datos['procedencia'];
					$departamento = $procedencia['departamento'];
					$provincia = $procedencia['provincia'];
					$distrito = $procedencia['distrito'];
	
					/********************/
					$procedencia_depa = strval($datos['procedencia']['departamento']);
					$procedencia_prov = strval($datos['procedencia']['provincia']);
					$procedencia_dist = strval($datos['procedencia']['distrito']);
	
					foreach($ubigeo as $ubi){
						if (intval($ubi['departamento']) == $departamento && intval($ubi['provincia']) == intval('00') && intval($ubi['distrito']) == intval('00') ) {
							$datos['procedencia']['departamento'] = $ubi['nombre'];
						}
						if (intval($ubi['departamento']) == $departamento && intval($ubi['provincia']) == $provincia && intval($ubi['distrito']) == intval('00') ) {
							$datos['procedencia']['provincia'] = $ubi['nombre'];
						}
						if (intval($ubi['departamento']) == $departamento && intval($ubi['provincia']) == $provincia && intval($ubi['distrito']) == $procedencia_dist ) {
							$datos['procedencia']['distrito'] = $ubi['nombre'];
						}
					}
	
					$clave_procedencia = $datos['procedencia']['distrito'];
	
					// Contar patologías para el distrito
					if (!isset($data['procedencia'][$clave_procedencia]['patologias'])) {
						$data['procedencia'][$clave_procedencia]['patologias'] = [];
					}
	
					// Inicializar el contador total
					if (!isset($data['procedencia'][$clave_procedencia]['total'])) {
						$data['procedencia'][$clave_procedencia]['total'] = 0;
					}
	
					foreach ($datos['diag'] as $diag) {
						$sigl = $diag['cie10']['sigl'];
						if (!isset($data['procedencia'][$clave_procedencia]['patologias'][$sigl])) {
							$data['procedencia'][$clave_procedencia]['patologias'][$sigl] = 0;
						}
						$data['procedencia'][$clave_procedencia]['patologias'][$sigl]++;
						$data['procedencia'][$clave_procedencia]['total']++; // Incrementar el contador total
					}
				}
			}
		}
		//print_r($data['procedencia']);

		$f->response->json($data);
	}
	function execute_save(){
		global $f;
		$data = $f->request->data;
		$data['fecmod'] = new MongoDate();
		$data['trabajador'] = $f->session->userDBMin;

		$data['hist'] = 'DD.PER.'.$data['paciente']['docident'][0]['num'];
		if(isset($data['femi'])){
			$data['femi']=new MongoDate(strtotime($data['femi']));
		}
		if(isset($data['fecreg'])){
			$data['fecreg']=new MongoDate(strtotime($data['fecreg']));
		}
		if(isset($data['descanso']['indi'])){
			$data['descanso']['indi']=new MongoDate(strtotime($data['descanso']['indi']));
		}
		if(isset($data['descanso']['fidi'])){
			$data['descanso']['fidi']=new MongoDate(strtotime($data['descanso']['fidi']));
		}
		if(isset($data['empresa'])){
			$data['empresa']['_id'] = new MongoId($data['empresa']['_id']);
		}
		if(isset($data['paciente'])){
			$data['paciente']['_id'] = new MongoId($data['paciente']['_id']);
		}

		if(!isset($f->request->data['_id'])){
			$data['procedencia']['departamento']= intval($data['procedencia']['departamento']);
			$data['procedencia']['provincia']= intval($data['procedencia']['provincia']);
			$data['procedencia']['distrito']= intval($data['procedencia']['distrito']);
			$data['fecreg'] = new MongoDate();
			$data['autor'] = $f->session->userDBMin;
			$data['estado'] = "H";
			$f->model("dc/reg")->params(array('data'=>$data))->save("insert");
		}else{
			$vari = $f->model("dc/reg")->params(array("_id"=>new MongoId($f->request->data['_id'])))->get("one")->items;
			$f->model("dc/reg")->params(array('_id'=>new MongoId($f->request->data['_id']),'data'=>$data))->save("update");
		}
		$f->response->print("true");
	}
	function execute_details(){
		global $f;
		$f->response->view("dc/reg.details");
	}
	function execute_delete(){
		global $f;
		$f->model('dc/reg')->params(array('_id'=>new MongoId($f->request->data['_id'])))->delete('reg');
		$f->response->print("true");
	}
	function execute_edit(){
		global $f;
		$f->response->view("dc/reg.edit");
	}
	function execute_pre(){
		global $f;
		$f->response->view("dc/reg.pre.edit");
	}
	function execute_rec(){
		global $f;
		$f->response->view("dc/reg.rec.edit");
	}
	function execute_inter(){
		global $f;
		$f->response->view("dc/reg.inter.edit");
	}
	function execute_labor(){
		global $f;
		$f->response->view("dc/reg.labor.edit");
	}
	function execute_exam(){
		global $f;
		$f->response->view("dc/reg.aux.edit");
	}
	function execute_desc(){
		global $f;
		$f->response->view("dc/reg.desc.edit");
	}
	function execute_info(){
		global $f;
		$f->response->view("dc/reg.info.edit");
	}
	function execute_print(){
		global $f;
		$doc = $f->model('dc/reg')->params(array('_id'=>new MongoId($f->request->data['_id'])))->get('one')->items;
		$usuario = $doc['autor']['_id']->{'$id'};
		$user = $f->model("ac/user")->params(array("_id"=>new MongoId($usuario)))->get("one_user")->items;
		if(isset($user['pref'])){
			$doc['pref'] = $user['pref'];
		}
		if(isset($user['url_imagen'])){
			$doc['firma'] = $user['url_imagen'];
		}
		$f->response->view("dc/reg.his.print",array('doc'=>$doc));
	}
	
}
?>