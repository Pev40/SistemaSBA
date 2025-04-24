<?php
class Controller_cm_terr extends Controller {
	function execute_lista(){
		global $f;
		$params = array("page"=>$f->request->data['page'],"page_rows"=>$f->request->data['page_rows']);
		if(isset($f->request->data['texto']))
			if($f->request->data['texto']!='')
				$params['texto'] = $f->request->data['texto'];
		if(isset($f->request->data['sort']))
			$params['sort'] = array($f->request->data['sort']=>floatval($f->request->data['sort_i']));
		$f->response->json( $f->model("cm/terr")->params($params)->get("lista") );
	}
	function execute_all(){
		global $f;
		if(isset($f->request->data['fields'])) $fields = array('nomb'=>true);
		else $fields = array();
		$model = $f->model('cm/terr')->params(array('fields'=>$fields))->get('all');
		$f->response->json($model->items);
	}
	function execute_get(){
		global $f;
		$items = $f->model("cm/terr")->params(array("_id"=>new MongoId($f->request->data['_id'])))->get("one")->items;
		$f->response->json( $items );
	}
	function execute_get_num(){
		global $f;
		$caja = '528fa3fea4b5c36405000003';
		$model = $f->model("cj/talo")->params(array("_id"=>new MongoId($caja)))->get("one");
		if (!is_null($model->items)) {
            foreach ($model->items as $i=>$item) {
                /*$cod = $f->model("cj/comp")->params(array(
                    'tipo'=>$item['tipo'],
                    'serie'=>$item['serie'],
                    'caja'=>$item['caja']['_id']
                ))->get("num");
                if($cod->items==null) $cod->items=$model->items[$i]['actual'];
                else $cod->items = intval($cod->items);
                $model->items[$i]['actual'] = $cod->items;
                */
            }
        }
		$f->response->json($model->items);
	}
	function execute_save_antiguo_recibo(){
		global $f;
		$data = $f->request->data;
		$data['fecmod'] = new MongoDate();
		$data['trabajador'] = $f->session->userDBMin;
		if(isset($data['fecve'])){
			$data['fecve']=new MongoDate(strtotime($data['fecve']));
		}
		if(!isset($f->request->data['_id'])){
			$data['fecreg'] = new MongoDate();
			$data['autor'] = $f->session->userDBMin;
			$data['estado'] = "H";
			$f->model("cm/terr")->params(array('data'=>$data))->save("insert");
			$f->model('ac/log')->params(array('modulo'=>'cm','bandeja'=>'Circuito del Terror','descr'=>'Se cre&oacute; la fecha <b>'))->save('insert');
		}else{
			$vari = $f->model("cm/terr")->params(array("_id"=>new MongoId($f->request->data['_id'])))->get("one")->items;
			if(isset($data['fecven'])){
				$data['fecven']=new MongoDate(strtotime($data['fecven']));
			}
			if(isset($data['cliente'])){
				$data['cliente']['_id'] = new MongoId($data['cliente']['_id']);
			}

			/*$conceptos = array(
				'conceptos' => array(
					array(
						"concepto" => array(
							"_id" => new MongoId('6356cd763e603727628b4567'),
							"nomb" => " Entrada para el evento “Mitos y Leyendas” General Mitos "
						)
					)
				)
			);*/
			if(isset($data['items'])){
				if (isset($data['items'][0]['conceptos'])) {
					foreach ($data['items'][0]['conceptos'] as $i => &$concepto) {
						$concepto['concepto']['_id'] = new MongoId($concepto['concepto']['_id']);
						$concepto['monto'] = floatval($data['total']);
					}
				}
	//			die();
				if (isset($data['items'][0]['cuenta_cobrar'])) {
					$data['items'][0]['cuenta_cobrar']['_id'] = '-';
					$data['items'][0]['cuenta_cobrar']['servicio']['_id'] = new MongoId($data['items'][0]['cuenta_cobrar']['servicio']['_id']);
					$data['items'][0]['cuenta_cobrar']['servicio']['organizacion']['_id'] = new MongoId($data['items'][0]['cuenta_cobrar']['servicio']['organizacion']['_id']);
					$data['items'][0]['cuenta_cobrar']['cuenta']['_id'] = new MongoId($data['items'][0]['cuenta_cobrar']['cuenta']['_id']);
				}
				
			}
			if(isset($data['caja'])){
				$data['caja']['_id'] = new MongoId($data['caja']['_id']);
				$data['caja']['local']['_id'] = new MongoId($data['caja']['local']['_id']);
			}
			if(isset($data['num'])){
				$data['num'] = floatval($data['num']);

			}
			if(isset($data['total'])){
				$data['total'] = floatval($data['total']);
				$efectivo = array(
					array(
						'moneda' => 'S',
						'monto' => $data['total']
					),
					array(
						'moneda' => 'D',
						'monto' => 0
					)
				);
				  
				$data['efectivos'] = $efectivo;
			}
		$trabajador = $f->session->userDB;
        if ($trabajador['_id']==new MongoId('57f3e8eb8e7358b007000042')) {
            $trabajador = array(
                '_id'=>new MongoId('56fd3c148e73584c07000062'),
                 "tipo_enti"=>"P",
                 "nomb"=>"PEDRO PERCY",
                 "apmat"=>"REVILLA",
                 "appat"=>"AMESQUITA",
                 "cargo"=>array(
                   "funcion"=>"APOYO ADMINISTRATIVO",
                   "organizacion"=>array(
                     "_id"=>new MongoId("51a50f0f4d4a13c409000013"),
                     "nomb"=>"Unidad de Cementerio y Servicios Funerarios",
                     "componente"=>array(
                       "_id"=>new MongoId("51e99d7a4d4a13c404000016"),
                       "nomb"=>"SERVICIOS FUNERARIOS Y DE CEMENTERIO",
                       "cod"=>"001"
                    ),
                     "actividad"=>array(
                       "_id"=>new MongoId("51e996044d4a13440a00000e"),
                       "nomb"=>"SERVICIOS FUNERARIOS Y DE CEMENTERIO",
                       "cod"=>"5001194"
                    )
                  )
                )
            );
        }
			$evento = array(
				array(
					'_id' => new MongoId($data['_id']),
					'fecve' => $data['fecve']
					)
				);
			$data['evento'] = $evento;
			if(!isset($data['autor'])){
				$data['autor'] = $trabajador;
			}
			unset($data['_id']);
			$comprobante = $f->model('cj/comp')->params(array('data'=>$data))->save('insert')->items;
			$f->model('cj/talo')->params(array(
				'tipo'=>$data['tipo'],
				'serie'=>$data['serie'],
				'num'=>floatval($data['num']),
				'caja'=>$data['caja']['_id']
			))->save('num');
			if(!isset($data['comprobante'])){
				$data['comprobante '] = new MongoId($comprobante['_id']);
			}
			if(!isset($comprobante['fecreg'])){
				$comprobante['fecreg'] = $data['fecven'];
			}
			$cuenta = $f->model("cj/cuen")->params(array('data'=>$data))->save("insert")->items;
			
			$cuenta_cobrar_id = $comprobante['items'][0]['cuenta_cobrar']['_id'];
			$cuenta_cobrar_id = $cuenta['_id'];
			$comprobante['items'][0]['cuenta_cobrar']['_id'] = $cuenta_cobrar_id;
			$f->model("cj/comp")->params(array('_id'=> new MongoId($comprobante['_id']),'data'=>array(
				'items.0.cuenta_cobrar._id'=>$comprobante['items'][0]['cuenta_cobrar']['_id'],
				'fecreg' => $comprobante['fecreg']
				)))->save("update");
		}
		$f->response->json($comprobante);
	}

