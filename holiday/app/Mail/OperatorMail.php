<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;



class OperatorMail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $OperatorMailsend;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($OperatorMailsend)
    {
        $this->OperatorMailsend = $OperatorMailsend;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@scmgalaxy.com')
        ->subject(' I Would Attend  - RVSP ')
        ->view('dynamic_email_template_ManagerMail')
        ->with('data', $this->OperatorMailsend);
    }
}
