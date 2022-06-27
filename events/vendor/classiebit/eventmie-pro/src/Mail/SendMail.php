<?php
namespace Classiebit\Eventmie\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
      public $OperatorMailsend;
      public $validToken;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($OperatorMailsend,$validToken)
    {
        $this->OperatorMailsend = $OperatorMailsend;
        $this->validToken = $validToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
       
        return $this->from('info@holidaylandmark.com','holidaylandmark')
        ->subject(' Now, you click for registeration by click activation button ')
       
        ->view('eventmie::mail.dynamic_email_template_ManagerMail', ['validToken'=>$this->validToken,'data'=>$this->OperatorMailsend]);
        
    }
}
