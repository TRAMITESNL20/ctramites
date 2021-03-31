<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class ReferenceController extends Controller {
    public function paid (Request $request, $reference) {
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

		if($reference->{'oper_transacciones-estatus'} != 0 && !$request->has('dev'))
			return abort(409, 'El estatus actual de la referencia es diferente de pagado (0).');

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
						'mensaje' => "Solicitud cerrada porque esta asignado al admin"
					]);
				}
			}
		}

		return [
			"code" => 200,
			"response" => "ok"
		];
    }

    public function cancel (Request $request, $reference) {
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

		if($reference->{'oper_transacciones-estatus'} != 99 && !$request->has('dev'))
			abort(409, 'El estatus actual de la referencia es diferente de cancelado (60) o devuelto (X).');

		$update = DB::connection('db_portal')
		->table('solicitudes_tramite')
		->where('id', $reference->{'solicitudes_tramite-id'})
		->update([
			'solicitudes_tramite.estatus' => $request->has('dev') ? 99 : $reference->{'oper_transacciones-estatus'}
		]);

		if($update){
			foreach($reference->solicitudes as $solicitud){
				$update = DB::connection('db_portal')
				->table('solicitudes_ticket')
				->where('id', $solicitud->id)
				->update([
					'solicitudes_ticket.status' => 5
				]);

				if($update){
					DB::connection('db_portal')
					->table('solicitudes_mensajes')
					->where('ticket_id', $solicitud->id)
					->delete();
				}
			}
		}

		return [
			"code" => 200,
			"response" => "ok"
		];
    }
}
