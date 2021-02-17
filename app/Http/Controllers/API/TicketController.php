<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\API\UserTicket;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class TicketController extends BaseController
{
    /**
     * @return \Illuminate\Http\Response
     *  create new ticket in user
     */
    public function addUserTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required',
            'name'       => 'required'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $ticket = new UserTicket;
        $ticket->name    = $request->name;
        $ticket->user_id = $request->user_id;
        
        $ticket->save();
        
        $success =  $ticket;
        
        return $this->sendResponse($success, 'Ticket generated successfully.');
    }
    
    /**
     * @return \Illuminate\Http\Response
     *  edit existing ticket of a user
     */
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required',
            'name'      => 'required'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $ticket = UserTicket::where('id', $request->ticket_id)->first();
        $ticket->name    = $request->name;
        
        $ticket->save();
        $success =  $ticket;
        
        return $this->sendResponse($success, 'Ticket updated successfully.');
    }

}