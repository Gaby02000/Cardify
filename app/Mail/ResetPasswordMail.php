<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class ResetPasswordMail extends Mailable
{
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        $resetUrl = url("password/reset/{$this->token}?email={$this->email}");

        return $this->from('chinogimenez0000@gmail.com', 'Cardify')
                    ->subject('Restablecer Contraseña - Cardify')
                    ->view('emails.email_reset_password')  
                    ->with([
                        'resetUrl' => $resetUrl,  
                    ]);
    }
}
