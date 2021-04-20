<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

use App\Repositories\InpcRepositoryEloquent;
use App\Repositories\EgobiernoporcentajesRepositoryEloquent;
use App\Repositories\EgobiernodiasferiadosRepositoryEloquent;


// modificacion para calculo de la complementaria
use App\Repositories\PortalsolicitudestramiteRepositoryEloquent;
use App\Repositories\PortalsolicitudesticketRepositoryEloquent;

class CalculoimpuestosController extends Controller
{
    //

    protected $inpc;
    protected $inpc_values;
    protected $porcentaje;
    protected $diasferiados;
    protected $a;
    protected $b;
    protected $c;
    protected $d;
    protected $e;
    protected $f;
    protected $g;
    protected $h;
    protected $j;
    protected $l = 0;
    protected $k = 0;
    protected $fecha_escritura;
    protected $factor_actualizacion;
    protected $inpc_vencimiento;
    protected $inpc_reciente;
    protected $solicitudes_tramite; // es el repo para buscar si el contribuyente tiene normales pagadas
    protected $solicitudes_ticket;  // es el repo para buscar los datos de la normal


    public function __construct(
    	InpcRepositoryEloquent $inpc,
    	EgobiernoporcentajesRepositoryEloquent $porcentaje,
      EgobiernodiasferiadosRepositoryEloquent $diasferiados,
      PortalsolicitudestramiteRepositoryEloquent $solicitudes_tramite,
    	PortalsolicitudesticketRepositoryEloquent $solicitudes_ticket
    )
    {

    	$this->inpc 		            = $inpc;
    	$this->porcentaje 	        = $porcentaje;
      $this->diasferiados         = $diasferiados;


      $this->solicitudes_tramite	= $solicitudes_tramite;
      $this->solicitudes_ticket   = $solicitudes_ticket;




    	$this->inpc_values			= $this->getInpcValues();
    	$this->porcentajes_values	= $this->getPorcentejesvalues();



    	$this->a 			= 0; //ganancia obtenida
    	$this->b 			= 0; //MONTO OBTENIDO CONFORME AL ARTICULO 127 LISR
    	$this->c 			= 0; //PAGO PROVISIONAL CONFORME AL ARTICULO 126 LISR
    	$this->d 			= 0; //IMPUESTO CORRESPONDIENTE A LA ENTIDAD FEDERATIVA
    	$this->e 			= 0; //PARTE ACTUALIZADA DEL IMPUESTO
    	$this->f 			= 0; //RECARGOS
    	$this->g 			= 0; //MULTA POR CORRECCION FISCAL
    	$this->h			= 0; //IMPORTE TOTAL



    }




