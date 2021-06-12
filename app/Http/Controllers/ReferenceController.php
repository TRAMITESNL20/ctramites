<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use DB;

class ReferenceController extends Controller {
	public function paid (Request $request, $reference) {
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		Log::channel('apilog')->info("\n\nENDPOINT: [{$_SERVER['REQUEST_METHOD']}] - ".$actual_link);
		$ref = $reference;
		$reference = DB::connection('db_operacion')
		->table('oper_transacciones')
		->select(
			'oper_transacciones.id_transaccion_motor as oper_transacciones-id_transaccion_motor',
			'oper_transacciones.estatus as oper_transacciones-estatus',
			'oper_transacciones.referencia as oper_transacciones-referencia',
			'solicitudes_tramite.id as solicitudes_tramite-id',
			'solicitudes_tramite.id_transaccion_motor as solicitudes_tramite-id_transaccion_motor',
			'solicitudes_tramite.estatus as solicitudes_tramite-estatus',
			'solicitudes_tramite.url_recibo as solicitudes_tramite-url_recibo'
		)
		->join('portal.solicitudes_tramite', 'oper_transacciones.id_transaccion_motor', 'solicitudes_tramite.id_transaccion_motor')
		->where('referencia', $reference)
		->first();
		Log::channel('apilog')->info("DATA: ".json_encode($reference, JSON_PRETTY_PRINT));

		if(!isset($reference->{'solicitudes_tramite-id'})){
			Log::channel('apilog')->error("RESPONSE: Esta referencia '{$ref}' no se encuentra en la base de datos de portal");
			return abort(409, "Esta referencia '{$ref}' no se encuentra en la base de datos de portal");
		}

		$reference->solicitudes = DB::connection('db_portal')
		->table('solicitudes_ticket')
		->select(
			'solicitudes_ticket.id as id',
			'solicitudes_ticket.clave as clave',
			'solicitudes_ticket.id_tramite as id_tramite',
			'solicitudes_ticket.recibo_referencia as recibo_referencia',
			'solicitudes_ticket.status as status',
			'solicitudes_ticket.catalogo_id as catalogo_id',
			'solicitudes_catalogo.atendido_por as solicitudes_catalogo-atendido_por'
		)
		->join('solicitudes_catalogo', 'solicitudes_ticket.catalogo_id', 'solicitudes_catalogo.id')
		->where('id_transaccion', $reference->{'solicitudes_tramite-id'})
		->get();

		if($reference->{'oper_transacciones-estatus'} != 0 && !$request->has('dev')){
			Log::channel('apilog')->error("RESPONSE: El estatus actual de la referencia '{$ref}' es diferente de pagado (0).");
			return abort(409, "El estatus actual de la referencia '{$ref}' es diferente de pagado (0).");
		}

		$update = DB::connection('db_portal')
		->table('solicitudes_tramite')
		->where('id', $reference->{'solicitudes_tramite-id'})
		->update([
			'solicitudes_tramite.estatus' => $request->has('dev') ? 0 : $reference->{'oper_transacciones-estatus'},
			'solicitudes_tramite.url_recibo' => getenv('FORMATO_RECIBO').$reference->{'solicitudes_tramite-id_transaccion_motor'},
		]);

		if($update){
			foreach($reference->solicitudes as $solicitud){
				$update = DB::connection('db_portal')
				->table('solicitudes_ticket')
				->where('id', $solicitud->id)
				->update([
					'solicitudes_ticket.status' => $solicitud->{'solicitudes_catalogo-atendido_por'} == 1 ? 2 : 3
				]);

				if($update && $solicitud->{'solicitudes_catalogo-atendido_por'} == 1){
					DB::connection('db_portal')
					->table('solicitudes_mensajes')
					->insert([
						'ticket_id'=> $solicitud->id,
						'mensaje' => "Solicitud cerrada porque esta asignado al admin - API Bancos"
					]);
				}
			}
		}


		Log::channel('apilog')->info("RESPONSE: Referencia Actualizada: {$ref}");
		return [
			"code" => 200,
			"response" => "ok"
		];
	}

	public function cancel (Request $request, $reference=null) {
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		Log::channel('apilog')->info("\n\nENDPOINT: [{$_SERVER['REQUEST_METHOD']}] - ".$actual_link);
		if(!$reference) $reference = request()->toArray();
		if(gettype($reference) != 'array' && !isset($reference->service)) $reference = [$reference];
		unset($reference["service"]);
		unset($reference["dev"]);
		if(count($reference) == 0) abort(409, "No hay referencias para validar.");
		$response = [];

		foreach ($reference as $ref) {
			try{
				$this->process_delete_reference($ref);
				array_push($response, "Referencia Actualizada: {$ref}");
			}catch(HttpException $e){
				array_push($response, $e->getMessage());
			}
		}
	
		Log::channel('apilog')->info("DATA: ".json_encode($reference, JSON_PRETTY_PRINT));
		Log::channel('apilog')->info("RESPONSE: ".json_encode($response, JSON_PRETTY_PRINT));

		return [
			"code" => 200,
			"response" => "ok",
			"message" => $response
		];
	}

