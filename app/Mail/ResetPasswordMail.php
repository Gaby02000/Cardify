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
        // Se arma con route() y no a mano: así el link siempre apunta a la ruta
        // real de reseteo aunque cambie la URL.
        $resetUrl = route('password.reset', ['token' => $this->token, 'email' => $this->email]);

        return $this->from('chinogimenez0000@gmail.com', 'Cardify')
                    ->subject('Restablecer Contraseña - Cardify')
                    ->view('emails.email_reset_password')  
                    ->with([
                        'resetUrl' => $resetUrl,  
                    ]);
    }
}