	function execute_save()
	{
		global $f;
		$data = $f->request->data;
		$data['fecmod'] = new MongoDate();
		$data['trabajador'] = $f->session->userDBMin;
		$comprobante = array();
		if (isset($data['fecve'])) {
			$data['fecve'] = new MongoDate(strtotime($data['fecve']));
			$data['fecemi'] = new MongoDate(strtotime($data['fecven']));
		}
		if (!isset($f->request->data['_id'])) {
			$data['fecreg'] = new MongoDate();
			$data['autor'] = $f->session->userDBMin;
			$data['estado'] = "H";
			$f->model("cm/terr")->params(array('data' => $data))->save("insert");
			$f->model('ac/log')->params(array('modulo' => 'cm', 'bandeja' => 'Circuito del Terror', 'descr' => 'Se cre&oacute; la fecha <b>'))->save('insert');
		} else {
			$vari = $f->model("cm/terr")->params(array("_id" => new MongoId($f->request->data['_id'])))->get("one")->items;
			if (isset($data['fecven'])) {
				
				$data['fecven'] = new MongoDate(strtotime($data['fecven']));
				
			}
			if (isset($data['cliente'])) {
				$data['cliente']['_id'] = new MongoId($data['cliente']['_id']);
			}

			if (isset($data['items'])) {
				if (isset($data['items'][0]['conceptos'])) {
					foreach ($data['items'][0]['conceptos'] as $i => &$concepto) {
						$concepto['_id'] = new MongoId("6356cd763e603727628b4567");
						$concepto['monto'] = floatval($concepto['monto']);
						$concepto['cuenta']['_id'] = new MongoId("65f300183e603793138b4567");
					}
				}
			}
			if (isset($data['caja'])) {
				$data['caja']['_id'] = new MongoId($data['caja']['_id']);
				$data['caja']['local']['_id'] = new MongoId($data['caja']['local']['_id']);
			}
			if (isset($data['num'])) {
				$data['num'] = floatval($data['num']);
			}
			if (isset($data['total'])) {
				$data['total'] = floatval($data['total']);
				$efectivo = array(
					array(
						'moneda' => 'S',
						'monto' => $data['total']
					),
					array(
						'moneda' => 'D',
						'monto' => 0
					)
				);

				$data['efectivos'] = $efectivo;
			}
			$trabajador = $f->session->userDB;
			if ($trabajador['_id'] == new MongoId('57f3e8eb8e7358b007000042')) {
				$trabajador = array(
					'_id' => new MongoId('56fd3c148e73584c07000062'),
					"tipo_enti" => "P",
					"nomb" => "PEDRO PERCY",
					"apmat" => "REVILLA",
					"appat" => "AMESQUITA",
					"cargo" => array(
						"funcion" => "APOYO ADMINISTRATIVO",
						"organizacion" => array(
							"_id" => new MongoId("51a50f0f4d4a13c409000013"),
							"nomb" => "Unidad de Cementerio y Servicios Funerarios",
							"componente" => array(
								"_id" => new MongoId("51e99d7a4d4a13c404000016"),
								"nomb" => "SERVICIOS FUNERARIOS Y DE CEMENTERIO",
								"cod" => "001"
							),
							"actividad" => array(
								"_id" => new MongoId("51e996044d4a13440a00000e"),
								"nomb" => "SERVICIOS FUNERARIOS Y DE CEMENTERIO",
								"cod" => "5001194"
							)
						)
					)
				);
			}
			$evento = array(
				array(
					'_id' => new MongoId($data['_id']),
					'fecve' => $data['fecve']
				)
			);
			$data['evento'] = $evento;
			if (!isset($data['autor'])) {
				$data['autor'] = $trabajador;
			}
			unset($data['_id']);
			//var_dump($data);
			$comprobante = $f->model('cj/ecom')->params(array('data' => $data))->save('insert')->items;




			if (!isset($data['comprobante'])) {
				$data['comprobante '] = new MongoId($comprobante['_id']);
			}

			if (!isset($comprobante['fecreg'])) {
				$comprobante['fecreg'] = $data['fecven'];
			}
			$cuenta = $f->model("cj/cuen")->params(array('data' => $data))->save("insert")->items;

			$f->model("cj/ecom")->params(array('_id' => new MongoId($comprobante['_id']), 'data' => array(
				'fecreg' => $comprobante['fecreg']
			)))->save("update");

			// Uso de la función para enviar y guardar los datos
			$response = $this->enviar_y_guardar_datos($data, $f);
		}

		$f->response->json($response);
	}
	function transformar_datos_api($data)
	{
		//var_dump($data);
		// Formatear la fecha de emisión y vencimiento
		$fecha_emision = isset($data['fecve']) ? date('Y-m-d', $data['fecemi']->sec) : date('Y-m-d');
		$fecha_vencimiento = isset($data['fecven']) ? date('Y-m-d', $data['fecven']->sec) : $fecha_emision;

		// Formatear los items
		$items = array();
		foreach ($data['items'] as $item) {
			$items[] = array(
				"codigo" => isset($item['codigo']) ? $item['codigo'] : '',
				"descr" => isset($item['descr']) ? $item['descr'] : '',
				"cant" => isset($item['cant']) ? $item['cant'] : 1,
				"cod_unidad" => isset($item['cod_unidad']) ? $item['cod_unidad'] : 'ZZ',
				"unidad" => isset($item['unidad']) ? $item['unidad'] : 'ZZ',
				"valor_unitario" => isset($item['valor_unitario']) ? $item['valor_unitario'] : 0,
				"tipo_igv" => isset($item['tipo_igv']) ? $item['tipo_igv'] : '10',
				"igv" => isset($item['igv']) ? $item['igv'] : 0,
				"precio_venta_unitario" => isset($item['precio_venta_unitario']) ? $item['precio_venta_unitario'] : 0,
				"precio_total" => isset($item['precio_total']) ? $item['precio_total'] : 0,
				"tipo" => isset($item['tipo']) ? $item['tipo'] : 'servicio',
				"tipo_agregacion" => "after", // Se mantiene constante
				"descuento" => isset($item['descuento']) ? $item['descuento'] : 0,
				"gratuito" => isset($item['gratuito']) ? $item['gratuito'] : false,
				"valor_venta" => isset($item['valor_venta']) ? $item['valor_venta'] : 0,
				"valor_gratuito" => isset($item['valor_gratuito']) ? $item['valor_gratuito'] : 0,
				"isc" => isset($item['isc']) ? $item['isc'] : 0,
				
			);
		}
	
		// Formatear los efectivos
		$efectivos = array(
			array(
				"moneda" => "S",
				"monto" => isset($data['total']) ? $data['total'] : 0
			),
			array(
				"moneda" => "D",
				"monto" => 0
			)
		);
	
		// Datos generales del comprobante
		$payload = array(
			"observaciones" => isset($data['tickets'][0]['ini']) ? 
				"Ticket del " . $data['tickets'][0]['ini'] . 
				(isset($data['tickets'][0]['fin']) && $data['tickets'][0]['fin'] != $data['tickets'][0]['ini'] ? 
				" al " . $data['tickets'][0]['fin'] : 
				"") : 
				"Ticket",
			"tipo" => isset($data['tipo']) ? $data['tipo'] : 'B',
			"tipo_oper" => isset($data['tipo_oper']) ? $data['tipo_oper'] : '01',
			"tipo_doc" => isset($data['tipo_doc']) ? $data['tipo_doc'] : 'DNI',
			"serie" => isset($data['serie']) ? $data['serie'] : 'B001',
			"numero" => isset($data['numero']) ? $data['numero'] : '',
			"cliente_nomb" => isset($data['cliente_nomb']) ? $data['cliente_nomb'] : '',
			"cliente_doc" => isset($data['cliente_doc']) ? $data['cliente_doc'] : '',
			"cliente_domic" => isset($data['cliente_domic']) ? $data['cliente_domic'] : '',
			"cliente_email_1" => isset($data['cliente_email_l']) ? $data['cliente_email_l'] : '',
			"fecemi" => $fecha_emision,
			"fecven" => $fecha_vencimiento,
			"moneda" => isset($data['moneda']) ? $data['moneda'] : 'PEN',
			"tipo_cambio" => isset($data['tipo_cambio']) ? $data['tipo_cambio'] : '3.74',
			"porcentaje_igv" => isset($data['porcentaje_igv']) ? $data['porcentaje_igv'] : '18',
			"total_detraccion" => isset($data['total_detraccion']) ? $data['total_detraccion'] : 0,
			"porcentaje_detraccion" => isset($data['porcentaje_detraccion']) ? $data['porcentaje_detraccion'] : 0,
			"observ" => isset($data['observ']) ? $data['observ'] : '',
			"items" => $items,
			"total_isc" => isset($data['total_isc']) ? $data['total_isc'] : 0,
			"total_igv" => isset($data['total_igv']) ? $data['total_igv'] : 0,
			"total_otros_tributos" => isset($data['total_otros_tributos']) ? $data['total_otros_tributos'] : 0,
			"total_otros_cargos" => isset($data['total_otros_cargos']) ? $data['total_otros_cargos'] : 0,
			"total_ope_inafectas" => isset($data['total_ope_inafectas']) ? $data['total_ope_inafectas'] : 0,
			"total_ope_gravadas" => isset($data['total_ope_gravadas']) ? $data['total_ope_gravadas'] : 0,
			"total_desc" => isset($data['total_desc']) ? $data['total_desc'] : 0,
			"total_ope_exoneradas" => isset($data['total_ope_exoneradas']) ? $data['total_ope_exoneradas'] : 0,
			"total_ope_gratuitas" => isset($data['total_ope_gratuitas']) ? $data['total_ope_gratuitas'] : 0,
			"total" => isset($data['total']) ? $data['total'] : 0,
			"efectivos" => $efectivos,
			"descuento_global" => 0,
			"establecimiento_anexo" => "0012", // Ajustar si necesario
			"estado" => "BO", // Se mantiene constante como "BO"
			"horemi" => isset($data['horemi']) ? $data['horemi'] : '20:20:27',
			"metadata" => array(
				"efectivos" => $efectivos
			)
		);
		//var_dump($payload);
		return $payload;
	}
	
