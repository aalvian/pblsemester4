<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $Url;

    public function __construct($details, $Url)
    {
        $this->details = $details;
        $this->Url = $Url;
    }

    public function build()
    {
        return $this->subject('Email Status Pendaftaran')
                    ->view('mail.send'); // atau ->markdown('emails.my_email');
    }
}
