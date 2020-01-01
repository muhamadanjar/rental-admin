<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Rental\Models\Notification;
use Carbon\Carbon;
class NotificationCtrl extends BackendCtrl{
    public function read(Request $request,$id){
        if ($id != NULL) {
            Notification::where('id',$id)->update([
                'status'=>1,
                'read_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);    
        }

        if ($request->ajax()) {
            return response()->json(array('message'=>'Data Notif di perbaharui'));
        }else{
            return \response()->redirectToIntended('/');
        }
        
    }

    public function get_url(Request $request,$id){
        $b = Notification::where('id',$id)->select('url')->first();
        return (isset($b) && trim($b->url) !== '') ? trim($b->url): '';
    }

    public function get_data($id){
        if ($a != NULL) {
            $b = Notification::where('id',$id)->select('data')->first();
            return (isset($b) && trim($b->data) !== '') ? trim($b->data): '';
        }
    }
    public function to_users(Request $request){
        $a = $request->to_users;
        $b = $request;
        if (is_array($a)) {
			$d = array();
			foreach ($a as $c) {
				$f['data'] = ((isset($b['data']) && json_decode($b['data']) !== NULL) ? $b['data']: NULL);
                $f['message'] = (isset($b['text']) ? $b['text']: '');
                $f['jenis'] = (isset($b['jenis']) ? $b['jenis']: '');
				$f['user_id'] = $c;
				$f['url'] = (isset($b['url']) ? $b['url']: NULL);
				$f['created_at'] = date('Y-m-d H:i:s');
				array_push($d, $f);
			}
			return Notification::insert($d);
		}else if (is_numeric($a) || is_string($a)) {
			$c = array(
				'data' => ((isset($b['data']) && json_decode($b['data']) !== NULL) ? $b['data']: NULL),	
				'message' => (isset($b['text']) ? $b['text']: ''),
				'user_id' => $a,
				'url' => (isset($b['url']) ? $b['url']: NULL),
                'created_at' => date('Y-m-d H:i:s')
            );
			return Notification::insert($c);
		}else { return FALSE; }
    }

    public function to_roles($roles = NULL){
        if (is_array($roles)) {
            
        }elseif (is_numeric($roles) || is_string($roles)) {
            
        }
    }
}
