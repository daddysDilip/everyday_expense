<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\API\UserTransaction;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class TransactionController extends BaseController
{
    /**
     * @return \Illuminate\Http\Response
     *  insert new user transactions
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'          => 'required',
            'user_category_id' => 'required',
            'transaction_date' => 'required',
            'transaction_type' => 'required|in:0,1',
            'currency_symbol'  => 'required',
            'total_amount'     => 'required',
            'ticket_id'        => 'required'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $transation = new UserTransaction;

        $transation->user_id          = $request->user_id;
        $transation->user_category_id = $request->user_category_id;
        $transation->transaction_date = $request->transaction_date;
        $transation->transaction_type = $request->transaction_type;
        $transation->currency_symbol  = $request->currency_symbol;
        $transation->total_amount     = $request->total_amount;
        $transation->ticket_id        = $request->ticket_id;
        $transation->remark           = $request->remark;
        
        $transation->save();
        
        $success =  $transation;
        if($request->transaction_type == '1')
            return $this->sendResponse($success, 'successfully add expense.');
        else 
            return $this->sendResponse($success, 'successfully add income.');
    }
    
    /**
     * @return \Illuminate\Http\Response
     *  edit existing ticket of a user
     */
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'          => 'required',
            'user_category_id' => 'required',
            'transaction_date' => 'required',
            'transaction_type' => 'required|in:0,1',
            'currency_symbol'  => 'required',
            'total_amount'     => 'required',
            'ticket_id'        => 'required',
            'transaction_id'   => 'required'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $transation = UserTransaction::where('id', $request->transaction_id)->first();
        $transation->user_id          = $request->user_id;
        $transation->user_category_id = $request->user_category_id;
        $transation->transaction_date = $request->transaction_date;
        $transation->transaction_type = $request->transaction_type;
        $transation->currency_symbol  = $request->currency_symbol;
        $transation->total_amount     = $request->total_amount;
        $transation->ticket_id        = $request->ticket_id;
        $transation->remark           = $request->remark;
        
        $transation->save();
        $success =  $transation;
        
        if($request->transaction_type == '1')
            return $this->sendResponse($success, 'expense updated successfully.');
        else 
            return $this->sendResponse($success, 'income updated successfully.');
    }

    /*
    *   for delete an user's transaction
    */
    function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id'   => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $transation = UserTransaction::where('id', $request->transaction_id)->first();
        if($transation->transaction_type == 1)
        {
            $msg = 'expense deleted successfully';
        }else {
            $msg = 'income deleted successfully';
        }
        $transation->delete();
        $success = [];
        return $this->sendResponse($success, $msg);
    }

    /*
    *   get all currency symbols
    */
    function getSymbols()
    {
        $symbols = DB::table('country')
                ->select('currency_name','currency_symbol')->distinct()
                ->where('status', 1)->get();

        return $this->sendResponse($symbols->toArray(), 'Retrieve currency symbol successfully.');
    }

    /*
    *   get all transactions of a ticket
    */
    function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        if($request->from_date != "" && $request->to_date != "")
        {
            $total = DB::table('user_tickets as uti')
                    ->leftJoin('user_transactions as ut', 'ut.ticket_id', '=', 'uti.id')
                    ->select(DB::raw('(SUM(IF(ut.transaction_type = "0", ut.total_amount, 0)) - SUM(IF(ut.transaction_type = "1", ut.total_amount, 0))) as total_amount'))
                    ->where('uti.id', $request->ticket_id)
                    ->whereBetween('ut.transaction_date', array($request->from_date, $request->to_date))
                    ->groupBy('uti.id')
                    ->get();

            $income = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw('DATE_FORMAT((user_transactions.transaction_date), "%d/%m/%Y") as transaction_date'), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->whereBetween('user_transactions.transaction_date', array($request->from_date, $request->to_date))
                    ->where('ticket_id', $request->ticket_id)->where('transaction_type', '0')->get();

            $expense = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw('DATE_FORMAT((user_transactions.transaction_date), "%d/%m/%Y") as transaction_date'), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->whereBetween('user_transactions.transaction_date', array($request->from_date, $request->to_date))
                    ->where('ticket_id', $request->ticket_id)->where('transaction_type', '1')->get();
        } else {
            $total = DB::table('user_tickets as uti')
                    ->leftJoin('user_transactions as ut', 'ut.ticket_id', '=', 'uti.id')
                    ->select(DB::raw('(SUM(IF(ut.transaction_type = "0", ut.total_amount, 0)) - SUM(IF(ut.transaction_type = "1", ut.total_amount, 0))) as total_amount'))
                    ->where('uti.id', $request->ticket_id)
                    ->groupBy('uti.id')
                    ->get();

            $income = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw('DATE_FORMAT((user_transactions.transaction_date), "%d/%m/%Y") as transaction_date'), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->where('ticket_id', $request->ticket_id)->where('transaction_type', '0')->get();

            $expense = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw('DATE_FORMAT((user_transactions.transaction_date), "%d/%m/%Y") as transaction_date'), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->where('ticket_id', $request->ticket_id)->where('transaction_type', '1')->get();
        }

        $transations['total'] = $total->toArray();
        $transations['income'] = $income->toArray();
        $transations['expense'] = $expense->toArray();
        return $this->sendResponse($transations, 'Retrieve transactions successfully.');
    }
}