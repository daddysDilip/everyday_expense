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
        
        $temp_files = [];
        if ($files = $request->hasFile('transaction_images')) {
            //$delete_gallery = DB::table('tour_gallery')->where('tourid',$id)->delete();
            foreach ($request->file('transaction_images') as $file) {
                
                $file_name = uniqid().".". $file->getClientOriginalExtension();
                $directory = "./transaction/";
                if (!file_exists($directory)) {
                  mkdir($directory, 0777, true);
                }
                $save_name = "transaction/".$file_name;
                $file->move('transaction',$file_name); 
                $temp_files[] = $save_name;
                
            }
        }
        $transation->transaction_images = implode("#||#", $temp_files);

        $transation->save();
        $transation->transaction_images = explode("#||#", $transation->transaction_images);
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
        if($request->exists('transaction_images'))
        {
            $temp_files = [];
            if ($files = $request->hasFile('transaction_images')) {

                if($transation->transaction_images != "")
                {
                    foreach(explode("#||#",$transation->transaction_images) as $v)
                    {
                        if(file_exists($v))
                            unlink($v);
                    }
                }

                foreach ($request->file('transaction_images') as $file) {
                    
                    $file_name = uniqid().".". $file->getClientOriginalExtension();
                    $directory = "./transaction/";
                    if (!file_exists($directory)) {
                      mkdir($directory, 0777, true);
                    }
                    $save_name = "transaction/".$file_name;
                    $file->move('transaction',$file_name); 
                    $temp_files[] = $save_name;
                    
                }
            }
            $transation->transaction_images = implode("#||#", $temp_files);
        }

        $transation->save();
        $transation->transaction_images = explode("#||#", $transation->transaction_images);
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
        $header = substr($request->header('Authorization'), 7);
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
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'user_transactions.transaction_images', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->whereBetween('user_transactions.transaction_date', array($request->from_date, $request->to_date))
                    ->where('ticket_id', $request->ticket_id)->where('transaction_type', '0')->get();

            $expense = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.transaction_images', 'user_transactions.total_amount', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
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
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'user_transactions.transaction_images', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->where('ticket_id', $request->ticket_id)
                    ->where('transaction_type', '0')
                    ->get();

            $expense = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'user_transactions.transaction_images', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->where('ticket_id', $request->ticket_id)
                    ->where('transaction_type', '1')
                    ->get();
        }
        
        $transations['total'] = $total->toArray();
        $transations['income'] = $income->toArray();
        $transations['expense'] = $expense->toArray();
        foreach($transations['income'] as $key => $val)
        {
            if($val['category_icon'] != "")
            {
                $transations['income'][$key]['category_icon'] = asset($val['category_icon']);
            }
            if($val['transaction_images'] != "")
            {
                $transations['income'][$key]['transaction_images'] = [];
                foreach(explode("#||#", $val['transaction_images']) as $k => $v)
                {
                    $transations['income'][$key]['transaction_images'][$k] = asset($v);
                }
            } else {
                $transations['income'][$key]['transaction_images'] = [];
            }
        }
        foreach($transations['expense'] as $key1 => $val1)
        {
            if($val1['category_icon'] != "")
            {
                $transations['expense'][$key1]['category_icon'] = asset($val1['category_icon']);
            }
            if($val1['transaction_images'] != "")
            {
                $transations['expense'][$key1]['transaction_images'] = [];
                foreach(explode("#||#", $val1['transaction_images']) as $k1 => $v1)
                {
                    $transations['expense'][$key1]['transaction_images'][$k1] = asset($v1);
                }
            } else {
                $transations['expense'][$key1]['transaction_images'] = [];
            }
        }
        return $this->sendResponse($transations, 'Retrieve transactions successfully.');
    }

    /*
    *   get transaction remaining detail
    */
    function getDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $transaction = DB::table('transactions_receipts')
                    ->select('receipts_image','id')
                    ->where('transactions_id', $request->transaction_id)
                    ->get();

        $detail = $transaction->toArray();
        $return = [];
        foreach($detail as $key => $val)
        {
            $val = (array)$val;
            if($val['receipts_image'] != "")
            {
                $return[$key]['category_icon'] = asset(trim($val['receipts_image']));
            }
            $return[$key]['id'] = $val['id'];
        }
        
        return $this->sendResponse($return, 'Retrieve detail successfully.');
    }
}