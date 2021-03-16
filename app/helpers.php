<?php
function getRoleType($roleid){

    $role = DB::table('roles')->select('type')->where('id', $roleid)->first();

	return $role->type;
}

function get_roles() {
    return DB::table('roles')->get();
}

function get_countries() {
    return DB::table('country')->get();
}

function get_contents() {
    return DB::table('tbl_content')->get();
}

function get_category() {
    return DB::table('category')->get();
}

function get_languages() {
    return DB::table('languages')->where('code','<>','en')->get();
}

// for genereate unique slug from given string and table
function get_unique_slug($string, $table, $field_name = "slug")
{
    $res = DB::table($table)->select($field_name)->where($field_name, $string)->first();
    if ($res == null) {
        return $string;
    } else {
        $temp = explode("-", $res->$field_name);
        $last = sizeof($temp) - 1;
        if ((int) $temp[$last] != 0) {
            $temp[$last] = $temp[$last] + 1;
            $_string = implode("-", $temp);
        } else {
            $_string = $string . "-1";
        }
        return get_unique_slug($_string, $table, $field_name);
    }
}

function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}
function total_rows($table, $where = array())

	{
		if (is_array($where)) {
		   if (sizeof($where) > 0) {
				//$CI->db->where($where);
				return DB::table($table)->where($where)->count();
			}
		} else if (strlen($where) > 0) {


			return DB::table($table)->where($where)->count();

		}

		return DB::table($table)->count();
		//return $CI->db->count_all_results($table);
    }

function get_user_permission($module,$type) 
{
    $Permission = DB::select(
        "SELECT
            perm.*,
            module.id as moduleid,module.name as moduleName
        FROM permissions as perm
            JOIN modules as module on perm.moduleid = module.id
        WHERE perm.roleid = ". Auth::user()->role ." and module.name = '".$module."' and perm." .$type. "=1"
    );
    return count($Permission) > 0 ? true : false;
}

function pr($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function date_query($field,$user_id,$alias)
{
    $user_date = DB::table('users')->where('api_token', $user_id)->first();
    if($user_date->date_format == 'DD/MM/YYYY')
        $sql_date = "%d/%m/%Y";
    else if($user_date->date_format == 'MM/DD/YYYY')
        $sql_date = "%m/%d/%Y";
    else if($user_date->date_format == 'DD-MM-YYYY')
        $sql_date = "%d-%m-%Y";
    else if($user_date->date_format == 'MM-DD-YYYY')
        $sql_date = "%m-%d-%Y";
    else if($user_date->date_format == 'DD-MMM-YYYY')
        $sql_date = "%d-%b-%Y";
    else 
        $sql_date = "%d-%m-%Y";

    return 'DATE_FORMAT(('.$field.'), "'.$sql_date.'") as '.$alias;
}

function push_notification_send($data, $message, $title, $payload)
{
            
    // if (sizeof($data) != 0) {

        // if($image == "") {
            // static image
            $image = "https://s3.us-west-2.amazonaws.com/meadionce-test/doctor/doctor_145498475120190920035703.png";
        // }

        // prep the bundle
        $msg = array(
            'message'   => $message,
            'title'     => $title,
            'image'     => $image,
            'payload'   => $payload,
            'type'      => ""
        );

        $fields = array(
            'registration_ids'  => $data,
            'data'          => $msg
        );

        $headers = array(
            'Authorization: key=AAAAY25-At0:APA91bHMilssntLNfBtIwXx6tYjW_bOO6pil9DpLcUtvjR6J-vy0fJkzZibv3ucMALuOF0F9x0QwFDJfa2-2k6-npGbRly0GVpskifL8ggBcalF0Qlc5I7njj1YF3J1wDoXDkdhfSkmT',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result["android"] = curl_exec($ch);
        curl_close($ch);
       
    // }
    return $result;
}