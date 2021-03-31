<?php

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfirmPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function(){
	return redirect()->route("dashboard");
});

Route::get("/ssl-proxy", function(){
	$url = Request::query('url');
	return file_get_contents($url);
});

Route::post("/ssl-proxy", function(){
	$headers = [];
	foreach(Request::header() as $key => $val){
		if(!in_array(strtolower($key), ["authorization", "content-type"])) continue;
		if(isset($val[0])) $val = $val[0];
		array_push($headers, "{$key}: {$val}");
	}
	$url = Request::query('url');
	$data = Request::toArray();
	$response = curlSendRequest('POST', $url, $data, $headers);
    return json_encode($response);
});


Route::group(["prefix" => getenv("APP_PREFIX") ?? "/"], function(){
	Route::get('/pago-referencia', function(){
		return view('pay-reference');
	});

	Route::post('/pago-referencia', function(){
		ini_set("soap.wsdl_cache_enabled", 0);

		list($usr, $pass) = explode("|", getenv("BANK_WS_CREDENTIALS"));
		$data = Request::all();
		$type = $data['type'];
		unset($data['type']);

        $data['date'] = date("Y-m-d");
        $data['string'] = "uno";
        $data['user'] = "LOCAL";
        $data['password'] = "LOCAL";
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
		      <urn:ConsultaTransaccion soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
		         <trans xsi:type="egob:transT1" xmlns:egob="http://localhost/egobierno/">
		            <!--You may enter the following 7 items in any order-->
		            <reference xsi:type="xsd:string">'.$data['reference'].'</reference>
		            <bank xsi:type="xsd:string">'.$data['bank'].'</bank>
		            <date xsi:type="xsd:string">'.$data['date'].'</date>
		            <string xsi:type="xsd:string">'.$data['string'].'</string>
		            <user xsi:type="xsd:string">'.$data['user'].'</user>
		            <password xsi:type="xsd:string">'.$data['password'].'</password>
		            <branch xsi:type="xsd:string">'.$data['branch'].'</branch>
		         </trans>
		      </urn:ConsultaTransaccion>
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

		$doc = new DOMDocument('1.0', 'utf-8');
		$doc->loadXML($response);
		$XMLresults = $doc->getElementsByTagName("ns1ConsultaTransaccionResponse");
		$output = $XMLresults->item(0)->nodeValue;
		dd($output);

		// $xml = new SimpleXMLElement($result);
		// dd($xml);
		// $body = $xml->xpath('//soapBody')[0];
		// $array = json_decode(json_encode((array)$body), TRUE);
		// dd($array);

		// $request_json= array(
		// 	'token' => $token,
		// 	'importe_transaccion' =>$sumMonto,
		// 	'id_transaccion' =>$id_trans,
		// 	'url_retorno' =>'www.prueba.com',
		// 	'entidad' =>$entidad,
		// 	'tramite' =>$tramite
		// );

		// $data = [
		// 	"reference" => "119974116120000246840330687248",
		// 	"bank" => "LOCAL",
		// 	"date" => "2021-03-31",
		// 	"string" => "uno",
		// 	"user" => "LOCAL",
		// 	"password" => "LOCAL",
		// 	"branch" => "123"
		// ];

		// $repuesta;
		// $datos;
		// $json=json_encode($data);
		// try {
		// 	$parameters=['json'=>$json];
		// 	$server = new \SoapClient($wsdl, [
		// 		'encoding' => 'UTF-8',
		// 		'verifypeer'=>false,
		// 		'trace' => true,
		// 		'authorization' => 'Basic '.base64_encode($usr.":".$pass)
		// 	]);
		// 	dd("server", $server->ConsultaTransaccion($parameters)->ConsultaTransaccion);
		// 	$datos =$server->GeneraReferencia($parameters)->GeneraReferenciaResult;
		// 	$json_d =json_decode($datos);
		// }catch(Exception $err){
		// 	dd("error", $err->getMessage());
		// }

		$client = new nusoap_client($wsdl, "wsdl");
		$client->setCredentials($usr, $pass);
		$client->soap_defencoding = 'UTF-8';
		$client->decode_utf8 = FALSE;
		$response = $client->call($type, $data);

		return dd(
			$data,
			$wsdl,
			$response,
			htmlspecialchars($client->request, ENT_QUOTES),
			htmlspecialchars($client->response, ENT_QUOTES)
		);
	});

	Route::get("/formato-declaracion/{id}", "FormatoDeclaracionController@index");
	Route::get("/email/template", "EmailController@index");
	Route::middleware(["validate_session", "validate_rol"])->group(function(){
		Route::get('/', function () {
			return redirect()->route("dashboard");
		})->name("home");
		Route::get('/dashboard', "DashboardController@index")->name("dashboard");
		Route::get('/tramites/{type}/{id}', "TramitesController@index")->name("tramites");
		Route::get('/detalle/{id}', "TramitesDetailsController@index")->name("tramites.details");
		Route::get('/nuevo-tramite', "TramitesController@new")->name("tramite.nuevo");
		Route::get('/perfil',  "AcountInfoController@index")->name("perfil");
		Route::get('/informacion-cuenta', "ProfileController@index")->name("informacion-cuenta");
		Route::get('/cambiar-contraseña', "changePassword@index")->name("cambiar-contraseña");
		Route::get('/usuarios', "UsersController@index")->name("usuarios");

		// LOGIN
		Route::get('/login', "LoginController@index")->name("login");
		Route::post('/login', "LoginController@validation");
		Route::get('/logout', "LoginController@logout")->name("logout");
		Route::get('/password/recovery', "RecoveryController@index")->name("password/recovery");
		Route::get('/password/recovery/{token}', [ConfirmPasswordController::class,'index'], function($token){
			return $token;
		})->name('/password/recovery/{token}');

		Route::get('/getTramites', 'TramitesController@listaTramites')->name("getTramites");

		//Solicitudes
		Route::get('/allTramites', 'SolicitudesController@getTramites')->name("allTramites");
		Route::get('/getCampos', 'SolicitudesController@getCampos')->name("getCampos");
		Route::post('/crearSolicitud', 'TramitesController@crearSolicitud');
		Route::get('/divisas', 'SolicitudesController@getDivisas');
		Route::get('/test', 'TramitesController@test');

		Route::get('/getCategories', 'SolicitudesController@allCategories')->name("allCategories");

		Route::post('/getcostoTramite', 'TramitesController@getcostoTramite')->name("costo-tramite");
		Route::post('/getcostoImpuesto', 'CalculoimpuestosController@index')->name("costo-impuesto");
		Route::post('/getComplementaria', 'CalculoimpuestosController@complementaria')->name("costo-complementaria");

		Route::get('/detalle-tramite/{idTramite}/{clave?}', "TramitesController@detalle")->name("tramite.detalle");
		Route::get('/pendiente-firma', "PendienteFirmaController@index")->name("pendiente-firma");
		Route::get('/cart/', "TramitesController@carshop")->name("tramite.cart");

		Route::get('/respuestaPago', "TramitesController@respuestaPago")->name("respuestaPago");

	});

	Route::post('/respuestaPagoBBVA', "TramitesController@respuestaPagoBBVA")->name("respuestaPagoBBVA");
});
