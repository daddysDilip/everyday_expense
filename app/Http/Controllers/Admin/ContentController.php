<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Content;


class ContentController extends Controller
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
        $activeTab = 'Content';
        $content = Content::all();
        return View('admin/content/index', compact('content', 'activeTab'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        // echo get_unique_slug(slugify("select-country"), "tbl_content", "key_slug"); die;
        $activeTab = 'Content';
        return View('admin/content/create', compact('activeTab'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'english_string'=>'required'
        ]);
        $content = new Content;
        $content->key_slug = get_unique_slug(slugify($request->input('english_string')), "tbl_content", "key_slug");
        $content->english_string = $request->input('english_string');
        $content->status = $request->input('status') != "" ? $request->input('status') : 0;

        if (!$content->save()) {
            //Redirect error
            return redirect()->route('content')->with('error', 'Failed to update content details.');
        }

        //Redirect success
        return redirect()->route('content')->with('success', 'Content added successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $activeTab = 'Content';
        $content = Content::find($id);
        return View('admin/content/create', compact('activeTab', 'content'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        try {
            $request->validate([
                'english_string'=>'required'
            ]);
            $content = Content::find($id);
            $content->english_string = $request->input('english_string');
            $content->status = $request->input('status') != "" ? $request->input('status') : 0;

            if ($content->save()) {
                //Redirect success
                return redirect()->route('content')->with('success', 'Content updated successfully.');
            }

            //Redirect error
            return redirect()->route('content')->with('error', 'Failed to update content.');

        } catch (Exception $e) {
            Log::error("Failed to update content.");
        }
    }
    /* for change status of a content*/
    public function changeStatus_datatable(Request $request, $id) {
        $content = Content::find($id);

        if ($content->status == 1) {
            $content->status = 0;
        } elseif ($content->status == 0) {
            $content->status = 1;
        }
        $content->save();

        return redirect()->route('content')->with('success', 'Content Status Updated');

    }

    /* for create datatable data*/
    public function allData(Request $request) {

        $columns = array(
            0 => 'id',
            1 => 'key_slug',
            2 => 'english_string',
            3 => 'status'
        );

        $totalData = Content::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = Content::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = Content::where('name', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Content::where('name', 'LIKE', "%{$search}%")
                ->where('name', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $show = route('content.edit', $post->id);
                $edit = route('content.edit', $post->id);
                $url = asset('uploads/content/' . $post->image);

                $st = route('content.status', $post->id);

                $nestedData['id'] = $post->id;
                $nestedData['key_slug'] = $post->key_slug;
                $nestedData['english_string'] = $post->english_string;
                
                if ($post->status == '1') {
                    $nestedData['status'] = '<span class="badge badge-success">Active</span>';
                } else {
                    $nestedData['status'] = '<span class="badge badge-danger">Paused</span>';
                }

                $options = "";

                if ($post->status == '1') {
                    $options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-warning" data-toggle="tooltip" title="Pause Content"> <i class="fa fa-power-off"></i> </a>&nbsp;';
                } else {
                    $options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-success changeCategoryStatus" data-toggle="tooltip" title="Activate Content"> <i class="fas fa-play"></i> </a>&nbsp;';
                }

                if (get_user_permission("Content", "edit")) {
                    $options .= '<a href="' . $edit . '" class="btn btn-rounded btn-sm btn-info" data-toggle="tooltip" title="Edit Content"><i class="fas fa-pencil-alt">
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


    public function existcontent(Request $request) {

        $id = $request->input('id');
        if ($id != '') {
            $title_exists = (count(Content::where('id', '!=', $id)->where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
            return response()->json($title_exists);
        } else {
            $title_exists = (count(Content::where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
            return response()->json($title_exists);
        }
    }
}