    public function index(Request $request)
    {

    	// mostrar la forma para ejemplo del calculo de declaracion normal
    	// $fecha_escritura 			= '2020-11-1'; // sin ceros iniciales ej 2020-9-1 para primero de sept de 2020
    	// $monto_operacion 			= 1500000;
    	// $ganancia_obtenida 			= 850000; // puede ser mayor o igual al monto de operacion
    	// $pago_provisional_lisr		= 40000;
    	// $multa_correccion_fiscal	= 0;

      //Recibir valores front
      $fecha_escritura 			= $request->fecha_escritura;
    	$monto_operacion 			= $request->monto_operacion;
    	$ganancia_obtenida 			= $request->ganancia_obtenida; // puede ser mayor o igual al monto de operacion
    	$pago_provisional_lisr		= $request->pago_provisional_lisr;
    	$multa_correccion_fiscal	= $request->multa_correccion_fiscal;


    	$this->a 					= $this->redondeo($ganancia_obtenida);
    	$this->c 					= $this->redondeo($pago_provisional_lisr);
    	$this->g 					= $this->redondeo($multa_correccion_fiscal);
    	$this->fecha_escritura		= $fecha_escritura;

      // obtener los dias inhabiles del año en curso
      $this->inhabiles	= $this->getInhabiles(date('Y'));
 		// $this->fecha_vencimiento	= $this->getVencimiento();
    $this->fecha_vencimiento  = $this->prueba();
 		$this->inpc_periodo			= $this->getInpc($this->fecha_escritura); // getInpcperiodo en caso de que sea la fecha acumulada del año vigente
 		$this->fecha_actual			= date("Y-m-d");
 		$this->inpc_reciente		= $this->getInpc($this->fecha_actual);

 		$this->factor_actualizacion = $this->getFA();

 		$this->porcentaje_recargos	= $this->getPorcentajeregargos();



    	$this->calculo();

    	$results = array(
    		"Entradas" => array(
	    			"fecha_escritura" => $this->fecha_escritura,
	    			"monto_operacion" => $monto_operacion,
	    			"ganancia_obtenida" => $ganancia_obtenida,
	    			"pago provisional conforme al art 127 LISR" => $pago_provisional_lisr,
	    			"multa por correccion fiscal (g)" => $this->g,
	    		),
    		"Salidas" => array(
    			"Fecha Actual"				=> $this->fecha_actual,
    			"Fecha vencimiento" 		=> $this->fecha_vencimiento,
    			"Factor de Actualizacion" 	=> $this->factor_actualizacion,
    			"INPC Periodo reciente" 	=> $this->inpc_reciente,
    			"INPC Periodo" 				=> $this->inpc_periodo,
    			"Porcentaje de recargos"	=> $this->porcentaje_recargos,
	    		"Ganancia Obtenida" => $this->a,
	    		"Monto obtenido conforme al art 127 LISR" => $this->b,
	    		"Pago provisional conforme al art 126 LISR" => $this->c,
	    		"Impuesto correspondiente a la entidad federativa" => $this->d,
	    		"Parte actualizada del impuesto" => $this->e,
	    		"Recargos" => $this->f,
	    		"Multa corrección fiscal" => $this->g,
	    		"Importe total" => $this->h,
    			),

    	);


    	return json_encode($results);

    }
    /**
     *
     * checkNormal obtener el folio de la declaracion normal
     *
     * @param $rfc
     *
     *
     * @return un array con la lista de declaraciones donde coincide el RFC
     *
    */
    public function checkNormal(Request $request)
    {

      // primero busco todas las declaraciones que corresponden a 5 %
      $normal = $this->solicitudes_tramite->findWhere(["id_transaccion_motor" => $request->folio]);

      if($normal->count() == 1)
      {
        $results = $normal->id;
      }else{
        $results = 0;
      }

      return $results;

    }


