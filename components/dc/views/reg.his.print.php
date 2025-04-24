<?php
global $f;
$f->library('pdf');

class repo extends FPDF
{
	
	function Header(){
		$this->Image(IndexPath.DS.'templates/dc/DocDoor.jpg', 8, 8, 60, 10);
        $y=10;
		$this->SetFont('Arial','B',9);
		//$this->SetXY(10,5);$this->MultiCell(60,5,"SOCIEDAD DE BENEFICENCIA PUBLICA DE AREQUIPA",'0','C');		
		$this->SetXY(10,5);$this->MultiCell(190,5,date("d/m/Y")." Página: ".$this->PageNo()."/{nb}",'0','R');
		$this->SetFont('Arial','',9);
		//$this->SetXY(10,15);$this->MultiCell(60,5,"Centro de Salud Mental",'0','C');
		//$this->SetXY(10,20);$this->MultiCell(60,5,"'Moises Heresi'",'0','C');
		$this->SetFont('Arial','B',16);
		
		$y=$y+10;
		

		
		$y=$y+5;
		
		$y=$y+5;
	}
	function Publicar($doc){
		$sexo = array(
			"0"=>"Femenino",
			"1"=>"Masculino"
		);
		$prin_cod = '';
		$prin_nomb = '';
		$x=5;
		$y=35;
		$y_ini = $y;
		$page_b = 275;
		$this->SetXY(5,25);$this->MultiCell(200,5,"CONSULTA MEDICA",'0','C');
        if($doc['tipo'] == 'CP'){
            $this->SetFont('Arial','B',10);
            $this->SetXY(10,35);$this->MultiCell(60,8,"1.- DATOS PERSONALES",'0','L');
            $y = $y+10;
            $this->SetFont('Arial','B',10);
            $this->SetXY(10,$y);$this->MultiCell(17,8,"Nombre:",'0','L');
            $this->SetFont('Arial','U',10);
            $this->SetXY(27,$y);$this->MultiCell(80,8,"".$doc['paciente']['nomb'].' '.$doc['paciente']['appat'].' '.$doc['paciente']['apmat'],'0','L');
            $this->SetFont('Arial','B',10);
            $this->SetXY(107,$y);$this->MultiCell(17,8,"Edad:",'0','L');
            $this->SetFont('Arial','U',10);
            $this->SetXY(124,$y);$this->MultiCell(6,8,"".$doc['edad'],'0','L');
            $this->SetFont('Arial','B',10);
            $this->SetXY(130,$y);$this->MultiCell(17,8,"Sexo:",'0','L');
            $this->SetFont('Arial','U',10);
            $this->SetXY(147,$y);$this->MultiCell(20,8,"".$sexo[$doc['sexo']],'0','L');
			$y = $y+10;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(17,8,"ID/CE:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(27,$y);$this->MultiCell(20,8,"".$doc['paciente']['docident'][0]['num'],'0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(47,$y);$this->MultiCell(20,8,"Empresa:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(67,$y);$this->MultiCell(100,8,"".$doc['empresa']['nomb'],'0','L');
			$y = $y+10;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(35,8,"Fecha de Atencion:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(45,$y);$this->MultiCell(22,8,"".date('d-m-Y',$doc["fecreg"]->sec),'0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(67,$y);$this->MultiCell(20,8,"Alergias	:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(87,$y);$this->MultiCell(100,8,"".$doc['aler'],'0','L');
			$y = $y+10;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"2.- FUNCIONES VITALES",'0','L');
			$y = $y+10;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(14,8,"PESO: ",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(24,$y);$this->MultiCell(20,8,"".$doc['funciones']['peso'].' .KG','0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(44,$y);$this->MultiCell(16,8,"TALLA: ",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(60,$y);$this->MultiCell(20,8,"".$doc['funciones']['talla'].' .M','0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(80,$y);$this->MultiCell(16,8,"IMC: ",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(96,$y);$this->MultiCell(28,8,"".$doc['funciones']['imc'].' .Kg/m2','0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(124,$y);$this->MultiCell(12,8,"T°: ",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(136,$y);$this->MultiCell(20,8,"".$doc['funciones']['temp'].' .C°','0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(156,$y);$this->MultiCell(20,8,"Sat02: ",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(176,$y);$this->MultiCell(28,8,"".$doc['funciones']['satu'].' .%','0','L');
			$y = $y+10;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(14,8,"P.A: ",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(24,$y);$this->MultiCell(20,8,"".$doc['funciones']['presa'].' .mmH','0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(44,$y);$this->MultiCell(16,8,"FC: ",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(60,$y);$this->MultiCell(20,8,"".$doc['funciones']['frec'].' .lpm','0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(80,$y);$this->MultiCell(16,8,"FR: ",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(96,$y);$this->MultiCell(28,8,"".$doc['funciones']['fecr'].' .rpm','0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(124,$y);$this->MultiCell(12,8,"T.E.: ",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(136,$y);$this->MultiCell(20,8,"".$doc['funciones']['tien'],'0','L');
			$y = $y+10;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"3.- MOTIVO DE LA CONSULTA",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,6,"".$doc['moti'],'1','L');
			$y = $y+18;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"4.- ANTECEDENTES",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,5,"".$doc['ante'],'1','L');
			$y = $y+20;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"5.- ANANMESIS",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,5,"".$doc['anan'],'1','L');
			$y = $y+16;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"6.- EXAMEN FISICO DIRIGIDO",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,5,"".$doc['exfi'],'1','L');
			$y = $y+20;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"7.- EXAMENES AUXILIARES",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,5,"".$doc['exau'],'1','L');
			$y = $y+16;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"8.- IMPRESION DIAGNOSTICA",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(20,8,"CIE-10",'1','C');
			$this->SetXY(30,$y);$this->MultiCell(120,8,"Diagnostico",'1','C');
			$this->SetXY(150,$y);$this->MultiCell(30,8,"Clasificacion",'1','C');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			if(isset($doc['diag'])){
				for($i=0;$i<count($doc['diag']);$i++){
					$this->SetXY(10,$y);$this->MultiCell(20,8,"".$doc['diag'][$i]['cie10']['sigl'],'1','C');
					$this->SetXY(30,$y);$this->MultiCell(120,8,"".$doc['diag'][$i]['cie10']['nomb'],'1','L');
					$this->SetXY(150,$y);$this->MultiCell(30,8,"".$doc['diag'][$i]['clasi'],'1','C');
					$y = $y+8;	
				}
				
			}
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"9.- INDICACIONES",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,5,"".$doc['indi'],'1','L');
			
        }else{
			$this->SetFont('Arial','B',10);
            $this->SetXY(10,35);$this->MultiCell(60,8,"1.- DATOS PERSONALES",'0','L');
            $y = $y+10;
            $this->SetFont('Arial','B',10);
            $this->SetXY(10,$y);$this->MultiCell(17,8,"Nombre:",'0','L');
            $this->SetFont('Arial','U',10);
            $this->SetXY(27,$y);$this->MultiCell(80,8,"".$doc['paciente']['nomb'].' '.$doc['paciente']['appat'].' '.$doc['paciente']['apmat'],'0','L');
            $this->SetFont('Arial','B',10);
            $this->SetXY(107,$y);$this->MultiCell(17,8,"Edad:",'0','L');
            $this->SetFont('Arial','U',10);
            $this->SetXY(124,$y);$this->MultiCell(6,8,"".$doc['edad'],'0','L');
            $this->SetFont('Arial','B',10);
            $this->SetXY(130,$y);$this->MultiCell(17,8,"Sexo:",'0','L');
            $this->SetFont('Arial','U',10);
            $this->SetXY(147,$y);$this->MultiCell(20,8,"".$sexo[$doc['sexo']],'0','L');
			$y = $y+10;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(17,8,"ID/CE:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(27,$y);$this->MultiCell(20,8,"".$doc['paciente']['docident'][0]['num'],'0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(47,$y);$this->MultiCell(20,8,"Empresa:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(67,$y);$this->MultiCell(100,8,"".$doc['empresa']['nomb'],'0','L');
			$y = $y+10;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(35,8,"Fecha de Atencion:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(45,$y);$this->MultiCell(22,8,"".date('d-m-Y',$doc["fecreg"]->sec),'0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(67,$y);$this->MultiCell(20,8,"Alergias	:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(87,$y);$this->MultiCell(100,8,"".$doc['aler'],'0','L');
			$y = $y+10;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"2.- MOTIVO DE LA CONSULTA",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,6,"".$doc['moti'],'1','L');
			$y = $y+18;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"3.- ANTECEDENTES",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,5,"".$doc['ante'],'1','L');
			$y = $y+20;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"4.- ANANMESIS",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,5,"".$doc['anan'],'1','L');
			$y = $y+16;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(80,8,"5.- EVALUACION POR TELECONSULTA",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,5,"".$doc['evte'],'1','L');
			$y = $y+20;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"6.- EXAMENES AUXILIARES",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,5,"".$doc['exau'],'1','L');
			$y = $y+16;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"7.- IMPRESION DIAGNOSTICA",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(20,8,"CIE-10",'1','C');
			$this->SetXY(30,$y);$this->MultiCell(120,8,"Diagnostico",'1','C');
			$this->SetXY(150,$y);$this->MultiCell(30,8,"Clasificacion",'1','C');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			if(isset($doc['diag'])){
				for($i=0;$i<count($doc['diag']);$i++){
					$this->SetXY(10,$y);$this->MultiCell(20,8,"".$doc['diag'][$i]['cie10']['sigl'],'1','C');
					$this->SetXY(30,$y);$this->MultiCell(120,8,"".$doc['diag'][$i]['cie10']['nomb'],'1','L');
					$this->SetXY(150,$y);$this->MultiCell(30,8,"".$doc['diag'][$i]['clasi'],'1','C');
					$y = $y+8;	
				}
				
			}
			$this->SetFont('Arial','B',10);
			$this->SetXY(10,$y);$this->MultiCell(60,8,"8.- INDICACIONES",'0','L');
			$y = $y+8;
			$this->SetFont('Arial','',10);
			$this->SetXY(10,$y);$this->MultiCell(180,5,"".$doc['indi'],'1','L');
        }
        
		if(isset($doc['receta'])){
			$this->AddPage();
			
				//$this->Image(IndexPath.DS.'templates/dc/DocDoor.jpg', 10, 10, 63, 13);
				$y=10;
				$this->SetFont('Arial','B',9);
				//$this->SetXY(10,5);$this->MultiCell(60,5,"SOCIEDAD DE BENEFICENCIA PUBLICA DE AREQUIPA",'0','C');		
				$this->SetXY(10,5);$this->MultiCell(190,5,date("d/m/Y")." Página: ".$this->PageNo()."/{nb}",'0','R');
				$this->SetFont('Arial','',9);
				//$this->SetXY(10,15);$this->MultiCell(60,5,"Centro de Salud Mental",'0','C');
				//$this->SetXY(10,20);$this->MultiCell(60,5,"'Moises Heresi'",'0','C');
				$this->SetFont('Arial','B',16);
				$this->SetXY(5,25);$this->MultiCell(200,5,"RECETA MEDICA",'0','C');
				$y=$y+10;
				
				$sexo = array(
					"0"=>"Femenino",
					"1"=>"Masculino"
				);
		
				$x=5;
				$y=35;
				$y_ini = $y;
				$page_b = 275;
				
				
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,35);$this->MultiCell(60,8,"1.- DATOS PERSONALES",'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(17,8,"Nombre:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(27,$y);$this->MultiCell(80,8,"".$doc['paciente']['nomb'].' '.$doc['paciente']['appat'].' '.$doc['paciente']['apmat'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(107,$y);$this->MultiCell(17,8,"Edad:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(124,$y);$this->MultiCell(6,8,"".$doc['edad'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(130,$y);$this->MultiCell(17,8,"Sexo:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(147,$y);$this->MultiCell(20,8,"".$sexo[$doc['sexo']],'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(17,8,"ID/CE:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(27,$y);$this->MultiCell(20,8,"".$doc['paciente']['docident'][0]['num'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(47,$y);$this->MultiCell(20,8,"Empresa:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(67,$y);$this->MultiCell(100,8,"".$doc['empresa']['nomb'],'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(35,8,"Fecha de Atencion:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(45,$y);$this->MultiCell(22,8,"".date('d-m-Y',$doc["fecreg"]->sec),'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(67,$y);$this->MultiCell(20,8,"Alergias	:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(87,$y);$this->MultiCell(100,8,"".$doc['aler'],'0','L');
					$y = $y+10;
					$this->SetXY(20,$y);$this->MultiCell(50,5,"Medicamento",'1','C'); 
					$this->SetXY(70,$y);$this->MultiCell(17,5,"Cantidad",'1','C');
					$this->SetXY(87,$y);$this->MultiCell(30,5,"Vía",'1','C');
					$this->SetXY(117,$y);$this->MultiCell(17,5,"Tiempo",'1','C');
					$this->SetXY(134,$y);$this->MultiCell(20,5,"Periodo",'1','C');
					$this->SetXY(154,$y);$this->MultiCell(20,5,"Dosis",'1','C');
					$this->SetXY(174,$y);$this->MultiCell(20,5,"Toma",'1','C');
					
					$y=$y+5;
					for($i = 0;$i<count($doc["receta"]);$i++){
						$this->SetXY(20,$y);$this->MultiCell(50,5,$doc["receta"][$i]["medicamento"],'1','C');
						$this->SetFont('Arial','U',10);
						$this->SetXY(70,$y);$this->MultiCell(17,5,$doc["receta"][$i]["cant"],'1','C');
						$this->SetFont('Arial','U',8);
						$this->SetXY(87,$y);$this->MultiCell(30,5,$doc["receta"][$i]["via"],'1','C');
						$this->SetFont('Arial','U',10);
						$this->SetXY(117,$y);$this->MultiCell(17,5,$doc["receta"][$i]["tiem"],'1','C');
						$this->SetFont('Arial','U',8);
						$this->SetXY(134,$y);$this->MultiCell(20,5,$doc["receta"][$i]["peri"],'1','C');
						$this->SetXY(154,$y);$this->MultiCell(20,5,$doc["receta"][$i]["dosi"],'1','C');
						$this->SetXY(174,$y);$this->MultiCell(20,5,$doc["receta"][$i]["admi"],'1','C');

						/*
								$row.find('[name=indi]').val(data.receta[i].indi);
								$row.find('[name=cant]').val(data.receta[i].cant);
								$row.find('[name=via]').val(data.receta[i].via);
								$row.find('[name=tiem]').val(data.receta[i].tiem);
								$row.find('[name=peri]').val(data.receta[i].peri);
								$row.find('[name=dosi]').val(data.receta[i].dosi);
								$row.find('[name=admi]').val(data.receta[i].admi);
							}
						*/

						$y=$y+5;
						
					}
					
					
		}

		if(isset($doc['inter'])){
			$this->AddPage();
			
				//$this->Image(IndexPath.DS.'templates/dc/DocDoor.jpg', 10, 10, 63, 13);
				$y=10;
				$this->SetFont('Arial','B',9);
				//$this->SetXY(10,5);$this->MultiCell(60,5,"SOCIEDAD DE BENEFICENCIA PUBLICA DE AREQUIPA",'0','C');		
				$this->SetXY(10,5);$this->MultiCell(190,5,date("d/m/Y")." Página: ".$this->PageNo()."/{nb}",'0','R');
				$this->SetFont('Arial','',9);
				//$this->SetXY(10,15);$this->MultiCell(60,5,"Centro de Salud Mental",'0','C');
				//$this->SetXY(10,20);$this->MultiCell(60,5,"'Moises Heresi'",'0','C');
				$this->SetFont('Arial','B',16);
				$this->SetXY(5,25);$this->MultiCell(200,5,"INTERCONSULTA",'0','C');
				$y=$y+10;
				
				$sexo = array(
					"0"=>"Femenino",
					"1"=>"Masculino"
				);
		
				$x=5;
				$y=35;
				$y_ini = $y;
				$page_b = 275;
				
				
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,35);$this->MultiCell(60,8,"1.- DATOS PERSONALES",'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(17,8,"Nombre:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(27,$y);$this->MultiCell(80,8,"".$doc['paciente']['nomb'].' '.$doc['paciente']['appat'].' '.$doc['paciente']['apmat'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(107,$y);$this->MultiCell(17,8,"Edad:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(124,$y);$this->MultiCell(6,8,"".$doc['edad'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(130,$y);$this->MultiCell(17,8,"Sexo:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(147,$y);$this->MultiCell(20,8,"".$sexo[$doc['sexo']],'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(17,8,"ID/CE:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(27,$y);$this->MultiCell(20,8,"".$doc['paciente']['docident'][0]['num'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(47,$y);$this->MultiCell(20,8,"Empresa:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(67,$y);$this->MultiCell(100,8,"".$doc['empresa']['nomb'],'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(35,8,"Fecha de Atencion:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(45,$y);$this->MultiCell(22,8,"".date('d-m-Y',$doc["fecreg"]->sec),'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(120,8,"2.- INTERCONSULTA CON LA ESPECIALES DE: ",'0','L');
					$y = $y+10;
					$this->SetFont('Arial','',10);
					$this->SetXY(67,$y);$this->MultiCell(100,8,"".$doc['inter'],'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(120,8,"3.- MOTIVO DE LA INTERCONSULTA: ",'0','L');
					$y = $y+10;
					$this->SetFont('Arial','',10);
					$this->SetXY(67,$y);$this->MultiCell(120,8,"".$doc['moir'],'1','L');
		}

		if(isset($doc['laboratorio'])){
			$this->AddPage();
			
				//$this->Image(IndexPath.DS.'templates/dc/DocDoor.jpg', 10, 10, 63, 13);
				$y=10;
				$this->SetFont('Arial','B',9);
				//$this->SetXY(10,5);$this->MultiCell(60,5,"SOCIEDAD DE BENEFICENCIA PUBLICA DE AREQUIPA",'0','C');		
				$this->SetXY(10,5);$this->MultiCell(190,5,date("d/m/Y")." Página: ".$this->PageNo()."/{nb}",'0','R');
				$this->SetFont('Arial','',9);
				//$this->SetXY(10,15);$this->MultiCell(60,5,"Centro de Salud Mental",'0','C');
				//$this->SetXY(10,20);$this->MultiCell(60,5,"'Moises Heresi'",'0','C');
				$this->SetFont('Arial','B',16);
				$this->SetXY(5,25);$this->MultiCell(200,5,"ORDEN DE LABORATORIO",'0','C');
				$y=$y+10;
				
				$sexo = array(
					"0"=>"Femenino",
					"1"=>"Masculino"
				);
		
				$x=5;
				$y=35;
				$y_ini = $y;
				$page_b = 275;
				
				
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,35);$this->MultiCell(60,8,"1.- DATOS PERSONALES",'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(17,8,"Nombre:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(27,$y);$this->MultiCell(80,8,"".$doc['paciente']['nomb'].' '.$doc['paciente']['appat'].' '.$doc['paciente']['apmat'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(107,$y);$this->MultiCell(17,8,"Edad:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(124,$y);$this->MultiCell(6,8,"".$doc['edad'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(130,$y);$this->MultiCell(17,8,"Sexo:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(147,$y);$this->MultiCell(20,8,"".$sexo[$doc['sexo']],'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(17,8,"ID/CE:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(27,$y);$this->MultiCell(20,8,"".$doc['paciente']['docident'][0]['num'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(47,$y);$this->MultiCell(20,8,"Empresa:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(67,$y);$this->MultiCell(100,8,"".$doc['empresa']['nomb'],'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(35,8,"Fecha de Atencion:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(45,$y);$this->MultiCell(22,8,"".date('d-m-Y',$doc["fecreg"]->sec),'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(60,8,"2.- DATOS DE LA ORDEN",'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(60,8,"Fecha de Emision de Orden:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(70,$y);$this->MultiCell(22,8,"".date('d-m-Y',$doc["femi"]->sec),'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(120,8,"3.- EXAMENES DE LABORATORIO A ORDENAR",'0','L');
					$y = $y+10;
					$this->SetXY(20,$y);$this->MultiCell(80,5,"Nro. de Examen",'1','C'); 
					$this->SetXY(100,$y);$this->MultiCell(90,5,"Examen de Laboratorio",'1','C');
					$y=$y+5;
					if(isset($doc["laboratorio"])){
						$this->SetFont('Arial','',10);
						for($j = 0;$j<count($doc["laboratorio"]);$j++){
							$this->SetXY(20,$y);$this->MultiCell(80,5,$j+1,'1','C');
							$this->SetXY(100,$y);$this->MultiCell(90,5,$doc["laboratorio"][$j]["examen"],'1','C');
							$y=$y+5;
							
						}
					}
		}
		if(isset($doc['examenes'])){
			$this->AddPage();
			
				//$this->Image(IndexPath.DS.'templates/dc/DocDoor.jpg', 10, 10, 63, 13);
				$y=10;
				$this->SetFont('Arial','B',9);
				//$this->SetXY(10,5);$this->MultiCell(60,5,"SOCIEDAD DE BENEFICENCIA PUBLICA DE AREQUIPA",'0','C');		
				$this->SetXY(10,5);$this->MultiCell(190,5,date("d/m/Y")." Página: ".$this->PageNo()."/{nb}",'0','R');
				$this->SetFont('Arial','',9);
				//$this->SetXY(10,15);$this->MultiCell(60,5,"Centro de Salud Mental",'0','C');
				//$this->SetXY(10,20);$this->MultiCell(60,5,"'Moises Heresi'",'0','C');
				$this->SetFont('Arial','B',16);
				$this->SetXY(5,25);$this->MultiCell(200,5,"ORDEN DE EXAMEN AUXILIAR",'0','C');
				$y=$y+10;
				
				$sexo = array(
					"0"=>"Femenino",
					"1"=>"Masculino"
				);
		
				$x=5;
				$y=35;
				$y_ini = $y;
				$page_b = 275;
				
				
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,35);$this->MultiCell(60,8,"1.- DATOS PERSONALES",'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(17,8,"Nombre:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(27,$y);$this->MultiCell(80,8,"".$doc['paciente']['nomb'].' '.$doc['paciente']['appat'].' '.$doc['paciente']['apmat'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(107,$y);$this->MultiCell(17,8,"Edad:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(124,$y);$this->MultiCell(6,8,"".$doc['edad'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(130,$y);$this->MultiCell(17,8,"Sexo:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(147,$y);$this->MultiCell(20,8,"".$sexo[$doc['sexo']],'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(17,8,"ID/CE:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(27,$y);$this->MultiCell(20,8,"".$doc['paciente']['docident'][0]['num'],'0','L');
					$this->SetFont('Arial','B',10);
					$this->SetXY(47,$y);$this->MultiCell(20,8,"Empresa:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(67,$y);$this->MultiCell(100,8,"".$doc['empresa']['nomb'],'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(35,8,"Fecha de Atencion:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(45,$y);$this->MultiCell(22,8,"".date('d-m-Y',$doc["fecreg"]->sec),'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(60,8,"2.- REFERENCIAS DEL MEDICO",'0','L');
					$y = $y+10;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(60,8,"REFERENCIA:",'0','L');
					$this->SetFont('Arial','U',10);
					$this->SetXY(70,$y);$this->MultiCell(100,8,"".$doc['refe'],'0','L');
					$y = $y+20;
					$this->SetFont('Arial','B',10);
					$this->SetXY(10,$y);$this->MultiCell(120,8,"3.- EXAMENES AUXILIARES",'0','L');
					$y = $y+10;
					$this->SetXY(20,$y);$this->MultiCell(20,5,"Nro.",'1','C'); 
					$this->SetXY(40,$y);$this->MultiCell(90,5,"Examen Auxiliar",'1','C');
					$this->SetXY(130,$y);$this->MultiCell(50,5,"Observacion",'1','C');
					$y=$y+5;
					if(isset($doc["examenes"])){
						$this->SetFont('Arial','',10);
						for($j = 0;$j<count($doc["examenes"]);$j++){
							$this->SetXY(20,$y);$this->MultiCell(20,5,$j+1,'1','C');
							$this->SetXY(40,$y);$this->MultiCell(90,5,$doc["examenes"][$j]["aux"],'1','C');
							$this->SetXY(130,$y);$this->MultiCell(50,5,$doc["examenes"][$j]["obs"],'1','C');
							$y=$y+5;
							
						}
					}
		}
		if(isset($doc['cons'])){
			if($doc['cons'] == 'SI'){
				$this->AddPage();
			
				//$this->Image(IndexPath.DS.'templates/dc/DocDoor.jpg', 10, 10, 63, 13);
				$y=10;
				$this->SetFont('Arial','B',9);
				//$this->SetXY(10,5);$this->MultiCell(60,5,"SOCIEDAD DE BENEFICENCIA PUBLICA DE AREQUIPA",'0','C');		
				$this->SetXY(10,5);$this->MultiCell(190,5,date("d/m/Y")." Página: ".$this->PageNo()."/{nb}",'0','R');
				$this->SetFont('Arial','',9);
				//$this->SetXY(10,15);$this->MultiCell(60,5,"Centro de Salud Mental",'0','C');
				//$this->SetXY(10,20);$this->MultiCell(60,5,"'Moises Heresi'",'0','C');
				$this->SetFont('Arial','B',16);
				$this->SetXY(5,30);$this->MultiCell(200,5,"CONSTANCIA DE ATENCION",'0','C');
				$y=$y+10;
						
				$x=5;
				$y=35;
				$y_ini = $y;
				$this->SetFont('Arial','',13);
				$y = $y+10;
				$this->SetXY(10,$y);$this->MultiCell(120,8,"Constancia de Atencion de: ".$doc['hist'].''.$doc['paciente']['docident'][0]['num'],'0','L');
				$y = $y+10;
				$this->MultiCell(195, 8, "Mediante el presente documento se deja constancia de la atención por ".$doc['pref']. ' ' . strtoupper($doc['autor']['nomb']) . ' '
				. strtoupper($doc['autor']['appat']) . ' ' . strtoupper($doc['autor']['apmat']) . ', brindada al paciente ' . $doc['paciente']['nomb'] . ' ' . $doc['paciente']['appat'] . ' '
				. $doc['paciente']['apmat'] . ' de ' . $doc['edad'] . ' años, identificado con DNI/CE N°. ' . $doc['paciente']['docident'][0]['num'], '0', 'J');
				$y = $y+35;
				$this->SetXY(10,$y);$this->MultiCell(195,8,"Se emite el presente documento a favor del paciente, para los trámites que considere por conveniente.",'0','J');
				$y = $y+35;
				date_default_timezone_set('America/Lima');
				$nombre_dias = array(
					'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'
				);
				// Definir los nombres de los meses en español
				$nombre_meses = array(
					'', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
					'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
				);
				
				$fecha_actual = date('d') . ' de ' . $nombre_meses[date('n')] . ' de ' . date('Y');

				$this->SetXY(10, $y);
				$this->MultiCell(195, 8, "Arequipa, " . $fecha_actual, '0', 'C');

				$this->Image(IndexPath.DS.'/tmp/'.$doc['firma'], 80, 160, 70, 20);
				$y = $y+40;
				$this->SetXY(10, $y);
				$this->MultiCell(195, 8, "_________________________________", '0', 'C');
				$autor = strtoupper($doc['autor']['nomb']) . ' '. strtoupper($doc['autor']['appat']) . ' ' . strtoupper($doc['autor']['apmat']);
				$y = $y+8;
				$this->SetXY(10, $y);
				$this->MultiCell(195, 8, $autor, '0', 'C');


			}
			
		}
		if(isset($doc['descanso'])){

			$this->AddPage();
			
			//$this->Image(IndexPath.DS.'templates/dc/DocDoor.jpg', 10, 10, 63, 13);
			$y=10;
			$this->SetFont('Arial','B',9);
			//$this->SetXY(10,5);$this->MultiCell(60,5,"SOCIEDAD DE BENEFICENCIA PUBLICA DE AREQUIPA",'0','C');		
			$this->SetXY(10,5);$this->MultiCell(190,5,date("d/m/Y")." Página: ".$this->PageNo()."/{nb}",'0','R');
			$this->SetFont('Arial','',9);
			//$this->SetXY(10,15);$this->MultiCell(60,5,"Centro de Salud Mental",'0','C');
			//$this->SetXY(10,20);$this->MultiCell(60,5,"'Moises Heresi'",'0','C');
			$this->SetFont('Arial','B',16);
			$this->SetXY(5,50);$this->MultiCell(200,5,"DESCANSO MEDICO",'0','C');
			$y=$y+10;
					
			$x=5;
			$y=13;
			$y_ini = $y;
			$this->SetFont('Arial','',13);
			$this->SetFont('Arial','B',10);
			$this->SetXY(79,$y);$this->MultiCell(18,8,"Paciente:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(97,$y);$this->MultiCell(80,8,"".$doc['paciente']['nomb'].' '.$doc['paciente']['appat'].' '.$doc['paciente']['apmat'],'0','L');
			$y=$y+8;
			$this->SetFont('Arial','B',10);
			$this->SetXY(79,$y);$this->MultiCell(18,8,"NHC:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(97,$y);$this->MultiCell(80,8,"".$doc['hist'],'0','L');
			$y=$y+8;
			$this->SetFont('Arial','B',10);
			$this->SetXY(79,$y);$this->MultiCell(18,8,"DNI:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(97,$y);$this->MultiCell(18,8,"".$doc['paciente']['docident'][0]['num'],'0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(130,$y);$this->MultiCell(18,8,"SEXO:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(148,$y);$this->MultiCell(18,8,"".$sexo[$doc['sexo']],'0','L');
			/*$y=$y+8;
			$this->SetFont('Arial','B',10);
			$this->SetXY(79,$y);$this->MultiCell(18,8,"F. NAC:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(97,$y);$this->MultiCell(80,8,"".$doc['hist'],'0','L');*/
			$y = $y+30;
			$this->SetXY(10,$y);
			
			if(isset($doc['diag'])){
				for($i=0;$i<count($doc['diag']);$i++){
					if($doc['diag'][$i]['prin'] == 'P'){
						$prin_cod = $doc['diag'][$i]['cie10']['sigl'];
						$prin_nomb = $doc['diag'][$i]['cie10']['nomb'];
					}
					
				}
				
			}
			$this->SetFont('Arial','',10);
			$this->MultiCell(195, 8, "El medico que suscribe, certifica que he atendido al paciente ".$doc['paciente']['nomb'].' '.$doc['paciente']['appat'].' '.$doc['paciente']['apmat']. ', de '.$doc['edad'].
			' años de edad, identificado con DNI Nro: '.$doc['paciente']['docident'][0]['num'].'. Quien tiene el DX. de '.$prin_nomb.' (CIE-10: '.$prin_cod.').', '0', 'J');
			$y = $y+30;
			$this->SetXY(10,$y);
			$this->MultiCell(195, 8, "Se indica descanso medico por ".$doc['descanso']['dia'].' dias. Del '.date('d-m-Y',$doc["descanso"]['indi']->sec).' Al '.date('d-m-Y',$doc["descanso"]['fidi']->sec).".", '0', 'J');
			$y = $y+10;
			$this->SetXY(10,$y);
			$this->MultiCell(195, 8, "Se emite el presente certificado a solicitud del paciente.", '0', 'J');
			$y = $y+10;
			$this->SetXY(10,$y);
			$this->MultiCell(195, 8, "Atentamente,", '0', 'J');
			$this->Image(IndexPath.DS.'/tmp/'.$doc['firma'], 80, 160, 70, 20);
				$y = $y+58;
				$this->SetXY(10, $y);
				$this->MultiCell(195, 8, "_________________________________", '0', 'C');
				$autor = strtoupper($doc['autor']['nomb']) . ' '. strtoupper($doc['autor']['appat']) . ' ' . strtoupper($doc['autor']['apmat']);
				$y = $y+8;
				$this->SetXY(10, $y);
				$this->MultiCell(195, 8, $autor, '0', 'C');
		}
		if(isset($doc['informe'])){

			$this->AddPage();
			
			$y=10;
			$this->SetFont('Arial','B',9);
			$this->SetXY(10,5);$this->MultiCell(190,5,date("d/m/Y")." Página: ".$this->PageNo()."/{nb}",'0','R');
			$this->SetFont('Arial','',9);
			$this->SetFont('Arial','B',16);
			$this->SetXY(5,50);$this->MultiCell(200,5,"INFORME MEDICO",'0','C');
			$y=$y+10;
					
			$x=5;
			$y=13;
			$y_ini = $y;
			$this->SetFont('Arial','',13);
			$this->SetFont('Arial','B',10);
			$this->SetXY(79,$y);$this->MultiCell(18,8,"Paciente:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(97,$y);$this->MultiCell(80,8,"".$doc['paciente']['nomb'].' '.$doc['paciente']['appat'].' '.$doc['paciente']['apmat'],'0','L');
			$y=$y+8;
			$this->SetFont('Arial','B',10);
			$this->SetXY(79,$y);$this->MultiCell(18,8,"NHC:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(97,$y);$this->MultiCell(80,8,"".$doc['hist'],'0','L');
			$y=$y+8;
			$this->SetFont('Arial','B',10);
			$this->SetXY(79,$y);$this->MultiCell(18,8,"DNI:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(97,$y);$this->MultiCell(18,8,"".$doc['paciente']['docident'][0]['num'],'0','L');
			$this->SetFont('Arial','B',10);
			$this->SetXY(130,$y);$this->MultiCell(18,8,"SEXO:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(148,$y);$this->MultiCell(18,8,"".$sexo[$doc['sexo']],'0','L');
			/*$y=$y+8;
			$this->SetFont('Arial','B',10);
			$this->SetXY(79,$y);$this->MultiCell(18,8,"F. NAC:",'0','L');
			$this->SetFont('Arial','U',10);
			$this->SetXY(97,$y);$this->MultiCell(80,8,"".$doc['hist'],'0','L');*/
			$y = $y+30;
			$this->SetXY(10,$y);
			$this->SetFont('Arial','',10);
			$this->MultiCell(195, 8, $doc['informe']['info'], '0', 'J');
			$y = $y+210;
			$this->Image(IndexPath.DS.'/tmp/'.$doc['firma'], 80, ($y-5), 70, 20);
			$this->SetXY(10, $y);
			$this->MultiCell(195, 8, "_________________________________", '0', 'C');
			$autor = strtoupper($doc['autor']['nomb']) . ' '. strtoupper($doc['autor']['appat']) . ' ' . strtoupper($doc['autor']['apmat']);
			$y = $y+8;
			$this->SetXY(10, $y);
			$this->MultiCell(195, 8, $autor, '0', 'C');
		}
	}
}
$pdf=new repo('P','mm','A4');
$pdf->AliasNbPages();
$pdf->SetMargins(10,10,0); 
$pdf->SetTitle("reporte");
$pdf->SetAutoPageBreak(false,0); 
$pdf->Open();
$pdf->AddPage();
$pdf->Publicar($doc);
$pdf->SetLeftMargin(25);
header('Content-Type: application/pdf; charset=utf-8');
$pdf->Output();
?>