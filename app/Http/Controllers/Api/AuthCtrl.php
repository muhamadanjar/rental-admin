<?php namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
class AuthCtrl extends Controller{
    use AuthenticatesUsers;
    protected $guard = 'api';
    public function signup(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);        
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);        
        $user->save();        
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
  
    public function login(Request $request){
        if ($request->isMethod('get')) {
            return response()->json(['message'=>'a']);
        }
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        };
        try {
            $credentials = request(['email', 'password']);
            $credentials['isactived'] = 1;
            // $credentials['isverified'] = 1;
            if(Auth::guard('users')->attempt($credentials)){
                // $user = Auth::guard('users')->user();
                // $success['token'] = $user->createToken('MyApp')->accessToken;
                $user = $request->user('users');
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                // dd($user);
                if ($user->isanggota === 2) {
                    User::whereId($user->id)->update(['isavail'=>1]);
                }
                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1);        
                    $token->save();        
                return response()->json([
                    'status'=>'success',
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'data'=>$user,
                    'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
                ],200);
                
            }else{
                return response()->json([
                    'message_code' => 'INVALID_PASSWORD',
                    'message' => trans('auth.failed'),
                    'code'=> 401,
                    'status'=>'failed'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'code'=>401,
                'message' => trans('auth.failed'),
                'status'=>'failed'
            ], 200);
        }
        
                    
    }
  
    public function logout(Request $request){
        try {
            User::find($request->user('api')->id)->update(['isavail'=>0]);
            $request->user('api')->token()->revoke();
            return response()->json([
                'message' => trans('auth.logout')
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }
  
    public function user(Request $request){
        $user = $request->user();
        
        return response()->json(['status'=>true,'data'=>$user,'meta'=>$user->user_metas,'saldo'=>$user->saldo]);
    }
}