    /**
     *
     * Complementaria calculo
     *
     * este metodo regresa el calculo de una declaracion complementaria
     *
     *
    */
    public function complementaria(Request $request) // Request $request
    {

      // mostrar la forma para ejemplo del calculo de declaracion normal
      // $fecha_escritura       = '2020-11-1'; // sin ceros iniciales ej 2020-9-1 para primero de sept de 2020
      // $monto_operacion       = 1500000;
      // $ganancia_obtenida       = 850000; // puede ser mayor o igual al monto de operacion
      // $pago_provisional_lisr   = 40000;
      // $multa_correccion_fiscal = 0;

      //Recibir valores front
      $fecha_escritura          =  $request->fecha_escritura;
      $monto_operacion          =  $request->monto_operacion;
      $ganancia_obtenida        =  $request->ganancia_obtenida; // puede ser mayor o igual al monto de operacion
      $pago_provisional_lisr    =  $request->pago_provisional_lisr;
      $multa_correccion_fiscal  =  $request->multa_correccion_fiscal;

      //id declaracion normal
      $normal     = $request->folio_anterior;
      //$normal                   =  (integer)2000022493;//es es el numero de folio de la declaracion normal del tramite

      if($normal == 0)
      {
        // este es un error el folio de la declaracion normal no existe
      }else{
        // buscamos el registro de la declaracion normal
        $info = $this->solicitudes_tramite->findWhere( ["id_transaccion_motor" => $normal] )->first();

        if($info->count() > 0)
        {
          // aqui tengo que obtener el monto de la declaracion anterior
          $s_ticket = $this->solicitudes_ticket->findWhere( ["id_transaccion" => $info->id] )->first();

          $info = json_decode($s_ticket->info);

          $datos_normal = $info->detalle;

          $salidas = $datos_normal->Salidas;

          foreach($salidas as $s => $v)
          {
            if(strcmp($s,"Importe total") == 0)
            {
              $importe = $v;
            }
          }

        }else{
          $results = array (
            "Code" => 400,
            "Error" => "No se encontró la declaración normal"
          );

          return json_encode($results);
        }
      }

      $this->a          = $ganancia_obtenida;
      $this->c          = $pago_provisional_lisr;
      $this->c       = $this->redondeo($this->c);
      $this->g          = $multa_correccion_fiscal;
      $this->fecha_escritura    = $fecha_escritura;

      // obtener los dias inhabiles del año en curso
      $this->inhabiles	= $this->getInhabiles(date('Y'));

      // $this->fecha_vencimiento  = $this->getVencimiento();
      $this->fecha_vencimiento  = $this->prueba();
      $this->inpc_periodo       = $this->getInpc($this->fecha_escritura); // getInpcperiodo en caso de que sea la fecha acumulada del año vigente
      $this->fecha_actual       = date("Y-m-d");
      $this->inpc_reciente      = $this->getInpc($this->fecha_actual);

      $this->factor_actualizacion = $this->getFA();

      $this->porcentaje_recargos  = $this->getPorcentajeregargos();

      $this->calculo();


      if($this->h < $importe)
      {
        $this->k = $importe - $this->h;
      }else{
        $this->l = $this->h - $importe;
      }



      $results = array(
        "Entradas" => array(
            "fecha_escritura" => $this->fecha_escritura,
            "monto_operacion" => $monto_operacion,
            "ganancia_obtenida" => $ganancia_obtenida,
            "pago provisional conforme al art 127 LISR" => $pago_provisional_lisr,
            "multa por correccion fiscal" => $this->g,
          ),
        "Salidas" => array(
          "Fecha Actual"        => $this->fecha_actual,
          "Fecha vencimiento"     => $this->fecha_vencimiento,
          "Factor de Actualizacion"   => $this->factor_actualizacion,
          "INPC Periodo reciente"   => $this->inpc_reciente,
          "INPC Periodo"        => $this->inpc_periodo,
          "Porcentaje de recargos"  => $this->porcentaje_recargos,
          "Ganancia Obtenida" => $this->a,
          "Monto obtenido conforme al art 127 LISR" => $this->b,
          "Pago provisional conforme al art 126 LISR" => $this->c,
          "Impuesto correspondiente a la entidad federativa" => $this->d,
          "Parte actualizada del impuesto" => $this->e,
          "Recargos" => $this->f,
          "Multa corrección fiscal" => $this->g,
          "Importe total" => $this->redondeo($this->h),
          ),
        "Complementaria"  => array(
          "Folio de la declaracion inmediata anterior"  => $normal,
          "Monto pagado en la declaracion inmediata anterior" => $importe,
          "Pago en exceso"  => $this->k,
          "Cantidad a cargo" => $this->l,
        )

      );


      return json_encode($results);

    }
    /**
     *
     * Calculo en ceros
     *
     * Este metodo debe de guardar
     *
     *
     *
    */

    public function calculoencero(Request $request)
    {

    }

    /* este metodo realiza el calculo del impuesto a pagar */
    private function calculo()
    {
    	// obtener b monto obtenido conforme al articulo 127 LISR
    	$this->b = $this->redondeo($this->a * .05);

    	// impuesto correspondiente a la entidad federativa
    	($this->b >= $this->c) ? $this->d = $this->c : $this->d = $this->b;

    	// parte actualizada del impuesto
    	$this->e = ($this->d * $this->factor_actualizacion) - $this->d;

      $this->e = $this->redondeo($this->e);
    	// obtener los recargos
    	$this->f = ($this->d + $this->e) * $this->porcentaje_recargos;

      $this->f = $this->redondeo($this->f);
    	// importe total
    	$this->h =  $this->d + $this->e + $this->f + $this->g ;

    }


    /* obtener el factor de actualizacion */
    private function getFA()
    {
    	// obtener el indice de la fecha de vencimiento
    	$factor = (bcdiv(($this->inpc_reciente/$this->inpc_periodo),1,4) < 1 ) ? 1 : bcdiv(($this->inpc_reciente/$this->inpc_periodo),1,4);

		  return $factor;

    }

    /* obtener la fecha de vencimiento */

    /**
     *
     *
     * getVencimiento. utilizando la fecha de la escrituracion se obtiene la nueva
     * fecha, sumando 15 dias
     * en caso de ser festivo o sabado o domingo se determina el dia habil siguiente
     *
     *
    */

