<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\API\UserTransaction;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Log;

class ReportController extends BaseController
{
    /**
     * @return \Illuminate\Http\Response
     *  get all transaction of a user
     */
    public function getAllTransactions(Request $request)
    {
        $user = Auth::user();
        $header = substr($request->header('Authorization'), 7);
        if($request->from_date != "" && $request->to_date != "")
        {
            $income = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.transaction_images', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->whereBetween('user_transactions.transaction_date', array($request->from_date, $request->to_date))
                    ->where('user_transactions.user_id', $user->id)->where('transaction_type', '0')->get();

            $expense = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.transaction_images', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->whereBetween('user_transactions.transaction_date', array($request->from_date, $request->to_date))
                    ->where('user_transactions.user_id', $user->id)->where('transaction_type', '1')->get();
        } else {
            
            $income = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.transaction_images', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->where('user_transactions.user_id', $user->id)
                    ->where('transaction_type', '0')
                    ->get();

            $expense = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'user_transactions.transaction_images', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->where('user_transactions.user_id', $user->id)
                    ->where('transaction_type', '1')
                    ->get();
        }
        
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
    
    function homeData(Request $request)
    {
        $user = Auth::user();
        $total = UserTransaction::select(DB::raw('SUM(IF(transaction_type = "0", total_amount, 0)) as total_income'), DB::raw('SUM(IF(transaction_type = "1", total_amount, 0)) as total_expense'),DB::raw('(SUM(IF(transaction_type = "0", total_amount, 0)) - SUM(IF(transaction_type = "1", total_amount, 0))) as total_amount'))
            ->where('user_id', $user->id)
            ->groupBy('user_id')
            ->first();
        $users_count = DB::table('user_tickets')
             ->where('user_id', '=', $user->id)
             ->count();
        if($total)
        {
            $transations = $total->toArray();   
        } else {
            $transations['total_income'] = 0;
            $transations['total_expense'] = 0;
            $transations['total_amount'] = 0;
        }
       
        $transations['ticket_count'] = $users_count;

        return $this->sendResponse($transations, 'Total of transactions successfully.');        
    }

    //parameters details
    // user_category_id_arr -> [1,2,3,4,5,6]
    // type -> 0/1 -> 1=exp, 0=income
    function getGraphData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'    => 'required|in:1,0'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $user = Auth::user();
        $header = substr($request->header('Authorization'), 7);
        DB::enableQueryLog();
        $query = DB::table('user_transactions')->leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
            ->leftJoin('category as c','c.id','=','uc.category_id');

        $query = $query->select('user_transactions.id', 'user_transactions.user_category_id', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'), DB::raw('SUM( user_transactions.total_amount) as total_amount'));
        
        if($request->from_date != "" && $request->to_date != "")
        {
            $query = $query->whereBetween('user_transactions.transaction_date', array($request->from_date, $request->to_date));
        }
        //->whereIn('id', [1, 2, 3])
        if(!empty($request->user_category_id_arr))
        {
            $query = $query->whereIn('user_transactions.user_category_id', $request->user_category_id_arr);
        }

        $query = $query->where('user_transactions.user_id', $user->id);

        $query = $query->where('user_transactions.transaction_type', $request->type);
         $query = $query->groupBy('user_transactions.user_category_id')->get();
        $res = $query->toArray();
        Log::debug('Last query-------->'.json_encode(DB::getQueryLog()));
        // pr($res); die;

        $x_axis = [];
        $y_axis = [];
        if(!empty($res))
        {
            foreach($res as $key => $val)
            {
                $x_axis[$key] = [
                    "user_category_id" => $val->user_category_id,
                    "category_name" => $val->category_name,
                    "category_icon" => asset($val->category_icon)
                ];
                $y_axis[$key] = $val->total_amount;
            }
        }

        $reports['x_axis'] = $x_axis;
        $reports['y_axis'] = $y_axis;

        return $this->sendResponse($reports, 'Report generated successfully.');  
    }
}