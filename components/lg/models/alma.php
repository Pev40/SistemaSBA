<?php
class Model_lg_alma extends Model {
	private $alma;
	public $items;
	
	public function __construct() {
		global $f;
		$this->alma = $f->datastore->lg_almacenes;
	}
	protected function get_one(){
		global $f;
		$this->items = $this->alma->findOne(array('_id'=>$this->params['_id']));
	}
	protected function get_farmacia(){
		global $f;
		$param = array(
			'estado' => "H", 
			'aplicacion' => "FA"
		);
		$this->items = $this->alma->findOne($param);
	}
	protected function get_agua_chapi(){
		global $f;
		$param = array(
			'estado' => "H", 
			'aplicacion' => "AG"
		);
		$this->items = $this->alma->findOne($param);
	}
	protected function get_lista(){
		global $f;
		$filter = array();
		$fields = array();
		$order = array('nomb'=>1);
		$data = $this->alma->find($filter,$fields)->skip( $this->params['page_rows'] * ($this->params['page']-1) )->sort($order)->limit( $this->params['page_rows'] );
		foreach ($data as $obj) {
		    $this->items[] = $obj;
		}
		$this->paging($this->params["page"],$this->params["page_rows"],$data->count());
	}
	protected function get_search(){
		global $f;
		if($this->params["texto"]!=''){
			$f->library('helpers');
			$helper=new helper();
			$parametro = $this->params["texto"];
			$criteria = $helper->paramsSearch($this->params["texto"], array('nomb','cod'));
		}else $criteria = array();
		$criteria['estado'] = array('$ne'=>'D');
		$fields = array();
		$cursor = $this->alma->find($criteria,$fields)->sort( array('nomb'=>1) )->skip( $this->params["page_rows"] * ($this->params["page"]-1) )->limit( $this->params["page_rows"] );
		foreach ($cursor as $obj) {
			$this->items[] = $obj;
		}
		$this->paging($this->params["page"],$this->params["page_rows"],$cursor->count());
	}
	protected function get_all(){
		global $f;
		$filter =array('estado'=>'H');
		$fields = array();
		if(isset($this->params['filter'])) $filter = $this->params['filter'];
		if(isset($this->params['fields'])) $fields = $this->params['fields'];
		$data = $this->alma->find($filter,$fields);
		foreach ($data as $ob) {
		    $this->items[] = $ob;
		}
	}
	protected function save_insert(){
		global $f;
		$this->alma->insert( $this->params['data'] );
	}
	protected function save_update(){
		global $f;
		unset($this->params['data']['_id']);
		$this->alma->update( array('_id'=>$this->params['_id']) , array('$set'=>$this->params['data']) );
	}
}
?>