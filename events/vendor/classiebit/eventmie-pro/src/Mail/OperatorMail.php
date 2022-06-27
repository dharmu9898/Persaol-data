<?php
namespace Classiebit\Eventmie\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

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
        
       
        return $this->from('info@holidaylandmark.com','holidaylandmark')
        ->view('eventmie::mail.dynamic_email_template_ManagerMails', ['data'=>$this->OperatorMailsend]);
        
    }
}
