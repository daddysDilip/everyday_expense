<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CronController extends Controller
{
    public function sendReminders()
    {
        date_default_timezone_set('Asia/kolkata');
        // echo date('d/m/Y H:i:s',time()); die;
        // $dt = new DateTime;
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
                $res1 = push_notification_send($v['fcm_token'], "static reminder", "Reminder", $v);
                echo json_encode($res1);
            }
        }
        if(!empty($weekly))
        {
            foreach($weekly as $va)
            {
                $va = (array)$va;
                $res2 = push_notification_send($va['fcm_token'], "static reminder", "Reminder", $va);
                echo json_encode($res2);
            }
        }
        if(!empty($monthly))
        {
            foreach($monthly as $val)
            {
                $val = (array)$val;
                $res3 = push_notification_send($val['fcm_token'], "static reminder", "Reminder", $val);
                echo json_encode($res3);
            }
        }
        if(empty($daily) && empty($weekly) && empty($monthly))
        {
            echo "no reminder found";
        }
    }
}
