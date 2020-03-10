<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
class AdminController extends Controller
{
public $successStatus = 200;

    public function loginAdmin(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['type']= User::join('user_roles', 'user_roles.id_user', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'user_roles.id_role')
            ->select('type')
            ->get()[0]['type'];
            $success['id'] = $user->id;
            $success['email'] = $user->email;
            $success['token'] =  $user->createToken('ainayati')-> accessToken;
            return response()->json(['code'=>'0','status'=>'200','data'=>$success], 200);
        }
        else{
            return response()->json(['code'=>'01','status'=>'401','data'=>[]], 200);
        }
    }

    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
                    return response()->json(['code'=>'02','status'=>'401','data'=>$validator->errors()], 200);
                }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        try{
            $user = User::create($input);
        }catch(\Exception $e){
            return response()->json(['code'=>'03','status'=>'500','data'=>$e->errorInfo[2]], 200);

        }        
        $userRole = UserRole::create(['id_user'=>$user->id,'id_role'=>2]);
        $success=$user;
        $success['token'] =  $user->createToken('ainayati')-> accessToken;
        return response()->json(['code'=>'0','status'=>'200','data'=>$success], 200);
    }

    public function getAdminList(Request $request)
    {
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.id_user')
                ->select(['users.id','users.email'])
                ->where('user_roles.id_role','=',2)
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        }else{
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No doctors found'], 200);
        }
    }
    
}
