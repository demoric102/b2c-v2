<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LiveRequestReport extends Mailable
{
    use Queueable, SerializesModels;

    public $file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('b2c@crccreditbureau.com')
        ->view('email.live-request')
        ->attach($this->file, [
            'as' => 'Credit Information Report.pdf',
            'mime' => 'application/pdf',
        ]);
    }
}
