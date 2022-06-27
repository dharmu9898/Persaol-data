<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Tax;
use App\Ticket;

class Tax_ticket extends Model
{
    protected $guarded = [];
    protected $table = 'tax_ticket';
    public $timestamps = false;
    public function save_event($tax_id, $tick_id)
    {
       log::info('Tax my save_event');// if have no event id then create new event
       log::info($tax_id);
       log::info($tick_id);
       $widget_id = str_replace(array('[',']'), '',$tax_id);
       $explode_id = explode(',', $widget_id);
       log::info($explode_id);
       $get_days= Tax::whereIn('id',$explode_id)->pluck('id');
       log::info($get_days);
       foreach ($get_days as $myday) {
           
        Tax_ticket::create(['ticket_id' => $tick_id,'tax_id' => $myday]);
  

}
      
}
    public function get_taxes()
    {
        $result = Tax::where(['status' => 1])->get();
    
        return to_array($result);
    }

    public function get_admin_taxes()
    {
        return Tax::where(['status' => 1])->get();
        
    }
}