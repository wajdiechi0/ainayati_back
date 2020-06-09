<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\HeartRate;
use App\Activity;

use Illuminate\Support\Facades\Auth;
use Validator;

class HealthController extends Controller
{
    public function addNewActivity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' =>'required',
            'type' => 'required',
            'distance' => 'required',
            'speed' => 'required',
            'date' => 'required',
            'duration' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $user = User::where('id', $request->id_user)->first();
        if (!$user) {
            return response()->json([
                'code' => '04',
                'status' => '404',
                'data' => "We can't find a user with that id"
            ], 200);
        }
        $input = $request->all();
        $activity = Activity::create($input);
        return response()->json([
            'code' => '0',
            'status' => '200',
            'data' => $activity,
        ], 200);
    }

    public function addNewHeartRate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' =>'required',
            'heart_rate' => 'required',
            'date_time' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $user = User::where('id', $request->id_user)->first();
        if (!$user) {
            return response()->json([
                'code' => '04',
                'status' => '404',
                'data' => "We can't find a user with that id"
            ], 200);
        }
        $input = $request->all();
        $heartRate = HeartRate::create($input);
        return response()->json([
            'code' => '0',
            'status' => '200',
            'data' => $heartRate,
        ], 200);
    }

    public function fetchPatientActivities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $patient = User::where('email', '=', request('email'))
                ->first();
        if (!$patient) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Patient not found"
                    ], 200);
        }
        $activities = Activity::where('user_email', '=', request('email'))
                ->get();
        return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $activities,
            ], 200);
    }

    public function fetchPatientHeartRates(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $patient = User::where('email', '=', request('email'))
                ->first();
        if (!$patient) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Patient not found"
                    ], 200);
        }
        $heartRates = HeartRate::where('user_email', '=', request('email'))
                ->get();
        return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $heartRates,
            ], 200);
    }
}
