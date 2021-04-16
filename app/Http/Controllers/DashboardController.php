<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index () {
    	$user = \Session::get('user');
    	if(isset($user->notary)) $notary = $user->notary->id;

    	$solicitudes = curlSendRequest('POST', getenv("TESORERIA_HOSTNAME")."/solicitudes-filtrar/count", [
    		"data" => [
    			[ "estatus" => 80, "id_usuario" => (int)$user->id ],
    			[ "estatus" => 99, "id_usuario" => (int)$user->id ],
    			[ "estatus" => 5, "id_usuario" => (int)$user->id ],
    			[ "estatus" => 3, "id_usuario" => (int)$user->id ],
    			[ "estatus" => 1, "id_usuario" => (int)$user->id ],
    			[ "estatus" => 98, "id_usuario" => (int)$user->id ],
    			[ "estatus" => 2, "id_usuario" => (int)$user->id ]
    		]
    	]);

		$draft = $solicitudes[0];
		$pendingPayment = $solicitudes[1];
		$pendingPaymentReference = $solicitudes[2];
		$waiting = $solicitudes[3];
		$progress = $solicitudes[4];
		$toSign = $solicitudes[5];
		$closed = $solicitudes[6];
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
				"pendingPaymentReference" => count($pendingPaymentReference),
				"waiting" => count($waiting),
				"progress" => count($progress),
				"toSign" => count($toSign),
				"closed" => count($closed)
			],
			"draft" => array_splice($draft, 0, $total),
			"pendingPayment" => array_splice($pendingPayment, 0, $total),
			"pendingPaymentReference" => array_splice($pendingPaymentReference, 0, $total),
			"waiting" => array_splice($waiting, 0, $total),
			"progress" => array_splice($progress, 0, $total),
			"toSign" => array_splice($toSign, 0, $total),
			"closed" => array_splice($closed, 0, $total)
		]);
    }
}
