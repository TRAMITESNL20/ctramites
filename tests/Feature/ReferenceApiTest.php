<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReferenceApiTest extends TestCase {

    public function testDeleteReference() {
        $response = $this->json('delete', 'api/reference-payment', ['referencia' => '3333']);
        $VAR = $response->baseResponse->getOriginalContent();
        
        $response->assertStatus(200);
        $this->assertEquals(1, count($VAR["message"]));
        $this->assertEquals("La referencia \"3333\" no se encuentra en la base de datos de portal", $VAR["message"][0]);
    }

}
