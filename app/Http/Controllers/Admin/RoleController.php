<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    /**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$activeTab = 'Role';
		$role = Role::all();
		return View('admin/role/index', compact('role', 'activeTab'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$activeTab = 'Role';
		return View('admin/role/create', compact('activeTab'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$date = date('Y-m-d h:i:s');
		$role = new Role;
        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $role->level = $request->input('level');
        $role->type = $request->input('type');
		$role->status = $request->input('status') != "" ? $request->input('status') : 0;
		$role->created_at = $date;

		if (!$role->save()) {
			//Redirect error
			return redirect()->route('role')->with('error', 'Failed to update role details.');
		}

		//Redirect success
		return redirect()->route('role')->with('success', 'Role added successfully.');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Banner  $banner
	 * @return \Illuminate\Http\Response
	 */
	public function show(Role $role) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$activeTab = 'Role';
		$role = Role::find($id);
		return View('admin/role/create', compact('activeTab', 'role'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		try {
			$date = date('Y-m-d h:i:s');
			$role = Role::find($id);
            $role->name = $request->input('name');
            $role->description = $request->input('description');
            $role->level = $request->input('level');
            $role->type = $request->input('type');
			$role->status = $request->input('status') != "" ? $request->input('status') : 0;
			$role->updated_at = $date;


        if ($role->save()) {
				//Redirect success
				return redirect()->route('role')->with('success', 'Role updated successfully.');
			}

			//Redirect error
			return redirect()->route('role')->with('error', 'Failed to update role.');

		} catch (Exception $e) {
			Log::error("Failed to update role.");
		}
	}



	public function changeStatus_datatable(Request $request, $id) {
		$role = Role::find($id);

		if ($role->status == 1) {
			$role->status = 0;
		} elseif ($role->status == 0) {
			$role->status = 1;
		}
		$role->save();

		return redirect()->route('role')->with('success', 'Role Status Updated');

	}

	public function allData(Request $request) {

		$columns = array(
			0 => 'id',
			1 => 'name',
            2 => 'description',
            3=> 'level',
            4 => 'type',
			5 => 'status',
		);

		$totalData = Role::count();

		$totalFiltered = $totalData;

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if (empty($request->input('search.value'))) {
			$posts = Role::offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
		} else {
			$search = $request->input('search.value');

			$posts = Role::where('name', 'LIKE', "%{$search}%")
				->orWhere('status', 'LIKE', "%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();

			$totalFiltered = Role::where('name', 'LIKE', "%{$search}%")
				->orWhere('status', 'LIKE', "%{$search}%")
				->count();
		}

		$data = array();
		//dd($posts);
		if (!empty($posts)) {
			foreach ($posts as $post) {
				$show = route('role.edit', $post->id);
				$edit = route('role.edit', $post->id);
				$url = asset('uploads/role/' . $post->image);

				$st = route('change.status', $post->id);

				$nestedData['id'] = $post->id;
				$nestedData['name'] = $post->name;
				$nestedData['description'] = $post->description;
				$nestedData['level'] = $post->level;
				$nestedData['type'] = $post->type;




				//$nestedData['status'] = $post->status;
				if ($post->status == '1') {
					$nestedData['status'] = '<span class="badge badge-success">Active</span>';
				} else {
					$nestedData['status'] = '<span class="badge badge-danger">Paused</span>';
				}

				$options = "";

				if ($post->status == '1') {

					$options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-warning" data-toggle="tooltip" title="Pause Role"> <i class="fa fa-power-off"></i> </a>&nbsp;';
				} else {
					// $options = '<button type="button" id="changeProductStatus" class="btn btn-rounded btn-sm btn-success changeProductStatus" data-product_id="' . $post->id . '" data-status="' . $post->status . '" data-toggle="tooltip" title="Activate product"><i class="fa fa-refresh"></i></button>&nbsp;';

					$options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-success changeCategoryStatus" data-toggle="tooltip" title="Activate Role"> <i class="fas fa-play"></i> </a>&nbsp;';
				}

				if (get_user_permission("Roles", "edit")) {
					$options .= '<a href="' . $edit . '" class="btn btn-rounded btn-sm btn-info" data-toggle="tooltip" title="Edit Role"><i class="fas fa-pencil-alt">
                    </i>
                    Edit</a>';
				}

				$nestedData['options'] = $options;

				/*$nestedData['body'] = substr(strip_tags($post->detail),0,50)."...";
					                $nestedData['created_at'] = date('j M Y h:i a',strtotime($post->created_at));
					                $nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
				*/
				$data[] = $nestedData;

			}
		}

		$json_data = array(
			"draw" => intval($request->input('draw')),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data" => $data,
		);

		echo json_encode($json_data);

    }


    public function existRole(Request $request) {

		$id = $request->input('id');

		if ($id != '') {
			$title_exists = (count(Role::where('id', '!=', $id)->where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
			return response()->json($title_exists);
		} else {
			$title_exists = (count(Role::where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
			return response()->json($title_exists);
		}
	}
}
