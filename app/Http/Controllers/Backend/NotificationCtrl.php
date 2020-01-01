<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Rental\Models\Notification;
class NotificationCtrl extends BackendCtrl{
    public function read($a){
        if ($a != NULL) {
            Notification::where('id',$a)->update([
                'status'=>1,
                'read_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);    
        }
    }

    public function get_url(){
        $b = Notification::where('id',$a)->select('url')->first();
        return (isset($b) && trim($b->url) !== '') ? trim($b->url): '';
    }

    public function get_data($a){
        if ($a != NULL) {
            $b = Notification::where('id',$a)->select('data')->first();
            return (isset($b) && trim($b->data) !== '') ? trim($b->data): '';
        }
    }
    public function to_users($a,$b = array()){
        if (is_array($a)) {
			$d = array();
			foreach ($a as $c) {
				$f['data'] = ((isset($b['data']) && json_decode($b['data']) !== NULL) ? $b['data']: NULL);
				$f['message'] = (isset($b['text']) ? $b['text']: '');
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
        # code...
    }
}
