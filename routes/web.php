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
	$response = curlSendRequest('POST', $url, $data, $headers, null, true);
    return json_encode($response);
});


Route::group(["prefix" => getenv("APP_PREFIX") ?? "/"], function(){
	Route::get('/pago-referencia', "ReferenceController@emulator")->name("pago-referencia");
	Route::post('/pago-referencia', "ReferenceController@bankWs");

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
		// Route::get('/usuarios', "UsersController@index")->name("usuarios");
		// Route::get('/landingPage', "LandingPageController@index")->name("landing-page");

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

		Route::get('/getInfoNormales/{folio}', 'SolicitudesController@getNormales')->name("tramite.complementaria");
	});

	Route::post('/respuestaPagoBBVA', "TramitesController@respuestaPagoBBVA")->name("respuestaPagoBBVA");
});
