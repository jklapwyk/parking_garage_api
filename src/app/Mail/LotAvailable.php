<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
//use App\Models\User;

class LotAvailable extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $userName )
    {
        $this->userName = $userName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@parkingapi.com', 'Parking Api - No Reply')
                ->subject('Parking Lot Available')
                ->view('emails.notification')
                ->with([
                        'userName' => $this->userName
                    ]);
    }
}