    private function getVencimiento()
    {
    	$vencimiento = date("Y-n-j", strtotime($this->fecha_escritura . "+ 15 days"));

    	$dia = date('w', strtotime($vencimiento));

    	$inhabil = 0;

    	if($dia == 0)
    	{
    		// es domingo
    		$vencimiento = date("Y-n-j", strtotime($vencimiento . "+ 1 days"));
    	}elseif($dia == 6){
    		// es sabado
    		$vencimiento = date("Y-m-j", strtotime($vencimiento . "+ 2 days"));
    	}

    	foreach($this->inhabiles as $fi)
    	{
    		if(strcmp($fi,$vencimiento))
    		{
    			$inhabil = 1;
    		}
    	}

    	// la fecha de vencimiento esta en un dia inhabil
    	if($inhabil == 1)
    	{
    		$vencimiento = date("Y-n-j", strtotime($vencimiento . "+ 1 days"));

    		$dia = date('w', strtotime($vencimiento));

    		if($dia == 0)
	    	{
	    		// es domingo
	    		$vencimiento = date("Y-n-j", strtotime($vencimiento . "+ 1 days"));
	    	}elseif($dia == 6){
	    		// es sabado
	    		$vencimiento = date("Y-m-j", strtotime($vencimiento . "+ 2 days"));
	    	}

	    	return $vencimiento;

    	}else{
    		return $vencimiento;
    	}



    }

    /**
     * getInhabiles. regresa los dias inhabiles del año
     *
     * @param year - entero que representa el año corriente
     *
     * @return array - con las fechas en la tabla
     *
     *
    */
    private function getInhabiles($year)
    {
    	try
    	{
        $fe = explode("-",$this->fecha_escritura);
   			$yf = $fe[0];  // año de inicio

        if($yf != $year){//Si son diferentes se obtienen los días inhabiles desde el año de escritura hasta el actual
          $fechas = $this->diasferiados->findWhereBetween('Ano', [ $yf,$year ] )->where('tipo', 'F');
      		if($fechas->count() > 0)
      		{
      			$dates = array();
      			foreach($fechas as $f)
  	    		{
  	    			$dates []= $f->Ano."-".$f->Mes."-".$f->Dia;
  	    		}

      		}else{
      			dd("CalculoimpuestosController::getInhabiles-No existen dias inhabiles en " . $year);
      		}

        }else{ //Si son el mismo año se obtienen solo los del año en curso

      		$fechas = $this->diasferiados->findWhere( [ "Ano" => $year ] )->where('tipo', 'F');
      		if($fechas->count() > 0)
      		{
      			$dates = array();
      			foreach($fechas as $f)
  	    		{
  	    			$dates []= $f->Ano."-".$f->Mes."-".$f->Dia;
  	    		}

      		}else{
      			dd("CalculoimpuestosController::getInhabiles-No existen dias inhabiles en " . $year);
      		}

        }

    		return $dates;

    	}catch( \Exception $e ){
    		dd("getInhabiles ERROR - " . $e->getMessage());
    	}
    }

    /**
     * getInpc. obtiene el inpc de una fecha
     *
     * @param fecha yyyy-m-d
     *
     * @return float
     *
     *
    */

    private function getInpc($fecha)
    {
    	$d = explode("-",$fecha);

    	$year 	= (integer)$d[0];
    	$month 	= (integer)$d[1];


    	// buscar en la tabla todos los factores que correspondan al año
    	try{

    		return $this->inpc_values[$year][$month];


    	}catch( \Exception $e ){
    		dd($this->inpc_values, "CalculoimpuestosController::getInpc " . $e->getMessage());
    	}


    }

    /**
     * getInpcPeriodo. obtiene el inpc de una fecha
     *
     * @param fecha solo el año yyyy
     *
     * @return float
     *
     *
    */

