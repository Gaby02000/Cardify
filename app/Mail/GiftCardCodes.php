<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftCardCodes extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $codes;

    public function __construct($user, $codes)
    {
        $this->user = $user;
        $this->codes = $codes;
    }

    public function build()
    {
        return $this->subject('Tus códigos de Gift Card')
                    ->view('emails.giftcard-codes');
    }
}
