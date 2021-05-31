<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReferenceApiTest extends TestCase {

    public function testDeleteReference() {

        $response = $this->json('delete', 'api/reference-payment', ['referencia' => '3333']);

        var_dump(json_encode($response));
        $VAR = $response->baseResponse->getOriginalContent();

        var_dump($VAR);
    }

}
