<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index () {
    	$user = \Session::get('user');
    	if(isset($user->notary)) $notary = $user->notary->id;

    	$counts = curlSendRequest('POST', getenv("TESORERIA_HOSTNAME")."/solicitudes-filtrar/count", [
    		"data" => [
    			[ "estatus" => 80, "id_usuario" => (int)$user->id ],
    			[ "estatus" => 99, "id_usuario" => (int)$user->id ],
    			[ "estatus" => 3, "id_usuario" => (int)$user->id ],
    			[ "estatus" => 1, "id_usuario" => (int)$user->id ]
    		]
    	]);
    	$pendingPayment = curlSendRequest('POST', getenv("TESORERIA_HOSTNAME")."/solicitudes-filtrar", [ "estatus" => 99, (isset($notary) ? "notary_id" : "id_usuario") => (int)(isset($notary) ? $notary : $user->id) ]);
    	$waiting = curlSendRequest('POST', getenv("TESORERIA_HOSTNAME")."/solicitudes-filtrar", [ "estatus" => 3, (isset($notary) ? "notary_id" : "id_usuario") => (int)(isset($notary) ? $notary : $user->id) ]);
    	$progress = curlSendRequest('POST', getenv("TESORERIA_HOSTNAME")."/solicitudes-filtrar", [ "estatus" => 1, (isset($notary) ? "notary_id" : "id_usuario") => (int)(isset($notary) ? $notary : $user->id) ]);
    	$total = 5;

		set_layout_arg([
			"subtitle" => "Dashboard",
			"fluid_container"=> true
		]);

		$draft = $draft ?? [];
		$pendingPayment = $pendingPayment ?? [];
		$waiting = $waiting ?? [];
		$progress = $progress ?? [];

		return layout_view("dashboard", [
			"totals" => [
				"draft" => count($draft),
				"pendingPayment" => count($pendingPayment),
				"waiting" => count($waiting),
				"progress" => count($progress),
			],
			"draft" => array_splice($draft, 0, $total),
			"pendingPayment" => array_splice($pendingPayment, 0, $total),
			"waiting" => array_splice($waiting, 0, $total),
			"progress" => array_splice($progress, 0, $total)
		]);
    }
}
