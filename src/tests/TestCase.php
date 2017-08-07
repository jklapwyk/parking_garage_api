<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
    * An assertion for all Test Cases for a successful JSON response
    */
    public function assertSuccessJSONResponse( $response )
    {
        $response->assertJsonStructure([
                 'data' => [
                   'id',
                   'type'
                 ]
             ]);

    }

    /**
    * An assertion for all Test Cases for an error JSON response
    */
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
