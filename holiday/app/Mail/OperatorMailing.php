<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;



class OperatorMailing extends Mailable
{
    use Queueable, SerializesModels;
  
    public $OperatorMailsends;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($OperatorMailsends)
    {
        $this->OperatorMailsends = $OperatorMailsends;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@scmgalaxy.com')
        ->subject(' Trip is created ')
        ->view('dynamic_email_template_ManagerMailing')
        ->with('data', $this->OperatorMailsends);
    }
}