    private function getInpcPeriodo($fecha)
    {
    	$d = explode("-",$fecha);

    	$year 	= $d[0];
    	$month 	= 1;


    	// buscar en la tabla todos los factores que correspondan al año
    	try{

    		return $this->inpc_values[$year][$month];


    	}catch( \Exception $e ){
    		dd("CalculoimpuestosController::getInpc " . $e->getMessage());
    	}


    }
    /**
     * getInpcValues. esta funcion regresa un arreglo por año y dentro un asociativo por mes y valor
     *
     * @param fecha yyyy-m-d
     *
     * @return float
     *
     *
    */
    private function getInpcValues()
    {
    	// obtener todos los valores de la tabla en orden descendiente
    	try
    	{
    		$list = $this->inpc
    			->orderBy('ano','DESC')
    			->orderBy('mes','ASC')
    			->all();


    		$years 	= array();

    		$months = array( 1,2,3,4,5,6,7,8,9,10,11,12 );

    		$years[]= (integer)date("Y");

    		$final = array();

    		foreach($list as $l)
    		{

    			if(!in_array((integer)$l->ano,$years))
    			{
    				$years[]= (integer)$l->ano;
    			}
    		}

    		foreach($years as $y)
    		{

    			$valores = array();

    			foreach($months as $m)
    			{
    				// buscar el valor en list
    				foreach($list as $l)
    				{
    					if((integer)$l->ano == $y && (integer)$l->mes == $m)
    					{
    						$valores [$m]= $l->indice;
    						break;
    					}
    				}
    				if(!isset($valores[$m])){

    					$valores[$m] = (isset($valores[$m-1])) ? $valores[$m-1] : 0 ;

    				}

    			}
    			$final[$y]= $valores;
    		}

    		return($final);

    	}catch( \Exception $e ){
    		dd("CalculoimpuestosController::getInpcValues " . $e->getMessage());
    	}
    }
    /**
     * getPorcentejesvalues. esta funcion regresa un arreglo por año y dentro un asociativo por mes y valor
     *
     * @param null
     *
     * @return array
     *
     *
    */
    private function getPorcentejesvalues()
    {
    	// obtener todos los valores de la tabla en orden descendiente
    	try
    	{
    		$list = $this->porcentaje
    			->orderBy('anio','DESC')
    			->orderBy('mes','ASC')
    			->all();

    		$years 	= array();

    		$months = array( 1,2,3,4,5,6,7,8,9,10,11,12 );

    		$years[]= (integer)date("Y");

    		$final = array();

    		foreach($list as $l)
    		{

    			if(!in_array((integer)$l->anio,$years))
    			{
    				$years[]= (integer)$l->anio;
    			}
    		}

    		foreach($years as $y)
    		{

    			$valores = array();

    			foreach($months as $m)
    			{
    				// buscar el valor en list
    				foreach($list as $l)
    				{
    					if((integer)$l->anio == $y && (integer)$l->mes == $m)
    					{
    						/*$valores [$m]= array(
    							"vencido" 	=> $l->vencido,
    							"requerido"	=> $l->requerido
    						);*/

    						if(strlen((string)$m) == 1)
    						{
    							$index = $y."0".$m;
    						}else{
    							$index = $y.$m;
    						}

    						$final[(integer)$index]= array(
    							"year"		=> $y,
    							"month"		=> $m,
    							"vencido" 	=> $l->vencido,
    							"requerido"	=> $l->federal_vencido
    						);
    						break;
    					}
    				}
    				/*
    				if(!isset($valores[$m])){

    					//$valores[$m] = (isset($valores[$m-1])) ? $valores[$m-1] : 0 ;
    					$final[]= (isset($valores[$m-1])) ? $valores[$m-1] : 0 ;

    				}*/

    			}
    			//$final[$y]= $valores;
    		}

    		return($final);

    	}catch( \Exception $e ){
    		dd("CalculoimpuestosController::getInpcValues " . $e->getMessage());
    	}
    }
    /**
     * getPorcentejesvalues. esta funcion regresa un arreglo por año y dentro un asociativo por mes y valor
     *
     * @param null
     *
     * @return array
     *
     *
    */
    private function getPorcentajeregargos()
    {
    	/*
    	$this->fecha_escritura
  		$this->fecha_vencimiento
  		$this->fecha_actual
  		*/
  		$total = 0;

  		$fa = strtotime($this->fecha_actual . " 00:00:00");
  		$fv = strtotime($this->fecha_vencimiento . " 00:00:00");

  		if($fa <= $fv){
  			return $total;
  		}else{
  			// esta vencido por lo tanto corre recargos
  			$fa = explode("-",$this->fecha_actual);
  			$yi = $fa[0];  // año de inicio
  			$mi = $fa[1];  // mes de inicio
        $di = $fa[2];
   			$fe = explode("-",$this->fecha_vencimiento);
   			$yf = $fe[0];  // año de inicio
  			$mf = $fe[1];  // mes de inicio
        $df = $fe[2];
        //Se considera vencido a partir de la fecha de vencimiento

  			if(strlen((string)$mi) == 1){
  				$i = $yi."0".$mi;
  			}else{
  				$i = $yi.$mi;
  			}

  			$i = (integer)$i;

  			if(strlen((string)$mf) == 1){
  				$f = $yf."0".$mf;
  			}else{
  				$f = $yf.$mf;
  			}
  			$f = (integer)$f;
  			//$count = 0;
        //$datafechas = array();
  			foreach($this->porcentajes_values as $p => $data)
  			{

  				if($p <= $i && $p >= $f)
  				{
            if($p == $f){

              if($di >= $df){ //si el día actual es mayor o igual al vencimiento se continua el flujo normal

              }else{ //Si es menor entonces se quita 1 mes de la suma de recargos para solo contar hasta el día en curso
                $total = $total - $data["requerido"];

              }
            }

            $total += $data["requerido"];
            // $datafechas []= array(
            //   'fecha en valores' => $p,
            //   'fechaactual' => $i,
            //   'fechavencimiento' => $f,
            //   'fecha actual' => $this->fecha_actual,
            //   'fecha ven' => $this->fecha_vencimiento,
            //   'dias actual -ven' => $di.'-'.$df,
            //   'total' => $total
            // );
  				}
      /*
  				else{
  					echo "\n--------";
  					echo "\nfecha_actual=>  ";
  					var_dump($this->fecha_actual);
  					echo "\nfecha escritura =>  ";
  					var_dump($this->fecha_escritura);
  					echo "\np =>  ";
  					var_dump($p);
  					echo "\ni =>  ";
  					var_dump($i);
  					echo "\nf =>  ";
  					var_dump($f);


  					$count ++;
  					if($count == 12)
  						exit();

  				}*/
  			}

  			return $total / 100;
  		}
    }


