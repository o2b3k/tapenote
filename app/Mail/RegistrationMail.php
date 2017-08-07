<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class RegistrationMail extends Mailable
{
    /**
     * User registration token
     *
     * @var string
     */
    private $token;


    /**
     * RegistrationMail constructor.
     *
     * @param $token string Registration token of invited user
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the message
     *
     * return $this
     */
    public function build()
    {
        return $this->view('mail.registration', ['token' => $this->token]);
    }
}