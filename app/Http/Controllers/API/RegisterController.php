<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
// use App\Models\API\AppUsers as User;
use App\Models\User;
use App\Models\Languages;
use App\Models\Country;
use App\Models\API\UserCategory;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;


class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     *  for register user if user is already exist then update user detail
     *  insert default categories in to user catagory if not exist
     */
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'username'   => 'required', // facebook or google's unique Id
            'name'       => 'required',
            'email'      => 'required|email|max:255',
            'mobile'     => 'numeric',
            'fcm_token'  => 'required',
            'gender'     => 'in:male,female,other',
            'login_type' => 'in:facebook,google'
        ]);
        

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $input = $request->all();

		$user = User::where('username', $request->username)->first();
        if(is_null($user))
        {
            $user = new User;
            $user->date_format = "DD/MM/YYYY";
        }
        $token = $user->createToken('EverydayExpense')->accessToken;

        $user->username   = $request->username;
        $user->name       = $request->name;
        $user->email      = $request->email;
        $user->mobile     = $request->mobile;
        $user->fcm_token  = $request->fcm_token;
        $user->gender     = $request->gender;
        $user->login_type = $request->login_type;
        $user->user_type  = 1;
        $user->api_token  = $token;
        $user->user_image = $request->user_image;
       
        $user->save();
        $success =  $user->toArray();

        // for insert default categories in to user catagory
        if(!is_null($user))
        {
            $user_category = UserCategory::where('user_id', $user->id)->first();
        }
        if(is_null($user) && is_null($user_category))
        {
            $category = DB::table('category')->where('status', 1)->get();
            $data = array();
            foreach($category->toArray() as $key => $val)
            {
                $data[] = [ "user_id"=>$user->id,
                            "category_id" => $val->id, 
                            "category_name" => $val->name, 
                            "type" => $val->type,
                            "icon" => $val->icon
                        ];
            }
            // insert batch
            UserCategory::insert($data);
        }

        $success['token'] = $token;
        return $this->sendResponse($success, 'User register successfully.');
    }

    /*
    *   for get all countries and languages in 2 arrays
    */
    public function getCountryLanguages()
    {
        $country = Country::all();
        $language = Languages::all();
        $response['country'] = $country->toArray();
        $response['language'] = $language->toArray();
        return $this->sendResponse($response, 'Country and Languages retrieved successfully.');

    }

    /*
    *   for upodate user language and country
    */
    public function updateLanguage(Request $request)
    {

        
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required|numeric', // facebook or google's unique Id
            'country_id' => 'required|numeric',
            'language_id' => 'required|numeric'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $id =$request->user_id;
        $user = User::find($id);
        $user->country_id  = $request->country_id;
        $user->language_id = $request->language_id;
        $user->save();

        $success = User::leftJoin('country', 'country.id', '=', 'users.country_id')
        ->leftJoin('languages', 'languages.id', '=', 'users.language_id')
        ->select('users.*', 'country.name as country_name', 'languages.name as language_name')
        ->where('users.id', $id)->get();
        $success = $success->toArray();
        unset($success[0]['api_token']);
        //pr($success); die;
        return $this->sendResponse($success, 'Country and Languages updated successfully.');
    }

    /*
    *   for upodate user date format
    */
    public function updateDateFormat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|numeric',
            'date_format' => 'required|in:DD/MM/YYYY,MM/DD/YYYY,DD-MM-YYYY,MM-DD-YYYY,DD-MMM-YYYY'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $id =$request->user_id;
        $user = User::find($id);
        $user->date_format  = $request->date_format;
        $user->save();

        $success = User::leftJoin('country', 'country.id', '=', 'users.country_id')
        ->leftJoin('languages', 'languages.id', '=', 'users.language_id')
        ->select('users.*', 'country.name as country_name', 'languages.name as language_name')
        ->where('users.id', $id)->get();
        
        return $this->sendResponse($success->toArray(), 'Date format updated successfully.');
    }

    /*
    *   get user's detail
    */
    function getUserDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|numeric'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $id =$request->user_id;
        $success = User::leftJoin('country', 'country.id', '=', 'users.country_id')
            ->leftJoin('languages', 'languages.id', '=', 'users.language_id')
            ->select('users.*', 'country.name as country_name', 'country.currency_symbol', 'languages.name as language_name')
            ->where('users.id', $id)->get();
        
        return $this->sendResponse($success->toArray(), 'User retrieved successfully.');
    }

    /*
    *   for get all settings, static buttons, or any text in perticular language. 
    *   also default seting in english
    */
    public function getLanguageSetting(Request $request)
    {
        // DB::enableQueryLog();
        $validator = Validator::make($request->all(), [
            'language_id' => 'required|numeric'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $contents = DB::table('tbl_content')
                ->leftJoin('content_translation', 'tbl_content.id', '=', 'content_translation.string_id')
                ->leftJoin('languages', 'languages.id', '=', 'content_translation.language_id')
                ->select('content_translation.language_id', 'content_translation.translation', 'tbl_content.english_string as english_string', 'languages.name', 'tbl_content.key_slug', 'languages.name_in_language','languages.code')
                ->where('languages.id', $request->language_id)
                ->where('tbl_content.status', 1)
                ->where('languages.status', 1)
                ->get();
        $contents_default = DB::table('tbl_content')
            ->select('key_slug', 'english_string')
            ->where('tbl_content.status', 1)
            ->get();
        if($contents->count() != 0)
        {
            $response['language_content'] = $contents->toArray();
        } else {
            $response['language_content'] = [];
        }
        $response['default_content'] = $contents_default->toArray();
        
        return $this->sendResponse($response, 'Settings retrieved successfully.');

    }

    /*public function login (Request $request) {
        $user = User::where('username', $request->username)->first();
        if ($user) {
        	$pwd = Crypt::decrypt($user->password);

            if ($request->password == $pwd) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token];
                $success =  $user;
                $success['token'] =  $token;
                
                return $this->sendResponse($success, 'Login successfully.');
            } else {
                $response = "Password incorrect";
                return $this->sendError('Password incorrect.'); 
            }
        } else {
            $response = 'User does not exist';
             return $this->sendError('User does not exist.'); 
        }
    }*/
    
    public function updateprofile(Request $request)
    {
        try {
        	$validator = Validator::make($request->all(), [
               'user_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $date = date('Y-m-d h:i:s');
            $id =$request->user_id;

            $user = User::find($id);
            if($user)
            {
            	$user->firstname  = $request->firstname;
		        $user->lastname   = $request->lastname;
		        $user->name       = $request->firstname .' '.$request->lastname;
		        $user->contactno  = $request->contactno;
		        $user->ageGroup   = $request->ageGroup;
		        $user->address    = $request->address;
		        $user->country    = $request->country;
		        $user->state      = $request->state;
		        $user->city       = $request->city;
		        $user->gender     = $request->gender;
		        $user->dob        = $request->dob;

		        if($request->hasFile('user_image')) 
		        {
	                 if($request->file('user_image'))
		             {
			            $new_name = rand() . '.' . $request->user_image->getClientOriginalExtension();
			            $request->user_image->move(public_path('profiles/'), $new_name);
			            $user->user_image       =   $new_name;
		             }
	            }

	            if(!$user->save()) {
	                //Redirect error
	                return $this->sendError('Failed to update profile'); 
	            }

	             //Redirect success
	             $success =  $user;
	             return $this->sendResponse($success, 'updated profile successfully.');

            }else
            {
            	 return $this->sendError('User does not exist'); 
            }

        } catch (Exception $e) {    
             return $this->sendError('Failed to update profile'); 
        }
    }

    public function index()
    {
        $products = User::all();
        return $this->sendResponse($products->toArray(), 'Products retrieved successfully.');
    }

    public function show($id)
    {
        $product = User::find($id);

        if (is_null($product)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse($product->toArray(), 'User retrieved successfully.');
    }
        

}