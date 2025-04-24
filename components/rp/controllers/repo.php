<?php
class Controller_rp_repo extends Controller {
	function execute_index(){
		global $f;
		$f->response->view('rp/repo.view');
	}

  public function execute_index2()
  {
      global $f;
      $f->response->view("rp/repo.view");
  }
  public function execute_dashboard()
  {
      global $f;
      $f->response->view("rp/dashboard");
  }
	function execute_registro_ventas(){
		global $f;
		$tipo_inm_actual = $f->request->data['tipo_caja'];
		if($tipo_inm_actual == 'CM'){
				$fec = $f->request->data['ano'].'-'.$f->request->data['mes'].'-01';
				$comp = $f->model("cj/comp")->params(array("filter"=>array(
					'items.cuenta_cobrar.servicio.organizacion._id'=>new MongoId("51a50f0f4d4a13c409000013"),
					'fecreg'=>array(
						'$gte'=>new MongoDate(strtotime($fec)),
						'$lte'=>new MongoDate(strtotime($fec.' +1 month -1 minute'))
					)

				),'fields'=>array(
					'fecreg'=>true,
					'tipo'=>true,
					'serie'=>true,
					'num'=>true,
					'cliente'=>true,
					'total'=>true,
					'estado'=>true,
					'cliente_nuevo'=>true
				),'sort'=>array(
					'fecreg'=>1,
					'serie'=>1,
					'num'=>1
				)))->get("all")->items;
				//echo date('Y-m-d H:i:s',strtotime($fec.' +1 month -1 hour'));die();
				$rede = $f->model("cj/rede")->params(array("filter"=>array(
					'fec_db'=>array(
						'$gte'=>new MongoDate(strtotime($fec)),
						'$lte'=>new MongoDate(strtotime($fec.' +1 month -1 hour'))
					)
				)))->get("all")->items;

				$f->response->view("cj/repo.registro_ventas.print",array(
					'data'=>$comp,'rede'=>$rede,'params'=>$f->request->data
				));
		}
		if($tipo_inm_actual == 'MH'){
			$fec = $f->request->data['ano'].'-'.$f->request->data['mes'].'-01';
			$comp = $f->model("cj/comp")->params(array("filter"=>array(
				'modulo'=>'MH',
				'fecreg'=>array(
					'$gte'=>new MongoDate(strtotime($fec)),
					'$lte'=>new MongoDate(strtotime($fec.' +1 month -1 minute'))
				)

			),'fields'=>array(
				'fecreg'=>true,
				'tipo'=>true,
				'serie'=>true,
				'num'=>true,
				'cliente'=>true,
				'total'=>true,
				'estado'=>true
			),'sort'=>array(
				'fecreg'=>1,
				'serie'=>1,
				'num'=>1
			)))->get("all")->items;
			//echo date('Y-m-d H:i:s',strtotime($fec.' +1 month -1 hour'));die();
			$rede = [];

			$f->response->view("in/repo.registro_ventas_doctor.xls", array(
				'data'=>$comp,'params'=>$f->request->data
			));
		}
		else{
			$comp = [];
			$fec = $f->request->data['ano'].'-'.$f->request->data['mes'].'-01';
			$series_por_tipo = array(
				'A' => array('B001', 'F001'),
				'P' => array('B004', 'F004'),
				'PA' => array('B006', 'F006','B013','F013'),
				'TDM' => array('B007', 'F007'),
				'CMC' => array('B012', 'F012'),
				'PAC' => array('B013', 'F013'),
				'PJ' => array('B010', 'F010'),

			);


			$series = isset($series_por_tipo[$tipo_inm_actual]) ? $series_por_tipo[$tipo_inm_actual] : array();
			if ($tipo_inm_actual == 'A') {
				$comp = $f->model("cj/comp")->params(array("filter"=>array(
					'modulo'=>'IN',
					'fecreg'=>array(
						'$gte'=>new MongoDate(strtotime($fec)),
						'$lte'=>new MongoDate(strtotime($fec.' +1 month -1 minute'))
					)
					/*,
					'estado'=>array('$-'=>'X')*/
				),'fields'=>array(
					'fecreg'=>true,
					'tipo'=>true,
					'serie'=>true,
					'num'=>true,
					'cliente'=>true,
					'subtotal'=>true,
					'igv'=>true,
					'sunat'=>true,
					'total'=>true,
					'estado'=>true,
					'cliente_nuevo'=>true
				),'sort'=>array(
					'fecreg'=>1,
					'serie'=>1,
					'num'=>1
				)))->get("all")->items;
			}

			$efilter = array(
				'fecemi'=>array(
					'$gte'=>new MongoDate(strtotime($fec)),
					'$lte'=>new MongoDate(strtotime($fec.' +1 month -1 minute'))
				),
				//'items.tipo' => array('$in' => array('agua_chapi')),
				'serie' => array('$in' => $series),
				'estado' => array('$in' => array('FI','ES','X')),
			);

			$ecom = $f->model("cj/ecom")->params(array("filter"=>$efilter,
				'fields'=>array(
					'fecemi'=>true,
					'fecreg'=>true,
					'tipo'=>true,
					'serie'=>true,
					'numero'=>true,
					'cliente_nomb'=>true,
					'cliente_doc'=>true,
					'tipo_doc'=>true,
					'total'=>true,
					'total_ope_gravadas'=>true,
					'total_ope_inafectas'=>true,
					'total_igv'=>true,
					'tipo_comprobante'=>true,
					'estado'=>true,
				),'sort'=>array(
					'fecemi'=>1,
					'serie'=>1,
					'numero'=>1
			)))->get("all")->items;

			if (is_null($comp) && !is_null($ecom)) {
				$comp = $ecom;
			} elseif (is_null($ecom) && !is_null($comp)) {
				$comp = $comp;
			} elseif (!is_null($ecom) && !is_null($comp)) {
				$comp = array_merge($comp, $ecom);
			}

			//COMPROBANTES
			foreach ($comp as $k => $row) {
				$freg[$k] = $row['fecreg'];
				$tipos[$k] = $row['tipo'];
				//$numer[$k] = $row['num'];
			}

			array_multisort($freg, SORT_ASC, $tipos, SORT_DESC, $comp);
			if ($f->request->data['type'] == 'xlss') {
				$f->response->view("in/repo.registro_ventas.xls", array(
					'data'=>$comp,'params'=>$f->request->data
				));
			} elseif ($f->request->data['type'] == 'pdf') {
				$f->response->view("in/repo.registro_ventas.pdf", array(
					'data'=>$comp,'params'=>$f->request->data
				));
			}
		}
	}

}
?>