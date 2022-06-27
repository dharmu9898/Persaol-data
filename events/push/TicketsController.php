<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Classiebit\Eventmie\Models\Currency;
use Auth;
use Redirect;
use File;
use Log;
use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\Ticket;
use Classiebit\Eventmie\Models\Category;
use Classiebit\Eventmie\Models\Country;
use Classiebit\Eventmie\Models\Schedule;
use Classiebit\Eventmie\Models\Tax;

class TicketsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // language change
        $this->middleware('common');
    
        $this->middleware('auth');

        $this->event    = new Event;

        $this->ticket   = new Ticket;

        $this->tax      = new Tax;
        
        $this->organiser_id = null;   
    }
     // get tickets by events
    public function tickets(Request $request)
    {
        // 1. validate data
        $request->validate([
            'event_id'          => 'required',
        ]);
        
        // if logged in user is admin
        $this->is_admin($request);

        $check_event    = $this->event->get_user_event($request->event_id, $this->organiser_id);

        if(empty($check_event))
        {
            return error(__('eventmie-pro::em.event').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );
        }

        $params    = [
            'event_id' =>  $request->event_id,
        ];
        $tickets   = $this->ticket->get_event_tickets($params);
        
        if($tickets->isEmpty())
        {
            return response()->json(['status' => false, 'currency' => setting('regional.currency_default')]);    
        }
 
        return response()->json(['tickets' => $tickets, 'status' => true, 'currency' => setting('regional.currency_default')]);
    }

    // get taxes for tickets
    public function taxes()
    {
        $taxes    = $this->tax->get_taxes();

        if(empty($taxes))
            return error(__('eventmie-pro::em.tax').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );

        return response()->json(['status' => true, 'taxes' => $taxes ]);
    }

    // add/edit tickets
    public function store(Request $request)
    {
        log::info('inside ticket store');
        log::info($request);


        $currece=Currency::where('currency_id',$request->currency_id)->first();
        Log::info('Currency ka data');
        Log::info($currece);
        Log::info('Currency id ka data');
        $name_currency= $currece->currencies;
        Log::info($name_currency);

        // if logged in user is admin
        $this->is_admin($request);
        

        $use_id=Auth::id();
        $use_email=Auth::User()->email;
        
        log::info('mera use id');
        log::info($use_email);
        // float validation and don't except nagitive value
        $regex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";
        // 1. validate data
        $request->validate([
            
           
            'price'         => ['required','regex:'.$regex],
            'description'   => 'max:512',
            'event_id'      => 'required|numeric',
           
        ]);
        
        // check event id with login user that event id valid or not
        $check_event            = $this->event->get_user_event($request->event_id, $this->organiser_id);

        if(empty($check_event))
        {
            return error('access denied!', Response::HTTP_BAD_REQUEST );
        }

        $params    = [
            'event_id' =>  $request->event_id,
        ];
        
        $params = [



            "title"        => $request->titleevent,
            "price"        => $request->price,
            "quantity"     => $request->evquantity,
            "description"  => $request->description,
            "event_id"     => $request->event_id,
            "ticket_currency"     => $request->currency_id,
            "ticket_type"     => $request->title_id,
            'user_id'      => $use_id,
            'operator_email'  => $use_email,
            "currency_name"     => $name_currency,
        ];
 
        $ticket_id  = $request->ticket_id;
        log::info($ticket_id);
        $ticket     =  $this->ticket->add_tickets($params, $ticket_id);
        
        if(empty($ticket))
        {
            return response()->json(['status' => false]);    
        }

        // add data in tax_ticket pivot table
        $taxes_ids    = json_decode($request->taxes_ids, true);
        log::info($taxes_ids);
        $ticket->taxes()->sync($taxes_ids);

        // if have tickets then check free tickets or not
        $tickets   = $this->ticket->get_event_tickets($params);
        
        // if($tickets->isNotEmpty())
        // {
        //     // check free tickets        
        //     $free_tickets           = $this->ticket->check_free_tickets($request->event_id);
            
        //     if(!empty($free_tickets) || (int)$request->price <= 0)
        //     {
        //         $params = [
        //             'price_type' => 0
        //         ];

        //         // update price type column of event table by 1
        //         $this->event->update_price_type($request->event_id, $params);
        //     }
        //     else
        //     {
        //         $params = [
        //             'price_type' => 1
        //         ];
        //         // update price type column of event table by 0
        //         $this->event->update_price_type($request->event_id, $params);
        //     }
        // }  

        // get update event
        $event            = $this->event->get_user_event($request->event_id, $this->organiser_id);
        // set step complete
        $this->complete_step($event->is_publishable, 'tickets', $request->event_id);

        return response()->json(['status' => true]);
        
    }

    // delete ticket base on id
    public function delete(Request $request)
    {
        // if logged in user is admin
        $this->is_admin($request);

        // 1. validate data
        $input = $request->validate([
            'ticket_id'   => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            'event_id'    => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
        ]);

        // check event id with login user that event id valid or not
        $event            = $this->event->get_user_event($request->event_id, $this->organiser_id);

        if(empty($event))
        {
            return error('access denied!', Response::HTTP_BAD_REQUEST );
        }

        // get event tickets
        $params    = [
            'event_id' =>  $request->event_id,
        ];

        $tickets   = $this->ticket->get_event_tickets($params);
        
        if($tickets->isEmpty())
        {
            return response()->json(['status' => false, 'message' => __('eventmie-pro::em.tickets').' '.__('eventmie-pro::em.not_found')]);    
        }

        if(count($tickets) <= 1)    
            return error(__('eventmie-pro::em.ticket').' '.__('eventmie-pro::em.required'), Response::HTTP_BAD_REQUEST );
            

        $delete    = $this->ticket->delete_tickets($input['ticket_id']);

        if(empty($delete))
        {
            return response()->json(['status' => false]);    
        }
    
        return response()->json(['status' => true]);
    }

    // check user authentication
    protected function is_admin(Request $request)
    {
        // if login user is Organiser then 
        // organiser id = Auth::id();
        $this->organiser_id = Auth::id();

        // if admin is creating event
        // then user Auth::id() as $organiser_id
        // and organiser id will be the id selected from Vue dropdown
        if(Auth::user()->hasRole('admin'))
        {
            $request->validate([
                'organiser_id'       => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            ]);
            
            $this->organiser_id = $request->organiser_id;
        }
    }

    // complete specific step
    protected function complete_step($is_publishable = [], $type = 'detail', $event_id = null)
    {
        if(!empty($is_publishable))
            $is_publishable             = json_decode($is_publishable, true);

        $is_publishable[$type]      = 1;
        
        // save is_publishable
        $params     = ['is_publishable' => json_encode($is_publishable)];
        $status     = $this->event->save_event($params, $event_id);

        return true;
    }
}