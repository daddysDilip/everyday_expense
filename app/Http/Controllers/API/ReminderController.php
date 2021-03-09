<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\API\Reminders;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class ReminderController extends BaseController
{
    /**
     *  create new reminder
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required',
            'title'             => 'required',
            'user_category_id'  => 'required',
            'frequency'         => 'required|in:daily,weekly,monthly',
            'week_day'          => 'in:sun,mon,tus,wed,thu,fri,sat,',
            'month_day'         => 'in:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,',
            'reminder_time'     => 'required',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $reminder                = new Reminders;
        $reminder->user_id       = $request->user_id;
        $reminder->title         = $request->title;
        $reminder->frequency     = $request->frequency;
        $reminder->week_day      = $request->week_day;
        $reminder->month_day     = $request->month_day;
        $reminder->reminder_time = $request->reminder_time;
        $reminder->user_category_id = $request->user_category_id;
        
        $reminder->save();
        $success =  $reminder;
        
        return $this->sendResponse($success, 'Category inserted successfully.');
    }
    
    /**
     *  edit user reminder
     */
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required',
            'title'             => 'required',
            'user_category_id'  => 'required',
            'frequency'         => 'required|in:daily,weekly,monthly',
            'week_day'          => 'in:sun,mon,tus,wed,thu,fri,sat,',
            'month_day'         => 'in:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,',
            'reminder_time'     => 'required',
            'id'                => 'required'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $reminder                = Reminders::find($request->id);
        $reminder->user_id       = $request->user_id;
        $reminder->title         = $request->title;
        $reminder->frequency     = $request->frequency;
        $reminder->week_day      = $request->week_day;
        $reminder->month_day     = $request->month_day;
        $reminder->reminder_time = $request->reminder_time;
        $reminder->user_category_id = $request->user_category_id;

        $reminder->save();
        $success =  $reminder;
        
        return $this->sendResponse($success, 'Category updated successfully.');
    }

    /**
     *  get all reminder of a user
     */
    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'  
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $reminder = Reminders::leftJoin('user_category as uc','uc.id','=','user_reminders.user_category_id')
                    ->leftJoin('category as c','c.id','=','uc.category_id')
                    ->where('user_reminders.user_id', $request->user_id)
                    ->select('user_reminders.*', 'uc.category_name', DB::raw('IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name'), DB::raw('IF(uc.is_user_defiend = "1", uc.icon, c.icon) as category_icon'))
                ->get();
        $success =  $reminder->toArray();
        foreach ($success as $key => $val)
        {
            if($val['category_icon'] != "")
            {
                $success[$key]['category_icon'] = asset($val['category_icon']);
            }
        }
        return $this->sendResponse($success, 'Retrieve reminders successfully.');
    }

    /**
     *  delete a reminder of a user
     */
    function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $reminder = Reminders::find($request->id);
        $reminder->delete();
        return $this->sendResponse([], 'Reminder deleted successfully.');
    }
}