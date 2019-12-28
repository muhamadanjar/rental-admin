<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Rental\Models\UserMeta;
use App\User;
use Carbon\Carbon;
class UserMetaCtrl extends ApiCtrl
{	
	private $auth;
	public function __construct(Request $request){
		parent::__construct();
		$this->auth = $request->user('api');
		
	}


    public function postMeta(Request $request)
    {
    	if($request->user('api') !== NULL)  {
			
			$messages = [
				'required' => 'The :attribute field is required.',
			];
			
			$validator = Validator::make($request->all(), [
				'meta_key'		=> 'required|max:225',
				'meta_value'	=> 'required|max:225'
			], $messages );

			if ($validator->fails()) {
				return response()->json(['status'=>'error', 'message'=> $validator->errors()->all(), 'code'=>200]);
			}
			
			$count = count($request->meta_key);
			$meta_users = array();
			for ($i=0; $i<$count; $i++){
				
		    	UserMeta::where('meta_key', $request->meta_key[$i])
		    		->where('meta_users', $this->auth->id)
		    		->where('meta_key', '!=', 'DOKUMEN_PRIBADI')
	    		->delete();
				

				if($request->meta_key[$i] == "DATA_DIRI_EKTP") {
					$checkKtp = $this->checkKtp($request->meta_value[$i]);
					if($checkKtp) {
						return response()->json(['status'=>'error', 'message'=>'KTP Sudah Terdaftar', 'code'=>200]);
						break;
					}
				}
		    	
				array_push($meta_users, array(
					'meta_key' 	 	=> $request->meta_key[$i],
					'meta_value' 	=> $request->meta_value[$i],
					'meta_users' 	=> $this->auth->id,
					'created_at' 	=> Carbon::now(),
					'updated_at' 	=> Carbon::now()
				));
	        }
			
			try{
				$meta = new UserMeta();
				$exec = $meta::insert($meta_users);
				if($exec) {
					return response()->json(['status'=>'success', 'message'=>'Data successfully saved', 'code'=>200]);
				}
			}
			catch (PDOException $e) {
				return response()->json(['status'=>'error', 'message'=> $e->getMessage(), 'code'=>404]);
			}
		}

		return response()->json(['status'=>'error', 'message'=> "Invalid Header Credential!", 'code'=>200]);
		
    }

    public function postFilesMeta(Request $request)
    {
    	if($request->user('api') !== NULL)
        {
        	$metafiles = getimagesize($_FILES["attach"]["tmp_name"]);
        	$metasizes = $_FILES["attach"]["size"];
        	
        	if(!$metafiles) {
    			return response()->json(['status'=>'error', 
					'message'=> 'files is not an image', 'code'=>404]);
        	}
        	
        	/*if($metafiles[0] > 20000 || $metafiles[1] > 20000) {
        		return response()->json(['status'=>'error', 
    				'message'=> 'files dimension is to large', 'code'=>404]);
        	}

        	if($metasizes > 10000000) {
        		return response()->json(['status'=>'error', 
        			'message'=> 'files size is to large', 'code'=>404]);
        	}*/

        	if($metafiles['mime'] != 'image/jpeg' && $metafiles['mime'] != 'image/jpg' && $metafiles['mime'] != 'image/png' && $metafiles['mime'] != 'image/webp') {
        		return response()->json(['status'=>'error', 
        			'message'=> 'files extention is not permitted', 'code'=>404]);
        	}

	    	try{

	    		$user 	= User::find($this->auth->getResourceOwnerID());
		    	$file 	= $request->file('attach')->getClientOriginalName();
		    	$upload = $request->file('attach')->move(base_path().'/public/images/users/',$file);

		    	UserMeta::where('meta_key','DOKUMEN_PRIBADI')->where('meta_value', $file)->where('meta_users', $this->auth->getResourceOwnerID())->delete();

		    	$data = array(
	    			'meta_key'	=> 'DOKUMEN_PRIBADI', 
	    			'meta_value'=> $file, 
	    			'meta_users'=> $this->auth->getResourceOwnerID(), 
	    			'created_at'=> Carbon::now(), 
	    			'updated_at'=> Carbon::now()
	    		);

		    	if($upload) {
					$meta = new UserMeta();
					$meta::insert($data);
					return response()->json(['status'=>'success', 'message'=>'Data successfully saved', 'code'=>200]);
				}

	    	} catch (\Illuminate\Database\QueryException $e) {
				return response()->json(['status'=>'error', 'message'=> $e->getMessage(), 'code'=>404]);
			}
		}

		return response()->json(['status'=>'error', 'message'=> "Invalid Header Credential!", 'code'=>404]);

    }

