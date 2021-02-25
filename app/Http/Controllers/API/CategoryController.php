<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\API\UserCategory;
use App\Models\Category;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class CategoryController extends BaseController
{
    /**
     *  create new user define category
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required',
            'type'    => 'required|in:1,0',
            'category_name'       => 'required'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $category                   = new UserCategory;
        $category->category_name    = $request->category_name;
        $category->user_id          = $request->user_id;
        $category->type             = $request->type;
        $category->is_user_defiend  = 1;
        
        // code for file upload
        if($files = $request->file('icon')){  
            $file_name = uniqid().".".$files->getClientOriginalExtension();
            $directory = "./user_category_icon/";
            if (!file_exists($directory)) {
              mkdir($directory, 0777, true);
            }
            $save_name = "user_category_icon/".$file_name;
            $files->move('user_category_icon',$file_name);
            $category->icon = $save_name;
        }
        // end file upload
        
        $category->save();
        $category->icon = asset($category->icon);
        $success =  $category;
        
        return $this->sendResponse($success, 'Category inserted successfully.');
    }
    
    /**
     *  edit user define category
     */
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required',
            'type'          => 'required|in:1,0',
            'category_name' => 'required',
            'id'            => 'required',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $category                  = UserCategory::find($request->id);
        $category->category_name   = $request->category_name;
        $category->user_id         = $request->user_id;
        $category->type            = $request->type;
        $category->is_user_defiend = 1;

        if($category->icon != "" && $request->file('icon'))
        {
            unlink($category->icon);
        }
        
        // code for file upload
        if($files = $request->file('icon')){  
            $file_name = uniqid().".".$files->getClientOriginalExtension();
            $directory = "./user_category_icon/";
            if (!file_exists($directory)) {
              mkdir($directory, 0777, true);
            }
            $save_name = "user_category_icon/".$file_name;
            $files->move('user_category_icon',$file_name);  
            $category->icon = $save_name;
        }
        // end file upload

        $category->save();
        $category->icon = asset($category->icon);
        $success =  $category;
        
        return $this->sendResponse($success, 'Category updated successfully.');
    }

    /**
     *  get all category of a user
     */
    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'  
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $category = UserCategory::leftJoin('category as c', 'c.id', '=', 'user_category.category_id')
                ->select(DB::raw('IF(user_category.is_user_defiend = "1", user_category.category_name, c.name) as category_name'), DB::raw('IF(user_category.is_user_defiend = "1", user_category.icon, c.icon) as icon'), DB::raw('IF(user_category.is_user_defiend = "1", user_category.type, c.type) as type'), 'user_category.id')
                ->where('user_category.user_id', $request->user_id)
                ->where('user_category.status', 1)
                ->get();
        
        $success =  $category->toArray();
        foreach($success as $key => $val)
        {
            if($val['icon'] != "")
            {
                $success[$key]['icon'] = asset($val['icon']);
            }
            $success[$key]['type_string'] = $val['type'] == '1' ? "expense" : "income";
        }

        return $this->sendResponse($success, 'Retrieve category successfully.');
    }

    /**
     *  delete a category of a user
     */
    function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $category         = UserCategory::find($request->id);
        $category->status = 0;

        $category->save();
        return $this->sendResponse([], 'Category deleted successfully.');
    }
}