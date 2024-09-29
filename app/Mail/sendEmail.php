<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $Url;

    public function __construct($details, $Url)
    {
        $this->details = $details;
        $this->Url = $Url;
    }
// 
    public function build()
    {
        return $this->subject('Email Status Pendaftaran')
                    ->view('mail.send'); // atau ->markdown('emails.my_email');
    }
}
