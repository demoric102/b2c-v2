<?php

namespace App\Mail;
use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FicoReport extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {      
        return $this->from('b2c@crccreditbureau.com')
                ->view('email.fico-report-stmt')
                ->attach('storage/fico/'.$this->data[0]['bvn'].'.pdf', [
                    'as' => 'Credit Score.pdf',
                    'mime' => 'application/pdf',
                ])
                ->with([
                    'data' => $this->data
                ]);
    }
}
