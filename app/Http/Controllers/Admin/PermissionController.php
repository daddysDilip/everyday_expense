<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Modules;
use DB;

class PermissionController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
    }
    public function showPermissions($id= null) {

	/* 	if(!get_user_permission("User Permissions","view")) {
            return redirect(403);
        } */
		$roles = Role::where('status',1)->get();
		if($id != null){

			$Permissions =  DB::select("
                select tm.id as moduleId,tm.name as moduleName,tm.status as moduleStatus,
                tr.id as roleId,tr.name as roleName,
                tp.*
                from modules as tm
                cross join roles as tr
                left join permissions as tp
                on tm.id=tp.moduleid and tr.id=tp.roleid where tm.status = 1 and tr.id = '".$id."'
            ");
		}else{
            $id=$roles[0]->id;
            $Permissions =  DB::select("
                select tm.id as moduleId,tm.name as moduleName,tm.status as moduleStatus,
                tr.id as roleId,tr.name as roleName,
                tp.*
                from modules as tm
                cross join roles as tr
                left join permissions as tp
                on tm.id=tp.moduleid and tr.id=tp.roleid where tm.status = 1 and tr.id = '".$roles[0]->id."'
            ");
        }
        $activeTab = 'manage_permissions';
        return view('admin.permissions.index',compact('Permissions','activeTab','id'));
    }

    public function fetchPermission(Request $request) {
        $RolePermissions =  DB::select("
            select tm.id as moduleId,tm.name as moduleName,tm.status as moduleStatus,
            tr.id as roleId,tr.name as roleName,
            tp.*
            from modules as tm
            cross join roles as tr
            left join permissions as tp
            on tm.id=tp.moduleid and tr.id=tp.roleid where tm.status = 1 and tr.id = '".$request->roleId."'
        ");
        $current_role = $request->roleId;
        return view('permissions.index',compact('RolePermissions','current_role'));
    }

    public function savePermissions(Request $request) {

		//echo '<pre>'; print_r($request->all());  echo '</pre>';
		try{

		if($request->all() != null && $request->has('permission')){
			if(count($request->get('permission')) > 0){
				$permissions =$request->get('permission');
                foreach($permissions as $permission){
					//echo '<pre>'; print_r($permission); echo '</pre>';
					$per_count = total_rows('permissions', array('moduleid'=>$permission['moduleId'], 'roleid'=>$permission['roleId']));

					if(isset($permission['add']) || isset($permission['edit'])  || isset($permission['view'])  || isset($permission['delete']) ||  $per_count > 0 ){
					//echo 'test';
					//echo '<pre>'; print_r($permission); echo '</pre>';
					//$per_count = total_rows('permissions', array('moduleid'=>$permission['moduleId'], 'roleid'=>$permission['roleId']));

					if($per_count == 0){
					//	echo 'save';
						$perm = new Permission;
						$perm->roleid = $permission['roleId'];
						$perm->moduleid = $permission['moduleId'];
						$perm->add = (isset($permission['add']) ? $permission['add']: null);
						$perm->edit = (isset($permission['edit']) ? $permission['edit']: null);
						$perm->delete = (isset($permission['delete']) ? $permission['delete']: null);
						$perm->view = (isset($permission['view']) ? $permission['view']: null);
						$perm->save();
					}else{
						//echo 'update';
						$data =array(
						 'add'=>(isset($permission['add']) ? $permission['add']: null),
						 'edit'=>(isset($permission['edit']) ? $permission['edit']: null),
						 'delete'=>(isset($permission['delete']) ? $permission['delete']: null),
						 'view'=>(isset($permission['view'])  ? $permission['view']: null),
						);
						 $perm = Permission::where([
                        'roleid' => $permission['roleId'],
                        'moduleid' => $permission['moduleId']
                    ])->update($data);
					}

					//echo '<pre>'; print_r($permission); echo '</pre>';
					}
				}

			}


		}
	//	exit;
		return redirect()->back()->with('success', 'Permission Saved Successfully');
		//exit;

	    }catch (Exception $e) {
                Log::error("Failed to save permission -> ".$e);
            }
	}
    public function addPermission(Request $request) {
        if($request->roleId != "" && $request->moduleId != "" && $request->value != "" && $request->type != "") {

            $roleId = $request->roleId;
            $moduleId = $request->moduleId;
            $value = $request->value;
            $type = $request->type;

            $check_perm = Permission::where(['roleid'=>$roleId,'moduleid'=>$moduleId])->get();

            //Check if permission type is add
            if($type == "add") {
                /**
                 * Value = 1 Then insert "add" permission
                 * Value = 0 Then update "add" permission
                 */
                if($value == 1 && count($check_perm) < 1) {
                    $permission = new Permission;
                    $permission->roleid = $roleId;
                    $permission->moduleid = $moduleId;
                    $permission->add = $value;
                    $permission->edit = null;
                    $permission->delete = null;
                    $permission->view = null;

                    if($permission->save()) {
                        echo "success";
                    } else {
                        echo "fail";
                    }

                } else if($value == 0) {

                    $data = array(
                        'add' => $value
                    );

                    $permission = Permission::where([
                        'roleid' => $roleId,
                        'moduleid' => $moduleId
                    ])->update($data);

                    if($permission) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                } else if($value == 1 && count($check_perm) > 0) {

                    $data = array(
                        'add' => $value
                    );

                    $permission = Permission::where([
                        'roleid' => $roleId,
                        'moduleid' => $moduleId
                    ])->update($data);

                    if($permission) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                }


            }

            if($type == "edit") {

                /**
                 * Value = 1 Then insert "edit" permission
                 * Value = 0 Then update "edit" permission
                 */

                if($value == 1 && count($check_perm) < 1) {
                    $permission = new Permission;

                    $permission->roleid = $roleId;
                    $permission->moduleid = $moduleId;
                    $permission->add = null;
                    $permission->edit = $value;
                    $permission->delete = null;
                    $permission->view = null;

                    if($permission->save()) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                } else if($value == 0) {

                    $data = array(
                        'edit' => $value
                    );

                    $permission = Permission::where([
                        'roleid' => $roleId,
                        'moduleid' => $moduleId
                    ])->update($data);

                    if($permission) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                } else if($value == 1 && count($check_perm) > 0) {

                    $data = array(
                        'edit' => $value
                    );

                    $permission = Permission::where([
                        'roleid' => $roleId,
                        'moduleid' => $moduleId
                    ])->update($data);

                    if($permission) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                }
            }

            if($type == "delete") {
                /**
                 * Value = 1 Then insert "edit" permission
                 * Value = 0 Then update "edit" permission
                 */
                if($value == 1 && count($check_perm) < 1) {
                    $permission = new Permission;

                    $permission->roleid = $roleId;
                    $permission->moduleid = $moduleId;
                    $permission->add = null;
                    $permission->edit = null;
                    $permission->delete = $value;
                    $permission->view = null;

                    if($permission->save()) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                } else if($value == 0) {

                    $data = array(
                        'delete' => $value
                    );

                    $permission = Permission::where([
                        'roleid' => $roleId,
                        'moduleid' => $moduleId
                    ])->update($data);

                    if($permission) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                } else if($value == 1 && count($check_perm) > 0) {

                    $data = array(
                        'delete' => $value
                    );

                    $permission = Permission::where([
                        'roleid' => $roleId,
                        'moduleid' => $moduleId
                    ])->update($data);

                    if($permission) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                }
            }

            if($type == "view") {

                /**
                 * Value = 1 Then insert "edit" permission
                 * Value = 0 Then update "edit" permission
                 */

                if($value == 1 && count($check_perm) < 1) {
                    $permission = new Permission;

                    $permission->roleid = $roleId;
                    $permission->moduleid = $moduleId;
                    $permission->add = null;
                    $permission->edit = null;
                    $permission->delete = null;
                    $permission->view = $value;

                    if($permission->save()) {
                        echo "success";
                    } else {
                        echo "fail";
                    }

                } else if($value == 0) {

                    $data = array(
                        'view' => $value
                    );

                    $permission = Permission::where([
                        'roleid' => $roleId,
                        'moduleid' => $moduleId
                    ])->update($data);

                    if($permission) {
                        echo "success";
                    } else {
                        echo "fail";
                    }

                } else if($value == 1 && count($check_perm) > 0) {

                    $data = array(
                        'view' => $value
                    );

                    $permission = Permission::where([
                        'roleid' => $roleId,
                        'moduleid' => $moduleId
                    ])->update($data);

                    if($permission) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                }

            }
        }
    }

}
