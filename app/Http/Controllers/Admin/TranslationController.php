<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Translation;


class TranslationController extends Controller
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
        $activeTab = 'Translation';
        $translation = Translation::all();
        return View('admin/translation/index', compact('translation', 'activeTab'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $activeTab = 'Translation';
        return View('admin/translation/create', compact('activeTab'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'content'=>'required',
            'language'=>'required',
            'translation'=>'required'
        ]);
        $translation = new Translation;
        $translation->string_id = $request->input('content');
        $translation->language_id = $request->input('language');
        $translation->translation = $request->input('translation');
        

        if (!$translation->save()) {
            //Redirect error
            return redirect()->route('translation')->with('error', 'Failed to update translation details.');
        }

        //Redirect success
        return redirect()->route('translation')->with('success', 'Translation added successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $activeTab = 'Translation';
        $translation = Translation::find($id);
        return View('admin/translation/create', compact('activeTab', 'translation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        try {
            $translation = Translation::find($id);
            $translation->string_id = $request->input('content');
            $translation->language_id = $request->input('language');
            $translation->translation = $request->input('translation');

            if ($translation->save()) {
                //Redirect success
                return redirect()->route('translation')->with('success', 'Translation updated successfully.');
            }

            //Redirect error
            return redirect()->route('translation')->with('error', 'Failed to update translation.');

        } catch (Exception $e) {
            Log::error("Failed to update translation.");
        }
    }
    

    /* for create datatable data*/
    public function allData(Request $request) {

        $columns = array(
            0 => 'id',
            1 => 'tbl_content.english_string',
            2 => 'languages.name',
            3 => 'languages.code'
        );

        $totalData = Translation::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = Translation::leftJoin('tbl_content', 'tbl_content.id', '=', 'content_translation.string_id')
                ->leftJoin('languages', 'languages.id', '=', 'content_translation.language_id')
                ->select('content_translation.*', 'tbl_content.english_string as english_string', 'languages.name','languages.name_in_language','languages.code')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = Translation::leftJoin('tbl_content', 'tbl_content.id', '=', 'content_translation.string_id')
                ->leftJoin('languages', 'languages.id', '=', 'content_translation.language_id')
                ->select('content_translation.*', 'tbl_content.english_string as english_string', 'languages.name','languages.name_in_language','languages.code')
                ->where('languages.name', 'LIKE', "%{$search}%")
                ->orWhere('languages.code', 'LIKE', "%{$search}%")
                ->orWhere('tbl_content.english_string', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Translation::leftJoin('tbl_content', 'tbl_content.id', '=', 'content_translation.string_id')
                ->leftJoin('languages', 'languages.id', '=', 'content_translation.language_id')
                ->select('content_translation.*', 'tbl_content.english_string as english_string', 'languages.name','languages.name_in_language','languages.code')
                ->where('languages.name', 'LIKE', "%{$search}%")
                ->orWhere('languages.code', 'LIKE', "%{$search}%")
                ->orWhere('tbl_content.english_string', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $show = route('translation.edit', $post->id);
                $edit = route('translation.edit', $post->id);
                $url = asset('uploads/translation/' . $post->image);

                $nestedData['id'] = $post->id;
                $nestedData['english_string'] = $post->english_string;
                $nestedData['language'] = $post->name."(".$post->name_in_language.")";
                $nestedData['code'] = $post->code;
                $nestedData['translation'] = $post->translation;
                
                $options = "";

                if (get_user_permission("Translation", "edit")) {
                    $options .= '<a href="' . $edit . '" class="btn btn-rounded btn-sm btn-info" data-toggle="tooltip" title="Edit Translation"><i class="fas fa-pencil-alt">
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
