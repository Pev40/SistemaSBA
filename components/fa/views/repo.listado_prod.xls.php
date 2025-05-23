<?php
global $f;
date_default_timezone_set('America/Lima');
$f->library('excel');

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load(IndexPath.DS.'templates/fa/listado_prod.xlsx');
$objPHPExcel->getActiveSheet()->setCellValue('D4',date('Y-m-d H:i'));
$row = 7;
foreach($data as $r => $item) {
	if(!isset($item['generico'])) $item['generico']="";
	if(!isset($item['stock'])){
		if(empty($item['stock'])){
			$stck_key = array_search($item['_id']->{'$id'}, array_column($stock, 'producto')); // $clave = 2;
			$item['stock']=floatval($stock[$stck_key]['stock']);
				//var_dump($stck_key);
				//echo '<br>';
				//var_dump($stock[$stck_key]['stock']);
				//echo '<br>';
				//print_r($item['nomb']);
				//echo '<br>';
			if(is_null($stck_key) || !isset($stck_key) || is_null($item['stock']) || !isset($item['stock']) || $stck_key===false){
				//print_r($item['nomb']);
				//var_dump($stock[$stck_key]['stock']);
				//var_dump($stck_key);
				//print_r($item);
				//print_r($stock);
				//die();
			}
		}
	} 
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $item['_id']->{'$id'})
	                              ->setCellValue('C'.$row, $item['cod'])
								  ->setCellValue('D'.$row, $item['nomb'])
								  ->setCellValue('E'.$row, $item['generico'])
								  ->setCellValue('F'.$row, $item['stock'])
								  ->setCellValue('G'.$row, $item['precio_venta']);
	$row++;
}
//die();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Listado de Productos al '.date('Y-m-d H:i').'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
$objWriter->save('php://output');
?>