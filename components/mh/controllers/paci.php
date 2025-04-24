<?php
class Controller_mh_paci extends Controller {
	function execute_lista(){
		global $f;
		$params = array("page"=>$f->request->data['page'],"page_rows"=>$f->request->data['page_rows']);
		if(isset($f->request->data['texto']))
			if($f->request->data['texto']!='')
				$params['texto'] = $f->request->data['texto'];
		if(isset($f->request->data['modulo']))
			$params['modulo'] = $f->request->data['modulo'];
		if(isset($f->request->data['sort']))
			$params['sort'] = array($f->request->data['sort']=>floatval($f->request->data['sort_i']));
		$f->response->json( $f->model("mh/paci")->params($params)->get("lista") );
	}
	function execute_get(){
		global $f;
		$items = $f->model("mh/paci")->params(array("_id"=>new MongoId($f->request->data['_id'])))->get("one")->items;
		//$social = $f->model('mh/social')->params(array('filter'=>array('paciente._id'=>$data['paciente']['_id'])))->get('one')->items;
		//SI SALE ERROR ES POR QUE SE CREO UNA FICHA SOCIAL CON EL CAMPO PACIENTE NULL
		//print_r($social);
		/*if($social!=null){
			$modulo = $social['modulo'];

			$f->$social("mh/social")->params(array('paciente._id'=>$social['paciente']['_id'],'data'=>array('modulo'=>$modulo)))->save('update')->items;
		}*/
		
		
		$f->response->json( $items );
	}
	function execute_get_reporte(){
		global $f;
		$data = $f->request->data;
		$params = array();
		if(isset($data['fecini']) && isset($data['fecfin'])) {
			$fecini = strtotime($data['fecini'].' 00:00:00');
			$fecfin = strtotime($data['fecfin'].' 23:59:59');
			$params['$and'] = array(
				array('fe_regi'=>array('$gte'=>new MongoDate($fecini))),
				array('fe_regi'=>array('$lte'=>new MongoDate($fecfin)))
			);
		}
		$items = $f->model("mh/paci")->params($params)->get("fichasalud")->items;
		$f->response->view("mh/paciente.report.php",array('diario'=>$items));
	}
	function execute_get_fichasalud(){
		global $f;
		$paciente = $f->model('mh/paci')->params(array('_id'=>new MongoId($f->request->data['_id'])))->get('one')->items;
		$f->response->view("mh/salud.report.php",array('paciente'=>$paciente));
		
	}
	function execute_get_adulto(){
		global $f;
		$paciente = $f->model('mh/paci')->params(array('_id'=>new MongoId($f->request->data['_id'])))->get('one')->items;
		$f->response->view("mh/adulto.report.php",array('paciente'=>$paciente));
		
	}
	function execute_get_carnet(){
		global $f;
		$paciente = $f->model('mh/paci')->params(array('_id'=>new MongoId($f->request->data['_id'])))->get('one')->items;
		$f->response->view("mh/carnet.report.php",array('paciente'=>$paciente));
		
	}
	function execute_get_tarjeta(){
		global $f;
		$paciente = $f->model('mh/paci')->params(array('_id'=>new MongoId($f->request->data['_id'])))->get('one')->items;
		$f->response->view("mh/tarjeta.report.php",array('paciente'=>$paciente));
		
	}
	function execute_get_historial(){
		global $f;
		$items = $f->model("mh/paci")->params(array())->get("historial")->items;
		$f->response->json( $items );
	}
	function execute_get_historia(){
		global $f;
		$cod = $f->request->data['cod'];
		$entidad = $f->model('mg/entidad')->params(array(
			'filter'=>array('roles.paciente.hist_cli'=>intval($cod)),
			'fields'=>array(),
			'sort'=>array('_id'=>1)
		))->get('custom_data')->items;
		if($entidad!=null){
			$entidad = $entidad[0];
		}
		
		$f->response->json( $entidad );
	}
	function execute_save(){
		global $f;
		$data = $f->request->data;
		$data['fecmod'] = new MongoDate();
		$data['trabajador'] = $f->session->userDBMin;
		if(isset($data['fe_regi'])){
			$data['fe_regi']=new MongoDate(strtotime($data['fe_regi']));
		}
		if(isset($data['fecha_na'])){
			$data['fecha_na']=new MongoDate(strtotime($data['fecha_na']));
		}
		if(isset($data['fecha_mod'])){
			$data['fecha_mod']=new MongoDate(strtotime($data['fecha_mod']));
		}
		if(isset($data['paciente'])){
			$data['paciente']['_id'] = new MongoId($data['paciente']['_id']);
		}

		if(isset($data['apoderado'])){
			$data['apoderado']['_id'] = new MongoId($data['apoderado']['_id']);
		}

		if(isset($data['reli'])){
			$data['reli'] = intval($data['reli']);
		}
		if(isset($data['idio'])){
			$data['idio'] = intval($data['idio']);
		}
		if(isset($data['instr'])){
			$data['instr'] = intval($data['instr']);
		}
		if(isset($data['ocupa'])){
			$data['ocupa'] = intval($data['ocupa']);
		}
		if(isset($data['his_cli'])){
			$data['his_cli'] = floatval($data['his_cli']);
		}

		if(!isset($f->request->data['_id'])){
			$data['his_cli']= floatval($data['his_cli']);
			$data['procedencia']['departamento']= intval($data['procedencia']['departamento']);
			$data['procedencia']['provincia']= intval($data['procedencia']['provincia']);
			$data['procedencia']['distrito']= intval($data['procedencia']['distrito']);

			$data['lugar_nacimiento']['departamento']= intval($data['lugar_nacimiento']['departamento']);
			$data['lugar_nacimiento']['provincia']= intval($data['lugar_nacimiento']['provincia']);
			$data['lugar_nacimiento']['distrito']= intval($data['lugar_nacimiento']['distrito']);

			$paciente = $f->model('mg/entidad')->params(array('_id'=>$data['paciente']['_id']))->get('one')->items;
			$f->model('mg/entidad')->params(array('_id'=>$data['paciente']['_id'],'data'=>array('roles.paciente.centro'=>$data['modulo'],'roles.paciente.hist_cli'=>$data['his_cli'])))->save('update');
			$data['fecreg'] = new MongoDate();
			$data['autor'] = $f->session->userDBMin;
			$data['estado'] = 'H';
			if(isset($data['paciente'])){
				$data['paciente']['fullname'] = $data['paciente']['nomb'].' '.$data['paciente']['appat'].' '.$data['paciente']['apmat'];
			}
			$model = $f->model("mh/paci")->params(array('data'=>$data))->save("insert")->items;
		}else{
			$vari = $f->model("mh/paci")->params(array("_id"=>new MongoId($f->request->data['_id'])))->get("one")->items;
			$vari['modulo'] = $data['modulo'];
			$model = $f->model("mh/paci")->params(array('_id'=>new MongoId($f->request->data['_id']),'data'=>$vari))->save("update")->items;
			
		}
		//QUE ES A PARTIR DE ESTA PARTE, LO QUE QUERIA ES QUE DESDE LA FICHA FRONTAL SE ACTUALICE EL CAMBIO DEL CAMPO MODULO EN LA FICHA SOCIAL
		$ficha_social = $f->model('mh/social')->params(array('filter'=>array('paciente._id'=>$model['paciente']['_id'])))->get('all')->items;
		if(is_null($ficha_social)){
			$ficha_social = array(
				'paciente'=>$data['paciente'],
				'edad'=>$data["edad"],
				'grad'=>$data["instr"],
				'his'=>$data["his_cli"],
				'sexo'=>$data['sexo'],
				'domi'=>$data["domicilios"][0]["direccion"],
				'categoria'=>$data['categoria'],
				'nmie'=>'',
				'tria'=>'',
				'cfami'=>'',
				'ingr'=>'',
				'nhab'=>'',
				'tfam'=>'',
				'pres'=>'',
				'dina'=>'',
				'tipo'=>'',
				'vivi'=>'',
				'cons'=>'',
				'tsoc'=>'',
				'dsoc'=>'',
				'fecreg'=>new MongoDate(),
				'fecmod'=>new MongoDate(),
				'trabajador'=>$f->session->userDBMin,
				'autor'=>$f->session->userDBMin,
				'estado'=>'H',
				'modulo'=>$data['modulo']
			);
			$paciente = $f->model('mg/entidad')->params(array('_id'=>$model['_id']))->get('one')->items;
			if(isset($ficha_social['paciente']['roles'])){
				$ficha_social['paciente']['roles']['paciente'] = $paciente['roles']['paciente'];
				$ficha_social['modulo'] = $model['modulo'];
			}else{
				$ficha_social['paciente']['roles']=array('paciente'=>$paciente['roles']['paciente']
				);
			}
			
			$f->model('mh/social')->params(array('data'=>$ficha_social))->save('insert');
			
		}else{
		$paciente = $f->model('mg/entidad')->params(array('_id'=>$model['_id']))->get('one')->items;
			if(isset($ficha_social[0]['paciente']['roles'])){
				$ficha_social[0]['paciente']['roles']['paciente'] = $paciente['roles']['paciente'];
				$ficha_social[0]['modulo'] = $model['modulo'];
			}else{
				$ficha_social[0]['paciente']['roles']=array(
					'paciente'=>$paciente['roles']['paciente']
				);
			}
			$f->model('mh/social')->params(array('_id'=>$ficha_social[0]['_id'],'data'=>$ficha_social[0]))->save('update');
		}
		$f->response->json($model);
	}
	function execute_edit(){
		global $f;
		$f->response->view("mh/paci.edit");
	}
	function execute_modulo(){
		global $f;
		$f->response->view("mh/paci.modulo");
	}
	function execute_details(){
		global $f;
		$f->response->view("mh/paci.details");
	}
	function execute_delete(){
		global $f;
		$f->model('mh/paci')->params(array('_id'=>new MongoId($f->request->data['_id'])))->delete('paci');
		$f->model('mg/entidad')->params(array(
			'_id'=>new MongoId($f->request->data['_id']),
			'data'=>array('$unset'=>array('roles.paciente'=>true))
		))->save('custom');
		$f->response->print("true");
	}
	function execute_print(){
		global $f;
		$paciente = $f->model('mh/paci')->params(array('_id'=>new MongoId($f->request->data['_id'])))->get('one')->items;
		$ubigeo_file = file_get_contents(IndexPath.DS."scripts/ubigeo-peru.min.json");
		$ubigeo = json_decode($ubigeo_file, true);
		//echo "asds";
		//var_dump($ubigeo);die();
		$procedencia_depa = strval($paciente['procedencia']['departamento']);
		$procedencia_prov = strval($paciente['procedencia']['provincia']);
		$procedencia_dist = strval($paciente['procedencia']['distrito']);
		foreach($ubigeo as $ubi){
			if (intval($ubi['departamento']) == $procedencia_depa && intval($ubi['provincia']) == intval('00') && intval($ubi['distrito']) == intval('00') ) {
				$paciente['procedencia']['departamento'] = $ubi['nombre'];
			}
			if (intval($ubi['departamento']) == $procedencia_depa && intval($ubi['provincia']) == $procedencia_prov && intval($ubi['distrito']) == intval('00') ) {
				$paciente['procedencia']['provincia'] = $ubi['nombre'];
			}
			if (intval($ubi['departamento']) == $procedencia_depa && intval($ubi['provincia']) == $procedencia_prov && intval($ubi['distrito']) == $procedencia_dist ) {
				$paciente['procedencia']['distrito'] = $ubi['nombre'];
			}
		}
		//print_r($paciente);die();
		/*$paciente['procedencia']['departamento'] = $procedencia_depa;
		$paciente['procedencia']['provincia'] = $procedencia_prov;
		$paciente['procedencia']['distrito'] = $procedencia_dist;*/
		$f->response->view("mh/paci.print",array('paciente'=>$paciente));
	}
	function execute_agregarfullname(){
		global $f;
		$model = $f->model('mh/paci')->params(array('limit'=>1,'filter'=>array('paciente.fullname'=>array('$exists'=>false))))->get('all')->items;
		if($model!=null){
			foreach($model as $item){
				$fullname = $item['paciente']['nomb'].' '.$item['paciente']['appat'].' '.$paciente['apmat'];
				$f->model("mh/paci")->params(array('_id'=>$item['_id'],'data'=>array('paciente.fullname'=>$fullname)))->save("update")->items;
			}
			echo 'true';
		}
	}
	function execute_string_number(){
		global $f;
		/*$model = $f->model('mh/paci')->get('hist_cli_string')->items;
		if($model!=null){
			foreach($model as $i=>$item){
				$set = array(
					'his_cli'=>floatval($item['his_cli'])
				);
				$f->model('mh/paci')->params(array('_id'=>$item['_id'],'data'=>$set))->save('update');
				echo 'HISTORIA CLINICA '.$item['his_cli'].' REPARADA';
			}
		}*/
		//echo count($model);
		print_r('prueba');
	}
	function execute_importar_cie10() {
    global $f;

    // Array JSON con los datos de ejemplo
    $datos_json = '[
        {"nomb":"ACAROS ( RASPADO DE PIEL)"},
		{"nomb":"ÁCIDO FOLICO"},
		{"nomb":"ACIDO URICO EN SANGRE"},
		{"nomb":"ADA"},
		{"nomb":"AGA (ANÁLISIS DE GASES ARTERIALES)"},
		{"nomb":"ALBÚMINA"},
		{"nomb":"ALFAFETOPROTEINA"},
		{"nomb":"EXAMEN DIRECTO DE HECES"},
		{"nomb":"AMILASA EN ORINA"},
		{"nomb":"AMILASA EN SANGRE"},
		{"nomb":"ANTICUERPOS ANTINUCLEARES( IFI )"},
		{"nomb":"ANTICUERPOS ANTI-TIROPEROXIDASA (ANTI-TPO)"},
		{"nomb":"ANTIESTREPTOLISINAS"},
		{"nomb":"ASPIRADO DE MEDULA OSEA (POR LAMINA)"},
		{"nomb":"ASPIRADO DE SECRECION MAMARIA (POR LAMINA)"},
		{"nomb":"BAFF (POR LAMINA)"},
		{"nomb":"BETA 2 MICOGLOBULINA"},
		{"nomb":"BILIRRUBINAS TOTALES Y FRACCIONADAS"},
		{"nomb":"BIOPSIA DE ANTRO GASTRICO"},
		{"nomb":"BIOPSIA DE CERVIX"},
		{"nomb":"BIOPSIA DE COLON"},
		{"nomb":"BIOPSIA DE ENCÍA"},
		{"nomb":"BIOPSIA DE ESTOMAGO"},
		{"nomb":"BIOPSIA DE HIGADO"},
		{"nomb":"BIOPSIA DE MEDULA OSEA"},
		{"nomb":"BIOPSIA DE PIEL"},
		{"nomb":"BIOPSIA DE SIGMOIDES"},
		{"nomb":"BIOPSIA POR CONGELACIÓN"},
		{"nomb":"BIOPSIA TRUCUT DE GANGLIO"},
		{"nomb":"BIOPSIA TRUCUT DE MAMA"},
		{"nomb":"BIOPSIA TRUCUT DE PROSTATA"},
		{"nomb":"BIOPSIAS PEQUEÑAS"},
		{"nomb":"CA 15.3"},
		{"nomb":"CA 19.9"},
		{"nomb":"CALCIO EN SANGRE U ORINA"},
		{"nomb":"CEA"},
		{"nomb":"CELULAS L. E."},
		{"nomb":"CHLAMYDIA IGG"},
		{"nomb":"CHLAMYDIA IGM"},
		{"nomb":"CITODIAGNOSTICO TZANCK"},
		{"nomb":"CITOMEGALOVIRUS IGG"},
		{"nomb":"CITOMEGALOVIRUS IGM."},
		{"nomb":"CITOQUIMICO DE LIQUIDOS CORPORALES"},
		{"nomb":"COCAINA EN ORINA"},
		{"nomb":"COLESTEROL TOTAL"},
		{"nomb":"COLORACIÓN DE ZIEHL – NEELSEN(BK)"},
		{"nomb":"COLORACIÓN DE ZIEHL – NEELSEN(BK) SERIADO"},
		{"nomb":"COMPLEMENTO C3"},
		{"nomb":"COMPLEMENTO C4"},
		{"nomb":"CONO LEEP"},
		{"nomb":"CONSTANTES CORPUSCULARES"},
		{"nomb":"CONTENIDO UTERINO"},
		{"nomb":"COPROCULTIVO"},
		{"nomb":"CORTISOL AM"},
		{"nomb":"CORTISOL PM"},
		{"nomb":"CPK TOTAL"},
		{"nomb":"CPK-MB"},
		{"nomb":"CREATININA EN SANGRE"},
		{"nomb":"CRIPTOSPORIDIUM E ISOSPORA EN HECES"},
		{"nomb":"CULTIVO DE HONGOS"},
		{"nomb":"CULTIVO DE LIQUIDO ASCITICO"},
		{"nomb":"CULTIVO DE LIQUIDO CEFALORAQUIDEO"},
		{"nomb":"CULTIVO DE LIQUIDO PLEURAL"},
		{"nomb":"CULTIVO DE SEMEN"},
		{"nomb":"CULTIVO PARA BK"},
		{"nomb":"CULTIVO SECRECION DE HERIDA"},
		{"nomb":"CULTIVO SECRECION FARINGEA"},
		{"nomb":"CULTIVO SECRECION MAMA"},
		{"nomb":"CULTIVO SECRECION NASAL"},
		{"nomb":"CULTIVO SECRECION URETRAL"},
		{"nomb":"CULTIVO SECRECION VAGINAL"},
		{"nomb":"CURVA DE TOLERANCIA A LA GLUCOSA"},
		{"nomb":"DEHIDROEPIANDROSTERONA(DHEAS)"},
		{"nomb":"DEPURACION DE LA CREATININA"},
		{"nomb":"DESHIDROGENASA LACTICA"},
		{"nomb":"DIMERO D"},
		{"nomb":"ELECTROLITOS EN ORINA DE 24HRS"},
		{"nomb":"ELECTROLITOS EN SANGRE(NA,K,CL)"},
		{"nomb":"ELISA PARA HIV 1 & 2"},
		{"nomb":"EOSINÓFILOS EN SECRECION NASAL"},
		{"nomb":"ESPERMATOGRAMA"},
		{"nomb":"ESTRADIOL"},
		{"nomb":"EXAMEN COMPLETO DE ORINA."},
		{"nomb":"EXAMEN DIRECTO Y GRAM DE SECRECIÓN"},
		{"nomb":"EXÁMEN MICOLOGICO DIRECTO"},
		{"nomb":"EXAMEN PARASITOLOGICO DE HECES"},
		{"nomb":"EXAMEN PARASITOLOGICO DE HECES SERIADO"},
		{"nomb":"FACTOR REMATOIDEO( CUALITATIVO)"},
		{"nomb":"FACTOR REMATOIDEO( CUANTITATIVO)"},
		{"nomb":"FERRITINA"},
		{"nomb":"FIBRINOGENO"},
		{"nomb":"FOSFATASA ACIDA PROSTATICA"},
		{"nomb":"FOSFATASA ACIDA TOTAL"},
		{"nomb":"FOSFATASA ALCALINA"},
		{"nomb":"FOSFORO EN SANGRE U ORINA"},
		{"nomb":"FSH"},
		{"nomb":"GPT, GOT, GGTP"},
		{"nomb":"GLUCOSA"},
		{"nomb":"GRUPO SANGUÍNEO Y RH"},
		{"nomb":"HCG-BETA"},
		{"nomb":"HDL COLESTEROL"},
		{"nomb":"HELICOBACTER PYLORI (IGG)"},
		{"nomb":"HELICOBACTER PYLORI (IGM)."},
		{"nomb":"HEMOCULTIVO CON ANTIBIOGRAMA"},
		{"nomb":"HEMOGLOBINA - HEMATOCRITO"},
		{"nomb":"HEMOGLOBINA GLICOSILADA"},
		{"nomb":"HEMOGRAMA COMPLETO"},
		{"nomb":"HEPATITIS A (IGM)"},
		{"nomb":"HEPATITIS A (IGM) A (ANTICUERPOS TOTALES )"},
		{"nomb":"HEPATITIS B ANTIGENO DE SUPERFICIE"},
		{"nomb":"HEPATITIS B ANTÍGENO E"},
		{"nomb":"HEPATITIS B CORE (ANTICUERPOS TOTALES )"},
		{"nomb":"HEPATITIS C ( ANTICUERPOS )"},
		{"nomb":"HERPES 1 IGG"},
		{"nomb":"HERPES 1 IGM"},
		{"nomb":"HERPES 2 IGG"},
		{"nomb":"HERPES 2 IGM"},
		{"nomb":"HIDATIDOSIS ANTICUERPOS IGG"},
		{"nomb":"HIERRO SERICO"},
		{"nomb":"HORMONA DEL CRECIMIENTO"},
		{"nomb":"HTLV 1&2"},
		{"nomb":"HUESO"},
		{"nomb":"IDENTIFICACIÓN DE PARASITOS"},
		{"nomb":"IGA TOTAL"},
		{"nomb":"IGE"},
		{"nomb":"IGE TOTAL"},
		{"nomb":"IGG TOTAL"},
		{"nomb":"IGM TOTAL"},
		{"nomb":"INSULINA"},
		{"nomb":"INSULINA BASAL Y POST PRANDIAL"},
		{"nomb":"LDL COLESTEROL"},
		{"nomb":"LH"},
		{"nomb":"LIPASA"},
		{"nomb":"LIPOMA GRANDE (12 CM. O MAYOR)"},
		{"nomb":"LIPOMA PEQUEÑO"},
		{"nomb":"MAGNESIO"},
		{"nomb":"MARIHUANA EN ORINA"},
		{"nomb":"MICROALBUMINURIA"},
		{"nomb":"MONOTEST"},
		{"nomb":"PERFIL LIPIDICO"},
		{"nomb":"PERFILHEPATICO"},
		{"nomb":"PLASMODIUN (GOTA GRUESA)"},
		{"nomb":"POOL DE PROLACTINA"},
		{"nomb":"PROGESTERONA"},
		{"nomb":"PROLACTINA"},
		{"nomb":"PROTEINA C REACTIVA ( CUALITATIVA)"},
		{"nomb":"PROTEINA C REACTIVA (CUANTITATIVA)"},
		{"nomb":"PROTEÍNAS TOTALES Y FRACCIONADAS"},
		{"nomb":"PROTEINURIA DE BENCE - JONES"},
		{"nomb":"PROTEINURIA EN ORINA"},
		{"nomb":"PRUEBA DE COOMBS DIRECTA"},
		{"nomb":"PRUEBA DE COOMBS INDIRECTA"},
		{"nomb":"PRUEBA DE EMBARAZO ( ORINA , LÁTEX )"},
		{"nomb":"PSA LIBRE"},
		{"nomb":"PSA TOTAL"},
		{"nomb":"REACCIÓN DE HUDDLESON (BRUCELA)"},
		{"nomb":"REACCION DE WEIL - FELIX"},
		{"nomb":"REACCION DE WIDAL"},
		{"nomb":"RECUENTO DE LEUCOCITOS"},
		{"nomb":"RECUENTO DE PLAQUETAS"},
		{"nomb":"RETRACCIÓN DE COAGULO"},
		{"nomb":"ROTAVIRUS EN HECES"},
		{"nomb":"RPR CUALITATIVA."},
		{"nomb":"RUBEOLA IGG"},
		{"nomb":"RUBEOLA IGM"},
		{"nomb":"SANGRE OCULTA EN HECES."},
		{"nomb":"SEDIMENTO URINARIO"},
		{"nomb":"T3"},
		{"nomb":"T3 LIBRE"},
		{"nomb":"T4"},
		{"nomb":"T4 LIBRE"},
		{"nomb":"TEST DE GRAHAM ( OXIURUS)"},
		{"nomb":"TESTOSTERONA"},
		{"nomb":"TGO"},
		{"nomb":"TGP"},
		{"nomb":"TIEMPO DE COAGULACIÓN Y SANGRÍA"},
		{"nomb":"TIEMPO DE PROTROMBINA"},
		{"nomb":"TIEMPO DE TROMBINA"},
		{"nomb":"TIEMPO PARCIAL DE TROMBOPLASTINA"},
		{"nomb":"TIROGLOBULINA"},
		{"nomb":"TOXOPLASMA IGG"},
		{"nomb":"TOXOPLASMA IGM"},
		{"nomb":"TRANSFERRINA"},
		{"nomb":"TRIGLICERIDOS"},
		{"nomb":"TROPONINA T"},
		{"nomb":"TSH"},
		{"nomb":"UREA"},
		{"nomb":"UROCULTIVO"},
		{"nomb":"VELOCIDAD DE SEDIMENTACION"},
		{"nomb":"VITAMINA B12"},
		{"nomb":"VLDL COLESTEROL"},
		{"nomb":"OTROS: PROCALCITONINA"}
]';