	public function emulator (){
		return view('pay-reference');
	}

	public function bankWs (Request $request) {
		ini_set("soap.wsdl_cache_enabled", 0);

		list($usr, $pass) = explode("|", getenv("BANK_WS_CREDENTIALS"));
		$data = $request->all();
		$type = $data['type'];
		unset($data['type']);

	    $data['date'] = date("Y-m-d");
	    $data['string'] = "uno";
	    $data['user'] = "LOCAL";
	    $data['password'] = "LOCAL";
	    $data['bank'] = "LOCAL";
	    if(in_array($type, ["NotificarPago"])) $data['paymentType'] = "0";
	    if(in_array($type, ["NotificarPago", "ReversoPago"])) $data['paymentId'] = "0";
	    $data['branch'] = "123";
	    if(in_array($type, ["NotificarPago"])) $data['account'] = "0000";
	    $wsdl = getenv("BANK_WS_HOSTNAME")."/wsbancos/egobws.php?wsdl";
	    if(in_array($type, ["ConsultaTransaccion"])) unset($data["amount"]);

	    $auth = array(
			'Username' => $usr,
			'Password' => $pass
		);

		$request = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:egobws">
		   <soapenv:Header/>
		   <soapenv:Body>
		      <urn:'.$type.' soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
		         <trans xsi:type="egob:transT1" xmlns:egob="http://localhost/egobierno/">
		         	';
		         	if ($type == 'ConsultaTransaccion'){
		         		$request .= '
							<reference xsi:type="xsd:string">'.$data['reference'].'</reference>
							<bank xsi:type="xsd:string">'.$data['bank'].'</bank>
							<date xsi:type="xsd:string">'.$data['date'].'</date>
							<string xsi:type="xsd:string">'.$data['string'].'</string>
							<user xsi:type="xsd:string">'.$data['user'].'</user>
							<password xsi:type="xsd:string">'.$data['password'].'</password>
							<branch xsi:type="xsd:string">'.$data['branch'].'</branch>
		         		';
		         	} else if ($type == 'NotificarPago') {
						$request .= '
							<reference xsi:type="xsd:string">'.$data['reference'].'</reference>
							<bank xsi:type="xsd:string">'.$data['bank'].'</bank>
							<date xsi:type="xsd:string">'.$data['date'].'</date>
							<string xsi:type="xsd:string">'.$data['string'].'</string>
							<user xsi:type="xsd:string">'.$data['user'].'</user>
							<password xsi:type="xsd:string">'.$data['password'].'</password>
							<amount xsi:type="xsd:string">'.$data['amount'].'</amount>
							<paymentType xsi:type="xsd:string">'.$data['paymentType'].'</paymentType>
							<paymentId xsi:type="xsd:string">'.$data['paymentId'].'</paymentId>
							<branch xsi:type="xsd:string">'.$data['branch'].'</branch>
							<account xsi:type="xsd:string">'.$data['account'].'</account>
						';
		         	} else if ($type == 'ReversoPago') {
		         		$request .= '
							<reference xsi:type="xsd:string">'.$data['reference'].'</reference>
							<bank xsi:type="xsd:string">'.$data['bank'].'</bank>
							<date xsi:type="xsd:string">'.$data['date'].'</date>
							<string xsi:type="xsd:string">'.$data['string'].'</string>
							<user xsi:type="xsd:string">'.$data['user'].'</user>
							<password xsi:type="xsd:string">'.$data['password'].'</password>
							<amount xsi:type="xsd:string">'.$data['amount'].'</amount>
							<paymentId xsi:type="xsd:string">'.$data['paymentId'].'</paymentId>
							<branch xsi:type="xsd:string">'.$data['branch'].'</branch>
						';
		         	}
					$request .= '
		         </trans>
		      </urn:'.$type.'>
		   </soapenv:Body>
		</soapenv:Envelope>
		';

		$header = [
			'Content-type: text/xml;charset="utf-8"',
			'Accept: text/xml',
			'Cache-Control: no-cache',
			'Authorization: Basic '.base64_encode($usr.":".$pass)
		];

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $wsdl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		$result = curl_exec($ch);
		curl_close($ch);
		$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $result);

