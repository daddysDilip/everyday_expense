<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CategoryTranslat;


class CategoryTranslatController extends Controller
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
        $activeTab = 'CategoryTranslat';
        $categoryTranslat = CategoryTranslat::all();
        return View('admin/categoryTranslat/index', compact('categoryTranslat', 'activeTab'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $activeTab = 'CategoryTranslat';
        return View('admin/categoryTranslat/create', compact('activeTab'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'category'=>'required',
            'language'=>'required',
            'translation'=>'required'
        ]);
        $categoryTranslat = new CategoryTranslat;
        $categoryTranslat->category_id = $request->input('category');
        $categoryTranslat->language_id = $request->input('language');
        $categoryTranslat->translation = $request->input('translation');
        

        if (!$categoryTranslat->save()) {
            //Redirect error
            return redirect()->route('categoryTranslat')->with('error', 'Failed to update category Translat details.');
        }

        //Redirect success
        return redirect()->route('categoryTranslat')->with('success', 'Category Translat added successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $activeTab = 'CategoryTranslat';
        $categoryTranslat = CategoryTranslat::find($id);
        // dd($categoryTranslat); die;
        return View('admin/categoryTranslat/create', compact('activeTab', 'categoryTranslat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        try {
            $categoryTranslat = CategoryTranslat::find($id);
            $categoryTranslat->category_id = $request->input('category');
            $categoryTranslat->language_id = $request->input('language');
            $categoryTranslat->translation = $request->input('translation');

            if ($categoryTranslat->save()) {
                //Redirect success
                return redirect()->route('categoryTranslat')->with('success', 'Category Translat updated successfully.');
            }

            //Redirect error
            return redirect()->route('categoryTranslat')->with('error', 'Failed to update category Translat.');

        } catch (Exception $e) {
            Log::error("Failed to update category Translat.");
        }
    }
    

    /* for create datatable data*/
    public function allData(Request $request) {

        $columns = array(
            0 => 'id',
            1 => 'category.name',
            2 => 'category.key_slug',
            3 => 'languages.name',
            4 => 'languages.code'
        );

        $totalData = CategoryTranslat::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = CategoryTranslat::leftJoin('category', 'category.id', '=', 'category_translation.category_id')
                ->leftJoin('languages', 'languages.id', '=', 'category_translation.language_id')
                ->select('category_translation.*', 'category.name as category', 'languages.name','languages.name_in_language','languages.code')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = CategoryTranslat::leftJoin('category', 'category.id', '=', 'category_translation.category_id')
                ->leftJoin('languages', 'languages.id', '=', 'category_translation.language_id')
                ->select('category_translation.*', 'category.name as category', 'languages.name','languages.name_in_language','languages.code')
                ->where('languages.name', 'LIKE', "%{$search}%")
                ->orWhere('languages.code', 'LIKE', "%{$search}%")
                ->orWhere('category.name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = CategoryTranslat::leftJoin('category', 'category.id', '=', 'category_translation.category_id')
                ->leftJoin('languages', 'languages.id', '=', 'category_translation.language_id')
                ->select('category_translation.*', 'category.name as category', 'languages.name','languages.name_in_language','languages.code')
                ->where('languages.name', 'LIKE', "%{$search}%")
                ->orWhere('languages.code', 'LIKE', "%{$search}%")
                ->orWhere('category.name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $show = route('categoryTranslat.edit', $post->id);
                $edit = route('categoryTranslat.edit', $post->id);
                $url = asset('uploads/categoryTranslat/' . $post->image);

                $nestedData['id'] = $post->id;
                $nestedData['category'] = $post->category;
                $nestedData['language'] = $post->name."(".$post->name_in_language.")";
                $nestedData['code'] = $post->code;
                $nestedData['translation'] = $post->translation;
                
                $options = "";

                if (get_user_permission("CategoryTranslation", "edit")) {
                    $options .= '<a href="' . $edit . '" class="btn btn-rounded btn-sm btn-info" data-toggle="tooltip" title="Edit Category Translat"><i class="fas fa-pencil-alt">
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

}
