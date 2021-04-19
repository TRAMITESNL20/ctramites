<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade as PDF;


class FormatoDeclaracionController extends Controller
{
    public function index ($id) {
		$enajenante="";
		$tramite = curlSendRequest("GET", getenv("TESORERIA_HOSTNAME")."/solicitudes-get-tramite-pdf/{$id}");
		if(isset($tramite->tramite) && count( $tramite->tramite->solicitudes) > 0){
			$info = $tramite->tramite;
			// dd($info);
			$control = $tramite;
			if($info->solicitudes[0]->info->tipoTramite != 'complementaria'){
				$enajenante = $info->solicitudes[0]->info->enajenante;
			}
			$tipoTramite =  $info->solicitudes[0]->info->tipoTramite;
			$user = session()->get("user");
			
			$pdf = PDF::loadView('pdf.formatoDeclaracionISR', compact('info', 'enajenante', 'tipoTramite', 'control', 'user'));
			$tipo = "";
			$escritura = $info->solicitudes[0]->info->campos->{'Escritura'} ?? "";
			switch ($info->solicitudes[0]->info->{'tipoTramite'}) {
				case 'declaracionEn0': $tipo = " - EN CERO"; break;
				default: $tipo = " - ".strtoupper($info->solicitudes[0]->info->{'tipoTramite'}); break;
			}
			return $pdf->stream(($escritura ? "{$escritura} - " : "")."{$info->tramite}".($tipo).".pdf");
			// return view("pdf/formatoDeclaracionISR", [ "info" => $info , "enajenante" => $enajenante, "tipoTramite" =>$tipoTramite ]);

		}

		dd("No existe un trámite con el ID '{$id}' en nuestro registro.");
    }
}
