<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserRole;


use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public function loginUser()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success = $user;
            $success['type'] = User::join('user_roles', 'user_roles.id_user', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'user_roles.id_role')
                ->where('users.id', '=', $user->id)
                ->select('type')
                ->get()[0]['type'];
            $success['token'] =  $user->createToken('ainayati')->accessToken;
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $success,
            ], 200);
        } else {
            return response()->json(['code' => '01', 'status' => '401', 'data' => []], 200);
        }
    }

    public function registerDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required',
            'home_address' => 'required',
            'work_address' => '',
            'specialty' => '',
            'gender' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        try {
            $user = User::create($input);
        } catch (\Exception $e) {
            return response()->json(['code' => '03', 'status' => '500', 'data' => $e->errorInfo[2]], 200);
        }
        $userRole = UserRole::create(['id_user' => $user->id, 'id_role' => 3]);
        $success = $user;
        $success['token'] =  $user->createToken('ainayati')->accessToken;
        return response()->json(['code' => '0', 'status' => '200', 'data' => $success], 200);
    }

    public function registerPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required',
            'home_address' => 'required',
            'description' => '',
            'weight' => '',
            'height' => '',
            'gender' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        try {
            $user = User::create($input);
        } catch (\Exception $e) {
            return response()->json(['code' => '03', 'status' => '500', 'data' => $e->errorInfo[2]], 200);
        }
        $userRole = UserRole::create(['id_user' => $user->id, 'id_role' => 5]);
        $success = $user;
        $success['token'] =  $user->createToken('ainayati')->accessToken;
        return response()->json(['code' => '0', 'status' => '200', 'data' => $success], 200);
    }

    public function registerNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required',
            'home_address' => 'required',
            'work_address' => '',
            'gender' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        try {
            $user = User::create($input);
        } catch (\Exception $e) {
            return response()->json(['code' => '03', 'status' => '500', 'data' => $e->errorInfo[2]], 200);
        }
        $userRole = UserRole::create(['id_user' => $user->id, 'id_role' => 4]);
        $success = $user;
        $success['token'] =  $user->createToken('ainayati')->accessToken;
        return response()->json(['code' => '0', 'status' => '200', 'data' => $success], 200);
    }

    public function getProfileInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $user = User::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                'code' => '04',
                'status' => '404',
                'data' => "We can't find a user with that id"
            ], 200);
        }
        return response()->json([
            'code' => '0',
            'status' => '200',
            'data' => $user,
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $user = User::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                'code' => '04',
                'status' => '404',
                'data' => "We can't find a user with that id"
            ], 200);
        }

        foreach ($request->all() as $key => $value) {
            $user[$key] = $value;
        }
        $user->save();
        return response()->json([
            'code' => '0',
            'status' => '200',
            'data' => $user,
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $user = User::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                'code' => '04',
                'status' => '404',
                'data' => "We can't find a user with that id"
            ], 200);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['code' => '0', 'status' => '200', 'data' => $user], 200);
    }

    public function getPatientList(Request $request)
    {
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.id_user')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.description','users.height','users.weight','users.gender'])
                ->where('user_roles.id_role', '=', 5)
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No patients found'], 200);
        }
    }

    public function getDoctorList(Request $request)
    {
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.id_user')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.work_address','users.specialty','users.gender'])
                ->where('user_roles.id_role', '=', 3)
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No doctors found'], 200);
        }
    }

    public function getNurseList(Request $request)
    {
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.id_user')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.work_address','users.gender'])
                ->where('user_roles.id_role', '=', 4)
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No nurses found'], 200);
        }
    }

    public function removeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $userRole = UserRole::where('id_user', '=', request('id'))
                ->delete();
        if (!$userRole) {
            return response()->json(['code' => '04', 'status' => '404', 'data' => 'User not found'], 200);
        }
        $user = User::where('id', '=', request('id'))
                ->get();
        if ($user) {
            User::where('id', '=', request('id'))
                ->delete();
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $user
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '404', 'data' => 'User not found'], 200);
        }
    }
}