	function enviar_y_guardar_datos($data, $f)
	{
		// Transformar los datos para la API
		$datos_formateados = $this->transformar_datos_api($data);
	
		// Configuración de cURL para enviar a la API
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, 'https://einvoice.conflux.pe/index.php/api/documents/invoice/format/json/');
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_POST, 1);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
			//'X-CONFLUX-API-KEY: 1f3870be274f6c49b3e31a0c6728957f'
			'Authorization: Token xfNxKIW6BjaCTS2CbeEhltuK2X6iIhWL'
		));
	
		
		$data_to_rest = json_encode($datos_formateados);
        $data_base64 = base64_encode($data_to_rest);
        
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "data=".$data_base64."&enviar=1");
        // var_dump($data_to_rest); 
		
		
		//$buffer = curl_exec($curl_handle);
	    $buffer = false;
	    
		//var_dump($buffer);
		if ($buffer === false) {
			// Error en la solicitud
			curl_close($curl_handle);
			return array('status' => 'error', 'message' => 'El servidor no respondió a la solicitud.');
		}
	
		curl_close($curl_handle);
	
		
		// Procesar la respuesta
		$result = json_decode($buffer, true);
	
		if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
			return array('status' => 'error', 'message' => 'Error inesperado en la respuesta del servidor.');
		}
		// Example result
		/*$result = array(
			"data" => array(
				"serie" => "B012",
				"enlace_del_pdf" => "https://einvoice.conflux.pe/cpe/76e2b342-8db9-11ef-8079-0ed722f61213.pdf",
				"enlace" => "https://see.conflux.pe/cpe/76e2b342-8db9-11ef-8079-0ed722f61213/",
				"_id" => 29957047,
				"enlace_del_xml" => "https://einvoice.conflux.pe/cpe/76e2b342-8db9-11ef-8079-0ed722f61213.xml",
				"enlace_del_cdr" => false,
				"digest_value" => "XYAEokig2n5fXYB1PDnml7sfM48=",
				"id" => 29957047,
				"tipo" => "03",
				"codigo_barras_pdf" => "iVBORw0KGgoAAAANSUhEUgAAAcIAAAHCAQAAAABUY/ToAAADeklEQVR4nO2cXYrjMBCES2tDHmWYA+xR5BvskfZq9lFygAH5ccCm9kGtHycZBmYmO3GofghO4g/FINTV1VIc8bmYf30SBESKFClSpEiRIh+PdBZ9+tCN2ByALX8+bA5Y8rvxh3+tyIckA0kyAsDSA1hOdOPSg4wdOS0nAuhIktyTP/FrRT4UiTQtQgQQYpemCuBXcALACR0RuLbfBpKcjvWcIv8fyalc2rzaHGbXt19895gin4t0fyOAcO53n/r1nmOKPDaZc1nSQyWXpRcA8GR6CTHrIeUykTfIOZVfKBkMJoDCuS/LkpVpP/9rRT4UmXJWbXgQeHPEkq/moQPg3xzbu742psjnIlMu45SzFULOVqkagy9JzltxZsSxnlPk/Ui0lk+q6EsJHzumaixwBackrDWHRF5Err/8a2/2oo+WtOYBjkDNYR2BZXD86pgin4vMeig50VuPcHawmbMMcGlypenjduSxnlPk/ci2tk9pzJPVT+RU9JClNvnUIi/D5E1Elj1ozWoySyGT3fKHRF6G6aF57FbMrqMpIx/hgM7s6RA3OPit5zxktXSw5xR5P3K/DpUCPwKtRU3Lb+lFdZnINkpdRjgsA2Cm4tpzHk1EE8sAhPiy65od6zlF3o9s16Ekp4tJFOqmEK5JGeUrrUMim6j7h6q9yAmdedfplgjYLV5zSORl2DqUF5kqhWwLI4Cm66HaXuR15H7ZavteU+vDl+bGtT+kdUjkPmq/LPmJxQuyGiwC7ZTSHBJ5FdfrUNk77YuSjihGo/SQyMuoeiivNEAzfeyWFdUkkh4SeYucB8CNyynPqPOJbkx9WCA19Od0+qxskj3kc4q8A5n1kCdrXWYqCKVz1vTLlMtEXkb2GLvczSjKKPlDNavlK80hke+TPhvTwOYQzuVU2dKbAzm7/ib5+TFFHp5sa3uW/dRWwld/6IrQOiSyBPeBcr4sO4tAbd5LD4m8EXUdQm2VMdam2dpuCpHHKPI9MrCkrGwSOfsDmY6csLndF98ypsgnIxf7XyE35vnCCfn4PZYTMdcjrt80psjnIPuL95z/AA4AGOIAAltv51yXlxXAprNBIj8gw/lkSnr+vSZ3mow5g6m2F3kdSVOHbDTWq+os2gnpqP3UIm/GVV1WWhplM2OzGw2Aeq4i9+H48T03Q/9xLlKkSJEiRYp8EvIfqcJhDUmE1OsAAAAASUVORK5CYII=",
				"numero" => 26
			),
			"status" => "success",
			"message" => "El comprobante numero B012-00000026, ha sido firmado"
		);*/
		//var_dump($result);
		// Verificar si la respuesta es exitosa
		if ($result['status'] !== 'error') {
			// Actualizar los datos en la base de datos con la respuesta
			$update_documento = array(
				'estado' => 'FI',
				'digest_value' => isset($result['data']['digest_value']) ? $result['data']['digest_value'] : '',
				'signature_value' => isset($result['data']['signature_value']) ? $result['data']['signature_value'] : '',
				'codigo_barras' => isset($result['data']['codigo_barras']) ? $result['data']['codigo_barras'] : '',
				'ruta_pdf' => isset($result['data']['ruta_pdf']) ? $result['data']['ruta_pdf'] : '',
				'ruta_xml_firmado' => isset($result['data']['ruta_xml_firmado']) ? $result['data']['ruta_xml_firmado'] : '',
				'ruta_cdr_xml' => isset($result['data']['ruta_cdr_xml']) ? $result['data']['ruta_cdr_xml'] : '',
				'conflux_see_id' => isset($result['data']['id']) ? $result['data']['id'] : '',
				'feccon' => new MongoDate(),
				'autor_con' => $f->session->userDB,
				'numero' => isset($result['data']['numero']) ? $result['data']['numero'] : ''
			);
	
			// Guardar en la base de datos
			$f->model('cj/ecom')->params(array('_id' => new MongoId($data['_id']), 'data' => $update_documento))->save('update');
			// Abrir un link en otra ventana si la respuesta contiene una URL
			//if (isset($result['data']['enlace_del_pdf'])) {
			//	echo "<script type='text/javascript'>window.open('" . $result['data']['enlace_del_pdf'] . "', '_blank');</script>";
			//}
			return array('status' => 'success', 'message' => 'Documento enviado y firmado con éxito.', 'pdf' => $result['data']['enlace_del_pdf']);
		} else {
			return array('status' => 'error', 'message' => isset($result['message']) ? $result['message'] : 'Error desconocido.');
		}
	}


	function execute_details(){
		global $f;
		$f->response->view("cm/terr.details");
	}
	function execute_edit(){
		global $f;
		$f->response->view("cm/terr.edit");
	}
	function execute_ticket(){
		global $f;
		$f->response->view("cm/ticket.edit");
	}
}
?>