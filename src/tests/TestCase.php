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

    public function assertErrorJSONResponse( $response )
    {
        $response->assertJsonStructure([
                 'errors' => [
                   'status',
                   'code',
                   'title',
                   'detail'
                 ]
             ]);
    }


}
