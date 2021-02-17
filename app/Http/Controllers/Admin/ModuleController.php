<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modules;

class ModuleController extends Controller
{
    /**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$activeTab = 'Modules';
		$module = Modules::all();
		return View('admin/module/index', compact('module', 'activeTab'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$activeTab = 'Modules';
		return View('admin/module/create', compact('activeTab'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$date = date('Y-m-d h:i:s');
		$module = new Modules;
        $module->name = $request->input('name');
		$module->status = $request->input('status') != "" ? $request->input('status') : 0;
		$module->created_at = $date;

		if (!$module->save()) {
			//Redirect error
			return redirect()->route('module')->with('error', 'Failed to update module details.');
		}

		//Redirect success
		return redirect()->route('module')->with('success', 'Modules added successfully.');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Banner  $banner
	 * @return \Illuminate\Http\Response
	 */
	public function show(Modules $module) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$activeTab = 'Modules';
		$module = Modules::find($id);
		return View('admin/module/create', compact('activeTab', 'module'));
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
			$module = Modules::find($id);
            $module->name = $request->input('name');
			$module->status = $request->input('status') != "" ? $request->input('status') : 0;
			$module->updated_at = $date;


        if ($module->save()) {
				//Redirect success
				return redirect()->route('module')->with('success', 'Modules updated successfully.');
			}

			//Redirect error
			return redirect()->route('module')->with('error', 'Failed to update module.');

		} catch (Exception $e) {
			Log::error("Failed to update module.");
		}
	}



	public function changeStatus_datatable(Request $request, $id) {
		$module = Modules::find($id);

		if ($module->status == 1) {
			$module->status = 0;
		} elseif ($module->status == 0) {
			$module->status = 1;
		}
		$module->save();

		return redirect()->route('module')->with('success', 'Modules Status Updated');

	}

	public function allData(Request $request) {

		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'status',
		);

		$totalData = Modules::count();

		$totalFiltered = $totalData;

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if (empty($request->input('search.value'))) {
			$posts = Modules::offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
		} else {
			$search = $request->input('search.value');

			$posts = Modules::where('name', 'LIKE', "%{$search}%")
				->orWhere('status', 'LIKE', "%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();

			$totalFiltered = Modules::where('name', 'LIKE', "%{$search}%")
				->orWhere('status', 'LIKE', "%{$search}%")
				->count();
		}

		$data = array();
		//dd($posts);
		if (!empty($posts)) {
			foreach ($posts as $post) {
				$show = route('module.edit', $post->id);
				$edit = route('module.edit', $post->id);
				$url = asset('uploads/module/' . $post->image);

				$st = route('change.status', $post->id);

				$nestedData['id'] = $post->id;
				$nestedData['name'] = $post->name;




				//$nestedData['status'] = $post->status;
				if ($post->status == '1') {
					$nestedData['status'] = '<span class="badge badge-success">Active</span>';
				} else {
					$nestedData['status'] = '<span class="badge badge-danger">Paused</span>';
				}

				$options = "";

				if ($post->status == '1') {

					$options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-warning" data-toggle="tooltip" title="Pause Modules"> <i class="fa fa-power-off"></i> </a>&nbsp;';
				} else {
					// $options = '<button type="button" id="changeProductStatus" class="btn btn-rounded btn-sm btn-success changeProductStatus" data-product_id="' . $post->id . '" data-status="' . $post->status . '" data-toggle="tooltip" title="Activate product"><i class="fa fa-refresh"></i></button>&nbsp;';

					$options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-success changeCategoryStatus" data-toggle="tooltip" title="Activate Modules"> <i class="fas fa-play"></i> </a>&nbsp;';
				}

				if (get_user_permission("Module", "edit")) {
					$options .= '<a href="' . $edit . '" class="btn btn-rounded btn-sm btn-info" data-toggle="tooltip" title="Edit Modules"><i class="fas fa-pencil-alt">
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


    public function existModules(Request $request) {

		$id = $request->input('id');

		if ($id != '') {
			$title_exists = (count(Modules::where('id', '!=', $id)->where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
			return response()->json($title_exists);
		} else {
			$title_exists = (count(Modules::where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
			return response()->json($title_exists);
		}
	}
}
