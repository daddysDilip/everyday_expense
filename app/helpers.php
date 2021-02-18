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
