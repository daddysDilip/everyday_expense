<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Log;

class CronController extends Controller
{
    public function sendReminders()
    {
        //This is Daily reminder that it's time to add transaction for Bills.
        $reminder =  (array)DB::table('notification_template')->where('title', 'reminder')->first();
        // pr($reminder); die;
        // date_default_timezone_set('Asia/kolkata');
        
        $daily =  DB::select('SELECT `ur`.*, `u`.`fcm_token`, IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name FROM `user_reminders` AS `ur` LEFT JOIN `users` AS `u` ON `u`.`id` = `ur`.`user_id` LEFT JOIN `user_category` AS `uc` ON `uc`.`id` = `ur`.`user_category_id` LEFT JOIN `category` AS `c` ON `c`.`id` = `uc`.`category_id` WHERE `u`.`fcm_token` IS NOT NULL AND `ur`.`is_active` = 1 AND `ur`.`frequency` = "daily" AND HOUR(ur.reminder_time) = '.date("H",time()).' AND MINUTE(ur.reminder_time) = '.date("i",time()));

       $weekly = DB::select('SELECT `ur`.*, `u`.`fcm_token`, IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name FROM `user_reminders` AS `ur` LEFT JOIN `users` AS `u` ON `u`.`id` = `ur`.`user_id` LEFT JOIN `user_category` AS `uc` ON `uc`.`id` = `ur`.`user_category_id` LEFT JOIN `category` AS `c` ON `c`.`id` = `uc`.`category_id` WHERE `u`.`fcm_token` IS NOT NULL AND `ur`.`is_active` = 1 AND `ur`.`week_day` = "'.strtolower(date("D")).'" AND `ur`.`frequency` = "weekly" AND HOUR(ur.reminder_time) = '.date("H",time()).' AND MINUTE(ur.reminder_time) = '.date("i",time()));

       $monthly = DB::select('SELECT `ur`.*, `u`.`fcm_token`, IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name FROM `user_reminders` AS `ur` LEFT JOIN `users` AS `u` ON `u`.`id` = `ur`.`user_id` LEFT JOIN `user_category` AS `uc` ON `uc`.`id` = `ur`.`user_category_id` LEFT JOIN `category` AS `c` ON `c`.`id` = `uc`.`category_id` WHERE `u`.`fcm_token` IS NOT NULL AND `ur`.`is_active` = 1 AND `ur`.`month_day` = "'.date('j',time()).'" AND `ur`.`frequency` = "monthly" AND HOUR(ur.reminder_time) = '.date("H",time()).' AND MINUTE(ur.reminder_time) = '.date("i",time()));

         /*$monthly = DB::table('user_reminders as ur')->leftJoin('users as u', 'u.id', '=', 'ur.user_id')->whereNotNull('u.fcm_token')->where('ur.is_active', 1)->where('ur.frequency', 'monthly')->where('ur.week_day', date('j',time()))->where('HOUR(ur.reminder_time)', date('H',time()))->where('MINUTE(ur.reminder_time)', date('i',time()))->get();
            .' AND MINUTE(ur.reminder_time) = '.date("i",time())
            .' AND MINUTE(ur.reminder_time) = '.date("i",time())
            .' AND MINUTE(ur.reminder_time) = '.date("i",time())
         */
        /*pr($daily);
        pr($weekly);
        pr($monthly);*/
        $last = [];
        if(!empty($daily)) {
            foreach($daily as $v)
            {
                $v = (array)$v;
                $reminder['msg'] = str_replace("{{frequency}}",$v["frequency"],$reminder['msg']);
                $reminder['msg'] = str_replace("{{category_name}}",$v["category_name"],$reminder['msg']);
                $res1 = push_notification_send($v['fcm_token'], $reminder['msg'], $v["title"], $v, $reminder['image']);
                echo json_encode($res1);
                Log::info("notification resp-------------->".json_encode($res1));
            }
        }
        if(!empty($weekly))
        {
            foreach($weekly as $va)
            {
                $va = (array)$va;
                $reminder['msg'] = str_replace("{{frequency}}",$va["frequency"],$reminder['msg']);
                $reminder['msg'] = str_replace("{{category_name}}",$va["category_name"],$reminder['msg']);
                $res2 = push_notification_send($va['fcm_token'], $reminder['msg'], $va["title"], $va, $reminder['image']);
                echo json_encode($res2);
                Log::info("notification resp-------------->".json_encode($res2));
            }
        }
        if(!empty($monthly))
        {
            foreach($monthly as $val)
            {
                $val = (array)$val;
                $reminder['msg'] = str_replace("{{frequency}}",$val["frequency"],$reminder['msg']);
                $reminder['msg'] = str_replace("{{category_name}}",$val["category_name"],$reminder['msg']);
                $res3 = push_notification_send($val['fcm_token'], $reminder['msg'], $val["title"], $val, $reminder['image']);
                echo json_encode($res3);
                Log::info("notification resp-------------->".json_encode($res3));
            }
        }
        if(empty($daily) && empty($weekly) && empty($monthly))
        {
            echo "no reminder found";
            Log::info("notification resp-------------->".json_encode("no reminder found"));
        }
    }

    function send_noti()
    {
        // new_curl(); die;
        // test_notification(); die;
        $daily =  DB::select('SELECT `ur`.*, `u`.`fcm_token`, IF(uc.is_user_defiend = "1", uc.category_name, c.name) as category_name FROM `user_reminders` AS `ur` LEFT JOIN `users` AS `u` ON `u`.`id` = `ur`.`user_id` LEFT JOIN `user_category` AS `uc` ON `uc`.`id` = `ur`.`user_category_id` LEFT JOIN `category` AS `c` ON `c`.`id` = `uc`.`category_id` WHERE `u`.`fcm_token` IS NOT NULL AND `ur`.`is_active` = 1 AND `ur`.`frequency` = "daily"');

        if(!empty($daily)) {
            foreach($daily as $v)
            {
                $v = (array)$v;
                pr($v);
                $token = 'efdJ498WTP2CajBIGut_dE:APA91bFSe1HUAqmjspU0qEyfoeSxj4IMZKOkT-fEpFxomwKnPYnd-2kPnM-j4bXlzXwrovoDETrDMd9gqtcTguP7f91FsgydWp15uuFF9zByJTGup9FcMq9NJ_gJdgHKJelBFAh6T9Md';
                $res1 = push_notification_send($token, "static reminder test", "Reminder", $v);
                echo json_encode($res1);
            }
        } else {
            echo "no reminder found";
        }
    }


}
