<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function assertSuccessJSONResponse( $response )
    {
        $response->assertJsonStructure([
                 'data' => [
                   'id',
                   'type'
                 ]
             ]);

    }

    public function assertErrorJSONResponse( $response, $statusCode, $errorCode )
    {
        $response->assertJsonStructure([
                 'errors' => [
                   '*' => [
                     'status',
                     'code',
                     'title',
                     'detail'
                   ]

                 ]
             ]);

        $response->assertJsonFragment([
            'status' => $statusCode,
            'code' => $errorCode
        ]);
    }


}
