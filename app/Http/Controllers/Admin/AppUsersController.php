<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;


class AppUsersController extends Controller
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
        $activeTab = 'AppUsers';
        $users = User::all();
        // dd($users); die;
        return View('admin/app_users/index', compact('users', 'activeTab'));
    }

    /* for create datatable data*/
    public function allData(Request $request) {

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'mobile',
            4 => 'country.name',
            5 => 'languages.name',
            6 => 'gender',
            7 => 'last_open',
            8 => 'login_type',
        );

        $totalData = User::where('user_type', '=', 1)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = User::leftJoin('country', 'country.id', '=', 'users.country_id')
                ->leftJoin('languages', 'languages.id', '=', 'users.language_id')
                ->select('users.*', 'country.name as country_name', 'languages.name as language_name')
                ->where('users.user_type', '=', 1)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = User::leftJoin('country', 'country.id', '=', 'users.country_id')
                ->leftJoin('languages', 'languages.id', '=', 'users.language_id')
                ->select('users.*', 'country.name as country_name', 'languages.name as language_name')
                ->where('users.user_type', '=', 1)
                ->where('country.name', 'LIKE', "%{$search}%")
                ->where('languages.name', 'LIKE', "%{$search}%")
                ->orWhere('users.name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = User::leftJoin('country', 'country.id', '=', 'users.country_id')
                ->where('users.name', 'LIKE', "%{$search}%")
                ->where('users.user_type', '=', 1)
                ->where('country.name', 'LIKE', "%{$search}%")
                ->where('languages.name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        
        if (!empty($posts)) {
            foreach ($posts as $post) {
                
                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['email'] = $post->email;
                $nestedData['mobile'] = $post->mobile;
                $nestedData['country_name'] = $post->country_name;
                $nestedData['language_name'] = $post->language_name;
                $nestedData['gender'] = $post->gender;
                $nestedData['last_open'] = $post->last_open;
                // $nestedData['user_image'] = '<img src="'. asset($post->user_image) .'" alt="Everyday Expense" style="width: 50px;">';
                $nestedData['login_type'] = $post->login_type;
                
                // $options = "";

                // $nestedData['options'] = $options;
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
