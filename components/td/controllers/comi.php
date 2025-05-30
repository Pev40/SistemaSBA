<?php
class Controller_td_comi extends Controller {
	function execute_lista(){
		global $f;
		$params = array("page"=>$f->request->data['page'],"page_rows"=>$f->request->data['page_rows']);
		if(isset($f->request->data['texto']))
			if($f->request->data['texto']!='')
				$params['texto'] = $f->request->data['texto'];
		if(isset($f->request->data['sort']))
			$params['sort'] = array($f->request->data['sort']=>floatval($f->request->data['sort_i']));
		$f->response->json( $f->model("td/comi")->params($params)->get("lista") );
	}
	function execute_get(){
		global $f;
		$items = $f->model("td/comi")->params(array("_id"=>new MongoId($f->request->data['_id'])))->get("one")->items;
		$f->response->json( $items );
	}
	function execute_save(){
		global $f;
		$data = $f->request->data;
		$data['fecmod'] = new MongoDate();
		$data['trabajador'] = $f->session->userDB;
		if(isset($data['oficina'])){
			foreach ($data['oficina'] as $k => $ofi) {
				$data['oficina'][$k]['_id'] = new MongoId($ofi['_id']);
			}
		}
		if(!isset($f->request->data['_id'])){
			$data['fecreg'] = new MongoDate();
			$data['autor'] = $f->session->userDB;
			$data['estado'] = 'H';
			$model = $f->model("td/comi")->params(array('data'=>$data))->save("insert")->items;
			$f->model('ac/log')->params(array(
				'modulo'=>'TD',
				'bandeja'=>'Comite',
				'descr'=>'Se creó el Comite <b>'.$data['nomb'].'</b>.'
			))->save('insert');
		}else{
			$vari = $f->model("td/comi")->params(array("_id"=>new MongoId($f->request->data['_id'])))->get("one")->items;
			$f->model('ac/log')->params(array(
				'modulo'=>'TD',
				'bandeja'=>'Comite',
				'descr'=>'Se actualizó el Comite <b>'.$vari['nomb'].'</b>.'
			))->save('insert');
			$model = $f->model("td/comi")->params(array('_id'=>new MongoId($f->request->data['_id']),'data'=>$data))->save("update")->items;
		}
		$f->response->json($model);
	}
	function execute_edit(){
		global $f;
		$f->response->view("td/comi.edit");
	}
	function execute_details(){
		global $f;
		$f->response->view("td/comi.details");
	}
}
?>