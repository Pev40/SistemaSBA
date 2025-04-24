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
$objPHPExcel->getActiveSheet()->setCellValue('I4',$meses[$params["mes"]].", ".$params["ano"] );
$total_dia = 0;
$total_all = 0;
$tmp_dia = date('Y-m-d', $data[0]['fecreg']->sec);
foreach($data as $r => $item) {
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
	
	if($tmp_dia!=date('Y-m-d', $item['fecreg']->sec)){
		foreach($rede as $re => $itemr) {
			if($tmp_dia==$itemr['fec']){
				$row = $baseRow + $r + $rr;
				$nomb = $itemr['entidad']['nomb'];
				if(isset($itemr['entidad']['appat']))
					$nomb = $itemr['entidad']['appat'].' '.$itemr['entidad']['apmat'].', '.$nomb;
				/*if(substr($itemr['total'],strlen($itemr['total'])-3,3)=='.00'){
					$itemr['total'] = substr($itemr['total'],0,strlen($itemr['total'])-3);
				}*/

				$total_parcial = $item['total'];

				if($total_parcial=='0'){
					$total_parcial = 0;
				}
				if($item['estado']=='X'){
					$total_parcial = 0;
					$nomb = 'Anulado';
				}
				
				$itemr['fec'] = date("d/m/Y", strtotime($itemr['fec']));
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $r+$rr+1)
				                              ->setCellValue('D'.$row, $itemr['fec'])
											  ->setCellValue('F'.$row, $tipo)
											  ->setCellValue('G'.$row, 'Rec.')
											  ->setCellValue('H'.$row, $itemr['num'])
											  ->setCellValue('L'.$row, $nomb)
											  ->setCellValue('P'.$row, $total_parcial)
											  ->setCellValue('V'.$row, $total_parcial);
											  //->setCellValue('W'.$row, $itemr['items']["cuenta"]["descr"]);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$row, $tipo, PHPExcel_Cell_DataType::TYPE_STRING);
				$total_dia += floatval($total_parcial);
				$total_all += floatval($total_parcial);
				$rr++;
			}
		}
		$objPHPExcel->getActiveSheet()->setCellValue('AD'.($row), $total_dia);
		$total_dia = 0;
		$tmp_dia = date('Y-m-d', $item['fecreg']->sec);
	}
	$total_parcial = $item['total'];

	if($total_parcial=='0'){
		$total_parcial = 0;
	}
	$row = $baseRow + $r + $rr;
	$nomb = $item['cliente']['nomb'];
	if(isset($item['cliente']['appat']))
		$nomb = $item['cliente']['appat'].' '.$item['cliente']['apmat'].', '.$nomb;
	if($item['estado']=='X'){
		$total_parcial = 0;
		$nomb = 'Anulado';
	}
	//$item['total'] = "'".$item['total'];
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $r+$rr+1)
	                              ->setCellValue('D'.$row, date('d/m/Y', $item['fecreg']->sec))
								  ->setCellValue('F'.$row, $tipo)
								  ->setCellValue('G'.$row, $item['serie'])
								  ->setCellValue('H'.$row, $item['num'])
								  ->setCellValue('L'.$row, $nomb)
								  ->setCellValue('P'.$row, $total_parcial)
								  ->setCellValue('V'.$row, $total_parcial);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$row, $tipo, PHPExcel_Cell_DataType::TYPE_STRING);
	$total_dia += floatval($total_parcial);
	$total_all += floatval($total_parcial);
}

if(isset($rede)){
	foreach($rede as $re => $itemr) {
		if($tmp_dia==$itemr['fec']){
			$row = $baseRow + $r + 1 + $rr;
			$nomb = $itemr['entidad']['nomb'];
			if(isset($itemr['entidad']['appat']))
				$nomb = $itemr['entidad']['appat'].' '.$itemr['entidad']['apmat'].', '.$nomb;
			/*if(substr($itemr['total'],strlen($itemr['total'])-3,3)=='.00'){
				$itemr['total'] = substr($itemr['total'],0,strlen($itemr['total'])-3);
			}*/
			$total_parcial = $item['total'];
			if($total_parcial=='0'){
				$total_parcial = 0;
			}
			if($item['estado']=='X'){
				$total_parcial = 0;
				$nomb = 'Anulado';
			}
			$itemr['fec'] = date("d/m/Y", strtotime($itemr['fec']));
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $r+$rr+2)
										  ->setCellValue('D'.$row, $itemr['fec'])
										  ->setCellValue('F'.$row, $tipo)
										  ->setCellValue('G'.$row, 'Rec.')
										  ->setCellValue('H'.$row, $itemr['num'])
										  ->setCellValue('L'.$row, $nomb)
										  ->setCellValue('P'.$row, $total_parcial)
										  ->setCellValue('V'.$row, $total_parcial);
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$row, $tipo, PHPExcel_Cell_DataType::TYPE_STRING);
			$total_dia += floatval($total_parcial);
			$total_all += floatval($total_parcial);
			$rr++;
		}
	}
}

$objPHPExcel->getActiveSheet()->setCellValue('AD'.($row), $total_dia);

$row++;
$objPHPExcel->getActiveSheet()->setCellValue('L'.$row, 'TOTALES')
                              ->setCellValue('P'.$row, number_format($total_all,2))
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