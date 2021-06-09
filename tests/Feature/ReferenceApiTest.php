<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use DB;

class ReferenceApiTest extends TestCase {

    public $reference = "999999xxx";

    protected function getReference($estatus) {

        $fecha_consulta = date("Y-m-d", strtotime(date("Y-m-d") . "- 15 days"));
        $datosLimite = DB::table('operacion.oper_transacciones as A')
                ->select('A.referencia')
                ->where('A.referencia', '!=', '')
                ->where('A.estatus', '=', $estatus)
                ->where('A.fecha_transaccion', '<=', $fecha_consulta)
                ->orderByDesc('A.id_transaccion_motor')
                ->limit(1)
                ->get();
        $this->reference = $datosLimite[0]->referencia;
    }

    // delete
    public function testCancelURLReferenceNotFound() {
        $reference = $this->reference;
        $response = $this->json('delete', 'api/reference-payment/' . $reference);
        $VAR = $response->baseResponse->getOriginalContent();
        $response->assertStatus(200);
        $this->assertEquals(1, count($VAR["message"]));
        $this->assertEquals("La referencia \"$reference\" no se encuentra en la base de datos de portal", $VAR["message"][0]);
    }

    public function testCancelReferenceNotFound() {
        $reference = $this->reference;
        $response = $this->json('delete', 'api/reference-payment', ['referencia' => $reference]);
        $VAR = $response->baseResponse->getOriginalContent();
        $response->assertStatus(200);
        $this->assertEquals(1, count($VAR["message"]));
        $this->assertEquals("La referencia \"$reference\" no se encuentra en la base de datos de portal", $VAR["message"][0]);
    }

    public function testCancelURLNOReference() {
        $response = $this->json('delete', 'api/reference-payment/');
        $VAR = $response->baseResponse->getOriginalContent();
        $response->assertStatus(409);
        $this->assertEquals("error", $VAR["response"]);
        $this->assertEquals("No hay referencias para validar.", $VAR["message"]);
    }

    public function testCancelEmptyReference() {
        $reference2 = "";
        $response = $this->json('delete', 'api/reference-payment', [$reference2]);
        $VAR = $response->baseResponse->getOriginalContent();
        $response->assertStatus(200);
        $this->assertEquals(1, count($VAR["message"]));
        $this->assertEquals("La referencia \"$reference2\" no se encuentra en la base de datos de portal", $VAR["message"][0]);
    }

    public function testCancelMultipleReferenceOK() {
        $this->getReference(65);
        $reference1 = $this->reference;
        $this->getReference(65);
        $reference2 = $this->reference;
        $reference = array($reference1, $reference2);
        $response = $this->json('delete', 'api/reference-payment', $reference);
        $VAR = $response->baseResponse->getOriginalContent();
        $response->assertStatus(200);
        $this->assertEquals(2, count($VAR["message"]));
        $this->assertEquals("Referencia Actualizada: $reference1", $VAR["message"][0]);
        $this->assertEquals("Referencia Actualizada: $reference2", $VAR["message"][1]);
    }

    public function testCancelMultipleReferenceItemNoFound() {
        $reference1 = $this->reference;
        $reference2 = "";
        $reference = array($reference1, $reference2);
        $response = $this->json('delete', 'api/reference-payment', $reference);
        $VAR = $response->baseResponse->getOriginalContent();
        $response->assertStatus(200);
        $this->assertEquals(2, count($VAR["message"]));
        $this->assertEquals("La referencia \"$reference1\" no se encuentra en la base de datos de portal", $VAR["message"][0]);
        $this->assertEquals("La referencia \"$reference2\" no se encuentra en la base de datos de portal", $VAR["message"][1]);
    }

//     post
    public function testReferencePaidOK() {
        $this->getReference(0);
        $response = $this->json('post', 'api/reference-payment/' . $this->reference);
        $VAR = $response->baseResponse->getOriginalContent();
        $response->assertStatus(200);
        $this->assertEquals("ok", $VAR["response"]);
    }

    public function testPaidNOReference() {
        $referencia = "";
        $response = $this->json('post', 'api/reference-payment/' . $referencia);
        $VAR = $response->baseResponse->getOriginalContent();
        $this->assertEquals("The POST method is not supported for this route. Supported methods: DELETE.", $VAR["message"]);
        $response->assertStatus(405);
    }

    public function testPaidReferenceNoFound() {
        $referencia = $this->reference;
        $response = $this->json('post', 'api/reference-payment/' . $referencia);
        $VAR = $response->baseResponse->getOriginalContent();
        $this->assertEquals("error", $VAR["response"]);
        $this->assertEquals("Esta referencia '$referencia' no se encuentra en la base de datos de portal", $VAR["message"]);
        $response->assertStatus(409);
    }

    public function testPaidErrorStatus() {
        $this->getReference(65);
        $referencia = $this->reference;
        $response = $this->json('post', 'api/reference-payment/' . $referencia);
        $VAR = $response->baseResponse->getOriginalContent();
        $this->assertEquals("error", $VAR["response"]);
        $this->assertEquals("El estatus actual de la referencia '$referencia' es diferente de pagado (0).", $VAR["message"]);
        $response->assertStatus(409);
    }
}
