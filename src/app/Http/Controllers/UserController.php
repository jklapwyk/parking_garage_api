<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Repositories\UserRepositoryInterface;
use App\Models\User;
use App\Models\ParkingVenueQueue;
use App\Models\ParkingVenue;

class UserController extends ApiController
{

    protected $userRepository;


    /**
     * Request various repositories and services
     *
     * @param UserRepositoryInterface
     */
    public function __construct( UserRepositoryInterface $userRepository )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Create User
     *
     * @param Request
     * @return Response
     */
    public function createUser(Request $request )
    {

        $jsonData = $request->json()->all();
        $data = $jsonData['data'];
        $attributes = $data['attributes'];
        $firstName = $attributes['first_name'];
        $lastName = $attributes['last_name'];
        $email = $attributes['email'];
        $password = $attributes['password'];

        $sameEmailUser = User::where('email', $email)->first();

        if( isset($sameEmailUser) ){
          return $this->sendErrorResponse( 400, 4 );
        }

        $user = $this->userRepository->createUser( $firstName, $lastName, $email, $password );


        return $this->sendSuccessResponse( 201, [
            'type' => 'user',
            'id' => $user->id,
            'attributes' => [
              'first_name' => $user->first_name,
              'last_name' => $user->last_name,
              'email' => $user->email,
            ]
          ] );
    }



}
