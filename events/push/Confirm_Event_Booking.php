<?php
namespace Classiebit\Eventmie\Mail;
use Log;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Confirm_Event_Booking extends Mailable
{
    use Queueable, SerializesModels;
      public $eveadminMailsend;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($eveadminMailsend)
    {
        $this->eveadminMailsend = $eveadminMailsend;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        log::info('data aa raha hai');    
             
        return $this->from('info@holidaylandmark.com','holidaylandmark')
        ->view('eventmie::mail.dynamic_email_template_EveManagerMailsadmin', ['data'=>$this->eveadminMailsend]);
        
    }
}
