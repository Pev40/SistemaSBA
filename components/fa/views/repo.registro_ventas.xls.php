<?php
global $f;
date_default_timezone_set('America/Lima');
$f->library('excel');

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load(IndexPath.DS.'templates/cj/registro_ventas.xlsx');
//echo date('H:i:s') , " Add new data to the template" , EOL;
$baseRow = 11;
$index=0;
$rr = 0;
$meses = array('',"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre");
//$objPHPExcel->getActiveSheet()->setCellValue('I4',$meses[$params["mes"]].", ".$params["ano"]);
$objPHPExcel->getActiveSheet()->setCellValue('I4',$meses[floatval($params["mes"])].", ".$params["ano"]);
$total_dia = 0;
$total_all = 0;
$total_all_grv = 0;
$total_all_igv = 0;
	//print_r($data);
if(!isset($data[0]['fecreg'])) $data[0]['fecreg']=$data[0]['fecemi'];
$tmp_dia = date('Y-m-d', $data[0]['fecreg']->sec);
foreach($data as $r => $item){
	//$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
	$tipo = '';
	$inafecta = 0;
	$total = 0;
	/*if(substr($item['total'],strlen($item['total'])-3,3)=='.00'){
		$item['total'] = substr($item['total'],0,strlen($item['total'])-3);
	}*/
	switch($item['tipo']){
		case 'R':
			$tipo = '00';
			break;
	}
	#Para el caso de electronicos, se copiara fecemi a fecreg como parche
	if(!isset($item['fecreg'])) $item['fecreg']=$item['fecemi'];

	if($tmp_dia!=date('Y-m-d', $item['fecreg']->sec)){
		/*
		if(isset($rede)){
			foreach($rede as $re => $itemr) {
				if($tmp_dia==$itemr['fec']){
					$row = $baseRow + $r + $rr;
					$nomb = $itemr['entidad']['nomb'];
					if(isset($itemr['entidad']['appat']))
						$nomb = $itemr['entidad']['appat'].' '.$itemr['entidad']['apmat'].', '.$nomb;
					/*if(substr($itemr['total'],strlen($itemr['total'])-3,3)=='.00'){
						$itemr['total'] = substr($itemr['total'],0,strlen($itemr['total'])-3);
					}*/
					/*
					if($itemr['total']=='0'){
						$itemr['total'] = 0;
					}
					$itemr['fec'] = date("d/m/Y", strtotime($itemr['fec']));
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $r+$rr+1)
					                              ->setCellValue('D'.$row, $itemr['fec'])
												  ->setCellValue('F'.$row, $tipo)
												  ->setCellValue('G'.$row, 'Rec.')
												  ->setCellValue('H'.$row, $itemr['num'])
												  ->setCellValue('L'.$row, $nomb)
												  ->setCellValue('P'.$row, $itemr['total'])
												  ->setCellValue('V'.$row, $itemr['total']);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$row, $tipo, PHPExcel_Cell_DataType::TYPE_STRING);
					$total_dia += floatval($itemr['total']);
					$total_all += floatval($itemr['total']);
					$rr++;
				}
			}
		}
			*/
		///PONE TOTAL DEL DIA
		$objPHPExcel->getActiveSheet()->setCellValue('AD'.($row), $total_dia);
		$total_dia = 0;
		$tmp_dia = date('Y-m-d', $item['fecreg']->sec);
	}
	

	if($item['total']=='0')
		$item['total'] = 0;
	$row = $baseRow + $r + $rr;

	/** 
	*	A PARTIR DE AQUI SE APLICA TODO LO REALACIONADO A FACTURACION ELECTRONICA
	*/
	#REALIZAR NOMBRE DE COMPROBANTES ELECTRONICOS
	if(isset($item['cliente']['nomb'])){
		$nomb = $item['cliente']['nomb'];
	}else{
		$nomb = $item['cliente_nomb'];
	}

	$doc = '';
	$ope_gravada = -10;
	$igv = 0;
	/*
	if(isset($item['cliente']['nomb'])){
		$nomb = $item['cliente']['nomb'];
	}
	*/
	if(isset($item['tipo'])){
		$tipo = $item['tipo'];
		$doc = $item['cliente_doc'];
	}
	///OBTIENE TOTAL GRAVADA E IGV
	if(isset($item['total_ope_gravadas'])){
		$ope_gravada = $item['total_ope_gravadas'];
		$igv = $item['total_igv'];
	}
	/*
	if(isset($item['cliente']['appat']))
		$nomb = $item['cliente']['appat'].' '.$item['cliente']['apmat'].', '.$nomb;
	*/
	if($item['estado']=='X'){
		$item['total'] = 0;
		$nomb = 'Anulado';
		$tipo = '';
		$doc = '';
		$ope_gravada = 0;
		$igv = 0;
		$item['tipo_doc']='';
	}

	if(!isset($item['num'])){
		$item['num'] = floatval($item['numero']);
	}
	//AQUI IMPRIME EN DOCUMENTO
	//$item['total'] = "'".$item['total'];
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $r+$rr+1)
	                              ->setCellValue('D'.$row, date('d/m/Y', $item['fecreg']->sec))
								  //->setCellValue('F'.$row, $tipo)
								  //->setCellValue('F'.$row, $item['tipo'])
								  ->setCellValue('G'.$row, $item['serie'])
								  ->setCellValue('H'.$row, $item['num'])
								  ->setCellValue('J'.$row, $item['tipo_doc'])
								  //->setCellValue('K'.$row, $item['cliente_doc'])
								  ->setCellValue('L'.$row, $nomb)
								  ->setCellValue('N'.$row, $ope_gravada)
								  ->setCellValue('R'.$row, $igv)
								  ->setCellValue('V'.$row, $item['total']);
								  //->setCellValue('V'.$row, $item['total']);
	//$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$row, $tipo, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$row, $tipo, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$row, $doc, PHPExcel_Cell_DataType::TYPE_STRING);
	$total_dia += floatval($item['total']);
	$total_all += floatval($item['total']);

	$total_all_grv += floatval($ope_gravada);
	$total_all_igv += floatval($igv);
}
/*
if(isset($rede)){
	foreach($rede as $re => $itemr) {
		if($tmp_dia==$itemr['fec']){
			$row = $baseRow + $r + 1 + $rr;
			$nomb = $itemr['entidad']['nomb'];
			if(isset($itemr['entidad']['appat']))
				$nomb = $itemr['entidad']['appat'].' '.$itemr['entidad']['apmat'].', '.$nomb;
			/*if(substr($itemr['total'],strlen($itemr['total'])-3,3)=='.00'){
				$itemr['total'] = substr($itemr['total'],0,strlen($itemr['total'])-3);
			}*//*
			if($itemr['total']=='0'){
				$itemr['total'] = 0;
			}
			$itemr['fec'] = date("d/m/Y", strtotime($itemr['fec']));
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $r+$rr+2)
			                              ->setCellValue('D'.$row, $itemr['fec'])
										  ->setCellValue('F'.$row, $tipo)
										  ->setCellValue('G'.$row, 'Rec.')
										  ->setCellValue('H'.$row, $itemr['num'])
										  ->setCellValue('L'.$row, $nomb)
										  ->setCellValue('P'.$row, $itemr['total'])
										  ->setCellValue('V'.$row, $itemr['total']);
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$row, $tipo, PHPExcel_Cell_DataType::TYPE_STRING);
			$total_dia += floatval($itemr['total']);
			$total_all += floatval($itemr['total']);
			$rr++;
		}
	}
}
*/

$objPHPExcel->getActiveSheet()->setCellValue('AD'.($row), $total_dia);

$row++;
$objPHPExcel->getActiveSheet()->setCellValue('L'.$row, 'TOTALES')
                              //->setCellValue('P'.$row, number_format($total_all,2))
							  //->setCellValue('V'.$row, number_format($total_all,2))
							  ->setCellValue('N'.$row, number_format($total_all_grv,2))
							  ->setCellValue('R'.$row, number_format($total_all_igv,2))
							  ->setCellValue('V'.$row, number_format($total_all,2))
							  ->setCellValue('AD'.$row, number_format($total_all,2));
$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FF0000'),
        'size'  => 12,
        'name'  => 'Verdana'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    )
);
$objPHPExcel->getActiveSheet()->getStyle('L'.$row)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('P'.$row)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('V'.$row)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('AD'.$row)->applyFromArray($styleArray);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Registro de Ventas '.$params['ano'].'-'.$params['mes'].'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
$objWriter->save('php://output');
?>