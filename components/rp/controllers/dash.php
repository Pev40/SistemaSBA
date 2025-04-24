<?php
class Controller_ci_dash extends Controller
{
    public function execute_index()
    {
        global $f;
        $f->response->view("rp/dashboard");
    }
    public function execute_get()
    {
        global $f;
        $meses = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre");
        $ini_mes = strtotime(date('Y-m-01'));
        $ini_mes_ = intval(date('m'));
        $data = array(
            'recaudacion'=>array(
                'legend'=>array(),
                'cementerio'=>array(0,0,0,0),
                'alquileres'=>array(0,0,0,0),
                'playas'=>array(0,0,0,0),
                'playasAzules'=>array(0,0,0,0),
                'tallerDeMaria'=>array(0,0,0,0),
                'recorridoCementerio'=>array(0,0,0,0),
                'moisesHeresi'=>array(0,0,0,0)
            ),
            'expedientes'=>array(0,0),
            'inhumaciones'=>array()
        );
        for ($i=8; $i>=0; $i--) {
            $tmp = $ini_mes_-$i;
            $tmp_a = intval(date('y'));
            if ($tmp<1) {
                $tmp = 12+$tmp;
                $tmp_a--;
            }
            $data['recaudacion']['legend'][] = $meses[$tmp].' \''.$tmp_a;
        }
      
        for ($i = 0; $i <= 8; $i++) {
            $tmp = $ini_mes_ - $i;
            $tmp_a = intval(date('Y'));
        
            if ($tmp < 1) {
                $tmp = 12 + $tmp;
                $tmp_a--;
            }
        
            $inicio = strtotime("-$i month", $ini_mes);
            $fin = strtotime("last day of", $inicio);
        
            /******************************************************************************************
            * RECAUDACION CEMENTERIO
            ******************************************************************************************/
            $recau = $this->get_month_recau('CM', new MongoDate($inicio), new MongoDate($fin));
            //$rede = $this->get_month_rede(new MongoDate($inicio), new MongoDate($fin));
        
            // Invertir el orden de asignación en el array 'cementerio'
            $data['recaudacion']['cementerio'][8 - $i] = $recau ;
            $data['recaudacion']['alquileres'][8 - $i] = $this->get_month_recau('IN',new MongoDate($inicio), new MongoDate($fin), 'A');
            $data['recaudacion']['playas'][8 - $i] = $this->get_month_recau('IN',new MongoDate($inicio), new MongoDate($fin), 'P');
            $data['recaudacion']['playasAzules'][8 - $i] = $this->get_month_recau('IN',new MongoDate($inicio), new MongoDate($fin), 'PA');
            $data['recaudacion']['tallerDeMaria'][8 - $i] = $this->get_month_recau('IN',new MongoDate($inicio), new MongoDate($fin), 'TDM');
            $data['recaudacion']['recorridoCementerio'][8 - $i] = $this->get_month_recau('IN',new MongoDate($inicio), new MongoDate($fin),'CMC');
            $data['recaudacion']['moisesHeresi'][8 - $i] = $this->get_month_recau('MH', new MongoDate($inicio), new MongoDate($fin));
        
        }

        /******************************************************************************************
        * EXPEDIENTES
        ******************************************************************************************/
        $expds = $f->model('td/expd')->params(array(
            'filter'=>array(
                'tupa'=>array('$exists'=>true),
                'fecreg'=>array(
                    '$gte'=>new MongoDate(strtotime('-3 month', $ini_mes)),
                    '$lte'=>new MongoDate()
                )
            ),
            'fields'=>array('estado'=>true)
        ))->get('custom')->items;
        if ($expds!=null) {
            foreach ($expds as $expd) {
                if ($expd['estado']=='C') {
                    $data['expedientes'][0]++;
                } else {
                    $data['expedientes'][1]++;
                }
            }
        }
        /******************************************************************************************
        * INHUMACIONES DEL DIA
        ******************************************************************************************/
        $inhumaciones = $f->model('cm/oper')->params(array(
            'filter'=>array(
                'inhumacion'=>array('$exists'=>true),
                'programacion.fecprog'=>array(
                    '$gte'=>new MongoDate(strtotime(date('Y-m-d'))),
                    '$lt'=>new MongoDate(strtotime(date('Y-m-d').' +3 day'))
                )
            ),
            'fields'=>array('ocupante'=>true,'programacion.fecprog'=>true,'inhumacion.funeraria'=>true,'inhumacion.puerta'=>true),
            'sort'=>array('programacion.fecprog'=>1)
        ))->get('custom')->items;
        if ($inhumaciones!=null) {
            foreach ($inhumaciones as $inhu) {
                $data['inhumaciones'][] = $inhu;
            }
        }
        $f->response->json($data);
    }
    public function get_month_recau($modulo, $ini, $fin, $tipo_inm=null)
    {
        global $f;
        $rpta = 0;
        $filter = array(
            'modulo'=>$modulo,
            'estado'=>'R',
            'fecreg'=>array(
                '$gte'=>$ini,
                '$lt'=>$fin
            )
        );
        if ($tipo_inm=='A' ||$modulo=='CM'||$modulo=='MH') {
            $filter['tipo_inm'] = $tipo_inm;    
        $comps = $f->model('cj/comp')->params(array(
            'filter'=>$filter,
            'fields'=>array('total'=>true)
        ))->get('custom')->items;
        if ($comps!=null) {
            foreach ($comps as $k=>$comp) {
                $rpta += floatval($comp['total']);
            }
        }
        }
        if ($tipo_inm!=null) {

                $rpta += $f->model('cj/ecom')->params(array(
              'moi'=>$ini,
              'mq'=>$fin,
              'tipo_inm'=>$tipo_inm
            ))->get('total_alquiler')->items;
            
        }
        return $rpta;
    }
    public function get_month_rede($ini, $fin)
    {
        global $f;
        $rpta = 0;
        $comps = $f->model('cj/rede')->params(array(
            'filter'=>array(
                'fec_db'=>array(
                    '$gte'=>$ini,
                    '$lt'=>$fin
                )
            ),
            'fields'=>array('total'=>true)
        ))->get('custom')->items;
        if ($comps!=null) {
            foreach ($comps as $k=>$comp) {
                $rpta += floatval($comp['total']);
            }
        }
        return $rpta;
    }
}
