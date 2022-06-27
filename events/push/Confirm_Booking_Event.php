<?php
namespace Classiebit\Eventmie\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Confirm_Booking_Event extends Mailable
{
    use Queueable, SerializesModels;
      public $EvebtopMailsend;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($EvebtopMailsend)
    {
        $this->EvebtopMailsend = $EvebtopMailsend;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
              
        return $this->from('info@holidaylandmark.com','holidaylandmark')
        ->view('eventmie::mail.dynamic_email_template_EventMailsoperator', ['data'=>$this->EvebtopMailsend]);
        
    }
}
