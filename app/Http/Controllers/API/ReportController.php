<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\API\UserTransaction;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

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
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->whereBetween('user_transactions.transaction_date', array($request->from_date, $request->to_date))
                    ->where('user_transactions.user_id', $user->id)->where('transaction_type', '0')->get();

            $expense = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->whereBetween('user_transactions.transaction_date', array($request->from_date, $request->to_date))
                    ->where('user_transactions.user_id', $user->id)->where('transaction_type', '1')->get();
        } else {
            
            $income = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                    ->where('user_transactions.user_id', $user->id)
                    ->where('transaction_type', '0')
                    ->get();

            $expense = UserTransaction::leftJoin('user_category as uc','uc.id','=','user_transactions.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->select('user_transactions.id', DB::raw(date_query("user_transactions.transaction_date",$header,"transaction_date")), 'user_transactions.currency_symbol', 'user_transactions.total_amount', 'user_transactions.remark', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
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
        }
        foreach($transations['expense'] as $key1 => $val1)
        {
            if($val1['category_icon'] != "")
            {
                $transations['expense'][$key1]['category_icon'] = asset($val1['category_icon']);
            }
        }
        return $this->sendResponse($transations, 'Retrieve transactions successfully.');
    }
    
}