    public function redondeo($costo_real){
      //Redondeo
      $exacto = floor($costo_real);

      $dec = $costo_real - $exacto;

      if($dec <= "0.50"){
        $costo_real = floor($costo_real);
      }else{
        $costo_real = $exacto + 1;
      }

      return $costo_real;
    }
    //Funcion para aplicar días habiles de L-V
    /**
     * getVencimiento -> prueba. utilizando la fecha de la escrituracion se obtiene la nueva
     * fecha, sumando 15 dias
     * en caso de ser festivo o sabado o domingo se determina el dia habil siguiente
    */
    public function prueba(){
    		$fecha = $this->fecha_escritura;
    		$inhabil = $this->inhabiles;

        $dias=0;
        $fechaTermino = '';
        $hora = date("H",strtotime($fecha));
        $fecha = ($hora>=13) ? date("Y-n-j",strtotime($fecha.' +1 days')) : date("Y-n-j",strtotime($fecha)) ;
        $comienzo = $fecha;
        $test_dia = 'inicio';

        //15 es el numero de dias que calcularemos
        // $datafechas = array();
        while ($dias <= 15) {
            $finDeSemana = date("w",strtotime($comienzo));
            $asueto = in_array($comienzo, $inhabil, TRUE);

            // $datafechas []= array(
            //   'diasemana' => $finDeSemana,
            //   'fecha' => $comienzo,
            //   'test' => $test_dia,
            //   'asueto' => $asueto
            // );

            //Si la fecha es sabado o domingo O la fecha existe en los inhabil

            if($finDeSemana == 0 || $finDeSemana == 6 || $asueto == true ){
              $comienzo = date("Y-n-j",strtotime($comienzo.' +1 days'));

              $test_dia = 'finDeSemana';
            }else{
                $fechaTermino = date("Y-n-j",strtotime($comienzo));
                $comienzo = date("Y-n-j",strtotime($comienzo.' +1 days'));

                $dias++;
                $test[]= $fechaTermino;
                // $test_dia = 'habil';
            }
            //$com[]= $comienzo;

        }
        $fechaTermino = date("Y-m-d", strtotime($fechaTermino));
        //dd($fechaTermino);
        return $fechaTermino;
	}
}