    // Decodificar el JSON a un array de PHP
    $datos = json_decode($datos_json, true);

    // Verificar si $datos es un array antes de intentar iterar sobre él
    if (is_array($datos)) {
        // Iterar sobre los datos y guardar en la base de datos
        foreach ($datos as $dato) {
            // Configura el parámetro 'data' antes de llamar al modelo
            $f->model("mh/dini")->params(array('data' => $dato))->save("insert");
        }
    } else {
        // Manejar el caso donde $datos no es un array
        $f->response->json(array('error' => 'Los datos no están en el formato correcto.'));
    }

    $f->response->json(array('message' => 'Datos insertados correctamente en la base de datos.'));
}
	/*function execute_migrar_categorias(){
		global $f;
		$social = $f->model('mh/social')->params(array('filter'=>array('categoria'=>'8'),'fields'=>array('paciente'=>true,'categoria'=>true)))->get('all')->items;
		if($social!=null){
			foreach($social as $soc){
				$paciente_id = $soc['paciente']['_id'];
				$paciente = $f->model('mh/paci')->params(array('_id'=>$paciente_id))->get('one_entidad')->items;
				//echo $paciente['_id']->{'$id'}.'<br />';
				//echo $paciente.'<br />';
				echo $soc['categoria'].'<br />';
				$f->model('mh/paci')->params(array('_id'=>$paciente['_id'],'data'=>array('categoria'=>'8')))->save('update');
			}
		}
	}*/
}
?>