		$doc = new \DOMDocument('1.0', 'utf-8');
		$doc->loadXML($response);
		$error = $doc->getElementsByTagName("error")->item(0)->nodeValue;
		$message = $doc->getElementsByTagName("message")->item(0)->nodeValue;
		
		if($type == 'ConsultaTransaccion'){
			$amount = $doc->getElementsByTagName("amount")->item(0)->nodeValue;
			$clientInformation = $doc->getElementsByTagName("clientInformation")->item(0)->nodeValue;
			$response = [
				"amount" => $amount,
				"clientInformation" => $clientInformation
			];
		} else {
			$paymentId = $doc->getElementsByTagName("paymentId")->item(0)->nodeValue;
			$response = [
				"paymentId" => $paymentId
			];
		}

		$response['error'] = $error;
		$response['message'] = $message;
		$response['reference'] = $data["reference"];

		echo json_encode($response)."\n\n";
		echo '<a href="'.url()->route("pago-referencia").'"><-- Regresar</a>';
		return true;
	}

    protected function process_delete_reference ($reference) {
    	$ref = $reference;
    	$request = request();
    	$reference = DB::connection('db_operacion')
		->table('oper_transacciones')
		->select(
			'oper_transacciones.id_transaccion_motor as oper_transacciones-id_transaccion_motor',
			'oper_transacciones.estatus as oper_transacciones-estatus',
			'oper_transacciones.referencia as oper_transacciones-referencia',
			'solicitudes_tramite.id as solicitudes_tramite-id',
			'solicitudes_tramite.id_transaccion_motor as solicitudes_tramite-id_transaccion_motor',
			'solicitudes_tramite.estatus as solicitudes_tramite-estatus',
			'solicitudes_tramite.url_recibo as solicitudes_tramite-url_recibo'
		)
		->join('portal.solicitudes_tramite', 'oper_transacciones.id_transaccion_motor', 'solicitudes_tramite.id_transaccion_motor')
		->where('referencia', $reference)
		->first();
		if(!isset($reference->{'solicitudes_tramite-id'})) return abort(409, "La referencia \"{$ref}\" no se encuentra en la base de datos de portal");
		
		$reference->solicitudes = DB::connection('db_portal')
		->table('solicitudes_ticket')
		->select(
			'solicitudes_ticket.id as id',
			'solicitudes_ticket.clave as clave',
			'solicitudes_ticket.id_tramite as id_tramite',
			'solicitudes_ticket.recibo_referencia as recibo_referencia',
			'solicitudes_ticket.status as status',
			'solicitudes_ticket.catalogo_id as catalogo_id',
			'solicitudes_catalogo.atendido_por as solicitudes_catalogo-atendido_por'
		)
		->join('solicitudes_catalogo', 'solicitudes_ticket.catalogo_id', 'solicitudes_catalogo.id')
		->where('id_transaccion', $reference->{'solicitudes_tramite-id'})
		->get();


		if($reference->{'oper_transacciones-estatus'} != 99 && $reference->{'oper_transacciones-estatus'} != 65 && !$request->has('dev'))
			return abort(409, 'El estatus actual de la referencia es diferente de cancelado (60) o devuelto (65).');

		// SI ES STATUS 65 HAY QUE PASAR LA TRANSACCIÓN A PENDIENTE DE PAGO
		// VALIDAR QUE SE NECESITARA MODIFICAR PARA QUE SE PUEDA EDITAR
		$update = DB::connection('db_portal')
		->table('solicitudes_tramite')
		->where('id', $reference->{'solicitudes_tramite-id'})
		->update([
			'solicitudes_tramite.estatus' => $request->has('dev') ? 99 : $reference->{'oper_transacciones-estatus'}
		]);

		if($update){
			foreach($reference->solicitudes as $solicitud){
				$changes = [
					'solicitudes_ticket.status' => 5
				];

				if($reference->{'oper_transacciones-estatus'} == 65){
					$changes = [
						'solicitudes_ticket.status' => 9,
						// 'recibo_referencia' => null,
						// 'id_transaccion' => null,
						// 'asignado_a' => null,
						// 'en_carrito' => 0,
						'updated_at' => date('Y-m-d H:i:s')
					];
				}

				$update = DB::connection('db_portal')
				->table('solicitudes_ticket')
				->where('id', $solicitud->id)
				->update($changes);

				if($update){
					$update = DB::connection('db_portal')
					->table('solicitudes_mensajes')
					->where('ticket_id', $solicitud->id)
					->delete();
				}
			}
		}

		return true;
    }
}
