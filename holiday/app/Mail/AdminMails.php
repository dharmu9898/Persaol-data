<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;



class AdminMails extends Mailable
{
    use Queueable, SerializesModels;
  
    public $AdminMailsend;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($AdminMailsend)
    {
        $this->AdminMailsend = $AdminMailsend;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@scmgalaxy.com')
        ->subject(' Trip is Created ')
        ->view('dynamic_email_template_AdminMails')
        ->with('data', $this->AdminMailsend);
    }
}
