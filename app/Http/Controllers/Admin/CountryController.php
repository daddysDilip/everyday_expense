<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;


class CountryController extends Controller
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
        $activeTab = 'Country';
        $country = Country::all();
        return View('admin/country/index', compact('country', 'activeTab'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $activeTab = 'Country';
        return View('admin/country/create', compact('activeTab'));
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
            'currency_name'=>'required',
            'currency_symbol'=>'required'
        ]);
        $country = new Country;
        $country->name = $request->input('name');
        $country->currency_name = $request->input('currency_name');
        $country->currency_symbol = $request->input('currency_symbol');
        $country->status = $request->input('status') != "" ? $request->input('status') : 0;

        if (!$country->save()) {
            //Redirect error
            return redirect()->route('country')->with('error', 'Failed to update country details.');
        }

        //Redirect success
        return redirect()->route('country')->with('success', 'Country added successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $activeTab = 'Country';
        $country = Country::find($id);
        return View('admin/country/create', compact('activeTab', 'country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        try {
            $country = Country::find($id);
            $country->name = $request->input('name');
            $country->currency_name = $request->input('currency_name');
            $country->currency_symbol = $request->input('currency_symbol');
            $country->status = $request->input('status') != "" ? $request->input('status') : 0;

            if ($country->save()) {
                //Redirect success
                return redirect()->route('country')->with('success', 'Country updated successfully.');
            }

            //Redirect error
            return redirect()->route('country')->with('error', 'Failed to update country.');

        } catch (Exception $e) {
            Log::error("Failed to update country.");
        }
    }
    /* for change status of a country*/
    public function changeStatus_datatable(Request $request, $id) {
        $country = Country::find($id);

        if ($country->status == 1) {
            $country->status = 0;
        } elseif ($country->status == 0) {
            $country->status = 1;
        }
        $country->save();

        return redirect()->route('country')->with('success', 'Country Status Updated');

    }

    /* for create datatable data*/
    public function allData(Request $request) {

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'currency_name',
            3 => 'status',
        );

        $totalData = Country::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = Country::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = Country::where('name', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->orWhere('currency_name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Country::where('name', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->orWhere('currency_name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $show = route('country.edit', $post->id);
                $edit = route('country.edit', $post->id);
                $url = asset('uploads/country/' . $post->image);

                $st = route('country.status', $post->id);

                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['currency_name'] = $post->currency_name;
                $nestedData['currency_symbol'] = $post->currency_symbol;
                
                if ($post->status == '1') {
                    $nestedData['status'] = '<span class="badge badge-success">Active</span>';
                } else {
                    $nestedData['status'] = '<span class="badge badge-danger">Paused</span>';
                }

                $options = "";

                if ($post->status == '1') {
                    $options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-warning" data-toggle="tooltip" title="Pause Country"> <i class="fa fa-power-off"></i> </a>&nbsp;';
                } else {
                    $options = '<a href="' . $st . '" class="btn btn-rounded btn-sm btn-success changeCategoryStatus" data-toggle="tooltip" title="Activate Country"> <i class="fas fa-play"></i> </a>&nbsp;';
                }

                if (get_user_permission("Countries", "edit")) {
                    $options .= '<a href="' . $edit . '" class="btn btn-rounded btn-sm btn-info" data-toggle="tooltip" title="Edit Country"><i class="fas fa-pencil-alt">
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


    public function existCountry(Request $request) {

        $id = $request->input('id');
        if ($id != '') {
            $title_exists = (count(Country::where('id', '!=', $id)->where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
            return response()->json($title_exists);
        } else {
            $title_exists = (count(Country::where('name', '=', $request->input('name'))->get()) > 0) ? false : true;
            return response()->json($title_exists);
        }
    }
}
