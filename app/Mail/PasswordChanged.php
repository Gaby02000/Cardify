<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class PasswordChanged extends Mailable
{
    public $user;

    // Constructor para recibir el usuario y usarlo en el correo
    public function __construct($user)
    {
        $this->user = $user;
    }

    // Método para construir el correo
   public function build()
{
    return $this->from('chinogimenez0000@gmail.com', 'Cardify')
        ->view('emails.email_password_changed')
        ->with([
            'name' => $this->user->name,  
        ]);
}
}