    public function checkUserMeta(Request $request) {

    	if($request->user('api') !== NULL){
			return response()->json(['status'=>'error', 'message'=> "Invalid Header Credential!", 'code'=>404]);
    	}

    	$messages = [
			'unique'   => 'The :attribute is already exist',
			'required' => 'The :attribute field is required.',
			'max'      => 'The :attribute character length does not match'
		];
			
		$validator = Validator::make($request->all(), [
			'meta_value'    => 'required|unique:m_users_meta|max:255',
		], $messages );

		if ($validator->fails()) {
			return response()->json(['status'=>'success', 'message'=> true, 'code'=>200]);
		}

		return response()->json(['status'=>'success', 'message'=> false, 'code'=>200]);
    }

    public function postStatusMeta(Request $request) {

    	$messages = [
			'unique'   => 'The :attribute is already exist',
			'required' => 'The :attribute field is required.',
			'max'      => 'The :attribute character length does not match'
		];
			
		$validator = Validator::make($request->all(), [
			'name' 	=> 'required|max:25|min:11',
			'value'	=> 'required|max:1|min:1',
		], $messages );

		if ($validator->fails()) {
			return response()->json(['status'=>'success', 'message'=> $validator->errors()->all(), 'code'=>200]);
		}

		switch ($request->name) {
		    case "informasi_pribadi":
		        $field = "informasi_pribadi";
	        break;
		    case "data_alamat":
		        $field = "data_alamat";
	        break;
    	 	case "data_pekerjaan":
		        $field = "data_pekerjaan";
	        break;
	        default:
	        	$field = null;
		}

		if($field == null) {
			return response()->json(['status'=>'success', 'message'=> false, 'code'=>200]);
		}

		$exec = User::where("id", $this->auth->id)->update([ $field => $request->value]);
		if(!$exec) {
			return response()->json(['status'=>'success', 'message'=> false, 'code'=>200]);
		}

		return response()->json(['status'=>'success', 'message'=> true, 'code'=>200]);
    }

    public function getStatusMeta(Request $request) {

    	$messages = [
			'unique'   => 'The :attribute is already exist',
			'required' => 'The :attribute field is required.',
			'max'      => 'The :attribute character length does not match'
		];
			
		$validator = Validator::make($request->all(), [
			'name' 	=> 'required|max:25|min:11',
		], $messages );

		if ($validator->fails()) {
			return response()->json(['status'=>'success', 'message'=> $validator->errors()->all(), 'code'=>200]);
		}

		switch ($request->name) {
		    case "informasi_pribadi":
		        $field = "informasi_pribadi";
	        break;
		    case "data_alamat":
		        $field = "data_alamat";
	        break;
    	 	case "data_pekerjaan":
		        $field = "data_pekerjaan";
	        break;
	        default:
	        	$field = null;
		}

		if($field == null) {
			return response()->json(['status'=>'success', 'message'=> false, 'code'=>200]);
		}

		$exec = User::select($field)->where("id", $this->auth->getResourceOwnerID())->first();

		if(!$exec) {
			return response()->json(['status'=>'success', 'message'=> false, 'code'=>200]);
		}

		return response()->json(['status'=>'success', 'message'=> (int)$exec->$field, 'code'=>200]);
    }

    

 	private function checkKtp($ktp_value) {
 		$res = UserMeta::where("meta_key", "DATA_DIRI_EKTP")->where("meta_value", $ktp_value)->first();
 		if($res) {
 			return true;
 		}

 		return false;
    }
}
