<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

    /**
     * @return \Illuminate\Http\Response
     *  get all tickets of a user
     */
    public function getUserTickets(Request $request)
    {
        try{
            echo 10/0;
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            $header = substr($request->header('Authorization'), 7);
            if(!empty($request->search))
            {
                $search = $request->search;
                $ticket = UserTicket::leftJoin('user_transactions as ut', 'ut.ticket_id', '=', 'user_tickets.id')
                    ->select(DB::raw(date_query("user_tickets.created_at",$header,"ticket_date")), 'user_tickets.name', DB::raw('(SUM(IF(ut.transaction_type = "0", ut.total_amount, 0)) - SUM(IF(ut.transaction_type = "1", ut.total_amount, 0))) as total_amount'), 'user_tickets.id')
                    ->where('user_tickets.user_id', $request->user_id)
                    ->where('user_tickets.name', 'LIKE', "%{$search}%")
                    ->groupBy('user_tickets.id')
                    ->get();    
            } else {
                $ticket = UserTicket::leftJoin('user_transactions as ut', 'ut.ticket_id', '=', 'user_tickets.id')
                        ->select(DB::raw(date_query("user_tickets.created_at",$header,"ticket_date")), 'user_tickets.name', DB::raw('(SUM(IF(ut.transaction_type = "0", ut.total_amount, 0)) - SUM(IF(ut.transaction_type = "1", ut.total_amount, 0))) as total_amount'), 'user_tickets.id')
                        ->where('user_tickets.user_id', $request->user_id)
                        ->groupBy('user_tickets.id')
                        ->get();
            }
            $success =  $ticket;
            return $this->sendResponse($success, 'Retrieve tickets successfully.');

        } catch (Exception $e) {
            die('adadaadad');
            $this->reportException($e);
            // This calls the report() method of `App\Exceptions\Handler`

            $response = $this->renderException($request, $e);
            // This calls the render() method of `App\Exceptions\Handler`
        }
    }

}