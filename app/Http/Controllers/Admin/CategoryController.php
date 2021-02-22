<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;


class CategoryController extends Controller
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
        $activeTab = 'Category';
        $category = Category::all();
        return View('admin/category/index', compact('category', 'activeTab'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $activeTab = 'Category';
        return View('admin/trans/create', compact('activeTab'));
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
            'type'=>'required'
        ]);
        $category = new Category;
        $category->name = ucfirst($request->input('name'));
        $category->type = $request->input('type');
        $category->status = $request->input('status') != "" ? $request->input('status') : 0;
        $category->key_slug = get_unique_slug(slugify($request->input('name')), "category", "key_slug");
        // code for file upload
        if($files = $request->file('icon')){  
            $file_name = uniqid().".".$files->getClientOriginalExtension();
            $directory = "./category_icon/";
            if (!file_exists($directory)) {
              mkdir($directory, 0777, true);
            }
            $save_name = "category_icon/".$file_name;
            $files->move('category_icon',$file_name);  
            $category->icon = $save_name;
        }
        // end file upload
        if (!$category->save()) {
            //Redirect error
            return redirect()->route('category')->with('error', 'Failed to update category details.');
        }

        //Redirect success
        return redirect()->route('category')->with('success', 'Category added successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $activeTab = 'Category';
        $category = Category::find($id);
        return View('admin/category/create', compact('activeTab', 'category'));
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
                'name'=>'required',
                'type'=>'required'
            ]);
            $category = Category::find($id);
            $category->name = $request->input('name');
            $category->type = $request->input('type');
            $category->status = $request->input('status') != "" ? $request->input('status') : 0;

            
            if($category->icon != "" && $request->file('icon'))
            {
                unlink($category->icon);
            }

            // code for file upload
            if($files = $request->file('icon')){  
                $file_name = uniqid().".".$files->getClientOriginalExtension();
                $directory = "./category_icon/";
                if (!file_exists($directory)) {
                  mkdir($directory, 0777, true);
                }
                $save_name = "category_icon/".$file_name;
                $files->move('category_icon',$file_name);  
                $category->icon = $save_name;
            }
            if ($category->save()) {
                //Redirect success
                return redirect()->route('category')->with('success', 'Category updated successfully.');
            }

            //Redirect error
            return redirect()->route('category')->with('error', 'Failed to update category.');

        } catch (Exception $e) {
            Log::error("Failed to update category.");
        }
    }
    /* for change status of a category*/
    public function changeStatus_datatable(Request $request, $id) {
        $category = Category::find($id);

        if ($category->status == 1) {
            $category->status = 0;
        } elseif ($category->status == 0) {
            $category->status = 1;
        }
        $category->save();

        return redirect()->route('category')->with('success', 'Category Status Updated');

    }

    /* for create datatable data*/
    public function allData(Request $request) {

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'icon',
            3 => 'type',
            4 => 'status',
        );

        $totalData = Category::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = Category::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = Category::where('name', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Category::where('name', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $show = route('category.edit', $post->id);
                $edit = route('category.edit', $post->id);
                $url = asset('uploads/category/' . $post->image);

                $st = route('category.status', $post->id);

                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['icon'] = '<img src="'. asset($post->icon) .'" alt="Everyday Expense" style="width: 50px;">';
                $nestedData['type'] = $post->type == "1" ? "Expense" : "Income";
                
                if ($post->status == '1') {
                    $nestedData['status'] = '<span class="badge badge-success">Active</span>';
                } else {
                    $nestedData['status'] = '<span class="badge badge-danger">Paused</span>';
                }

                $options = "";

                if ($post->status == '1') {
                    $options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-warning" data-toggle="tooltip" title="Pause Category"> <i class="fa fa-power-off"></i> </a>&nbsp;';
                } else {
                    $options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-success changeCategoryStatus" data-toggle="tooltip" title="Activate Category"> <i class="fas fa-play"></i> </a>&nbsp;';
                }

                if (get_user_permission("Category", "edit")) {
                    $options .= '<a href="' . $edit . '" class="btn btn-rounded btn-sm btn-info" data-toggle="tooltip" title="Edit Category"><i class="fas fa-pencil-alt">
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


    public function existCategory(Request $request) {

        $id = $request->input('id');
        if ($id != '') {
            $title_exists = (count(Category::where('id', '!=', $id)->where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
            return response()->json($title_exists);
        } else {
            $title_exists = (count(Category::where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
            return response()->json($title_exists);
        }
    }
}
