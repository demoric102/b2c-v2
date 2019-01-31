<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDoc extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('b2c@crccreditbureau.com')
                ->view('email.documentation')
                ->attach('storage/documentation.pdf', [
                    'as' => 'CRC B2C Documentation.pdf',
                    'mime' => 'application/pdf',
                ]);
    }
}
