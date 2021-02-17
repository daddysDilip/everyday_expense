<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Languages;


class LanguagesController extends Controller
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
        $activeTab = 'Languages';
        $languages = Languages::all();
        return View('admin/languages/index', compact('languages', 'activeTab'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $activeTab = 'Languages';
        return View('admin/languages/create', compact('activeTab'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'name'=>'required',
            'name_in_language'=>'required',
            'code'=>'required',
            'country'=>'required'
        ]);
        $languages = new Languages;
        $languages->name = $request->input('name');
        $languages->country_id = $request->input('country');
        $languages->name_in_language = $request->input('name_in_language');
        $languages->code = strtolower($request->input('code'));
        $languages->status = $request->input('status') != "" ? $request->input('status') : 0;

        if (!$languages->save()) {
            //Redirect error
            return redirect()->route('languages')->with('error', 'Failed to update languages details.');
        }

        //Redirect success
        return redirect()->route('languages')->with('success', 'Languages added successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $activeTab = 'Languages';
        $languages = Languages::find($id);
        return View('admin/languages/create', compact('activeTab', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        try {
            $languages = Languages::find($id);
            $languages->name = $request->input('name');
            $languages->country_id = $request->input('country');
            $languages->name_in_language = $request->input('name_in_language');
            $languages->code = strtolower($request->input('code'));
            $languages->status = $request->input('status') != "" ? $request->input('status') : 0;

            if ($languages->save()) {
                //Redirect success
                return redirect()->route('languages')->with('success', 'Languages updated successfully.');
            }

            //Redirect error
            return redirect()->route('languages')->with('error', 'Failed to update languages.');

        } catch (Exception $e) {
            Log::error("Failed to update languages.");
        }
    }
    /* for change status of a languages*/
    public function changeStatus_datatable(Request $request, $id) {
        $languages = Languages::find($id);

        if ($languages->status == 1) {
            $languages->status = 0;
        } elseif ($languages->status == 0) {
            $languages->status = 1;
        }
        $languages->save();

        return redirect()->route('languages')->with('success', 'Languages Status Updated');

    }

    /* for create datatable data*/
    public function allData(Request $request) {

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'country.name',
            3 => 'name_in_language',
            4 => 'code',
            5 => 'status'
        );

        $totalData = Languages::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = Languages::leftJoin('country', 'country.id', '=', 'languages.country_id')
                ->select('languages.*', 'country.name as country_name')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = Languages::leftJoin('country', 'country.id', '=', 'languages.country_id')
                ->select('languages.*', 'country.name as country_name')
                ->where('languages.name', 'LIKE', "%{$search}%")
                ->orWhere('languages.status', 'LIKE', "%{$search}%")
                ->orWhere('country.name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Languages::leftJoin('country', 'country.id', '=', 'languages.country_id')
                ->select('languages.*', 'country.name as country_name')
                ->where('languages.name', 'LIKE', "%{$search}%")
                ->where('country.name', 'LIKE', "%{$search}%")
                ->orWhere('languages.status', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $show = route('languages.edit', $post->id);
                $edit = route('languages.edit', $post->id);
                $url = asset('uploads/languages/' . $post->image);

                $st = route('languages.status', $post->id);

                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['country_name'] = $post->country_name;
                $nestedData['name_in_language'] = $post->name_in_language;
                $nestedData['code'] = $post->code;
                
                if ($post->status == '1') {
                    $nestedData['status'] = '<span class="badge badge-success">Active</span>';
                } else {
                    $nestedData['status'] = '<span class="badge badge-danger">Paused</span>';
                }

                $options = "";

                if ($post->status == '1') {
                    $options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-warning" data-toggle="tooltip" title="Pause Languages"> <i class="fa fa-power-off"></i> </a>&nbsp;';
                } else {
                    $options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-success changeCategoryStatus" data-toggle="tooltip" title="Activate Languages"> <i class="fas fa-play"></i> </a>&nbsp;';
                }

                if (get_user_permission("Languages", "edit")) {
                    $options .= '<a href="' . $edit . '" class="btn btn-rounded btn-sm btn-info" data-toggle="tooltip" title="Edit Languages"><i class="fas fa-pencil-alt">
                    </i>
                    Edit</a>';
                }

                $nestedData['options'] = $options;
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


    public function existlanguages(Request $request) {

        $id = $request->input('id');
        if ($id != '') {
            $title_exists = (count(Languages::where('id', '!=', $id)->where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
            return response()->json($title_exists);
        } else {
            $title_exists = (count(Languages::where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
            return response()->json($title_exists);
        }
    }
}
